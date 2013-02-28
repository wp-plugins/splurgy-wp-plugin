<?php

/**
 * All functions for hooks to settings page and will output HTML should go here.
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
 * WordPress Settings View Class definition
 *
 * @category WordPressSettingsView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
class WordPressSettingsView
{

    //private $_offerCount = 0;
    private $_templateGenerator;
    private $_path;
    private $_messages = array();

    /**
     * WordpressSettings construct function
     */
    public function __construct()
    {
        $this->_templateGenerator = new TemplateGenerator();
        $this->_path = dirname(__FILE__). '/view-templates/';
        $this->_templateGenerator->setPath($this->_path);
    }

    /**
     * Handles displaying messages
     *
     * @return type None
     */
    public function showWordPressMessage()
    {
        foreach ($this->_messages as $message) {
            echo $message;
        }
        $this->_message = null;
    }

    /**
     * Notice displayed when Token is not available
     *
     * @return type None
     */
    public function missingTokenNotice()
    {
        $token = get_option('splurgyToken');
        if (is_admin() && !isset($token)) {
            $url = admin_url('admin.php?page=settings');
            $this->setWordPressMessage(
                "<b>Splurgy Offers</b> To use this plugin, please configure
                your <a href='$url'>settings</a>", true
            );
        }
    }

    /**
     * Handles displaying error message
     *
     * @param type $message Message to be displayed
     * @param type $error   Status False/True
     *
     * @return type None
     */
    public function setWordPressMessage($message, $error=false)
    {
        if ($error===true) {
            $this->_messages[] = '<div id="message" class="error"><p>'
                    .$message.'</p></div>';
        } else {
            $this->_messages[] = '<div id="message" class="updated"><p>'
                    .$message.'</p></div>';
        }
    }
    /**
     * Displays the content for the Settings Page from the templates
     *
     * @return type None
     */

    public function settingsPage()
    {
        $token = get_option('splurgyToken');
        $this->settingsPageView($token);
    }

    /**
     * This will set the Settings Page View html
     *
     * @param type $token The Token for the current channel
     *
     * @return type None
     */

    public function settingsPageView($token)
    {
        echo "<h2>Settings</h2>";
        $message = '';
        echo "This will be the default token used unless specified otherwise in a Post or Page.<br>";
        if (!empty($token)) {
            $message = "Your current default token is <b>$token</b><br/>";
            $message .= "You now have options to add offers when adding a
                new post!<br/>";
        } else {
            $message = "Your token is not setup right now<br/><br/>";
        }

        if (!empty($token)) {
            $value = 'update';
        } else {
            $value = 'Add';
        }

        $this->_templateGenerator->setTemplateName('settingsPageViewInput');
        $this->_templateGenerator->setPatterns(
            array('{$message}', '{$value}')
        );
        $this->_templateGenerator->setReplacements(
            array($message, $value)
        );
        echo $this->_templateGenerator->getTemplate();
    }

    /**
     * Handler for the Settings Page [checks if the token is available]
     *
     * @return type None
     */

    public function settingsPagePostHandler()
    {
        if (isset($_POST['token'])) {
            try {                
                update_option('splurgyToken', $_POST['token']);
                $this->setWordPressMessage('Successfully saved token!');
            } catch (Exception $e) {
                $this->setWordPressMessage($e->getMessage(), true);
            }

        } elseif (isset($_POST['delete']) && $_POST['delete']==true) {
            delete_option('splurgyToken');
        }
    }
}
?>
