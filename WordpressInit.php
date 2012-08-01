<?php
/*
Plugin Name: Splurgy WP Plugin
Plugin URI: https://github.com/splurgy/plugins/
Description: This plugin will allow users to easily add offers at the end of their post
Version: 1.0
Author: Splurgy
Author URI: http://www.splurgy.com
License: MIT
*/
?>
<?php
/*  Copyright (C) 2012 Splurgy Inc

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
?>
<?php
require_once 'WordpressHooks.php';
require_once 'WordpressView.php';

/*
 * This file runs the plugin
 */
class WordpressInit
{
    protected $wordpressHooks;
    protected $wordpressView;


    public function __construct()
    {
       $this->wordpressView = new WordpressView();
       $this->wordpressHooks = new WordpressHooks($this->wordpressView);

    }
}

$wordpressInit = new WordpressInit();
?>
