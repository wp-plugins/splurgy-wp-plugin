<?php
/* Copyright (C) 2012 Splurgy Inc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * ===========================================================
 */
/*
 * This class will generated different types of Embeds with factory method
 */

require_once 'SplurgyEmbedGenerator.php';
require_once 'Exceptions.php';

class SplurgyEmbed
{

    private $_file;

    public function __construct() {
        $this->_file = dirname(__FILE__) . '/token.config';
        // Create a token.config file
        $this->createTokenConfig();
    }

    private function createTokenConfig() {
        if(!file_exists($this->_file)) {
             file_put_contents($this->_file,'');
        }
    }


    public function setToken($token) {
        $token = preg_replace('/[^a-zA-Z0-9_]*/', '', $token);
        $token = str_replace(' ', '', $token);
        if(empty($token)) {
            throw new TokenErrorException("Your token cannot be empty");
        }

        if(!preg_match('/^c_[a-zA-Z0-9]{40}$/', $token)) {
            throw new TokenErrorException("Your token is incorrect! Make sure you copied it correctly with no spaces!");
        }
        file_put_contents($this->_file, $token);

    }

    public function getToken() {
        if(file_exists($this->_file)) {
            $token = file_get_contents($this->_file);
        }
        return $token;
    }

    public function deleteToken(){
        file_put_contents($this->_file,'');
    }


    /*
     * Examples of templates,
     * mobile
     * offers
     * analytics
     * small
     * big
     * etc...
     *
     *
     */
    public function getEmbed($templateName=null, $offerId=null) {
         return new SplurgyEmbedGenerator(
                    $this->getToken(),
                    $templateName,
                    $offerId
                );
    }




}

?>
