<?php

class App_Log
{
	
	static function write($tabela,$tipo,$id,$data) {
		try {
			serialize($data);
		} catch (Exception $e) {
			print_r($data); 
			exit;
		}
		Heerdt_Db_Adapter::get()->insert('log', array(
			'table' => $tabela, 
			'parent_id' => $id,
			'type' => $tipo,
			'created_at' => date('Y-m-d H:i:s'),
			'buffer' => serialize($data)
		));
	}
	
}