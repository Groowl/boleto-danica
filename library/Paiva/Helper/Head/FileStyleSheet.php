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

class Paiva_Helper_Head_FileStyleSheet extends Paiva_Helper_Head_FileJavaScript {

    /**
     * Return path to file described in item
     *
     * @param  stdClass $item
     * @return string|null
     */
    protected function _getItemPath($item) {
        return empty($item->href) ? null : $item->href;
    }

    /**
     * Return conditional attributes for item
     *
     * @param  stdClass $item
     * @return string|null
     */
    protected function _getItemConditional($item) {
        return isset($item->conditionalStylesheet) ? $item->conditionalStylesheet : false;
    }

}