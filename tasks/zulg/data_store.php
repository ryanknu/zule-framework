<?php

// writes and reads to data stores

namespace Zulg;

class DataStore
{
    // the file to write/read from
    private $filename;
    
    // array of data
    private $data = [];
    
    public function saveFromPost()
    {
        $this->filename = $_POST['filename'];
        $ftype = $_POST['filetype'];
        $this->getData();
        if ( $ftype == 'json' )
        {
            $this->saveJson();
        }
        else if ( $ftype == 'serial' )
        {
            $this->saveSerial();
        }
        $this->writeFile();
    }
    
    private function cleanUp($array)
    {
        $arClean = [];
        foreach ($array as $datum)
        {
            if ( is_array($datum['value']) )
            {
                $arClean[$datum['name']] = $this->cleanUp($datum['value']);
            }
            else
            {
                $arClean[$datum['name']] = $datum['value'];
            }
        }
        return $arClean;
    }
    
    private function getData()
    {
        foreach ( array_keys($_POST) as $key )
        {
            if ( substr($key, 0, 5) == 'name_' )
            {
                $array = &$this->data;
                // name element matched
                $id = substr($key, 5);
                // find the right sub-array
                $layers = explode('_', $id);
                for($the_i = 0; $the_i < count($layers) - 1; $the_i++)
                {
                    $array = &$array[$layers[$the_i]]['value'];
                }
                $thisLayer = $layers[count($layers) - 1];
                $type = $_POST['type_' . $id];
                if ( $type == 'text' )
                {
                    $array[$thisLayer] = [
                        'name' => $_POST['name_' . $id],
                        'value' => $_POST['value_' . $id]
                    ];
                }
                else if ( $type == 'map' )
                {
                    $array[$thisLayer] = [
                        'name' => $_POST['name_' . $id],
                        'value' => []
                    ];
                }
            }
        }
        print_r($this->data);
        $this->data = $this->cleanUp($this->data);
    }
    
    public function saveSerial()
    {
        $this->data = serialize($this->data);
    }
    
    public function saveJson()
    {
        $this->data = json_encode($this->data);
    }
    
    public function setFilename($fn)
    {
        $this->filename = $fn;
    }
    
    public function writeFile()
    {
        $file = new \SplFileObject($this->filename, 'w');
        $bytes = $file->fwrite($this->data);
        echo $this->data;
        //$this->writeLine("Wrote $bytes to {$this->fileName}");
    }
    
    public function setData($arr)
    {
        $this->data = $arr;
    }
    
    public function readFile($file)
    {
        if ( !is_file($file) )
        {
            $this->data = ['key' => 'value'];
            return;
        }
        if ( substr($file, strlen($file) - 4) == 'json' )
        {
            $this->data = json_decode( file_get_contents( $file ), 1 );
        }
        else if ( substr($file, strlen($file) - 3) == 'ser' )
        {
            $this->data = unserialize( file_get_contents( $file ) );
        }
    }
    
    public function toForm($prefix = '', &$root='')
    {
        if ( !is_array($root) )
        {
            $root = &$this->data;
        }
        $index = 0;
        $form = '';
        $s = new \Smarty;
        $indent = '';
        for($i = 0; $i < substr_count($prefix, '_'); $i++)
        {
            $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        foreach ($root as $key => $value)
        {
            if ( is_array($value) )
            {
                $s->assign('indent', $indent);
                $s->assign('key', $key);
                $s->assign('id', $prefix . $index);
                $form .= $s->fetch('ds_mapline.tpl');
                $form .= $this->toForm($index . '_', $root[$key]);
            }
            else
            {
                $s->assign('indent', $indent);
                $s->assign('key', $key);
                $s->assign('id', $prefix . $index);
                $s->assign('value', $root[$key]);
                $form .= $s->fetch('ds_textline.tpl');
            }
            $index++;
        }
        return $form;
    }
}
