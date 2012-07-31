<?php

namespace Zule\Models\Data;

class Redis
{
    protected $model;
    protected $redis = '';
    protected $awake = no;
    
    public function __construct($object)
    {
        $this->model = $object;
        // todo: separate connections
        $this->redis = \Zule\Tools\Predis::instance();
		
		$this->awake = yes;
    }
}
