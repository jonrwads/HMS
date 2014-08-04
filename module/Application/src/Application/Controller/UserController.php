<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

/**
 * Description of UserController
 *
 * @author jonw
 */
class UserController  extends AbstractActionController {
    public function indexAction() {    
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $em->getRepository('Application\\Model\\User')->findBy([],['username'=>'ASC']);
        $viewModel = new ViewModel(['users'=>$users]);
        $viewModel->setTemplate('application/user/index');
        return $viewModel;
    }
    
    public function deleteAction() {
        $data = $this->params()->fromQuery();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = $em->getReference('Application\\Model\\User', $data['id']);
        $em->remove($user);
        $em->flush();
        $this->redirect()->toUrl('/application/user');
    }
    
    public function createAction() {
        $params = $this->params()->fromPost();
        if(is_null($params) || count($params) == 0) {
            $viewModel = new ViewModel;
            $viewModel->setTemplate('application/user/edit');
            return $viewModel;
        }
        else {
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $user = new \Application\Model\User($this->getServiceLocator());
            $entityGenerator = $this->getServiceLocator()->get('EntityGenerator');
            $msg = '';
            if($entityGenerator->generate($user, $params, $msg)) {
                $auth = new AuthenticationService();
                if($auth->hasIdentity()){
                    $identity = $auth->getIdentity();
                    $user->createdBy = $em->getReference('Application\\Model\\User', $identity['id']);
                    $user->password = sha1($user->password);
                }
                $em->persist($user);
                $em->flush();
                $this->redirect()->toUrl('/application/user');
            }
        }
    }
}
