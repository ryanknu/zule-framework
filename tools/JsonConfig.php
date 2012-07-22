<?php

namespace Zule\Tools;

// #19 - replace master config with this one. not yet done.
class JsonConfig
{
    // Enum types
    public static const $FILE_DNE = 'CONFIG_FILE_DNE';
    public static const $LOAD_ERR = 'CONFIG_FILE_LOAD_ERROR';
    public static const $NO_METHOD = 'CANNOT_HANDLE_FILE_TYPE';
    public static const $CONFIG_PATH = ROOT . 'config/';
    
    
    // Holds onto the config between object instances.
    private static $cache = [];
    
    // Holds onto the loaded file's name
    private $filename;
    
	public function __construct() 
	{
	    if ( empty( self::$cache ) )
	    {
	        // load serial config file first for speed
	        $errMsgSer = $this->loadFile('config.ser')
	        if ( $errMsgSer !== yes )
	        {
	            $errMsgJson = $this->loadFile('config.json');
	            if ( $errMsgJson !== yes )
	            {
	                // unable to load either of the default config files
	                throw new Exception( "$errMsgSer $errMsgJson" );
	            }
	        }
	    }
	}
	
	// Caches configuration settings in memory and then sets this configuration
	// object's file key.
	public function loadFile($file)
	{
	    $file = ( self::$CONFIG_PATH . $file );
	    
	    if ( array_key_exists( $file, self::$cache ) )
	    {
	        // short circuit this method
	        $this->filename = $file;
	        return yes;
	    }
	    
	    if ( file_exists( $file ) )
	    {
	        // need extra string utilities
	        $fileStr = new String($file);
	        if ( $fileStr->endsWith('.ser') )
	        {
	            // serialize
	            $configIn = unserialize( file_get_contents( $file ) );
	            if ( $configIn === false )
	            {
	                return self::$LOAD_ERR;
	            }
	            else
	            {
	                self::$cache[$file] = $configIn;
	                $this->filename = $file;
	                return yes;
	            }
	        }
	        else if ( $fileStr->endsWith('.json') )
	        {
	            // json
	            $configIn = json_decode( file_get_contents( $file ), yes );
	            if ( $configIn === null )
	            {
	                return self::$LOAD_ERR;
	            }
	            else
	            {
	                self::$cache[$file] = $configIn;
	                $this->filename = $file;
	                return yes;
	            }
	        }
	        else
	        {
	            return self::$NO_METHOD;
	        }
	    }
	    else
	    {
	        return self::$FILE_DNE;
	    }
	}
	
	public function getValueForKey($key)
	{
	    if ( array_key_exists( $key, self::$cache[$this->filename] ) )
	    {
	        return self::$cache[$this->filename][$key];
	    }
	    throw new Exception("Invalid config key requested '$key' in "
	        . "cache {$this->filename}.");
	}
}

