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
class Paiva_Helper_CompressScript extends Zend_View_Helper_HeadScript {

    /**
     * @var string
     */
    protected $_defaultCacheDir = '/cached/js/';

    /**
     * Object for file processing
     *
     * @var PaivaHelperHeadFileJavaScript
     */
    protected $_processor = null;

    /**
     * Constructor
     *
     * Set separator to PHP_EOL
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
     * or just return headScript helpers result if combine options set to FALSE
     *
     * @param  array|Zend_Config|null $config
     * @return string
     */
    public function compressScript($config=null) {
        if (null !== $config) {
            $this->setConfig($config);
        }
        return $this->getOption('combine', true) ? $this->toString() : $this->view->headScript();
    }

    /**
     * Retrieve string representation
     *
     * @param  string|int $indent
     * @return string
     */
    public function toString($indent = null) {
        $headScript = $this->view->headScript();
        $indent = is_null($indent) ? $headScript->getIndent() : $headScript->getWhitespace($indent);
        $useCdata = ($this->view) ? $this->view->doctype()->isXhtml() : (bool) $headScript->useCdata;

        list($escapeStart, $escapeEnd) = ($useCdata) ? array('//<![CDATA[', '//]]>') : array('//<!--', '//-->');

        $items = array();
        $headScript->getContainer()->ksort();

        foreach ($headScript as $item) {
            if (!$headScript->_isValid($item)) {
                continue;
            }

            if (!$this->getProcessor()->isCachable($item)) {
                $items[] = $this->itemToString($item, $indent, $escapeStart, $escapeEnd);
            } else {
                $this->getProcessor()->cache($item);
            }
        }

        array_unshift($items, $this->itemToString($this->_getCompiledItem(), $indent, $escapeStart, $escapeEnd));

        return implode($headScript->getSeparator(), $items);
    }

    /**
     * Build SCRIPT conteiner for given item (HTML reprsentation)
     *
     * @param  string $item
     * @param  string $indent
     * @param  string $escapeStart
     * @param  string $escapeEnd
     * @return string 
     */
    public function itemToString($item, $indent, $escapeStart, $escapeEnd) {
        $attrString = '';
        if (!empty($item->attributes)) {
            foreach ($item->attributes as $key => $value) {
                if (!$this->arbitraryAttributesAllowed()
                        && !in_array($key, $this->_optionalAttributes)) {
                    continue;
                }

                if ('defer' == $key) {
                    $value = 'defer';
                }

                $attrString .= sprintf(' %s="%s"', $key, ($this->_autoEscape) ? $this->_escape($value) : $value);
            }
        }

        $type = ($this->_autoEscape) ? $this->_escape($item->type) : $item->type;
        $html = $indent . '<script type="' . $type . '"' . $attrString . '>';

        if (!empty($item->source)) {
            $html = implode(PHP_EOL, array($html,
                $indent . '  ' . $escapeStart,
                $item->source . $indent . '  ' . $escapeEnd,
                $indent));
        }

        $html .= '</script>';

        if (!empty($item->attributes['conditional']) && is_string($item->attributes['conditional'])) {
            $html = '<!--[if ' . $item->attributes['conditional'] . ']> ' . $html . '<![endif]-->';
        }

        return $html;
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
                throw new Zend_View_Exception('Impossible to create destination directory ' . $dir);
            }

            $jsContent = '';
            foreach ($fileProcessor->getCache() as $js) {
                $jsContent .= (is_array($js) ? file_get_contents($js['filepath']) : $js) . ";\n\n";
            }

            if ($this->getOption('compress')) {
                $jsContent = Paiva_Minify_JS::minify($jsContent);
            }

            file_put_contents($path, $jsContent);
            $fileProcessor->gzip($path, $jsContent);
        }

        return $this->createData('text/javascript', array('src' => $fileProcessor->getWebPath($path)));
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

        $config = array_merge(array('dir' => $this->_defaultCacheDir, 'extension' => 'js'), $config);
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
     * @return Paiva_Helper_Head_File
     */
    public function getProcessor() {
        if (null === $this->_processor) {
            $this->setProcessor(new Paiva_Helper_Head_FileJavaScript());
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
