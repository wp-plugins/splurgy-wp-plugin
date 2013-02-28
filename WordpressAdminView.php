<?php

/**
 * All functions for hooks and will output HTML should go here.
 * Wordpress is so dirty, we have to echo!
 *
 * PHP version 5.3.1
 *
 * @category WordPressSettingsView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */

require_once 'splurgy-lib/TemplateGenerator.php';

/**
 * WordPressAdminView Class definition
 *
 * @category WordPressAdminView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */

class WordPressAdminView
{
    
    private $_templateGenerator;
    private $_path;
    //private $_messages = array();
    
    /**
     * WordpressAdmin construct function
     */
    public function __construct()
    {
        $this->_templateGenerator = new TemplateGenerator();
        $this->_path = dirname(__FILE__). '/view-templates/';
        $this->_templateGenerator->setPath($this->_path);
    }
    
        /**
     * Displays the content for the Short Code Help from the templates
     *
     * @return type None
     */
    public function pagePostMetaBoxShortCodeHelp()
    {
        $this->_templateGenerator->setTemplateName('pagePostMetaBoxShortCodeHelp');
        echo $this->_templateGenerator->getTemplate();
    }
    
    /**
     * Adds a box to the main column on the Post and Page edit screens
     *
     * @return type None
     */

    public function addPostMetaBoxOfferList()
    {

        // Splurgy Token sections
        add_meta_box(
            'myplugin_sectionid', __('Splurgy Token', 'myplugin_textdomain'),
            array($this, 'pageMetaBoxOfferList'), 'post', 'side', 'high'
        );

        add_meta_box(
            'myplugin_sectionid', __('Splurgy PageLock™ Token', 'myplugin_textdomain'),
            array($this, 'pageMetaBoxOfferList'), 'page', 'side', 'high'
        );

        // Short code help sections
        add_meta_box(
            'myplugin_sc_sectionid', __(
                'Splurgy Short Code Help', 'myplugin_sc_textdomain'
            ), array($this, 'pagePostMetaBoxShortCodeHelp'), 'post', 'normal', 'high'
        );

        add_meta_box(
            'myplugin_sc_sectionid', __(
                'Splurgy Short Code Help', 'myplugin_sc_textdomain'
            ), array($this, 'pagePostMetaBoxShortCodeHelp'), 'page', 'normal', 'high'
        );
    }
    
    /**
     * If the Splurgy Offers checkbox is ON on the WordPress Pages this function
     * is called
     *
     * @return type None
     */
    public function pageMetaBoxOfferList()
    {
        wp_nonce_field(plugin_basename(__FILE__), 'splurgyOfferNonce');

        $sOfferPowerSwState = get_post_custom_values('SplurgyOfferPowerSwitch');
        $splurgyPostToken = "none";
        if(!is_null(get_post_custom_values('splurgyPostToken'))) {        
            $splurgyPostToken = get_post_custom_values('splurgyPostToken');
        }

        $TestMode = get_post_custom_values('TestMode');
        $checked = ('on' == $sOfferPowerSwState[0])
                    ? "checked=checked"
                    : '';

        $testchecked = ('true' == $TestMode[0])
                        ? ''
                        : "checked=checked";

        echo "<div class='offerPowerSwitch'>"
                ."<input $checked type='checkbox' name='offerPowerSwitch' id='offerPowerSwitch' />"
            ."</div>";

        echo "<div id='token_input' style='word-wrap: break-word;'>"
                ."Current PageLock token: <br> <b>$splurgyPostToken[0]</b><br>"
                ."<input type='text' placeholder='Location Token' name='token' id='token' /><br/>"
            ."</div>";    
        
        echo "<div>"
                ."<p class='tooltip_question'>What does the switch do?</p>"
                ."Turning this switch on will enable PageLock only if you specify a token below. Click the button, and then update your post."
            ."</div>";

        echo "<div>"
                ."<p class='tooltip_question'>Where is my location token?</p>"
                ."You can find your Location token in the<br/> <a href='http://offers.splurgy.com/channels' target='_blank'>Splurgy Locations Panel</a>.<br/>"
            ."</div>";

        echo "<div id='advanced'>"
                ."<a id='Advanced' title='Advanced'>Advanced Menu</a>"
                ."<div id='advancedPanel'>"
                    ."<div class='testmode'>"
                        ."<input {$testchecked} type='checkbox' name='testmode' id='testmode' />"
                        ."<label id='testmodelabel'>Test mode</label><a id='testmodeq'>?</a>"
                    ."</div>"
                ."</div>"
            ."</div>​";
    }
    

}
?>