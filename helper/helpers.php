<?php

use Xmhafiz\FbFeed\FbFeed;

if (! function_exists('fb_feed')) {

    function fb_feed($config = null)
    {
        return new FbFeed($config);
    }
}