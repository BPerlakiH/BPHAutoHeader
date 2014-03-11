<?php

namespace BPHAutoHeader;

use Zend\Mvc\MvcEvent;

/**
 * Copyright (c) 2014, Balazs Perlaki-Horvath.
 * All rights reserved.
 */
class Module {
  
	public function onBootstrap(MvcEvent $e) {
      $eventManager = $e->getApplication()->getEventManager();
      $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'),1);
	}
    
    public function onRender(MvcEvent $e) {
      $serviceManager = $e->getApplication()->getServiceManager();
      $autoHeader = $serviceManager->get('BPHAutoHeader\AutoHeader');
      $autoHeader->setHeaders($e);
    }
    
	public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}