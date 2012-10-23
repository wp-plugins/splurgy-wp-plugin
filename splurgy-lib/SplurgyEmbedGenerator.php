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
    private $_testmode;
    private $_unlocktext;
    private $_patterns = array();
    private $_replacements = array();
    private $_templateGenerator;

    public function __construct($token, $filename=null, $offerId=null, $testmode=null, $unlocktext=null)
    {
        $this->_token = $token;
        $this->_filename = $filename;
        $this->_offerId = $offerId;
        $this->_testmode = $testmode;
        $this->_unlocktext = $unlocktext;
        $path = dirname(__FILE__). '/embed-templates/';
        $this->patternsAndReplacementsInit();
        parent::__construct($filename, $path, $this->_patterns, $this->_replacements);
    }

    public function patternsAndReplacementsInit() {
        // Use string replace later on instead of regex
        $this->_patterns[] = '{$token}';
        $this->_patterns[] = '{$offerid}';
        $this->_patterns[] = '{$testmode}';
        $this->_patterns[] = '{$unlocktext}';

        $this->_replacements[] = $this->_token;
        $this->_replacements[] = $this->_offerId;
        $this->_replacements[] = $this->_testmode;
        $this->_replacements[] = $this->_unlocktext;
    }
}

?>
