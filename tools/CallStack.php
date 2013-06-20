<?php

namespace Zule\Tools;

// The callstack takes a snapshot of the call stack when it is constructed,
// then it is stagnant. This class is not meant for you to make one object
// and then generate a hundred call stacks. Make a new copy for each one you
// want.

class CallStack
{
    private $backtrace;
    private $defaultPrint;
    
    public function __construct()
    {
        $this->defaultPrint = '';
        $this->backtrace = debug_backtrace();
        for ($i = 0; $i < count($this->backtrace); $i++) {
            $call = $this->backtrace[$i];
            $this->defaultPrint .= $call['function'];
            $this->defaultPrint .= '(';
            foreach ( $call['args'] as $arg ) {
                switch (true) {
                    case is_numeric($arg):
                        $this->defaultPrint .= (string)$arg;
                        break;
                    case is_string($arg):
                        $egArg = str_replace('\'', '\\\'', $arg);
                        $this->defaultPrint .= '\'';
                        $this->defaultPrint .= $egArg;
                        $this->defaultPrint .= '\'';
                        break;
                    case is_object($arg):
                        $this->defaultPrint .= '[Object]';
                        break;
                    default:
                        $this->defaultPrint .= '$arg';
                }
                $this->defaultPrint .= ',';
            }
            if ( $this->defaultPrint{strlen($this->defaultPrint) - 1} == ',' ) {
                $this->defaultPrint = substr($this->defaultPrint, 0, strlen($this->defaultPrint) - 1);
            }
            $this->defaultPrint .= ');';
            $this->defaultPrint .= ' // --> ';
            $this->defaultPrint .= $call['file'];
            $this->defaultPrint .= ':';
            $this->defaultPrint .= $call['line'];
            $this->defaultPrint .= PHP_EOL;
        }
    }
    
    public function __toString()
    {
        return $this->defaultPrint;
    }
}
