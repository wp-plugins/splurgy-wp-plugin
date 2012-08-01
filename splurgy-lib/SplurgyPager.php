<?php

/*
 * This class will be used for talking with Splurgy's API and parsing data
 */

class SplurgyPager
{
    //put your code here
    private $_token;

    public function __construct()
    {
        //
    }

    public function getOffers() {
        // Return an object or maybe an array for the offers
        return array(
                   'offer1',
                   'offer2',
                   'offer3',
                   'offer4',
                   'offer5',
                   'offer6',
                   'offer7',
                   'offer8',
                   'offer9',
                   'offer10'
                );
    }


}

?>
