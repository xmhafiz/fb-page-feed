# Get Facebook Page Feed 

[![Build Status](https://travis-ci.org/xmhafiz/fb-page-feed.svg?branch=master)](https://travis-ci.org/xmhafiz/fb-page-feed)
[![Coverage](https://img.shields.io/codecov/c/github/xmhafiz/fb-page-feed.svg)](https://codecov.io/gh/xmhafiz/fb-page-feed)

It is simple wrapper class written in php to fetch posts from certain Facebook page.

Currently I am using [Facebook graph API](https://developers.facebook.com/docs/graph-api) with cool [guzzle](https://github.com/guzzle/guzzle) and [dotenv](https://github.com/vlucas/phpdotenv)

Tested in PHP 5.6, 7.0 and 7.1

## Requirement
- PHP 5.6+

## Installation

#### Step 1: Getting Facebook App
- Go to [Facebook developer](https://developers.facebook.com/apps/) website
- Click "Add a New App" and fill in details
- On your **Dashboard**, get the "App ID" and "App Secret"
- Yeah, you are ready to code

#### Step 2: Install from composer
```
composer require xmhafiz/fb-page-feed
```
Alternatively, you can specify as a dependency in your project's existing composer.json file
```
{
   "require": {
      "xmhafiz/fb-page-feed": "^1.1"
   }
}
```

## Usage
After installing, you need to require Composer's autoloader and add your code.

#### Default (maximum post is 100)
```php
$data = fb_feed()->setAppId($fbAppId)
        ->setSecretKey($fbSecretKey)
        ->setPage($fbPageName)
        ->fetch();
```

#### Custom Maximum Post Shown
```php
// only show 5 post maximum
$data = fb_feed()->setAppId($fbAppId)
        ->setSecretKey($fbSecretKey)
        ->setPage($fbPageName)
        ->feedLimit(5)
        ->fetch();
```

#### Filter By Keyword
```php
// only show 5 post maximum
$data = fb_feed()->setAppId($fbAppId)
        ->setSecretKey($fbSecretKey)
        ->setPage($fbPageName)
        ->findKeyword("#JomPay")
        ->fetch();
```

#### Change Request Field
```php
// only show 5 post maximum
$data = fb_feed()->setAppId($fbAppId)
        ->setSecretKey($fbSecretKey)
        ->setPage($fbPageName)
        ->fields("id,message") //default 'id,message,created_time'
        ->fetch();
```


## Code Example

Change the `$fbSecretKey` and `$fbAppId` based on the "App ID" and "App Secret" in Step 1

```php
<?php

require_once 'vendor/autoload.php';

$fbSecretKey='580c7...';
$fbAppId='237...';
$fbPageName='LaravelCommunity';

$response = fb_feed()->setAppId($fbAppId)->setSecretKey($fbSecretKey)->setPage($fbPageName)->findKeyword("#AirSelangor")->fetch();

//or

$response = fb_feed()->setCredential($fbAppId, $fbSecretKey)->setPage($fbPageName)->findKeyword("#AirSelangor")->fetch();

header('Content-type: application/json');
echo json_encode($data);

```

#### Using ENV

```
FB_SECRET_KEY=absbo123o233213
FB_APP_ID=123123123123
FB_PAGENAME=pagename

```

Then, Just

```
$response = fb_feed()->findKeyword("#AirSelangor")->fetch();
```


### Method
<table border="1">
    <tr>
        <th>Method</th>
        <th>Param</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>setAppId</td>
        <td><code>String</code></td>
        <td>FB Application ID (Default is in .env)</td>
    </tr>
    <tr>
        <td>setSecretKey</td>
        <td><code>String</code></td>
        <td>FB Application Secret ID (Default is in .env)</td>
    </tr>
    <tr>
        <td>setCredential</td>
        <td><code>String, String</code></td>
        <td>Set Both Secret and App Id (Default is in .env)</td>
    </tr>
    <tr>
        <td>fields</td>
        <td><code>String</code></td>
        <td>List of Attributes (Default : <code>id,message,created_time,from,permalink_url,full_picture</code>)</td>
    </tr>
    <tr>
        <td>setPage</td>
        <td><code>String</code></td>
        <td>Set Page Name (Default is in .env)</td>
    </tr>
    <tr>
        <td>findKeyword</td>
        <td><code>String | Array</code></td>
        <td>Filter String by certain Keywords</td>
    </tr>
    <tr>
        <td>feedLimit</td>
        <td><code>Integer</code></td>
        <td>Set result count limit</td>
    </tr>
</table>

### Result

You should getting data similarly like below:
```json
{
    "error": false,
    "status_code": 200,
    "data": [
        {
            "id": "365155643537871_1321961834523909",
            "message": "The APPDATA or COMPOSER_HOME environment variable must be set for composer to run correctly\"\nwhat bug?",
            "created_time": "2017-05-14T15:45:30+0000",
            "from": {
                "name": "Phạm Nam",
                "id": "424522607913714"
            },
            "permalink_url": "https://www.facebook.com/LaravelCommunity/posts/1321961834523909"
        },
        {
            "id": "365155643537871_1766722286972894",
            "message": "https://www.youtube.com/channel/UCQ6fynaWa81JqPzOBMmBTSw\nLaravel BAsic To Advance LEarning Step by STep",
            "created_time": "2017-05-13T07:18:53+0000",
            "from": {
                "name": "Wasiim Khan",
                "id": "1766622610316195"
            },
            "permalink_url": "https://www.facebook.com/photo.php?fbid=1766722286972894&set=o.365155643537871&type=3",
            "full_picture": "https://scontent.xx.fbcdn.net/v/t1.0-9/18403359_1766722286972894_2242179936023685636_n.jpg?oh=679c3e230ef55759ebe0e42239318e27&oe=597B1F7D"
        },
        {
            "id": "365155643537871_1320698884650204",
            "message": "ai cho em hou noi nay bi sao vay.\nIntegrity constraint violation: 1048 Column 'order' cannot be null",
            "created_time": "2017-05-13T05:05:27+0000",
            "from": {
                "name": "Trong Phạm Sr.",
                "id": "891899864284241"
            },
            "permalink_url": "https://www.facebook.com/LaravelCommunity/posts/1320698884650204"
        }
    ]
}
```

## To use with **dotenv** 
- Look at [example code](https://github.com/xmhafiz/fb-page-feed/blob/master/example/index.php)
- copy the `env.example` file to `.env` and make sure fill all the required environment variable

## Todo
- flexible query fields

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
