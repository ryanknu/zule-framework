<?php

namespace Zule\Tools;
use SplFileObject;

define('LANGUAGE_SESSION_STORE', 'zf:lang');
define('LANGUAGE_DEFAULT', 'en');

class Language
{
    // is a map of the $key => localized string pairs for this language
    private $localizedStrings;
    
    // a session store referring to the 
    private $sessionStore;
    
    // the language chosen
    private $language;
    
    // the short name for the language
    private $shortName;
    
    // Pull selected language from session (or set if does not exist).
    // This allows our code always serve the client the correct language
    // even on pages that do not have a setLanguage call. This said,
    // in order to switch back to default you need to call setLanguage
    // again.
    public function __construct()
    {
        $this->localizedStrings = [];
        $this->sessionStore = 
            (new Session)->getStoreNamed(LANGUAGE_SESSION_STORE);
        $lang = $this->sessionStore->get('language');
        if ( $lang )
        {
            $this->language = $lang;
            $this->shortName = $this->sessionStore->get('short');
        }
        else
        {
            $this->language = LANGUAGE_DEFAULT;
            $this->shortName = LANGUAGE_DEFAULT;
        }
    }
    
    // sets the session's language based on name.
    public function setLanguage($newLanguage)
    {
        $this->sessionStore->assign('language', $newLanguage);
        $this->sessionStore->assign('short', $newLanguage);
    }
    
    public function compileView($path, $compiledPath)
    {
        $compileDir = ROOT . 'cache/' . $this->shortName;
        $compiled = $compileDir . '/' . md5_file($path) . '.' . $compiledPath . '.tpl';
        $controller = Router::router()->getController()->getName();
        if ( !file_exists( $compileDir ) )
        {
            mkdir($compileDir, 0777, yes);
        }
        
        if ( !file_exists( $compiled ) )
        {
            $inStr = file_get_contents($path);
            $strings = json_decode(file_get_contents(ROOT . 'lang/' . $this->shortName . '/' . $controller . '.json'), yes);
            $outStr = '';
            $i = 0;
            while ( $i < strlen($inStr) )
            {
                $start = strpos($inStr, '<#', $i);
                if ( $start !== false )
                {
                    $end = strpos($inStr, '#>', $start);
                    $var = trim(substr($inStr, $start + 2, $end - $start - 2));
                    $outStr .= substr($inStr, $i, $start - $i);
                    $outStr .= $strings[$var];
                    $i = $end + 2;
                }
                else
                {
                    $outStr .= substr($inStr, $i);
                    $i = strlen($inStr);
                }
            }
            $fh = new SplFileObject($compiled, 'x');
            $fh->fwrite($outStr);
        }
        return $compiled;
    }
    
}
