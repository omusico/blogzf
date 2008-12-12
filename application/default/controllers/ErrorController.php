<?php

class ErrorController extends Zend_Controller_Action {

    private static $errorTitle = 'unknown_error';
    private static $httpCode = 500;

    public function init() {
    }

    public function indexAction() {
        $this->_forward('error');
    }

    public function errorAction() {

        $_exception = $this->_getParam('error_handler',null);

        if (isset($_exception)) {

        switch ($_exception->type) {
            case Zend_Config_Exception:
                    self::$httpCode = 500;
                    self::$errorTitle = 'La aplicaci—n no esta configurada';
                        break;

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                self::$httpCode = 404;
                self::$errorTitle = 'La pagina no existe';
                break;

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                                switch (get_class($this->_exception->exception)) {
                      case 'Zend_View_Exception' :
                       self::$httpCode = 500;
                       self::$errorTitle = 'Errror de tratamiento dentro de una vista';
                      break;
                      case 'Zend_Db_Exception' :
                      case 'Zend_Db_Table_Row_Exception':
                       self::$httpCode = 503;
                       self::$errorTitle = 'Error de tratamiento con la base de datos';
                      break;
                      case 'Payment_Exception':
                       self::$httpCode = 500;
                       self::$errorTitle = 'Error procesando el pago';
                           break;
                      default:
                       self::$httpCode = 500;
                       self::$errorTitle = 'Error desconocida';
                       break;
                     }
        }

                $this->view->exception = $_exception['exception'];
                $this->view->message = $this->view->exception->getMessage();

        }


                $this->getResponse()->setHttpResponseCode(self::$httpCode);
                $this->view->title = self::$errorTitle;


        }

}

