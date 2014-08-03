<?php

namespace Application\Library;

use Zend\ServiceManager\ServiceManager              as ServiceManager;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Session\Container;

class AuthAdapter implements AdapterInterface {
    /**
     *
     * @var Doctrine\ORM\EntityManager $_em
     */
    private $_em;
    
    /**
     *
     * @var Zend\ServiceManager\ServiceManager $_serviceManager
     */
    private $_serviceManager;
    
    /**
     *
     * @var Zend\Mvc\I18n\Translator $_translator
     */
    private $_translator;
    
    
    private $_username;
    private $_password;
    
    public function __construct(ServiceManager $serviceManager, $username, $password) 
    {
        $this->_serviceManager = $serviceManager;    
        $this->_em = $this->_serviceManager->get('Doctrine\ORM\EntityManager');
        $this->_translator = $this->_serviceManager->get('translator');
        $this->_username = $username;
        $this->_password = $password;
    }
    
    public function authenticate() {
        try {
            $user = $this->_em->getRepository('Application\\Model\\User')->findBy([
                'username'=>$this->_username,
                'password'=>sha1($this->_password)
            ]);

            if(!is_null($user) && count($user) == 1){
                $user = $user[0];
                if($user->enabled) {                
                    return $this->createResult(Result::SUCCESS, $user->toIdentity());
                }
                else {
                    $message = $this->_translator->translate('The given user is disabled, please contact system administrator to enable account.');
                    return $this->createResult(Result::FAILURE_CREDENTIAL_INVALID, null, $message);
                }
            }
            else {
                $message = $this->_translator->translate('The provided credentials were not recognized. Please verify username and password and try again.');
                return $this->createResult(Result::FAILURE_CREDENTIAL_INVALID, null, $message);
            }
        }
        catch(\Exception $ex) {
            return $this->createResult(Result::FAILURE_UNCATEGORIZED, null, $ex->getMessage());
        }
    }
    
    private function createResult($result, $ident = null, $message = null)
    {
        $attemptContainer = new Container('lastAttempt');
        $attemptContainer->username = $this->_username;
        return new Result($result, $ident, [$message]);
    }

}
