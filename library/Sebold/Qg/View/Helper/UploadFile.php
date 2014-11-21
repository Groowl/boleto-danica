<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sebold_Qg_View_Helper_UploadFile extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'QGUploadFile' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function uploadFile($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        
        // is it disabled?
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }
        
        $attribs['class'] = 'upload-file';
        $attribs['config'] = Zend_Json::encode($attribs['upload']);
        unset($attribs['upload']);
        
        $xhtml = '<div '
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . '></div> <br /> <br />';
        return $xhtml;
    }

}
