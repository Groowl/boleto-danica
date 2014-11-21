<?php

class App_Db_Adapter
{
	
     /**
     * retorna sempre o db adapter
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function get($database = 'portal')
    {

        $conexao = Zend_Registry::get('db')->getDb($database);

        if ($database == "portal") {
            //$conexao->query("SET time_zone='America/Sao_Paulo';");
        }

        return $conexao;
    }
    
	/**
     * retorna sempre um novo select com o db adapter
     *
     * @return Zend_Db_Select
     */
    public static function getSelect($database = 'portal')
    {
        $select = new Zend_Db_Select(self::get($database));

        return $select;
    }
    
}