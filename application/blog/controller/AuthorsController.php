<?php
class Blog_AuthorsController extends Zend_Controller_Action 
{
	/**
	 * Contiene la instancia de Zend_Db
	 * @var $_db
	 */
	private $_db;
	public function preDispatch()
	{
    	/**
    	 * Traemo los datos del archivo de configuaracion
    	 */
    	$registry = Zend_Registry::getInstance();
		$config = $registry->get( 'config_ini' );
		/**
		 * Sete el layout que voy a usar en este caso va a ser admin
		 * 
		 */
		$this->_helper->layout->setLayout('admin/index');
    	/**
         * Url basicas del sistema
         */
        $this->view->staticServer = $config->site->static->server;
        $this->view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->view->headTitle()->append('Admin de Blogzf');
        /**
         * Agrego los css para esta pagina
         */
        $this->view->headLink()
            ->appendStylesheet( $this->view->staticServer . 'layout/admin/css/style.css' );
        /**
         * Agrego los js basicos - POr ahora ninguno
         */
        /*
         $this->view->headScript()
			->appendFile( $this->view->staticServer . '/js/mootools/mootools.js');
		 */
         /**
          * Nos conectamos a la base de datos. 
          */
		$this->_db = Zend_Db::factory( 
			$config->database->db->adapter, 
			$config->database->db->config
			->toArray() );
	}
	public function createAction()
	{
        $form = new forms_Authors();
	    if ( $this->_request->isPost() ) {
		    $authorData = $this->_request->getPost();
		    if ( $form->isValid( $authorData ) ) {
		        /**
		         * Si los datos estan ok, Creamos el registro
		         */
		        unset( $authorData['submit']);
		        $authors = new Authors();
		        $authorData['password'] = md5( $authorData['password'] );
		        $authorId = $authors->add( $userData );
		        $this->_redirect( '/authors/read/' );
		    } else {
		        $form->populate( $userData );
		    }
	    }
	    $this->form = $form;
	}
	public function readAction()
	{
		/**
		 * Configuramos el paginador, para que me traiga todos los resultados paginados.
		 */
		Zend_Paginator::setDefaultScrollingStyle( 'all' );
		/**
		 * Este es el tpl que voy a usar como paginador
		 */
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $query = $this->_db->select()->from( 'authors' );
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
	}
	public function updateAction()
	{
		
	}
	public function deleteAction()
	{
		
	}
}
