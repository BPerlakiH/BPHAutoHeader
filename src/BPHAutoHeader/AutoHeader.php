<?php

/**
 * @author Balazs Perlaki Horvath <ba.perlaki at gmail.com>
 */
namespace BPHAutoHeader;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Log\Logger;

require_once "vendor/spyc.php";

class AutoHeader {
  
  /**
   * @var MvcEvent 
   */
  private $_event;
  
  /**
   * @var ViewModel
   */
  private $_view;
  
  private $_missingUris = []; 
  
  
  /**
   * @var Logger 
   */
  protected $logger;
  
  protected $_appRoute = "Application\\Controller\\";
  protected $_ackey = "";
  
  /**
   * @var array
   */
  protected $_headYaml = array();
  
  public $publicDir = "/public"; //fallback value
  public $cssDir = "css"; //fallback value
  public $jsDir = "js"; //fallback value
  public $headerFilePath = false;
  
  public function __construct($headerFilePath, $publicDir) {
    $this->publicDir = $publicDir;
    $this->headerFilePath = getcwd() . $headerFilePath;
  }
  
  public function setLogger(Logger $logger) {
    $this->logger = $logger;
  }
  
  public function setHeaders(MvcEvent $e) {
    if(!$this->_isValidHeaderFile()) {
      $this->logger->log(Logger::ERR, sprintf("missing header file: %s", $this->headerFilePath));
      return;
    }
    $this->_event = $e;
    $this->_setACKeys();
    $this->_loadHeaders();
    $this->_setHeaders();
  }
  
  private function _isValidHeaderFile() {
    return is_file(realpath($this->headerFilePath));
  }
  
  /**
   * Sets the controller and action keys
   */
  private function _setACKeys() {
    $route = $this->_event->getRouteMatch();
    if(!$route) {
      $this->logger->log(Logger::ERR, "no route match found");
      return;
    }
    $controller = $route->getParam('controller', 'index');
    $action = $route->getParam('action', 'index');
    if(substr($controller, 0, strlen($this->_appRoute)) === $this->_appRoute) {
      $controller = substr($controller, strlen($this->_appRoute));
    }
    $this->_ackey = ($action == "index") ? $controller : ($controller . "/" . $action);
  }
  
  private function _loadHeaders() {
    if(!$this->headerFilePath) {
      $this->logger->log(Logger::ERR, "no header config file was set");
      return;
    }
    $this->_headYaml = spyc_load_file($this->headerFilePath);
    $this->cssDir = isset($this->_headYaml['_cssDir']) ? $this->_headYaml['_cssDir'] : 'css';
    $this->jsDir = isset($this->_headYaml['_jsDir']) ? $this->_headYaml['_jsDir'] : 'js';
  }
  
  private function _setHeaders() {
    $this->_missingUris = [];
    
    $commonCssUris = $this->_getHeadFilesForKey("_common", "_css");
    $acCssUris = $this->_getHeadFilesForKey($this->_ackey, "_css");
    $commonJSUris = $this->_getHeadFilesForKey("_common", "_js");
    $acJsUris = $this->_getHeadFilesForKey($this->_ackey, "_js");
    
    $jsUris = array_merge($commonJSUris, $acJsUris);
    $cssUris = array_merge($commonCssUris, $acCssUris);
    
    $this->_logMissingFiles();
    
    $renderer = $this->_event->getApplication()->getServiceManager()->get('Zend\View\Renderer\PhpRenderer');
    $headLink = $renderer->headLink();
    foreach($cssUris as $cssUri) {
      $headLink->appendStylesheet($cssUri);
    }
    $headScript = $renderer->headScript();
    foreach($jsUris as $jsUri) {
      $headScript->appendFile($jsUri);
    }
  }
  
  private function _getHeadFilesForKey($acKey, $key) {
    $uris = array();
    $subDir = ($key === "_css") ? $this->cssDir : $this->jsDir;
    if(isset($this->_headYaml[$acKey][$key])) {
      $values = $this->_headYaml[$acKey][$key];
      $extension = preg_replace('/_/', ".", $key);
      $fileNames = preg_split('/\,/', $values);
      foreach ($fileNames as $fileName) {
        $fileRelPath = "/" .  $subDir . "/". trim($fileName) . $extension;
        $filePath = getcwd() . $this->publicDir . $fileRelPath;
        if(file_exists($filePath)) {
          $uris[] = $fileRelPath;
        } else {
          $this->_missingUris[] = $filePath;
        }
      }
    }
    return $uris;
  }
  
  private function _logMissingFiles() {
    if (0 < count($this->_missingUris)) {
      $this->logger->log(Logger::NOTICE, sprintf('missing files for: %s', $this->_ackey));
      foreach ($this->_missingUris as $mURI) {
        $this->logger->log(Logger::NOTICE, sprintf("\t%s",$mURI));
      }
    }
  }
}
