<?php

namespace Zulg;

class ModelSettings
{
    // Determines if we should generate the Models\Data table gateway
    // classes. Provides automatic abstraction of the data layer to the
    // application developer.
    private $makeGenerators;
    
    // Determines if the generator should add in generic setXXX($val)
    // methods. These are not typically required to make most applications
    // and can foster bad habits, moving logic to places that work but may
    // not be logical (exposing internal data to the public). Seeing it
    // is one thing, allowing anyone to set it is another.
    private $useUnsafeSetters;
}
