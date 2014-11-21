<?php

/**
 * PAIVA
 * Biblioteca de apoio para o desenvolvimento em Zend Framework
 * 
 * --
 * 
 * @author     Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @copyright  2002 Stephen Clay <steve@mrclay.org>
 * @copyright  2011 Marcio Paiva Barbosa <mpaivabarbosa@gmail.com>
 * @version    1.0.0 (2011-10-27)
 * @license    http://opensource.org/licenses/mit-license.php MIT License  
 * @link       https://github.com/marciopaiva/zf1-paiva
 */


/**
 * @see https://github.com/mrclay/minify/blob/master/min/lib/Minify/CSS.php
 */
class Paiva_Minify_CSS {

    /**
     * Minify a CSS string
     * 
     * @param string $css
     * 
     * @param array $options available options:
     * 
     * 'preserveComments': (default true) multi-line comments that begin
     * with "/*!" will be preserved with newlines before and after to
     * enhance readability.
     * 
     * 'prependRelativePath': (default null) if given, this string will be
     * prepended to all relative URIs in import/url declarations
     * 
     * 'currentDir': (default null) if given, this is assumed to be the
     * directory of the current CSS file. Using this, minify will rewrite
     * all relative URIs in import/url declarations to correctly point to
     * the desired files. For this to work, the files *must* exist and be
     * visible by the PHP process.
     *
     * 'symlinks': (default = array()) If the CSS file is stored in 
     * a symlink-ed directory, provide an array of link paths to
     * target paths, where the link paths are within the document root. Because 
     * paths need to be normalized for this to work, use "//" to substitute 
     * the doc root in the link paths (the array keys). E.g.:
     * <code>
     * array('//symlink' => '/real/target/path') // unix
     * array('//static' => 'D:\\staticStorage')  // Windows
     * </code>
     * 
     * @return string
     */
    public static function minify($css, $options = array()) {

        if (isset($options['preserveComments'])
                && !$options['preserveComments']) {

            $css = Paiva_Minify_CSS_Compressor::process($css, $options);
        } else {

            $css = Paiva_Minify_CSS_Comment::process(
                            $css
                            , array('Paiva_Minify_CSS_Compressor', 'process')
                            , array($options)
            );
        }
        if (!isset($options['currentDir']) && !isset($options['prependRelativePath'])) {
            return $css;
        }

        if (isset($options['currentDir'])) {
            return Paiva_Minify_CSS_UriRewriter::rewrite(
                            $css
                            , $options['currentDir']
                            , isset($options['docRoot']) ? $options['docRoot'] : $_SERVER['DOCUMENT_ROOT']
                            , isset($options['symlinks']) ? $options['symlinks'] : array()
            );
        } else {
            return Paiva_Minify_CSS_UriRewriter::prepend(
                            $css
                            , $options['prependRelativePath']
            );
        }
    }

}
