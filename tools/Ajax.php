<?php

namespace Zule\Tools;
use Smarty;

class Ajax
{

	function __construct()
	{
	    // When ajax is initialized, we start output buffering.
	    ob_start();
	    
	    $logErrors = (new Config)->get('dev');
        if ( $logErrors )
        {
            set_error_handler(function($errNo, $errStr, $errFile, $errLine) {
                $this->Error("$errStr occurred on $errFile:$errLine");
            });
            set_exception_handler(function($exception) {
                $this->Error($exception->__toString());
            });
            
            Smarty::muteExpectedErrors();
        }
	}
	
	public function Error($message)
	{
	    $this->Push(array(
	        'result' => 'error',
	        'message' => $message
	    ));
	}
	
	public function Push($array)
	{
	    // kill output buffering
	    ob_end_clean();
	    if ( !isset($array['result']) )
	    {
	        $array['result'] = 'success';
	    }
	    header('Content-type: application/json');
	    echo json_encode($array);
	    exit;
	}
}

