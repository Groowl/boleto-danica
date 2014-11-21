<?php

/**
 * Error controller
 *
 * @uses       Zend_Controller_Action
 * @package    QuickStart
 * @subpackage Controller
 */
class ErrorController extends Zend_Controller_Action
{


    public function errorAction()
    {


         $errors = $this->_getParam('error_handler');
 
        
        $this->view->exception = $errors->exception;

    }
}