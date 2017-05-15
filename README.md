# Fb Page Feed
It is simple wrapper class written in php to fetch posts from certain Facebook page.

Currently I am using [Facebook graph API](https://developers.facebook.com/docs/graph-api) with cool [guzzle](https://github.com/guzzle/guzzle) and (dotenv)[https://github.com/vlucas/phpdotenv]
## Requirement
- PHP 5.5++

## Installation

#### Step 1: Getting Facebook App
- Go to [Facebook developer](https://developers.facebook.com/apps/) website
- Click "Add a New App" and fill in details
- On your **Dashboard**, get the "App ID" and "App Secret"
- Yeah, you are ready to code

#### Step 2: Install from composer
```
composer require xmhafiz/fb-page-feed:dev-master
```
Alternatively, you can specify as a dependency in your project's existing composer.json file
```
{
   "require": {
      "xmhafiz/fb-page-feed": "dev-master"
   }
}
```

## Usage
After installing, you need to require Composer's autoloader and add your code.

#### Default (maximum post is 20)
```php
$data = Request::getPageFeed($pageName, $fbSecretKey, $fbAppId);
```

#### Custom Maximum Post Shown
```php
// only show 5 post maximum
$data = Request::getPageFeed($pageName, $fbSecretKey, $fbAppId, 5);
```



## Code Example

```php
<?php

require_once 'vendor/autoload.php';

use Xmhafiz\FbFeed\Request;

$fbSecretKey='580c7...';
$fbAppId='237...';
$fbPageName='LaravelCommunity';

$data = Request::getPageFeed($fbPageName, $fbSecretKey, $fbAppId);

header('Content-type: application/json');
echo json_encode($data);
```

Then, you should getting data similarly like below:
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
- copy the `env.example` file to `env` and make sure fill all the required environment variable
- detail usage please refer

## Todo
- flexible query fields

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
