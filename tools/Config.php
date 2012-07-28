<?php

namespace Zule\Tools;

// autoloader is not yet defined, so we need these files
require ROOT . 'tools/Exception.php';
require ROOT . 'tools/String.php';

define('CONFIG_PATH', ROOT . 'config/');

class Config
{
    // Enum types
    public static $FILE_DNE = 'CONFIG_FILE_DNE';
    public static $LOAD_ERR = 'CONFIG_FILE_LOAD_ERROR';
    public static $NO_METHOD = 'CANNOT_HANDLE_FILE_TYPE';
    
    // Holds onto the config between object instances.
    private static $cache = [];
    
    private static $mainConfigPath = no;
    
    // Holds onto the loaded file's name
    private $filename;
    
	public function __construct() 
	{
	    if ( empty( self::$cache ) )
	    {
	        // load serial config file first for speed
	        $errMsgSer = $this->loadFile('config.ser');
	        if ( $errMsgSer !== yes )
	        {
	            $errMsgJson = $this->loadFile('config.json');
	            if ( $errMsgJson !== yes )
	            {
	                // unable to load either of the default config files
	                throw new Exception( "$errMsgSer $errMsgJson" );
	            }
	            else
	            {
	                self::$mainConfigPath = $this->filename;
	            }
	        }
	        else
	        {
	            self::$mainConfigPath = $this->filename;
	        }
	    }
	    else
	    {
	        // if we're not building the main config, load the main config
	        $this->filename = self::$mainConfigPath;
	    }
	}
	
	// Caches configuration settings in memory and then sets this configuration
	// object's file key.
	public function loadFile($file)
	{
	    $file = ( CONFIG_PATH . $file );
	    
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
	                if ( !empty( self::$cache[$file]['use'] ) )
	                {
	                    foreach( self::$cache[$file]['use'] as $use )
	                    {
	                        $this->useFile($use);
	                    }
	                }
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
	                if ( !empty( self::$cache[$file]['use'] ) )
	                {
	                    foreach( self::$cache[$file]['use'] as $use )
	                    {
	                        $this->useFile($use);
	                    }
	                }
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
	
	// Implements use logic in [#24]
	public function useFile($file)
	{
	    $file = ( CONFIG_PATH . $file );
	    
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
	                self::$cache[$file] = array_merge(
	                    self::$cache[$file],
	                    $configIn
	                );
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
	                self::$cache[$file] = array_merge(
	                    self::$cache[$file],
	                    $configIn
	                );
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
	
	public function get($key)
	{
	    if ( array_key_exists( $key, self::$cache[$this->filename] ) )
	    {
	        $rVal = self::$cache[$this->filename][$key];
	        if ( $rVal == 'no' )
	        {
	            return no;
	        }
	        else
	        {
	            return $rVal;
	        }
	    }
	    throw new Exception("Invalid config key requested '$key' in "
	        . "cache {$this->filename}.");
	}
	
	public function getFilename()
	{
	    return $this->filename;
	}
}

