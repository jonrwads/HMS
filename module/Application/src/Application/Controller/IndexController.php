<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Zend\Authentication\Result;

class IndexController extends AbstractActionController {
    
    public function indexAction() {
        $auth = new \Zend\Authentication\AuthenticationService();
        $data = [];
        
        if($auth->hasIdentity()){            
            $this->layout("layout/layout");
            $data = $auth->getIdentity();
            $viewModel = new ViewModel($data);
            $viewModel->setTemplate('application/home');
        }
        else {
            $this->layout("layout/login");
            $viewModel = new ViewModel();
        }
        return $viewModel;
    }
    
    public function loginAction() {
        $params = $this->params()->fromPost();
        $authAdapter = new \Application\Library\AuthAdapter($this->getServiceLocator(), $params['username'], $params['password']);
        $auth = new \Zend\Authentication\AuthenticationService();
        $result = $auth->authenticate($authAdapter);
        if( $result->getCode() == Result::SUCCESS ) {
            $this->layout("layout/layout");
        }
        else {            
            $this->redirect()->toRoute();
        }
        $this->redirect()->toRoute();
        return new ViewModel();
    }
    
    public function logoutAction() {
        $auth = new \Zend\Authentication\AuthenticationService();
        $auth->clearIdentity();
        $this->redirect()->toRoute();
    }
}
