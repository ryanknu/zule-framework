{$open_php}

namespace {$namespace}\Controllers;

class {$controller} extends \{$system}\Controllers\Controller
{   
    {foreach from=$actions item=act}
    
    public function {$act}()
    {
        {if $generateViews}$s = new \Smarty;
        $s->assign('controller', '{$controller}');
        $s->assign('action', '{$act}');
        $s->display(\{$system}\Tools\View::find('{$act}'));{/if}
    
    }
    
    {/foreach}

}
