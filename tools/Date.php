<?php

namespace Zule\Tools;

class Date
{
    private $timestamp;
    private $inStr;
    
    public function __construct($inStr)
    {
        $this->timestamp = strtotime($inStr);
        $this->inStr = $inStr;
    }
    
    public function toReadable()
    {
        return date('M/D/Y H:i:s', $this->timestamp);
    }
    
    public function toSQL()
    {
        return date('Y-m-d H:i:s', $this->timestamp);
    }
}
