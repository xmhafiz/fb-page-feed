<?php

use Xmhafiz\FbFeed\FbFeed;

if (! function_exists('fb_feed')) {

    function fb_feed()
    {
        return new FbFeed();
    }
}