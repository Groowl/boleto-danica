<?php
class Sebold_Filter{
	public static function get($value, $classBaseName, array $args = array(), $namespaces = array()){
        require_once 'Zend/Loader.php';
        $namespaces = array_merge(array('Sebold_Filter'), (array) $namespaces);
        foreach ($namespaces as $namespace) {
            $className = $namespace . '_' . ucfirst($classBaseName);
            try {
                @Zend_Loader::loadClass($className);
            } catch (Zend_Exception $ze) {
                continue;
            }
            $class = new ReflectionClass($className);
            if ($class->implementsInterface('Zend_Filter_Interface')) {
                if ($class->hasMethod('__construct')) {
                    $object = $class->newInstanceArgs($args);
                } else {
                    $object = $class->newInstance();
                }
                return $object->filter($value);
            }
        }
        require_once 'Zend/Filter/Exception.php';
        throw new Zend_Filter_Exception("Filter class not found from basename '$classBaseName'");
    }
}