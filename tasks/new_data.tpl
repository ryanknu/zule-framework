{$php_open}

namespace \{$namespace}\Models\Data;

class {$model_name} extends {$system}\Models\Data\Table
{
    protected $_name = '{$table_name}';
    
    public function save()
    {
        $row = [
            {foreach from=$columns item=column}'{$column}' => $this->model->get{$camels[$column]}(),
            {/foreach}
];
        
        $this->insert($row);
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
            
            {foreach from=$columns item=column}$model->set{$camels[$column]}($row['{$column}']);
            {/foreach}

            return $model;
        }
        else
        {
            throw new \{$namespace}\Tools\Exception("{$model_name} not found ({$primary_key}: ${$primary_key})");
        }
    }
}
