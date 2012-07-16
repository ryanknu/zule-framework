<?php

namespace Zulg;

require_once 'zulg/naming.php';

define ( 'ZULG_END', '    /// ZULG END OF DEFINITIONS' );
define ( 'ZULG_PATH', 'generator/' );
define ( 'ZULG_TOP', 'generator/auto_top.tpl' );
define ( 'ZULG_CENTER', 'generator/auto_center.tpl' );

class Generator
{
    // Handle to a pre-loaded smarty object.
    private $smarty;
    
    // Holds the filename of the target generated file
    private $fileName;
    
    // Holds generated file content in memory for a short while.
    private $buffer;
    
    // Determines whether or not we need to generate the
    // top of the file or not.
    private $generateTop;
    
    public function __construct($file)
    {
        $this->fileName = $file;
        $this->smarty = null;
        $this->generateTop = true;
        $this->buffer = '';
        $this->awaken();
    }
    
    public function awaken()
    {
        // Perform tasks that should happen after data is initialized
        if ( file_exists( $this->fileName ) )
        {
            // Top already exists, only generate the body.
            $this->generateTop = false;
            
            // Read body from disk
            $lines = file( $this->fileName );
            for ( $the_i = 0; $the_i < count($lines); $the_i++ )
            {
                $this->buffer .= ( $lines[$the_i] . PHP_EOL );
                if ( trim($lines[$the_i]) == ZULG_END )
                {
                    // stop buffering at this point. all content below
                    // this line should be regenerated.
                    break;
                }
            }
        }
    }
    
    public function generate(\Smarty $smarty, $tplName)
    {
        $this->writeLine("Generating file for $tplName");
        
        $smarty->assign('open_php', '<?php');
        
        if ( $this->generateTop )
        {
            $this->buffer .= $smarty->fetch(ZULG_TOP);
            // Not sure if zulg_center is required here, I don't see
            // any custom logic going between top and center. However,
            // I'll leave it like this in case we do want to have a
            // model top, etc.
            $this->buffer .= $smarty->fetch(ZULG_CENTER);
        }
        
        $this->buffer .= $smarty->fetch(ZULG_PATH . $tplName . '.tpl');
        
        $this->writeLine("Generated template for $tplName");
        
        $this->saveToDisk();
    }
    
    public function writeLine($line)
    {
        // We could do more with this.
        echo $line, PHP_EOL;
    }
    
    public function saveToDisk()
    {
        $file = new \SplFileObject($this->fileName, 'w');
        $bytes = $file->fwrite($this->buffer);
        
        $this->writeLine("Wrote $bytes to {$this->fileName}");
    }
}
