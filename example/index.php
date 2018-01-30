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

$response = fb_feed()->setAppId($fbAppId)->setSecretKey($fbSecretKey)->setPage($fbPageName)->findKeyword("#JomPAY")->fetch();

header('Content-type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
        