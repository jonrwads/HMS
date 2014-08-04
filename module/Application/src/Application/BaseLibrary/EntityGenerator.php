<?php
namespace Application\BaseLibrary;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

class EntityGenerator implements ServiceLocatorAwareInterface{
    
    private $_serviceLocator;
    private $_translator;
    
    public function generate($entity, $data, &$msg, $primaryKey = null)
    {
        $valid = true;
        
        foreach($data as $key=>$value)
        {
            if($key != $primaryKey)
            {
                //This will link doctrine foreign keys to actual objects
                if(key_exists($key, $entity->_generatorInstructions))
                {
                    $instruction = $entity->_generatorInstructions[$key];                    
                    $property = $instruction['property']; 
                    
                    switch($instruction['type']) {
                        case 'foreignKey':
                            if(is_null($value) || (is_string($value) && trim($value) == ''))
                            {
                                if(isset($instruction['required']) && $instruction['required']) {
                                    $valid = false;
                                    $currentMessage = $this->_translator->translate('The linked entity %1$s was not found and is required');
                                    $msg .= PHP_EOL . vsprintf($currentMessage, [$this->_translator->translate($instruction['readableName'])]); 
                                }
                                $entity->$property = null;
                            }
                            else
                            {    
                                $foreignEntity = $this->_em->getReference($foreignKey['entityName'], $value);
                                $entity->$property = $foreignEntity;
                            }
                            break;
                        case 'datetime':
                            $date = $this->ConvertToDate($value);
                            if($date !== false) {                                
                                $entity->$property = $date;
                            }
                            elseif(isset($instruction['required']) && $instruction['required']) {
                                $valid = false;
                                $currentMessage = $this->_translator->translate('The given date %1$s is not in the correct format');
                                $msg .= PHP_EOL . vsprintf($currentMessage, $value);
                            }
                            break;
                    }
                }
                else if(property_exists(get_class($entity), $key))
                {
                    if(!is_string($value) || trim($value)!="")
                    {
                        if(is_string($value))
                        {
                            $value = trim($value);
                        }
                        $entity->$key = $value;
                    }
                    elseif(is_string($value) || trim($value)!="")
                    {
                        $entity->$key = null;
                    }
                }
            }
        }
        
        return $valid;
    }
    
    public static function ConvertToDate($dateString)
    {
        try
        {
            $pos = strpos($dateString, 'T');
            if($pos !== false)
            {
               $dateString = substr($dateString, 0, $pos);
            }
            if(strlen($dateString) == 10)
            {
                return \DateTime::createFromFormat('Y-m-d H:i:s', $dateString . ' 00:00:00');
            }
            else
            {
                return \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
            }
        }
        catch (Exception $ex)
        {
            return false;
        }
    }

    public function getServiceLocator() {
        return $this->_serviceLocator;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $this->_serviceLocator = $serviceLocator;
        $this->_translator = $serviceLocator->get('translator');
    }

}
