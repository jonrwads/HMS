<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Zend\Authentication\Result;

/**
 * Description of UserController
 *
 * @author jonw
 */
class UserController  extends AbstractActionController {
    public function indexAction() {    
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $em->getRepository('Application\\Model\\User')->findAll();
        $viewModel = new ViewModel(['users'=>$users]);
        $viewModel->setTemplate('application/user/index');
        return $viewModel;
    }
}
