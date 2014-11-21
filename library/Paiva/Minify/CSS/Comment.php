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
 * @see https://github.com/mrclay/minify/blob/master/min/lib/Minify/CommentPreserver.php
 */
class Paiva_Minify_CSS_Comment {

    /**
     * String to be prepended to each preserved comment
     *
     * @var string
     */
    public static $prepend = "\n";

    /**
     * String to be appended to each preserved comment
     *
     * @var string
     */
    public static $append = "\n";

    /**
     * Process a string outside of C-style comments that begin with "/*!"
     *
     * On each non-empty string outside these comments, the given processor
     * function will be called. The comments will be surrounded by
     * Minify_CommentPreserver::$preprend and Minify_CommentPreserver::$append.
     *
     * @param string $content
     * @param callback $processor function
     * @param array $args array of extra arguments to pass to the processor
     * function (default = array())
     * @return string
     */
    public static function process($content, $processor, $args = array()) {
        $ret = '';
        while (true) {
            list($beforeComment, $comment, $afterComment) = self::_nextComment($content);
            if ('' !== $beforeComment) {
                $callArgs = $args;
                array_unshift($callArgs, $beforeComment);
                $ret .= call_user_func_array($processor, $callArgs);
            }
            if (false === $comment) {
                break;
            }
            $ret .= $comment;
            $content = $afterComment;
        }
        return $ret;
    }

    /**
     * Extract comments that YUI Compressor preserves.
     *
     * @param string $in input
     *
     * @return array 3 elements are returned. If a YUI comment is found, the
     * 2nd element is the comment and the 1st and 3rd are the surrounding
     * strings. If no comment is found, the entire string is returned as the
     * 1st element and the other two are false.
     */
    private static function _nextComment($in) {
        if (
                false === ($start = strpos($in, '/*!'))
                || false === ($end = strpos($in, '*/', $start + 3))
        ) {
            return array($in, false, false);
        }
        $ret = array(
            substr($in, 0, $start)
            , self::$prepend . '/*!' . substr($in, $start + 3, $end - $start - 1) . self::$append
        );
        $endChars = (strlen($in) - $end - 2);
        $ret[] = (0 === $endChars) ? '' : substr($in, -$endChars);
        return $ret;
    }

}
