<?php

namespace Zule\Tools;

define('LANGUAGE_SESSION_STORE', 'zf:lang');

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
    }
    
    // sets the session's language based on name.
    public function setLanguage($newLanguage)
    {
        $this->sessionStore->assign('language', $newLanguage);
    }
    
}
