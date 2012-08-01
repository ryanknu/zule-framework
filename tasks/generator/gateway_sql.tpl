
    protected $_name = '{$table_name}';
    
    public function save()
    {
        {$primary_key} = $this->model->get{$primary_key}();
        $row = [
            {foreach from=$columns item=column}
            {if $column['name'] != $primary_key}
            '{$column['name']}' => $this->model->get{$column['camel']}(),
            {/if}
            {/foreach}
        ];
        if ( {$primary_key} )
        {
            $this->insert($row);
        }
        else
        {
            $this->update($row, "`{$primary_key}` = '${$primary_key}'");
        }
    }
    
    public function load(${$primary_key})
    {
        $db = $this->getAdapter();
        $s = $db->select()
            ->from($this->_name)
            ->where('{$primary_key} = ?', ${$primary_key});
        $row = $db->fetchRow($s);
        
        if ( $row )
        {
            $model = new \{$namespace}\Models\{$model_name};
            
            $row = [
                {foreach from=$columns item=column}
                {if $column != $primary_key}
                '{$column['name']}' => $row['{$column['name']}'],
                {/if}
                {/foreach}
            ];
            
            $model->set{$primary_key}({$columns[$primary_key]['name']});
            $model->setAllByArray($row);
            $model->awaken();

            return $model;
        }
        else
        {
            throw new \{$system}\Tools\Exception("{$model_name} not found ({$primary_key}: ${$primary_key})");
        }
    }
}
