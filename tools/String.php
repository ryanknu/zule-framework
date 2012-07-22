<?php

namespace Zule\Tools;

class String
{
    private $str;
    
    public function __construct($str)
    {
        $this->str = $str;
    }
    
    public function endsWith($str)
    {
        $oStLen = strlen($this->str);
        $mStLen = strlen($str);
        
        if ( $oStLen > $mStLen )
        {
            if ( substr($this->str, $oStLen - $mStLen) == $str )
            {
                return yes;
            }
        }
        return no;
    }
    
    public function subStrFromEnd($chars)
    {
        return substr($this->str, strlen($this->str) - $chars);
    }
}

