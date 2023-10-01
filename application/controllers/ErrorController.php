<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $this->_helper->layout->setLayout('layout-system');
        $errors = $this->_getParam('error_handler');
        //если произошла ошибка при обработке ajax запроса
        //fictive change
        if ($this->getRequest()->isXmlHttpRequest()){
        	
        	//$this->_helper->contextSwitch()->initJsonContext();
            $this->_helper->AjaxContext()->addActionContext('error', 'json')->initContext('json');

        	$this->view->result = false;

        	if ($this->getInvokeArg('displayExceptions') == true) {
        		// Add exception error message
        		$this->view->exception = $errors->exception->getMessage();
                $this->view->code = $errors->exception->getCode();

        		// Send stack trace
        		$this->view->trace = $errors->exception->getTrace();

        		// Send request params
        		$this->view->request = $this->getRequest()->getParams();
        	}

        	//echo Zend_Json::encode($response);
            _write_php_exception($errors->exception->getMessage(), $errors->exception->getTrace(), $errors->request->getParams());
        	return;
        }

        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                //$this->view->message = 'Application error';
                $this->view->message = $errors->exception->getMessage();
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
        _write_php_exception($this->view->message, $errors->exception->getTrace(), $errors->request->getParams());
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}

