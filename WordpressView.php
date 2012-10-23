<?php

/*
 * All functions for hooks and will output HTML should go here.
 * Wordpress is so dirty, we have to echo!
 */
require_once 'splurgy-lib/SplurgyPager.php';
require_once 'splurgy-lib/SplurgyEmbed.php';
require_once 'splurgy-lib/TemplateGenerator.php';


class WordpressView
{

    private $_offerCount = 0;
    private $_splurgyPager;
    private $_splurgyEmbed;
    private $_templateGenerator;
    private $_path;
    private $_messages = array();

    public function __construct()
    {
        $this->_splurgyPager = new SplurgyPager();
        $this->_splurgyEmbed = new SplurgyEmbed(get_option('splurgyToken')); 
        $this->_templateGenerator = new TemplateGenerator();
        $this->_path = dirname(__FILE__). '/view-templates/';
        $this->_templateGenerator->setPath($this->_path);
    }

    public function setWordPressMessage($message, $error=false) {
        if($error===true){
            $this->_messages[] = '<div id="message" class="error"><p>'.$message.'</p></div>';
        }else{
            $this->_messages[] = '<div id="message" class="updated"><p>'.$message.'</p></div>';
        }
    }

    public function showWordPressMessage() {
        foreach($this->_messages as $message) {
            echo $message;
        }
        $this->_message = null;
    }


    public function missingTokenNotice() 
    {
        $token = get_option('splurgyToken');
        if (is_admin() && !isset($token)) {	
            $url = admin_url('admin.php?page=settings');
            $this->setWordPressMessage("<b>Splurgy Offers</b> To use this plugin, please configure your <a href='$url'>settings</a>", true);	
         }
    }


    public function postMetaBoxOfferList()
    {
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'splurgyOfferNonce' );

        $splurgyOfferPowerSwitchState = get_post_custom_values('SplurgyOfferPowerSwitch');
        $splurgyOfferId = get_post_custom_values('SplurgyOfferId');

        $checked = '';
        $showOfferId = 'style="display: none;"';
        $currentOfferId = '';

        if('on' == $splurgyOfferPowerSwitchState[0]) {
            $checked = "checked='checked'";
            $showOfferId = "style='display: inline;'";
        }

        if(!empty($splurgyOfferId)) {
            $offerId = $splurgyOfferId[0];
            $currentOfferId =  "Current showing offer #: <b>" .$offerId. "</b>";
        }

        $this->_templateGenerator->setTemplateName('postMetaBoxOfferList');
        $this->_templateGenerator->setPatterns(array('{$checked}', '{$showOfferId}', '{$currentOfferId}'));
        $this->_templateGenerator->setReplacements(array($checked, $showOfferId, $currentOfferId));
        echo $this->_templateGenerator->getTemplate();
    }

    public function pageMetaBoxOfferList()
    {
        wp_nonce_field( plugin_basename( __FILE__ ), 'splurgyOfferNonce' );

        $splurgyOfferPowerSwitchState = get_post_custom_values('SplurgyOfferPowerSwitch');
        $splurgyOfferId = get_post_custom_values('SplurgyOfferId');
        $TestMode = get_post_custom_values('TestMode');
        $unlocktext = get_post_custom_values('unlocktext');
        $unlocktextinput = '';
        $checked = '';
        $testchecked = '';
        $showOfferId = 'style="display: none;"';

        if('on' == $splurgyOfferPowerSwitchState[0]) {
            $checked = "checked='checked'";
            $showOfferId = "style='display: inline;'";
        }
        
        if ('true' == $TestMode[0] ) {
            $testchecked = 'checked=checked';
        }
        if ('false' == $TestMode[0] ) {
            $testchecked = '';
        }
        
        if (!empty($unlocktext[0]) && $unlocktext[0] != 'true'){
            $unlocktextinput = $unlocktext[0];
        }
        
        $currentOfferId =  "Default Offer is set";
        if(!empty($splurgyOfferId)) {
            $offerId = $splurgyOfferId[0];
            $currentOfferId =  "Current showing offer #: <b>" .$offerId. "</b>";
        } 

        $this->_templateGenerator->setTemplateName('pageMetaBoxOfferList');
        $this->_templateGenerator->setPatterns(array('{$checked}', '{$testchecked}', '{$showOfferId}', '{$currentOfferId}', '{$unlocktextinput}'));
        $this->_templateGenerator->setReplacements(array($checked, $testchecked, $showOfferId, $currentOfferId, $unlocktextinput));
        echo $this->_templateGenerator->getTemplate();
    }

    public function settingsPage()
    {
        $token = $this->_splurgyEmbed->getToken(); 
        $this->settingsPageView($token);
    }

    public function settingsPageView($token) {
        echo "<h2>Settings</h2>";
        $message = '';
        $previewAndReset = '';
        
        if(!empty($token)) {
            $message = "Your current token is <b>$token</b><br/>";
            $message .= "You now have options to add offers when adding a new post!<br/>";
        } else {
            $message = "Your token is not setup right now<br/><br/>";
        }

        if(!empty($token)){
            $value = 'update';
            $embed = $this->_splurgyEmbed->getEmbed('settings-preview')->getTemplate();
            $this->_templateGenerator->setTemplateName('settingsPageViewPreviewAndReset');
            $this->_templateGenerator->setPatterns('{$embed}');
            $this->_templateGenerator->setReplacements($embed);
            $previewAndReset = $this->_templateGenerator->getTemplate();
        } else {
            $value = 'Add';
        }

        $this->_templateGenerator->setTemplateName('settingsPageViewInput');
        $this->_templateGenerator->setPatterns(array('{$message}', '{$value}', '{$previewAndReset}'));
        $this->_templateGenerator->setReplacements(array($message, $value, $previewAndReset));
        echo $this->_templateGenerator->getTemplate();
    }

    public function settingsPagePostHandler()
    {
        if (isset($_POST['token'])) {
            try {
                $this->_splurgyEmbed->setToken($_POST['token']);
                update_option('splurgyToken', $_POST['token']);
                $this->setWordPressMessage('Successfully saved token!');
            } catch (Exception $e) {
                $this->setWordPressMessage($e->getMessage() , true);
            }

        } elseif (isset($_POST['delete']) && $_POST['delete']==true) {
            $this->_splurgyEmbed->deleteToken();
        }
    }

    public function analyticsPage()
    {
        $img = "". plugins_url('/splurgy-wp-plugin/images/analytics.png') ."";
        $this->_templateGenerator->setTemplateName('analyticsPage');
        $this->_templateGenerator->setPatterns('{$img}');
        $this->_templateGenerator->setReplacements($img);
        echo $this->_templateGenerator->getTemplate();
    }

    public function offer($content) //TODO: remname since both offer and content-lock can be created here
    {
        echo $content;
        $splurgyOfferId = get_post_custom_values('SplurgyOfferId');
        $testmodevalue = get_post_custom_values('TestMode');
        $unlocktextvalue = get_post_custom_values('unlocktext');
        $splurgyOfferPowerSwitchState = get_post_custom_values('SplurgyOfferPowerSwitch');
        if( 'off' != $splurgyOfferPowerSwitchState[0] && null != $splurgyOfferPowerSwitchState[0]) {
            if(!empty($splurgyOfferId) && !is_page()) {
                $offerId = $splurgyOfferId[0];
                if ((is_single() || $this->_offerCount < 3)) {
                    echo '<a name="SplurgyOffer"></a>';
                    echo $this->_splurgyEmbed->getEmbed('offers', $offerId)->getTemplate();
                    $this->_offerCount++;
                } else {
                    $permalink = '" . get_permalink() . "#SplurgyOffer';
                    $this->_templateGenerator->setTemplateName('offer');
                    $this->_templateGenerator->setPatterns('{$permalink}');
                    $this->_templateGenerator->setReplacements("" . get_permalink() . "#SplurgyOffer");
                    echo $this->_templateGenerator->getTemplate();
                }
            } elseif(is_page() && !empty($splurgyOfferId)) {
                // TODO: make this dynamic based on type ('page-offer' or 'content-lock')
                $offerId = $splurgyOfferId[0];
                $testmode = $testmodevalue[0];
                $unlocktext = $unlocktextvalue[0];
                echo $this->_splurgyEmbed->getEmbed('content-lock', $offerId, $testmode, $unlocktext)->getTemplate(); // 'page-offer'
            } else {
                echo $this->_splurgyEmbed->getEmbed('page-offer')->getTemplate();
            }
        }
    }

    public function analyticsEmbed()
    {
        echo $this->_splurgyEmbed->getEmbed('analytics')->getTemplate();
    }

    /* Adds a box to the main column on the Post and Page edit screens */

    public function addPostMetaBoxOfferList()
    {
        add_meta_box(
                'myplugin_sectionid', __('Splurgy Offers!', 'myplugin_textdomain'), array($this, 'postMetaBoxOfferList'), 'post', 'side', 'high'
        );

        add_meta_box(
                'myplugin_sectionid', __('Splurgy Offers!', 'myplugin_textdomain'), array($this, 'pageMetaBoxOfferList'), 'page', 'side', 'high'
        );
    }

    public function savePostMetaBoxOfferData($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        /*
         * Verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times
         */

        if (!wp_verify_nonce($_POST['splurgyOfferNonce'], plugin_basename(__FILE__))) {
            return;
        }
        // Check permissions

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $offerPowerSwitchState = $_POST['offerPowerSwitch'];
        if( is_null($offerPowerSwitchState)) {
            $offerPowerSwitchState = 'off';
        }
        add_post_meta($post_id, 'SplurgyOfferPowerSwitch', $offerPowerSwitchState, true) or update_post_meta($post_id, 'SplurgyOfferPowerSwitch', $offerPowerSwitchState);
        
        $testmode = $_POST['testmode'];
        if(isset($_POST['testmode'])) {
            $testmode = 'true';
        }
        else {
            $testmode = 'false';
        }
        ;

        add_post_meta($post_id, 'TestMode', $testmode, true) or update_post_meta($post_id, 'TestMode', $testmode);
        
        $unlocktext = $_POST['pagelocktext'];
        
        if(empty($_POST['pagelocktext'])){
            $unlocktext= 'true';
        }
        ;
        
        add_post_meta($post_id, 'unlocktext', $unlocktext, true) or update_post_meta($post_id, 'unlocktext', $unlocktext);

        
        $offerId = intval(trim($_POST['offerId']));
        if( 0 >= $offerId ) {
            return;
        }

        add_post_meta($post_id, 'SplurgyOfferId', $offerId, true) or update_post_meta($post_id, 'SplurgyOfferId', $offerId);

    }
    
    public function addButtonsOnInit()
    {
        add_filter('mce_buttons', array($this, 'addButtonToPost'));
    }

}

?>
