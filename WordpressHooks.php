<?php
/**
 * All functions to hooks to settings/admin/view page are here.
 * All wordpress hooks should go here.
 *
 * PHP version 5.3.1
 * 
 */


/**
 * WordPress Settings View Class definition
 *
 * @category WordPressSettingsView
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

    }
}

?>
