<?php

/**
 * Application bootstrap
 * 
 * @uses    Zend_Application_Bootstrap_Bootstrap
 * @package QuickStart
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
     * Bootstrap autoloader
     * 
     * @return void
     */
	public function _initAutoload()
	{
		new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Api',
			'basePath'  => APPLICATION_PATH . '/modules/api'
		));
	}

	/**
     * Bootstrap autoloader
     * 
     * @return void
     */
	public function _initModifiedSession()
	{
		//Zend_Session::setOptions(array('cookie_domain' => '.oauthtests.com'));
		$this->bootstrap('session');
	}
    
    protected function _initSessions()
    {
        Zend_Session::start();
    }
    /**
     * Bootstrap db
     * 
     * @return void
     */
    public function _initDbAdapter()
    {
    	$this->bootstrap('multidb');
    	$resource = $this->getPluginResource('multidb');
    	
    	Zend_Registry::set('db',$resource);
    }
    
    /**
     * Bootstrap mail
     * 
     * @return void
     */
    public function _initTesteMail()
    {
    	
    }
    
    /**
     * Bootstrap the locale
     * 
     * @return void
     */
    protected function _initLocale()
    {
    	date_default_timezone_set('America/Sao_Paulo');
    	
    	$locale = new Zend_Locale('pt_BR');
    	Zend_Registry::set('Zend_Locale', $locale);
    	Zend_Locale::setDefault($locale);
    	
   		Zend_Controller_Front::getInstance()
    		->getRouter()
    		->setGlobalParam('language',strtolower($locale->getRegion()));
    	
    		
    	$translate = new Zend_Translate(array(
    		'adapter' => 'csv',
    		'content' => APPLICATION_PATH . '/languages/default/' . $locale->toString() . '.csv'
    	));
    	
    	Zend_Registry::set('Zend_Translate', $translate);
    }
    
    /**
     * Bootstrap routes
     * 
     * @return void
     */
    protected function _initRoutes()
    {
        $routesConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini',APPLICATION_ENV);
    	//Zend_Controller_Front::getInstance()->getRouter()->removeDefaultRoutes();
    	Zend_Controller_Front::getInstance()
    		->getRouter()
    		->addConfig($routesConfig,'routes');
                    $this->bootstrap('frontController');
       /* $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route($frontController);
        $frontController->getRouter()->addRoute('default', $restRoute);
        $this->bootstrap('frontController');*/
    }
	
    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_RDFA');
        
        /*$navigationConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/navigation.ini');
        Zend_Registry::set('navigation_options',$navigationConfig->navigation->toArray());
        
        $container = new Zend_Navigation($navigationConfig->navigation->toArray());
        $view->navigation()->setContainer($container);
        Zend_Registry::set('Zend_View_Navigation',$view->navigation());*/
    }
    
	/**
     * Initialize the layout loader
     * 
     */
    protected function _initLayoutHelper()
    {
    	Zend_Controller_Front::getInstance()
    		->registerPlugin(new Sebold_Controller_Plugin_CompressResponse());
    	
    	// Zend_Controller_Front::getInstance()
    	// 	->registerPlugin(new App_Controller_Action_Helper_Navigation());
    		
    	Zend_Controller_Action_HelperBroker::addHelper(
    		new Sebold_Controller_Action_Helper_LayoutLoader());
    	
//    	Zend_Controller_Action_HelperBroker::addHelper(
//    		new Sebold_Controller_Action_Helper_LayoutHead());
    }
    
    public function _initCache()
    {
//        $frontendOptions = array(
//        	'lifetime' => 1000,
//        	'default_options' => array(
//        		'cache' => false
//            ),
//            'regexps' => array(
//                '^(.*)?$' => array('cache' => true,
//                    'cache_with_get_variables' => true,
//                    'cache_with_session_variables' => true,
//                    'cache_with_cookie_variables' => true,
//                    'make_id_with_get_variables' => true,
//                    'make_id_with_session_variables' => true,
//                    'make_id_with_cookie_variables' => false
//                    )
//                ),
//                'debug_header' => false
//            );
//            $backendOptions = array(
//            'cache_dir' => APPLICATION_PATH . '/../tmp/'
//        );
//	    $cache = Zend_Cache::factory('Page','File',$frontendOptions,$backendOptions);
//        $cache->start();
    }
}
