zule-framework
==============

The Zule Framework is a PHP-based application development framework that promotes good MVC 
habits as well as model-gateway patterns. It also promotes 100% native PHP and does not rely 
on anything like annotations or database migration strategies. These things simply do not come 
naturally to developers and require developers to have deep, intimate knowledge of the 
framework in order to program effectively in it (see: the Zend Framework.)

That isn't to say requiring things like annotations or generation tools are a bad thing, in 
fact, the Zule framework comes with generation tools to help you kick start your project, 
allowing you to create controllers and model template files on the fly. 

We work with both Redis and MySQL, although, I don't necessarily support the MySQL implementation, 
as that would require me to write twice as much code (sorry!) 

My main goal with the Zule Framework is simplicity. If you require too much, you're thinking 
too hard. Controllers simply know what actions they can respond to via reflection, models simply 
know how to save themselves with gateways, project configurations can be dynamically loaded and 
unloaded, as well as cached for speed. Knowledge of third-party libraries is set to a minimum.

We require you to have no third-party libraries installed, with the exception of Smarty, if you 
don't want to. This framework simply will not work without Smarty. We recommend you install 
Predis if you want to take full advantage of our Redis support, but otherwise, you don't need 
any big or complicated or poorly documented libraries to use our code. Anybody who has tried using 
the aformentioned Zend Framework will know the frustration that I'm talking about.

So give our repo a clone, poke around, and see what you can't figure out on your own. I'm going 
to be writing instructions when I get closer to a finished version. Until then, you can also 
check out the issues tab to see what's in motion today.