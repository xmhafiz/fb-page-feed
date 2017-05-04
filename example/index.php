<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ .'/../vendor/autoload.php';

use Xmhafiz\FbFeed;

$fbSecretKey =  '580c7f49eed4aec66003b96ea03ea495';
$fbAppId = '237762316698462';
$pageId ='LaravelCommunity';

$data = FbFeed\Request::getPageFeed($pageId, $fbSecretKey, $fbAppId);

header('Content-type: application/json');
echo json_encode($data);
        
        