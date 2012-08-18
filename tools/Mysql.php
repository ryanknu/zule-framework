<?php

namespace Zule\Tools;
use mysqli;
use PDO;

class Mysql
{
    // Welcome all my people to the greatest wonder of the world
    // At this time, I am not supporting MySQL. It's a huge pain
    // in my rear end. One day though, we will, either I'll write
    // it, or someone else will. 
    // This is [#23].
    
    private $mysqli;
    private $preparedQuery;
    private $statement;
    private $result;
    
    private static $PDO;
    
    public function __construct()
    {
    	// get it from config
    	if ( self::$PDO == null )
    	{
			$params = (new Config)->get('mysql');
			$host = $params['host'] . ';port=' . $params['port'];
			self::$PDO = new PDO("mysql:host=$host;dbname={$params['database']}", $params['user'], $params['pass']);
			self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}
    }
    
    public function query($query, $args)
    {
    	$this->statement = self::$PDO->prepare($query);
    	$this->statement->execute($args);
    }
    
    public function getObject($class)
    {
    	return $this->statement->fetchObject($class);
    }
    
    public function getRow()
    {
    	return $this->statement->fetch();
    }
    
    public function getPdo()
    {
        return self::$PDO;
    }
    
    public function quote($str)
    {
        return self::$PDO->quote($str);
    }
    
}

