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

require_once 'splurgy-lib/SplurgyAdUnitGenerator.php';

/**
 * WordPress View Class definition
 *
 * @category WordPressView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
class ShortCodes
{

    private $_adUnitGenerator;

    public function adUnit( $atts , $content ) 
    {
        extract( shortcode_atts( array(
            'token' => null,
            'dimension' => null,
        ), $atts ) );
        
        $ad_unit = new SplurgyAdUnitGenerator($token, $dimension);
        $html = $ad_unit->getHtml();
        return $html;
    }

}

?>
