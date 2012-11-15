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
require_once 'splurgy-lib/SplurgyPager.php';
require_once 'splurgy-lib/SplurgyEmbed.php';
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
    
    //private $_offerCount = 0;
    private $_splurgyPager;
    private $_splurgyEmbed;
    private $_templateGenerator;
    private $_path;
    //private $_messages = array();
    
    /**
     * WordpressAdmin construct function
     */
    public function __construct()
    {
        $this->_splurgyPager = new SplurgyPager();
        $this->_splurgyEmbed = new SplurgyEmbed(get_option('splurgyToken'));
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
        /** Removing this since we want only short codes working on the Posts
         * add_meta_box(
         *   'myplugin_sectionid', __('Splurgy Offers!', 'myplugin_textdomain'),
         * array($this, 'postMetaBoxOfferList'), 'post', 'side', 'high'
        );**/

        add_meta_box(
            'myplugin_sectionid', __('Splurgy Page Lock', 'myplugin_textdomain'),
            array($this, 'pageMetaBoxOfferList'), 'page', 'side', 'high'
        );

        add_meta_box(
            'myplugin_sc_sectionid', __(
                'Splurgy Short Code Help', 'myplugin_sc_textdomain'
            ), array($this, 'pagePostMetaBoxShortCodeHelp'), 'page', 'normal', 'high'
        );

        add_meta_box(
            'myplugin_sc_sectionid', __(
                'Splurgy Short Code Help', 'myplugin_sc_textdomain'
            ), array($this, 'pagePostMetaBoxShortCodeHelp'), 'post', 'normal', 'high'
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
        $splurgyOfferId = get_post_custom_values('SplurgyOfferId');
        $TestMode = get_post_custom_values('TestMode');
        $unlocktext = get_post_custom_values('unlocktext');
        $unlocktextinput = '';
        $checked = '';
        $testchecked = '';
        $showOfferId = 'style="display: none;"';

        if ('on' == $sOfferPowerSwState[0]) {
            $checked = "checked='checked'";
            $showOfferId = "style='display: inline;'";
        }

        if ('true' == $TestMode[0]) {
            $testchecked = 'checked=checked';
        }
        if ('false' == $TestMode[0]) {
            $testchecked = '';
        }

        if (!empty($unlocktext[0]) && $unlocktext[0] != 'true') {
            $unlocktextinput = $unlocktext[0];
        }

        $currentOfferId =  "Default Offer is set";
        if (!empty($splurgyOfferId)) {
            $offerId = $splurgyOfferId[0];
            $currentOfferId =  "Current showing offer #: <b>" .$offerId. "</b>";
        }

        $this->_templateGenerator->setTemplateName('pageMetaBoxOfferList');
        $this->_templateGenerator->setPatterns(
            array('{$checked}', '{$testchecked}', '{$showOfferId}',
                '{$currentOfferId}', '{$unlocktextinput}')
        );
        $this->_templateGenerator->setReplacements(
            array($checked, $testchecked, $showOfferId, $currentOfferId,
                $unlocktextinput)
        );
        echo $this->_templateGenerator->getTemplate();
    }
    
    /**
     * Embeds Analytics
     *
     * @return type None
     */

    public function analyticsEmbed()
    {
        echo $this->_splurgyEmbed->getEmbed('analytics')->getTemplate();
    }
}
?>