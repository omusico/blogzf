<?php
class Blogzf_Controller extends Zend_Controller_Action
{
    protected $_registry;
    protected $_user;
    protected $_roles;
    protected $_config;
    protected $_db;
    public function getDb ()
    {
        return $this->_db;
    }
    public function getConfig ()
    {
        return $this->_config;
    }
    public function getUser ()
    {
        return $this->_user;
    }
    public function init ()
    {
        /**
         * Configuracion de base de datos, archivos config y otros
         */
        $this->_config = new Blogzf_Config();
        $this->_config->load();
        // El generic config debio configurar esto.
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        if ($this->_config->getValue("metadata_cache", "conf", "enabled")) {
            // First, set up the Cache
            $frontendOptions = $this->_config->getValue("metadata_cache", "frontend");
            $backendOptions = $this->_config->getValue("metadata_cache", "backend");
            $cache = Zend_Cache::factory('Core', 'Sqlite', $frontendOptions, $backendOptions);
            if ($this->_config->getValue("metadata_cache", "conf", "clean")) {
                $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
            }
            // Next, set the cache to be used with all table objects
            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }
        /**
         * Configuracion general.
         */
        $this->layoutConfig();
        $this->_session = new Zend_Session_Namespace();
        $this->_registry = Zend_Registry::getInstance();
        $this->initView();
    }
    public function initView ()
    {
        parent::initView();
        $this->view->staticServer = $this->_request->getBaseUrl();
        $this->view->addBasePath('/', '');
        // Setea el path de las vistas de cada modulo
        $this->view->addScriptPath($this->_config->getValue('paths', 'scripts', 'helpers') . $this->getRequest()->getControllerName() . '/');
        // Dojo
        $this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
    }
    public function preDispatch ()
    {
        // if authentication is required make sure user
        // has logged.
        if ($this->_requiresAuth) {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                // Identity exists; get it
                $this->_user = $auth->getIdentity();
            }
            if ($this->_user == false) {
                $this->_redirect('/user/login');
            }
            // add roles to class
            if (count($this->_user->roles) > 0) {
                foreach ($this->_user->roles as $role)
                    $this->_roles[] = $role->name;
            }
        }
        /**
         * Seteamos el menu de la derecha
         */
        $layout = Zend_Layout::getMvcInstance();
        //$layout->sidebar = $this->view->render( $this->_config->getValue( 'templates', 'sidebar', 'file' ) );
    }
    /**
     * Averigua si el usuario tiene permisos o no para acceder a esta seccion
     *
     * @param $resource es el recurso al cual el usuario va a acceder
     * @param $privilege si el usuario puede o no acceder a este recurso
     * @uses isAllowed metodo de Zend_Acl
     * @return true  o false, dependiendo si tiene o no permisos para acceder
     */
    public function allow ($resource, $privilege)
    {
        if (empty($this->_user)) {
            return false;
        }
        $allowed = false;
        foreach ($this->_roles as $role) {
            if ($this->_acl->isAllowed($role, $resource, $privilege)) {
                $allowed = true;
                break;
            }
        }
        return $allowed;
    }
    /**
     * Configuramos los layout que tiene el sistema
     */
    private function layoutConfig ()
    {
        $options = array('layout' => 'Cart' , 'layout' => 'Frontend' , 'layoutPath' => '../layout/');
        Zend_Layout::startMvc($options);
    }
}