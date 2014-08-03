<?php

namespace Application\BaseClass;

abstract class Entity 
{
    
    protected $_doctrine;
    protected $_em;
    protected $_generatorInstructions = array();
    protected $_sortMap = array();
    protected $_filterMap = array();
    protected $_serviceManager;
    protected $_translator;
    protected $_actionUser = null;
    
    public function __construct(\Zend\ServiceManager\ServiceManager $serviceManager = null)
    {
        if(!is_null($serviceManager))
        {
            $this->_serviceManager = $serviceManager;
            $this->_em = $this->getEntityManager();
            $this->_translator = $this->_serviceManager->get('translator');
        }
    }
    
    public function initialize(\Zend\ServiceManager\ServiceManager $serviceManager = null)
    {
        if(!is_null($serviceManager))
        {
            $this->_serviceManager = $serviceManager;
            $this->_em = $this->getEntityManager();
            $this->_translator = $this->_serviceManager->get('translator');
        }
    }
        
    /**
     *Gets the requested property
     * @param string $property
     * @return var
     */
    public function __get($property)
    {
        if(property_exists($this, $property))
        {
            return $this->$property;
        }
    }

    /**
     * Sets the requested property to the value sent
     * @param string $property
     * @param var $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }
    
    private function getEntityManager()
    {
        if (null === $this->_em) 
        {
          $this->_em = $this->_serviceManager->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }
}

