<?php

namespace Zule\Tools;

class Get
{
    
    public function __construct()
    {
    }
    
    public function getQueryString(array $array)
    {
        // This function returns a query string that goes after the ?
        // which means it does not include the ?
        $salt = mt_rand(100000000, 999999999);
        $stringToHash = $salt . http_build_query($array);
        $crypt = hash('sha512', $stringToHash);
        $qs = $salt . $crypt . base64_encode( http_build_query($array) );
        return $qs;
    }
    
    // returns array
    public function decodeQueryString()
    {
        $qs = $_SERVER['QUERY_STRING'];
        $salt = substr($qs, 0, 9);
        $crypt = substr($qs, 9, 128);
        $rest = base64_decode(substr($qs, 137));
        $out = array();
        if ( $crypt == hash('sha512', $salt . $rest) )
        {
            parse_str($rest, $out);
        }
        return $out;
    }
}
