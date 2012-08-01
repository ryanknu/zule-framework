
    protected $_name = '{$table_name}';
    
    public function exists()
    {
        $key = $this->model->getKey();
        return $this->redis->exists("{$table_name}:$key");
    }
    
    public function save()
    {
        $key = $this->model->getKey();
        $row = [
            {foreach from=$columns item=column}
            {if $column['name'] != $primary_key}
            '{$column['name']}' => $this->model->get{$column['camel']}(),
            {/if}
            {/foreach}
        ];
        $this->redis->hmset("{$table_name}:$key", $row);
    }
    
    public function load($key)
    {
        $redis_key = "{$table_name}:${$primary_key}";
        $data = $this->redis->hgetall($redis_key);
        
        if ( $data )
        {
            $model = new \{$namespace}\Models\{$model_name};
            
            $row = [
                {foreach from=$columns item=column}
                {if $column != $table_name}
                '{$column['name']}' => $row['{$column['name']}'],
                {/if}
                {/foreach}
            ];
            
            $model->setKey($key);
            $model->setAllByArray($row);
            $model->awaken();

            return $model;
        }
        else
        {
            throw new \{$system}\Tools\Exception("{$model_name} not found ($redis_key)");
        }
    }
}
