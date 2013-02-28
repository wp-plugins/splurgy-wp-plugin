<?php
/**
   Plugin Name: Splurgy WP Plugin
   Plugin URI: https://github.com/splurgy/plugins/
   Description: This plugin will allow users to easily add offers at the end of 
   their post
   Version: 1.2.0
   Author: Splurgy
   Author URI: http://www.splurgy.com
   License: MIT
 * 
 * PHP version 5.3.1
 *
 * @category WordPressSettingsView
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
*/
?>
<?php
/**  Copyright (C) 2012 Splurgy Inc

    Permission is hereby granted, free of charge, to any person obtaining a 
    copy of this software and associated documentation files (the "Software"), 
    to deal in the Software without restriction, including without limitation 
    the rights to use, copy, modify, merge, publish, distribute, sublicense, 
    and/or sell copies of the Software, and to permit persons to whom the 
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in 
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
    THE SOFTWARE.
*/
?>
<?php
require_once 'WordpressHooks.php';
require_once 'WordpressView.php';

/**
 * WordPress Settings View Class definition This file runs the plugin
 *
 * @category WordPressInit
 * @package  PackageName
 * @author   Splurgy <support@splurgy.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://www.splurgy.com Splurgy
 */
class WordpressInit
{
    protected $wpHooks;
    protected $wpView;
    /**
     * Wordpress Init construct function
     */
    public function __construct()
    {
        $this->wpView = new WordpressView();
        $this->wpHooks = new WordpressHooks($this->wpView);
        //$wpView = new WordpressView();
        //$hook = new WordpressHooks($wpView);
    }
}

$wordpressInit = new WordpressInit();
?>
