<?php
/**
 * All wordpress hooks should go here.
 *
 * PHP version 5.3.1
 * 
 */


/**
 * WordPress Hooks Class Definition
 *
 * @category WordPressHooks
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */

class WordpressHooks
{
    private $_shortCodes;
    private $_wpSettingsView;
    private $_wpAdminView;
    
    /**
     * Wordpress Hooks construct
     * 
     * @param type $shortCodes shortCodes variable
     */
    public function __construct(shortCodes $shortCodes)
    {
        $this->_shortCodes = $shortCodes;
        add_shortcode('splurgy_adunit', array($this->_shortCodes, 'adUnit'));
        register_deactivation_hook(dirname(__FILE__). '/WordpressInit.php', array($this, 'deactivate'));
    }

    /**
     * Clears database data from V3 
     *      
     */
    public function deactivate() 
    {   
        delete_option('splurgyToken');
    }
}

?>
