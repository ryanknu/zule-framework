<?php

function camel($name)
{
    $name{0} = strtoupper($name{0});
    while ( $pos = strpos( $name, '_' ) )
    {
        $f = substr($name, 0, $pos);
        $l = substr($name, $pos + 1);
        $l{0} = strtoupper($l{0});
        $name = $f . $l;
    }
    return $name;
}

function lCamel($name)
{
    while ( $pos = strpos( $name, '_' ) )
    {
        $f = substr($name, 0, $pos);
        $l = substr($name, $pos + 1);
        $l{0} = strtoupper($l{0});
        $name = $f . $l;
    }
    return $name;
}
