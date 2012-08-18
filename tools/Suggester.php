<?php

namespace Zule\Tools;

// Suggester
// Represents an auto-complete result set. For use with
// jQuery-ui Autocomplete ($.autocomplete). May work with
// other frameworks, I don't know.
class Suggester
{
    private $results = [];
    private static $errors = [];
    
    public function __construct()
    {
        ob_start();
        
        $logErrors = (new Config)->get('dev');
        if ( $logErrors )
        {
            set_error_handler(function($errNo, $errStr, $errFile, $errLine) {
                self::addError("$errStr occurred on $errFile:$errLine");
            });
            set_exception_handler(function($exception) {
                self::addError($exception->__toString());
            });
            
            \Smarty::muteExpectedErrors();
        }
    }
    
    public static function addError($str)
    {
        $obj = new \stdClass;
        $obj->label = $str;
        self::$errors[] = $obj;
    }
    
    public function addResult($label, $extra = [])
    {
        $obj = new \stdClass;
        $obj->label = $label;
        foreach ($extra as $key=>$val)
            $obj->$key = $val;
        $this->results[] = $obj;
    }
    
    public function returnResults()
    {
        ob_end_clean();
        echo json_encode(array_merge(self::$errors, $this->results));
        die;
    }
}
