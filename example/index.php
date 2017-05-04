<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ .'/../vendor/autoload.php';

use Xmhafiz\FbFeed\Request;

$fbSecretKey =  '';
$fbAppId = '';
$pageId = '';

$data = Request::getPageFeed($pageId, $fbSecretKey, $fbAppId);

header('Content-type: application/json');
echo json_encode($data);
        
        