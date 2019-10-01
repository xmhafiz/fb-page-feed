<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ .'/../vendor/autoload.php';

use Dotenv\Dotenv;

// load envirionment variable
// add this package in your composer.json if want to use dotenv "vlucas/phpdotenv"
$env = new Dotenv(__DIR__ . '/../');
$env->load();

// set page
$fbSecretKey =  getenv('FB_SECRET_KEY');
$fbAppId = getenv('FB_APP_ID');
$fbPageName = getenv('FB_PAGENAME');
$accessToken = getenv('FB_ACCESS_TOKEN');

// Example 1
$response = fb_feed()->setAppId($fbAppId)->setSecretKey($fbSecretKey)->setPage($fbPageName)->findKeyword("#tutorial")->fetch();
// Example 2
$response = fb_feed()->setCredential($fbAppId, $fbSecretKey)->setPage($fbPageName)->findKeyword("#tutorial")->fetch();

// Example 3
$response = fb_feed()->findKeyword("#tutorial")->fetch();

// Example 4
$response = fb_feed()->findKeyword(['#tutorial', '#tips'])->fetch();

// Example 5 - to fetch owner's pages only. Apps review not required
$response = fb_feed()->setAccessToken($accessToken)->fetch();

// Example 6
$response = fb_feed()->fetch();

header('Content-type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
        