<?php

namespace Zule\Tools;

define('ZULE_FORM_DEFAULT', 'Default');

define('ZF_PROBLEM', 301);

define('ZF_EMAIL', 201);
define('ZF_INTEGER', 202);
define('ZF_NUMBER', 203);
define('ZF_TEXT', 204);
define('ZF_IP', 205);
define('ZF_IPV4', 205);
define('ZF_URL', 206);
define('ZF_HIDDEN', 207);
define('ZF_MASKED', 208);
define('ZF_SUBMIT', 209);
define('ZF_LIST', 210);

define('ZF_MISSING', 100);
define('ZF_INVALID', 101);
define('ZF_MISMATCH', 102);

define('ZF_FILLED_FLAG', '_filled');
define('ZF_AJAX_VALIDATION_FLAG', 'zf_ajax_validation');
define('ZF_CSRF', 'csrf');

class Form
{
    // The name of the form
    private $name;
    
    // Components of the form
    private $components;
    
    // Results 
    private $results;
    
    // Problems
    private $issues;
    
    // boolean to detect if post exists
    private $postExists;
    
    // required for chaining calls
    private $lastComponent;
    
    // boolean, determines if we're validating over ajax.
    private $ajaxValidationMode;
    
    // the name of the component we are validating over ajax.
    private $ajaxValidationName;
    
    // ajax handle
    private $ajax;
    
    // URI of action page
    private $action;
    
    // Smarty Handle, for passing options into the form view.
    private $smarty;
    
    public function __construct()
    {
        $this->name = ZULE_FORM_DEFAULT;
        $this->components = [];
        $this->results = [];
        $this->issues = 0;
        $this->postExists = no;
        $this->ajaxValidationMode = no;
        $this->ajaxValidationName = '';
        $this->lastComponent = '';
        $this->ajax = null;
        $this->action = '/';
        $this->awaken();
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    public function awaken()
    {
        $this->postExists = array_key_exists( ZF_FILLED_FLAG, $_POST );
        $this->action = Router::Router()->getRequest();
        $this->smarty = (new View)->getSmarty();
        
        if ( array_key_exists( ZF_AJAX_VALIDATION_FLAG, $_POST ) )
        {
            $this->ajaxValidationMode = yes;
            $this->ajaxValidationName = $_POST[ZF_AJAX_VALIDATION_FLAG];
            $this->ajax = new Ajax;
        }
        else
        {
            $this->addCSRF();        
            $this->addInput(ZF_FILLED_FLAG, ZF_HIDDEN)->setDefault('yes');
        }
    }
    
    public function addInput($name, $type)
    {
        $this->components[$name] = [
            'name'     => $name,
            'type'     => $type,
            'label'    => $name,
            'required' => yes,
            'filter'   => yes,
            'default'  => no,
            'problem'  => no,
            'draw'     => $type,
            'matches'  => no,
            'options'  => no,
        ];
        
        $this->scanPost($name);
        $this->lastComponent = $name;
        
        return $this;
    }
    
    public function addOptionalInput($name, $type)
    {
        $this->components[$name] = [
            'name'     => $name,
            'type'     => $type,
            'label'    => $name,
            'required' => no,
            'filter'   => yes,
            'default'  => no,
            'problem'  => no,
            'draw'     => $type,
            'matches'  => no,
            'options'  =>no,
        ];
        
        $this->scanPost($name);
        $this->lastComponent = $name;
        
        return $this;
    }
    
    public function addCSRF()
    {
        $session = (new Session)->getStoreNamed('zf:form:' .  $this->name);
        if ( !$this->postExists )
        {
            $csrf = uniqid(yes);
            $session->assign('csrf', $csrf);
            $this->addInput(ZF_CSRF, ZF_HIDDEN)->setDefault($csrf);
        }
        else
        {
            // form is filled out, check CSRF
            $csrf = $session->get(ZF_CSRF);
            if ( !isset( $_POST[ZF_CSRF] ) || $_POST[ZF_CSRF] != $csrf )
            {
                print_r($_POST);
                $cc = "CSRF POST: {$_POST[ZF_CSRF]} CSRF SESS: $csrf";
                throw new Exception('CSRF check failed on form ' . $this->name . $cc );
            }
            else
            {
                // add csrf to form in case we're looping
                $this->addInput(ZF_CSRF, ZF_HIDDEN)->setDefault($session->get(ZF_CSRF));
            }
        }
    }
    
    public function setDefault($value)
    {
        if ( !$this->components[$this->lastComponent]['default'] )
        {
            $this->components[$this->lastComponent]['default'] = $value;
        }
        return $this;
    }
    
    public function setLabel($value)
    {
        $this->components[$this->lastComponent]['label'] = $value;
        return $this;
    }
    
    public function drawAs($type)
    {
        $this->components[$this->lastComponent]['draw'] = "$type.tpl";
        return $this;
    }
    
    public function matches($name)
    {
        $this->components[$this->lastComponent]['matches'] = $name;
        // check for a problem now, as any matching fields have not yet
        // been verified.
        if ( $this->postExists )
        {
            if ( $this->results[$this->lastComponent] != $this->results[$name] )
            {
                $this->issues ++;
                $this->components[$this->lastComponent]['problem'] = ZF_MISMATCH;
            }
        }
        return $this;
    }
    
    public function withOptions($opts)
    {
        $this->components[$this->lastComponent]['options'] = $opts;
        return $this;
    }
    
    public function isFilled()
    {
        if ( $this->ajaxValidationMode )
        {
            // check to see if the component specified is a problem
            if ( $this->components[$this->ajaxValidationName]['problem'] )
            {
                $this->ajax->Error('Invalid value.');
            }
            else
            {
                $this->ajax->Push([]);
            }
        }
        return $this->postExists && ($this->issues == 0);
    }
    
    public function getResults()
    {
        return $this->results;
    }
    
    private function getFile($type)
    {
        static $types = [
            ZF_EMAIL   => 'text.tpl',
            ZF_INTEGER => 'text.tpl',
            ZF_NUMBER  => 'text.tpl',
            ZF_TEXT    => 'text.tpl',
            ZF_IPV4    => 'text.tpl',
            ZF_URL     => 'text.tpl',
            ZF_HIDDEN  => 'hidden.tpl',
            ZF_MASKED  => 'masked.tpl',
            ZF_SUBMIT  => 'submit.tpl',
            ZF_PROBLEM => 'problem.tpl',
            ZF_LIST    => 'list.tpl',
        ];
        
        return $types[$type];
    }
    
    private function filterFlag($name)
    {
        static $types = [
            ZF_EMAIL   => FILTER_VALIDATE_EMAIL,
            ZF_INTEGER => FILTER_VALIDATE_INT,
            ZF_NUMBER  => FILTER_VALIDATE_FLOAT,
            ZF_TEXT    => FILTER_SANITIZE_STRING,
            ZF_IPV4    => FILTER_VALIDATE_IP,
            ZF_URL     => FILTER_VALIDATE_URL,
            ZF_HIDDEN  => no,
            ZF_MASKED  => no,
            ZF_SUBMIT  => no,
            ZF_LIST    => no,
        ];
        
        $type = $this->components[$name]['type'];
        
        return $types[$type];
    }
    
    public function errorString($type)
    {
        static $strings = [
            ZF_MISSING   => 'Required',
            ZF_INVALID   => 'Invalid', 
            ZF_MISMATCH  => 'Mismatched',
        ];
        
        return $strings[$type];
    }
    
    private function scanPost($name)
    {
        if ( $this->postExists )
        {
            if ( array_key_exists( $name, $_POST ) )
            {
                // TODO: some way to not filter input
                $filterFlag = $this->filterFlag($name);
                if ( $filterFlag )
                {
                    $this->results[$name] = filter_var(
                        $_POST[$name], $filterFlag
                    );
                }
                else
                {
                    $this->results[$name] = $_POST[$name];
                }
                
                // recycle values
                if ( $this->components[$name]['type'] <> ZF_MASKED )
                {
                    $this->components[$name]['default'] = $_POST[$name];
                }
                
                if ( !$this->results[$name] && $this->components[$name]['required'] )
                {
                    $this->components[$name]['problem'] = ZF_INVALID;
                    $this->issues ++;
                }
            }
            else if ( $this->components[$name]['required'] )
            {
                $this->components[$name]['problem'] = ZF_MISSING;
                $this->issues ++;
            }
            else
            {
                $this->results[$name] = '';
            }
        }
    }
    
    private function findElementFile($file)
    {
        $controllerName = $this->name;
        //$file = $this->getFile($file); // very confusing line
        
        $checks = [
            ROOT . "forms/$controllerName/Elements/$file",
            ROOT . 'forms/' . ZULE_FORM_DEFAULT . "/Elements/$file",
        ];
        
        foreach ( $checks as $check )
        {
            if ( file_exists( $check ) )
            {
                return $check;
            }
        }
        
        // author's note: $check contains last element in array aka default. 
        throw new Exception("Cannot locate template file. (tried '$check')");
    }
    
    private function findContainerFile()
    {
        $controllerName = $this->name;
        $fizzle = ROOT . "forms/$controllerName/Container/main.tpl";
        if ( file_exists( $fizzle ) )
        {
            return $fizzle;
        }
        
        throw new Exception("Cannot locate main form template file (tried '$fizzle')");
    }
    
    public function smartyAssign($key, $val)
    {
        $this->smarty->assign($key, $val);
    }
    
    public function getHtml()
    {
        foreach( array_keys($this->components) as $name )
        {
            // get properties from form
            foreach ( $this->components[$name] as $key => $value )
            {
                $this->smarty->assign($key, $value);
            }
            
            // check for problems
            if ( $this->components[$name]['problem'] )
            {
                $this->smarty->assign('problem', 
                    $this->errorString($this->components[$name]['problem'])
                );
                $problemHtml = $this->smarty->fetch(
                    $this->findElementFile($this->getFile(ZF_PROBLEM))
                );
                $this->smarty->assign('problem', $problemHtml);
            }
            else
            {
                $this->smarty->assign('problem', '');
            }
            
            $draw = $this->components[$name]['draw'];
            if ( is_int( $draw ) )
            {
                $file = $this->getFile( $draw );
            }
            else
            {
                $file = $draw;
            }
            
            $this->components[$name]['html'] = $this->smarty->fetch(
                $this->findElementFile($file)
            );
            
        }
        
        $this->smarty->assign('action', $this->action);
        $this->smarty->assign('form_components', $this->components);
        return $this->smarty->fetch( $this->findContainerFile() );
    }
}
