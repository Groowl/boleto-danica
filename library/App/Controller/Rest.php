<?php

class App_Controller_Rest extends Zend_Rest_Controller {

    protected $model;

    public function preDispatch() {

        parent::preDispatch();

        $request        = Zend_Controller_Front::getInstance()->getRequest();
        $this->data     = null;
        $this->model    = null;

        $modelName = 'Api_Model_'.ucwords($request->getControllerName());
        if (class_exists($modelName)) $this->model = new $modelName();

        $this->status = 'success';
        $this->config = array();
        $this->config['module']   = $request->getModuleName();
        $this->config['controller'] = $request->getControllerName();
        $this->config['action']   = $request->getActionName();
    }


    public function postDispatch() {

        header('Content-type: text/json');
        header('Content-type: application/json');


       /* if (is_array($this->data)) {
            
            // $this->data['__config'] = $this->config;
            $this->data['status'] = $this->status;

            echo json_encode($this->data);
        } else {*/
        echo json_encode(array('data' => $this->data, 'config' => $this->config, 'status' => $this->status));    
        //}
        
        exit;
    }
    
    public function indexAction()
    {
        if ($this->model != null)
            $this->data = $this->model->findByArray($this->getRequest()->getParams());
    }

    public function getAction() {
       if ($this->model != null)
            $this->data = $this->model->findOne($this->getRequest()->getParam('id'));
    }   
    public function postAction()
    {
        if ($this->model->save($this->getRequest()->getParams())) {
            $this->data = $this->model->toArray();
        } else {
            $this->status = 'error';
            $this->data = $this->model->getError();
        }
    }
    
    public function putAction()
    {
        if ($this->model->save($this->getRequest()->getParams())) {
            $this->data = $this->model->toArray();
        } else {
            $this->status = 'error';
            $this->data = $this->model->getError();
        }
    }
    
    public function deleteAction()
    {

        if (!$this->model->delete($this->getRequest()->getParam('id'))) {
            $this->status = 'error';
            $this->data = $this->model->getError();
        }
    }

}