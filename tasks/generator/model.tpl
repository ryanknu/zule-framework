
    // Control vars
    {foreach from=$columns item=column}private ${$lCamels[$column]};
    {/foreach}
    
    {if $generate_gateway}// $safe is a sanitized array, e.g. all values in safe
    // are assumed to be non-corrupted data.
    public function save(array $safe)
    {
        {foreach from=$columns item=column}if ( array_key_exists('{$column}', $safe) )
        {
            $this->{$lCamels[$column]} = $safe['{$column}'];
        }
        
        {/foreach}
        
        $this->getGateway()->save();
    }
    
    public function set{$camels[$primary_key]($aValue)
    {
        if ( !$this->awake )
        {
            $this->{$lcamels[$primary_key]} = $aValue;
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
        {if $column != $primary_key}
        $this->{$lCamels[$column]} = $inArray['{$column}'];
        {/if}
        {/foreach}
    }
    
    {/if}
    
    {foreach from=$columns item=column}public function get{$camels[$column]}()
    {
        return $this->{$lCamels[$column]};
    }
    
    {if $useUnsafeSetters}
    public function set{$camels[$column]}(${$lCamels[$column]})
    {
        $this->{$lCamels[$column]} = ${$lCamels[$column]};
    }
    {/if}
    
    {/foreach}
    
}
