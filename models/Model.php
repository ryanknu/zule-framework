<?php

namespace Zule\Models;

class Model
{
    public function getGateway()
    {
        $eName = explode('\\', get_class($this));
        $eName = $eName[count($eName) - 1];
        $nameSpace = \Zule\Tools\Config::zc()->framework->application_namespace;
        $class = "\\$nameSpace\\Models\\Data\\$eName";
        return new $class($this);
    }
}
