<?php

/**
 * All functions for hooks and will output HTML should go here.
 * Wordpress is so dirty, we have to echo!
 *
 * PHP version 5.3.1
 *
 * @category WordPressView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */

require_once 'splurgy-lib/SplurgyPager.php';
require_once 'splurgy-lib/SplurgyEmbed.php';
require_once 'splurgy-lib/TemplateGenerator.php';

/**
 * WordPress View Class definition
 *
 * @category WordPressView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
class WordpressView
{

    private $_offerCount = 0;
    private $_splurgyPager;
    private $_splurgyEmbed;
    private $_templateGenerator;
    private $_path;
    //private $_messages = array();
    private $_wpSettingsView;

    /**
     * Wordpress View construct function
     */
    public function __construct()
    {
        $this->_wpSettingsView = new WordPressSettingsView();
        $this->_splurgyPager = new SplurgyPager();
        $this->_splurgyEmbed = new SplurgyEmbed(get_option('splurgyToken'));
        $this->_templateGenerator = new TemplateGenerator();
        $this->_path = dirname(__FILE__). '/view-templates/';
        $this->_templateGenerator->setPath($this->_path);
    }




    /**
     * Short Code Function that makes the splurgy offer available via short code
     *
     * @param type $atts Attributes OfferId and TestMode for the Shortcode
     *
     * @return type the do_shortcode content i.e. the deal from Splurgy
     */

    public function splurgyShortCode($atts)
    {
        extract(
            shortcode_atts(
                array(
                    'offerid' => null,
                    'testmode' => false,
                ),
                $atts
            )
        );


        if ($offerid != null && !is_page()) {
            /** Offer Id specified and this is a post 1st IF**/
            return do_shortcode(
                $this->_splurgyEmbed->getEmbed('offers', $offerid)->getTemplate()
            );
        } elseif (($offerid != null) && is_page()) {
            /** Offer Id is specified and this is a page 2nd IF**/
            return do_shortcode(
                $this->_splurgyEmbed->getEmbed('offers', $offerid)->getTemplate()
            );
        } else {
            /** Offer id is not specified for a page/post **/
            return do_shortcode(
                $this->_splurgyEmbed->getEmbed('page-offer')->getTemplate()
            );
        }
    }

    /**
     * Main Function that will display the offer/content-lock on the Page / Post
     *
     * @param type $content The content
     *
     * @return type None
     */

    public function offer($content)
    {
        //TODO: remname since both offer and content-lock can be created here
        /**echo $content;**/
        echo do_shortcode($content);
        $splurgyOfferId = get_post_custom_values('SplurgyOfferId');
        $testmodevalue = get_post_custom_values('TestMode');
        $unlocktextvalue = get_post_custom_values('unlocktext');
        $sOfferPowerSwState = get_post_custom_values('SplurgyOfferPowerSwitch');
        if ('off' != $sOfferPowerSwState[0] && !is_null($sOfferPowerSwState[0])) {
            if (!empty($splurgyOfferId) && !is_page()) {
                $offerId = $splurgyOfferId[0];
                if ((is_single() || $this->_offerCount < 3)) {
                    echo '<a name="SplurgyOffer"></a>';
                    echo $this->_splurgyEmbed->getEmbed('offers', $offerId)
                        ->getTemplate();
                    $this->_offerCount++;
                } else {
                    $permalink = get_permalink() . '#SplurgyOffer';
                    $this->_templateGenerator->setTemplateName('offer');
                    $this->_templateGenerator->setPatterns('{$permalink}');
                    $this->_templateGenerator->setReplacements($permalink);
                    echo $this->_templateGenerator->getTemplate();
                }
            } elseif (is_page() && !empty($splurgyOfferId)) {
                    $offerId = $splurgyOfferId[0];
                    $testmode = $testmodevalue[0];
                    $unlocktext = $unlocktextvalue[0];
                    echo $this->_splurgyEmbed->getEmbed(
                        'content-lock', $offerId, $testmode, $unlocktext
                    )->getTemplate(); // 'page-offer'
                //}
            }
            /**else {
                echo $this->_splurgyEmbed->getEmbed('page-offer')->getTemplate();
            }**/
        }
    }

    /**
     * Save Offers Meta Box Data on Posts
     *
     * @param type $post_id Id of the Post
     *
     * @return type None
     */
    public function savePostMetaBoxOfferData($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        /*
         * Verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times
         */

        $authCheckBeforeSave = wp_verify_nonce(
            $_POST['splurgyOfferNonce'], plugin_basename(__FILE__)
        );

        /** Check permissions **/
        if ( !$authCheckBeforeSave && !current_user_can('edit_post', $post_id)) {
            return;
        }

        $this->_insertPowerSwitch($post_id, $_POST['offerPowerSwitch']);
        $this->_insertTestMode($post_id, $_POST['testmode']);
        $this->_insertPageLock($post_id, $_POST['pagelocktext']);
        $this->_insertOfferId($post_id, $_POST['offerId']);
    }

    /**
     * Inserts power switch data into database
     *
     * @param type $post_id           Post ID
     * @param type $offerPowerSwState Post Data from Page save
     *
     * @return type None
     */

    private function _insertPowerSwitch($post_id, $offerPowerSwState)
    {

        if (!is_null($offerPowerSwState)) {
            $offerPowerSwState = 'on';
        } else {
            $offerPowerSwState = 'off';
        }

        add_post_meta(
            $post_id, 'SplurgyOfferPowerSwitch', $offerPowerSwState, true
        ) or update_post_meta(
            $post_id, 'SplurgyOfferPowerSwitch', $offerPowerSwState
        );
    }


    /**
     * Inserts test mode data into database
     *
     * @param type $post_id  Post ID
     * @param type $testMode Post Data from Page save
     *
     * @return type None
     */

    private function _insertTestMode($post_id, $testMode)
    {
        switch($testMode) {
        case 'on':
            $testMode = 'true';
            break;
        default:
            $testMode = 'false';
            break;
        }
        add_post_meta($post_id, 'TestMode', $testMode, true) or
            update_post_meta($post_id, 'TestMode', $testMode);

    }

    /**
     * Inserts unlock text data into database
     *
     * @param type $post_id    Post ID
     * @param type $unlocktext Post Data from Page save
     *
     * @return type None
     */

    private function _insertPageLock($post_id, $unlocktext)
    {
        if (!empty($unlocktext)) {
            add_post_meta($post_id, 'unlocktext', $unlocktext, true) or
                update_post_meta($post_id, 'unlocktext', $unlocktext);
        }

    }

    /**
     * Inserts offer id data into database
     *
     * @param type $post_id Post ID
     * @param type $offerId Post Data from Page save
     *
     * @return type None
     */

    private function _insertOfferId($post_id, $offerId)
    {

        if ( 0 < intval(trim($offerId)) ) {
            add_post_meta($post_id, 'SplurgyOfferId', $offerId, true) or
                update_post_meta($post_id, 'SplurgyOfferId', $offerId);
        }
    }



    /**
     * Add Buttons On Init
     *
     * @return type None
     */

    public function addButtonsOnInit()
    {
        add_filter('mce_buttons', array($this, 'addButtonToPost'));
    }

}

?>
