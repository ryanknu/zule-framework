<?php

namespace Zulg;

require_once '../tools/Loader.php';
require_once 'zulg/model_generator.php';

echo '<pre>';

(new ModelGenerator)->generate();
        
echo '</pre>';
