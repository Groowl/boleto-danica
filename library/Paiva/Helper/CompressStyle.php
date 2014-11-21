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
class Paiva_Helper_CompressStyle extends Zend_View_Helper_HeadLink {

    /**
     * @var string
     */
    protected $_defaultCacheDir = '/cached/css/';

    /**
     * Object for file processing
     *
     * @var Paiva_Helper_Head_FileJavaScript
     */
    protected $_processor = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->setConfig();
    }

    /**
     * Processing helper
     *
     * Compress files using toString convertion
     * or just return headLink helpers result if combine options set to FALSE
     *
     * @param  array|Zend_Config|null $config
     * @return string
     */
    public function compressStyle($config=null) {
        if (null !== $config) {
            $this->setConfig($config);
        }

        return $this->getOption('combine', true) ? $this->toString() : $this->view->headLink();
    }

    /**
     * Retrieve string representation
     *
     * @param  string|int $indent
     * @return string
     */
    public function toString($indent = null) {
        $headLink = $this->view->headLink();

        $indent = (null !== $indent) ? $headLink->getWhitespace($indent) : $headLink->getIndent();
        $items = array();

        $headLink->getContainer()->ksort();

        foreach ($headLink as $item) {
            if (!$headLink->_isValid($item)) {
                continue;
            }

            if (!$this->getProcessor()->isCachable($item)) {
                $items[] = $this->itemToString($item);
            } else {
                $this->getProcessor()->cache($item);
            }
        }

        // Add to items list HTML container with compiled items
        array_unshift($items, $this->itemToString($this->_getCompiledItem()));
        return implode($headLink->getSeparator(), $items);
    }

    /**
     * Build STYLE conteiner for given item (HTML reprsentation)
     *
     * @param  stdClass $item
     * @return string
     */
    public function itemToString(stdClass $item) {
        $attributes = (array) $item;
        $link = '<link ';

        foreach ($this->_itemKeys as $itemKey) {
            if (isset($attributes[$itemKey])) {
                if (is_array($attributes[$itemKey])) {
                    foreach ($attributes[$itemKey] as $key => $value) {
                        $link .= sprintf('%s="%s" ', $key, ($this->_autoEscape) ? $this->_escape($value) : $value);
                    }
                } else {
                    $link .= sprintf('%s="%s" ', $itemKey, ($this->_autoEscape) ? $this->_escape($attributes[$itemKey]) : $attributes[$itemKey]);
                }
            }
        }

        $link .= ($this->view instanceof Zend_View_Abstract) ? ( $this->view->doctype()->isXhtml()) ? '/>' : '>' : '/>';

        if (($link == '<link />') || ($link == '<link >')) {
            return '';
        }

        if (!empty($attributes['conditionalStylesheet']) && is_string($attributes['conditionalStylesheet'])) {
            $link = '<!--[if ' . $attributes['conditionalStylesheet'] . ']> ' . $link . '<![endif]-->';
        }

        return $link;
    }

    /**
     * Compile full list of files in $this->_cache array
     *
     * @return string
     */
    protected function _getCompiledItem() {
        $fileProcessor = $this->getProcessor();
        $path = $fileProcessor->getServerPath(
                $this->getOption('dir') . $fileProcessor->fullFilename(md5(serialize($fileProcessor->getCache())))
        );
        if (!file_exists($path)) {
            // @todo: verificar a solução implementada no pacote minify
            $dir = dirname($path);
            if (!is_dir($dir) && !@mkdir($dir, 0777, true)) {
                throw new Exception('Impossible to create destination directory ' . $dir);
            }

            $cssContent = '';
            foreach ($fileProcessor->getCache() as $css) {
                $content = file_get_contents($css['filepath']);

                $cssContent .= $this->getOption('compress', true) ? Paiva_Minify_CSS::minify(
                                $content, array(
                            'prependRelativePath' => dirname($path),
                            'currentDir' => dirname($css['filepath']),
                            'symlinks' => $this->getOption('symlinks')
                                )
                        ) : Paiva_Minify_CSS_UriRewriter::rewrite(
                                $content, dirname($css['filepath']), $this->_getCompiledItem(''), $this->getOption('symlinks')
                        );
                $cssContent .= "\n\n";
            }

            file_put_contents($path, $cssContent);
            $fileProcessor->gzip($path, $cssContent);
        }
        
        return $this->createDataStylesheet(array('href' => $this->getProcessor()->getWebPath($path)));
    }

    /**
     * Set configuration, possible to use array or Zend_Config object
     *
     * @param  array|Zend_Config $config
     * @return null
     */
    public function setConfig($config=null) {

        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        } elseif (!is_array($config)) {
            $config = array();
        }

        $config = array_merge(array(
            'dir' => $this->_defaultCacheDir,
            'extension' => 'css'
                ), $config);
        return $this->getProcessor()->setConfig($config);
    }

    /**
     * Return option from current object configuration
     *
     * @param  string $name
     * @param  mixed  $defaultValue
     * @return mixed
     */
    public function getOption($name, $defaultValue=null) {
        return $this->getProcessor()->getOption($name, $defaultValue);
    }

    /**
     * Create file processing object or return existen
     *
     * @return Paiva_Helper_Head_FileStyleSheet
     */
    public function getProcessor() {
        if (null === $this->_processor) {
            $this->setProcessor(new Paiva_Helper_Head_FileStyleSheet());
        }

        return $this->_processor;
    }

    /**
     * Set file processor object using FileAbstract for dependency keeping
     *
     * @param  Paiva_Helper_Head_FileAbstract $processor
     * @return $this
     */
    public function setProcessor(Paiva_Helper_Head_FileAbstract $processor) {
        $this->_processor = $processor;
        return $this;
    }

}