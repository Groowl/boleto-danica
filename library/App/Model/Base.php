<?php

abstract class App_Model_Base
{

	protected $className;
	protected $data = array();
	protected $collumns;
	protected $db = 'portal';
	protected $fieldId = 'id';
	protected $errorMessage = null;

	/**
	 * carrega o objeto
	 *
	 * @return Service_BaseTableModel
	 */
	function __construct($source="")
	{

		if (is_array($source)) { 
			
			$this->bind($source);
			return $this;
			
		} elseif (is_string($source) && ($source != '')) {
			
			return $this->find($source);
		}
		

		$rows = Heerdt_Db_Adapter::get($this->db)
		->fetchAll("SHOW columns FROM $this->className");
		
		$this->collumns = array();
		foreach ($rows as $row) {
			$this->data[$row['Field']] = null;
			$this->collumns[] = $row['Field'];
		}

		return $this;
	}
	
	
	
	
	
	/**
	 * Antes de Salvar o registro
	 */
	function onBeforeSave() { return; }
	
	
	
	
	
	/**
	 * Após salvar o registro
	 */
	function onAfterSave() { return; }
	
	
	
	
	
	/**
	 * Quando der erro ao salvar o registro
	 */
	function onErrorSave() { return; }
	
	
	/**
	 * Pega o Id
	 * @return boolean
	 */
	function getId() {
		return $this->data[$this->fieldId];
	}
	
	/**
	 * Pega o Id
	 * @return boolean
	 */
	function getError() {
		return $this->errorMessage;
	}

	function evalData($array) {

		$keys = array_keys($array);

		foreach ($keys as $key) {
			if (!in_array($key, $this->collumns)) unset($array[$key]);
		}

		return $array;
	}
	
	
	
	/**
	 * Salva o registro
	 * @return boolean
	 */
	function save($data = null) {


		if ($data != null) {

			$data = $this->evalData($data);

			if (!isset($data['id']) || $data['id'] == '') $this->bind($data);
			else $this->data = $data;
		}
		
		$this->onBeforeSave();

		try {

			if ($this->isNew()) {
				
				Heerdt_Db_Adapter::get($this->db)->insert($this->className, $this->data);
			
				$this->data[$this->fieldId] = Heerdt_Db_Adapter::get($this->db)->lastInsertId();

			} else {
					
				Heerdt_Db_Adapter::get($this->db)->update($this->className, $this->data, $this->fieldId.' = '.$this->data[$this->fieldId]);
			}
		} catch (Exception $e) {
			
			App_Log::write($this->className, 'erro', $this->getId(), $e);
			
			$this->onErrorSave();
			$this->errorMessage = $e->getMessage();
			return false;
		}
		
		$this->onAfterSave();
		
		return true;
	}
	
	
	function delete($id) {
		return Heerdt_Db_Adapter::get($this->db)->delete($this->className, $this->fieldId.' = '.$id);
	}
	
	function query($select) {
		Heerdt_Db_Adapter::get($this->db)->query($select);
	}
	
	/**
	 * Busca Pelo ID
	 * @param string $id
	 * @return Service_Model_BaseTable
	 */
	function find($id) {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className)
		->where($this->fieldId.' = ?',$id);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchRow($select);
		$this->data = $row;

	
		return $this;
	}
	
	/**
	 * Busca Pelo ID
	 * @param string $id
	 * @return Service_Model_BaseTable
	 */
	function findOne($id) {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className)
		->where($this->fieldId.' = ?',$id);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchRow($select);

		return $row;
	}
	
	
	
	/**
	 * Busca Pela variável indicada
	 * @param string $id
	 * @return Service_Model_BaseTable
	 */
	function findOneBy($var,$val) {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className)
		->where("$var = ?",$val);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchRow($select);

		$this->data = $row;

		return $row;
	}
	
	/**
	 * Busca Todos Pela variável indicada
	 * @param string $id
	 * @return Service_Model_BaseTable
	 */
	function findAllBy($var,$val) {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className)
		->where("$var = ?",$val);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchAll($select);

		$arr = array();
		foreach ($row as $key => $value) {
			
			$a = self::getInstance();

			$a->bind($value);

			$arr[] = $a;

		}

		return $arr;
	}
	
	/**
	 * Busca Todos Pela variável indicada
	 * @return Service_Model_BaseTable
	 */
	function findAll() {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchAll($select);

		$arr = array();
		foreach ($row as $key => $value) {
			
			$a = self::getInstance();

			$a->bind($value);

			$arr[] = $a;

		}

		return $arr;
	}
	function findAllArray() {
	
		$select = Heerdt_Db_Adapter::getSelect($this->db)
		->from($this->className);
			
		$row = Heerdt_Db_Adapter::get($this->db)->fetchAll($select);

		return $row;
	}
	
	function findByArray($array) {
		
		$array = $this->evalData($array);

		$keys = array_keys($array);                          // get the value of keys
		$rows = array();                                     // create a temporary storage for rows
		foreach($keys as $key) 
		{                                                    // loop through
		    $value = $array[$key]; 
			
			$rows[] = "" . $key . " = '" . $value . "'";  
		}


		$select = Heerdt_Db_Adapter::getSelect($this->db)
					->from($this->className);
		if (count($rows) > 0) $select->where(implode(' AND ',$rows));

		$row = Heerdt_Db_Adapter::get($this->db)->fetchAll($select);

		return $row;
	}
	
	
	/**
	 * Return Static Class
	 * @param array $data
	 */
	public static function getInstance() {
		return new static();
	}
	
	
	
	
	
	/**
	 * Merge Data Array
	 * @param array $data
	 */
	function bind($data) {
		$this->data = array_merge($this->data,$data);
	}
	
	
	
	
	
	/**
	 * Retorna se é um novo objeto
	 * @return boolean
	 */
	function isNew() {
		return ($this->data[$this->fieldId] == '') ? 1 : 0;
	}
	
	
	
	
	
	/**
	 * Retorna os dados em Array
	 * @return array
	 */
	function toArray() {
		return $this->data;
	}
	
	
	
	
	/**
	 * função para chamar os 'getters' e 'setters' dinamicamente
	 *
	 * @return string
	 */
	public function __call($metodo,$argumentos) {
		$matches = array();
        if (preg_match('/^get(\w+?)?$/', $metodo, $matches)) {
			$funcao = Zend_Filter::filterStatic($matches[1], 'Word_CamelCaseToUnderscore');
			$funcao = Zend_Filter::filterStatic($funcao, 'StringToLower');

			if (isset($this->data[$funcao]) || $this->data[$funcao] === null) {
				return $this->data[$funcao];
			} else {
				$value = isset($matches[1]) ? $matches[1] : 'undefined';
				throw new Zend_Exception($this->className.' | Campo não encontrado on get ' . $funcao . ' (' . $value . ')');
			}
        } elseif (preg_match('/^set(\w+?)?$/', $metodo, $matches)) {

			$funcao = Zend_Filter::filterStatic($matches[1], 'Word_CamelCaseToUnderscore');
			$funcao = Zend_Filter::filterStatic($funcao, 'StringToLower');
			if (isset($this->data[$funcao])) {
				$this->data[$funcao] = $argumentos[0];
				return true;
			} else {
				$value = isset($matches[1]) ? $matches[1] : 'undefined';
				throw new Zend_Exception($this->className.' | Campo não encontrado on set ' . $funcao . ' (' . $value . ')');
			}
        } elseif (preg_match('/^findOneBy(\w+?)?$/', $metodo, $matches)) {
			$funcao = Zend_Filter::filterStatic($matches[1], 'Word_CamelCaseToUnderscore');
			$funcao = Zend_Filter::filterStatic($funcao, 'StringToLower');
			
			if (isset($this->data[$funcao])) {
				return $this->findOneBy($funcao,$argumentos[0]);
			} else {
				$value = isset($matches[1]) ? $matches[1] : 'undefined';
				throw new Zend_Exception($this->className.' | Campo não encontrado on findOneBy ' . $funcao . ' (' . $value . ')');
			}
			
        } elseif (preg_match('/^findAllBy(\w+?)?$/', $metodo, $matches)) {
			$funcao = Zend_Filter::filterStatic($matches[1], 'Word_CamelCaseToUnderscore');
			$funcao = Zend_Filter::filterStatic($funcao, 'StringToLower');
			
			if (isset($this->data[$funcao])) {
				return $this->findAllBy($funcao,$argumentos[0]);
			} else {
				$value = isset($matches[1]) ? $matches[1] : 'undefined';
				throw new Zend_Exception($this->className.' | Campo não encontrado on findAllBy ' . $funcao . ' (' . $value . ')');
			}

			
			
        }
        
        throw new Zend_Exception('Função não encontrada');
	}

}
