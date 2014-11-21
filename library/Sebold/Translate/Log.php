<?php

class Sebold_Translate_Log extends Zend_Log_Writer_Abstract
{
    /**
     * array of log events
     */
    public $events = array();

    /**
     * shutdown called?
     */
    public $shutdown = false;

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function _write($event)
    {
    	Default_Model_LocaleLanguage::insert($event['message']);
        $this->events[] = $event;
    }

    /**
     * Record shutdown
     *
     * @return void
     */
    public function shutdown()
    {
        $this->shutdown = true;
    }

    /**
     * Create a new instance of Zend_Log_Writer_Mock
     * 
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Mock
     * @throws Zend_Log_Exception
     */
    static public function factory($config) 
    {
        return new self();
    }
}
