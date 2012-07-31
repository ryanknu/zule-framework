<?php

namespace Zule\Tools;

class Session
{
    private static $started = no;
    // need an ability to store

    public function __construct()
    {    
        if ( !self::$started )
        {
            if ( headers_sent() )
            {
                throw new Exception('Cannot start session, headers already sent.');
            }
            session_start();
            self::$started = yes;
        }
    }
    
    public function getStoreNamed($name)
    {
        return new SessionStore($name);
    }
}

// put in a different file
class SessionStore
{
    private $storeName;
    
    public function __construct($name)
    {
        $this->storeName = $name;
        if ( array_key_exists($name, $_SESSION) )
        {
            $_SESSION[$name] = json_decode($_SESSION[$name], yes);
        }
        else
        {
            $_SESSION[$name] = [];
        }
    }
    
    public function get($key)
    {
        // TODO: shouldn't we check something here?
        return $_SESSION[$this->storeName][$key];
    }
    
    public function assign($key, $value)
    {
        $_SESSION[$this->storeName][$key] = $value;
    }
    
    public function destroy($key)
    {
        unset( $_SESSION[$this->storeName][$key] );
    }
    
    public function destroyStore()
    {
        unset( $_SESSION[$this->storeName] );
    }
    
    public function __destruct()
    {
        // flatten session
        $_SESSION[$this->storeName] = json_encode($_SESSION[$this->storeName]);
    }
}
