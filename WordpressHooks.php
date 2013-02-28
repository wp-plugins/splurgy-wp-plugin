<?php
/**
 * All functions to hooks to settings/admin/view page are here.
 * All wordpress hooks should go here.
 *
 * PHP version 5.3.1
 *
 * @category WordPressSettingsView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */

require_once 'WordpressSettingsView.php';
require_once 'WordpressAdminView.php';

/**
 * WordPress Settings View Class definition
 *
 * @category WordPressSettingsView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
global $post;

class WordpressHooks
{
    private $_wordpressView;
    private $_wpSettingsView;
    private $_wpAdminView;
    
    /**
     * Wordpress Hooks construct
     * 
     * @param type $wordpressView WordpressView variable
     */
    public function __construct(WordpressView $wordpressView)
    {
        $this->_wordpressView = $wordpressView;
        $this->_wpSettingsView = new WordPressSettingsView();
        $this->_wpAdminView = new WordPressAdminView();


        /** Required JavaScript files */
        add_action('admin_init', array($this, 'requiredJsEnqueue'));

        /** Admin notice for when token isn't set */
        add_action(
            'admin_notices', array($this->_wpSettingsView, 'missingTokenNotice')
        );

        /** Post handler function for settings page - This has to be before 
         * analytics */
        add_action(
            'admin_head', array($this->_wpSettingsView, 'settingsPagePostHandler')
        );

        
        /** Hook for adding admin menus */
        add_action('admin_menu', array( $this, 'adminMenu' ));

        /** Add shortcode button */
        add_shortcode('splurgy', array($this->_wordpressView, 'splurgyShortCode'));


        /** Hook for adding admin menus */
        add_action('the_content', array( $this->_wordpressView, 'offer' ), $post->ID);


        /** Add New post meta box */
        add_action(
            'add_meta_boxes', array(
            $this->_wpAdminView, 'addPostMetaBoxOfferList')
        );

        /** Save Splurgy offer post meta data */
        add_action(
            'save_post', array($this->_wordpressView, 'savePostMetaBoxOfferData')
        );

        /** JavaScript files */
        add_action('init', array( $this, 'javascriptEnque'));


        /** Display error/success messages - This should always be last */
        add_action(
            'admin_notices', array( $this->_wpSettingsView, 'showWordPressMessage')
        );
    }


    /**
     * Admin Menu Handler
     *
     * @return type None
     */

    public function adminMenu()
    {
        /**add_menu_page( $page_title, $menu_title, $capability, $menu_slug, 
         * $function, $icon_url, $position );**/
        add_menu_page('Splurgy', 'Splurgy', 'manage_splurgy', 'splurgy');

        /**add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, 
         * $menu_slug, $function ); **/
        add_submenu_page(
            'splurgy', 'Settings', 'Settings', 'manage_options', 'settings', 
            array($this->_wpSettingsView, 'settingsPage')
        );
    }

    /**
     * Javascript Enqueue Handler
     *
     * @return None
     */
    public function requiredJsEnqueue()
    {
        wp_enqueue_script('jquery');

        /** jconfirmaction */
        wp_enqueue_script(
            'jquery-jconfirmaction', plugins_url(
                '/splurgy-wp-plugin/js/vendors/jconfirmaction.jquery.js'
            )
        );


        wp_enqueue_script(
            'splurgy-jquery-settings', plugins_url(
                '/splurgy-wp-plugin/js/splurgy-jquery-settings.js'
            )
        );
        wp_enqueue_style(
            'splurgy-css-settings', plugins_url(
                '/splurgy-wp-plugin/css/splurgy-css-settings.css'
            )
        );

        wp_enqueue_script(
            'splurgy-jquery-postmetabox', plugins_url(
                '/splurgy-wp-plugin/js/splurgy-jquery-metabox.js'
            )
        );

        wp_enqueue_script(
            'jquery-iphone-checkboxes', plugins_url(
                '/splurgy-wp-plugin/js/vendors/iphone-style-checkboxes/
                    jquery/iphone-style-checkboxes.js'
            )
        );

        /** numeric */
        wp_enqueue_script(
            'jquery-numeric', plugins_url(
                '/splurgy-wp-plugin/js/vendors/numeric/jquery.numeric.js'
            )
        );

    }

    /**
     * More Javascript Enque Handler
     *
     * @return None
     */
    public function javascriptEnque()
    {
        wp_enqueue_style(
            'splurgy-css-metabox', plugins_url(
                '/splurgy-wp-plugin/css/splurgy-css-metabox.css'
            )
        );


        /** should refactor into other functions or class */
        /** iphone-style-checkboxes */
        wp_enqueue_style(
            'jquery-iphone-checkboxes-css', plugins_url(
                '/splurgy-wp-plugin/js/vendors/iphone-style-checkboxes/style.css'
            )
        );

    }

}

?>
