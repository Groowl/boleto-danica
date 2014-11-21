<?php
/**
 * PAIVA
 * Biblioteca de apoio para o desenvolvimento em Zend Framework
 * 
 * --
 * 
 * @author     Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @copyright  2011 Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @version    1.0.1 (2011-12-30)
 * @license    http://opensource.org/licenses/mit-license.php MIT License  
 * @link       https://github.com/marciopaiva/zf1-paiva
 */

require_once 'Doctrine/Common/ClassLoader.php';

class Paiva_Resource_Doctrine extends \Zend_Application_Resource_ResourceAbstract {

    private $_container;

    /**
     * @return \Paiva\Doctrine\Container
     */
    public function init() {
        $config = $this->getOptions();

        $classLoader = new \Doctrine\Common\ClassLoader(
                        'Doctrine'
        );
        $classLoader->register();

        $this->_container = new Paiva\Doctrine\Container($config);

        \Zend_Registry::set('Paiva_Doctrine', $this->_container);

        return $this->_container;
    }

}
