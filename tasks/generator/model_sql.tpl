
    // Control vars
    {foreach from=$columns item=column}private ${$column['l_camel']};
    {/foreach}
    
    // $safe is a sanitized array, e.g. all values in safe
    // are assumed to be non-corrupted data.
    public function save(array $safe)
    {
        {foreach from=$columns item=column}if ( array_key_exists('{$column['name']}', $safe) )
        {
            $this->{$column['l_camel']} = $safe['{$column['name']}'];
        }
        
        {/foreach}
        
        $this->getGateway()->save();
    }
    
    public function set{$columns[$primary_key]['name']}($aValue)
    {
        if ( !$this->awake )
        {
            $this->{$columns[$primary_key]['l_camel']} = $aValue;
        }
        else
        {
            throw new \Zule\Tools\Exception(
                'Primary key "{$primary_key}" can only be set when object is asleep.'
            );
        }
    }
    
    public function setAllByArray(array $inArray)
    {
        {foreach from=$columns item=column}
        {if $column['name'] != $primary_key}
        $this->{$column['l_camel']} = $inArray['{$column['name']}'];
        {/if}
        {/foreach}
    }
    
    {foreach from=$columns item=column}public function get{$column['camel']}()
    {
        return $this->{$column['l_camel']};
    }
    
    {if $use_unsafe_setters}
    public function set{$column['camel']}(${$column['l_camel']})
    {
        $this->{$column['l_camel']} = ${$column['l_camel']};
    }
    {/if}
    
    {/foreach}
    
}
