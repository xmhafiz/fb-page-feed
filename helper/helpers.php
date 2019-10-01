<?php

use Xmhafiz\FbFeed\FbFeed;

if (! function_exists('fb_feed')) {

    function fb_feed()
    {
        return new FbFeed();
    }
}

if (! function_exists('isAppsApproved')) {
    
    function isAppsApproved($response) 
    {
        // check if got 500 caused by fb Apps dont have approval
        $fbErrorMessage = 'must be reviewed and approved by Facebook';
        if ($response['error'] == 500 && isset($response['message']->message, $fbErrorMessage) && strpos($response['message']->message, $fbErrorMessage) !== false) {
            return false;
        }

        return true;
    }
}