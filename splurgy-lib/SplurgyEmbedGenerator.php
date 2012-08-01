<?php

/*
 * This class will check for the template folder and be able to generate the
 * proper template.
 */
require_once 'Exceptions.php';
require_once 'TemplateGenerator.php';

class SplurgyEmbedGenerator extends TemplateGenerator
{
    private $_token;
    private $_filename;
    private $_offerId;
    private $_patterns = array();
    private $_replacements = array();
    private $_templateGenerator;

    public function __construct($token, $filename=null, $offerId=null)
    {
        $this->_token = $token;
        $this->_filename = $filename;
        $this->_offerId = $offerId;
        $path = dirname(__FILE__). '/embed-templates/';
        $this->patternsAndReplacementsInit();
        parent::__construct($filename, $path, $this->_patterns, $this->_replacements);
    }

    public function patternsAndReplacementsInit() {
        // Use string replace later on instead of regex
        $this->_patterns[] = '{$token}';
        $this->_patterns[] = '{$offerid}';

        $this->_replacements[] = $this->_token;
        $this->_replacements[] = $this->_offerId;
    }
}

?>
