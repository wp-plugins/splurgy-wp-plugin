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

require_once 'splurgy-lib/SplurgyEmbedGenerator.php';

/**
 * WordPress View Class definition
 *
 * @category WordPressView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
class V4Embeds
{

    public function embedShortCode( $atts ) 
    {
        $html = '[There was an error in your shortcode]';
        extract( shortcode_atts( array(
            'type' => null,
            'token' => null,
        ), $atts ) );

        switch ($type) {
            case 'coupon':
                $html = $this->coupon($token);
                break;
            case 'giveaway':
                $html = $this->giveaway($token);
                break;
            case 'pagelock':
                $html = $this->pagelock($token);
                break;    
        }
        
        return $html;
    }

    public function coupon($token) 
    {
        $embed = new SplurgyEmbedGenerator('v4-coupon', $token);
        $html = $embed->getHtml();
        return $html;
    }

    public function giveaway($token) 
    {
        $embed = new SplurgyEmbedGenerator('v4-giveaway', $token);
        $html = $embed->getHtml();
        return $html;
    }

    public function pagelock($token) 
    {
        $embed = new SplurgyEmbedGenerator('v4-pagelock', $token);
        $html = $embed->getHtml();
        return $html;
    }

}

?>
