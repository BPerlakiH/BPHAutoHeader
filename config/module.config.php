<?php
/**
 * Copyright (c) 2014, Balazs Perlaki-Horvath.
 * All rights reserved.
 */

return array(
    
    'bph' => array(
        'headerFile' => '/config/headers.yml',
        'public_dir' => '/public',
        'css_dir' => 'css',
        'js_dir' => 'js',
        'log_file' => './data/logs/bph-autoheader.log',
    ),
    
    'service_manager' => array(
      
      'factories' => array(
        
          'BPHAutoHeader\AutoHeader' => function($sm) {
            $config = $sm->get('Config');
            $pConfig = $config['bph'];
            $autoHeader = new \BPHAutoHeader\AutoHeader($pConfig['headerFile'], $pConfig['public_dir']);
            $env = getenv('APP_ENV') ?: 'production';
            if($env == "development") {
              $autoHeader->setLogger($sm->get('Zend\Log'));
            }
            $autoHeader->cssDir = $pConfig['css_dir'];
            $autoHeader->jsDir = $pConfig['js_dir'];
            return $autoHeader;
          },
            
          'Zend\Log' => function ($sm) {
            $config = $sm->get('Config');
            $pConfig = $config['bph'];
            $logFile = $pConfig['log_file'];
            
            if(!is_dir(dirname($logFile))) mkdir(dirname($logFile), 0775, true);
            if(!file_exists($logFile)) touch($logFile);

            $log = new Zend\Log\Logger();
            $writer = new Zend\Log\Writer\Stream($logFile);
            $log->addWriter($writer);

            return $log;
          },
      ),
    ),
      
);