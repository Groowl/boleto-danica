<?php

/**
 * PAIVA
 * Biblioteca de apoio para o desenvolvimento em Zend Framework
 * 
 * --
 * 
 * @author     Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @copyright  2011 Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @copyright  2011 Alex S. Kachayev <kachayev@gmail.com>
 * @version    1.0.0 (2011-10-27)
 * @license    http://opensource.org/licenses/mit-license.php MIT License  
 * @link       https://github.com/marciopaiva/zf1-paiva
 */

abstract class Paiva_Helper_Head_FileAbstract {

    /**
     * Configuration for compressor working
     *
     * @var array
     */
    protected $_config = array(
        'dir' => '',
        'extension' => '',
        'combine' => true,
        'compress' => true,
        'symlinks' => array(),
        'gzcompress' => 9,
    );

    /**
     * Set configuration, possible to use array or Zend_Config object
     *
     * @param  array|Zend_Config $config
     * @return null
     */
    public function setConfig($config=null) {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        } elseif (is_null($config)) {
            $config = array();
        }

        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * Return option from current object configuration
     *
     * @param  string $name
     * @param  mixed  $defaultValue
     * @return mixed
     */
    public function getOption($name, $defaultValue=null) {
        return array_key_exists($name, $this->_config) ? $this->_config[$name] : $defaultValue;
    }

    /**
     * Check if file/source is cachable
     *
     * @param  array  $item
     * @return boolen
     */
    abstract public function isCachable($item);

    /**
     * Add given item (by source or file path) to caching queue
     *
     * @param  array $item
     * @return null
     */
    abstract public function cache($item);

    /**
     * Build full filename by ending it with JS extension and IS_COMPRESSED suffix
     *
     * @param  string $filename
     * @return string
     */
    abstract public function fullFilename($filename);

    /**
     * Build web path from server variant
     *
     * @param  string $path
     * @return string
     */
    abstract public function getWebPath($path);

    /**
     * Build server path from relative one
     *
     * @param  string $path
     * @return string
     */
    abstract public function getServerPath($path);
}