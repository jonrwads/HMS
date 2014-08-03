<?php

namespace Application\BaseLibrary;

class DoctrineInjector 
{
    protected $_serviceLocator = null;  

    public function __construct($serviceLocator)
    {
      $this->_serviceLocator = $serviceLocator;
    }
    
    public function postLoad($eventArgs)
    {
       $entity = $eventArgs->getEntity();
       if(method_exists($entity, 'initialize')){
            $entity->initialize($this->_serviceLocator);
       }
   }
}
