<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $serviceManager = $e->getApplication()->getServiceManager();
        
        $e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('controllerName', function($serviceManager) use ($e) {
            $viewHelper = new \Application\BaseLibrary\ControllerName($e->getRouteMatch());
            return $viewHelper;
        });

        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $doctrineEventManager = $em->getEventManager();
        $doctrineEventManager->addEventListener(array(\Doctrine\ORM\Events::postLoad), new \Application\BaseLibrary\DoctrineInjector($serviceManager) );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
