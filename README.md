# Fb Page Feed
It is simple wrapper class written in php to fetch posts from certain Facebook page.

## Requirement
- PHP 5.5 and above

## Installation

### Step 1: Getting Facebook App
- Go to [Facebook developer] (https://developers.facebook.com/apps/) website
- Click "Add a New App" and fill in details
- On your **Dashboard**, get the "App ID" and "App Secret"
- Yeah, you are ready to code

### Step 2: Install from composer
```
composer require yohafiz/fb-page-feed:1.0.0
```
Alternatively, you can specify as a dependency in your project's existing composer.json file
```
{
   "require": {
      "yohafiz/fb-page-feed": "1.0.0"
   }
}
```

## Usage
After installing, you need to require Composer's autoloader and add your code.

### Default (maximum post is 20)
```php
$data = FbFeed\Request::getPageFeed($pageId, $fbSecretKey, $fbAppId);
```

### Custom Maximum Post Shown
```php
// only show 5 post maximum
$data = FbFeed\Request::getPageFeed($pageId, $fbSecretKey, $fbAppId, 5);
```



## Code Example
```php
<?php

require_once 'vendor/autoload.php';

use Yohafiz\FbFeed;

$fbSecretKey='580c7...';
$fbAppId='237...';
$pageId='LaravelCommunity';

$data = FbFeed\Request::getPageFeed($pageId, $fbSecretKey, $fbAppId);

header('Content-type: application/json');
echo json_encode($data);
```

Then, you should getting data similarly like below:
```json
[
    {
        "id": "365155643537871_10210239095868608",
        "message": "HOSTING LARAVEL\nIt offers web hosting for your projects in Laravel Framework all versions (4.x up to 5.4), we have support for PHP versions 5.3, 5.4, 5.5, 5.6, 7.0 and 7.1, up to 20 times faster thanks to the storage technology SSD.\n",
        "created_time": "2017-03-25T04:25:19+0000",
        "from": {
            "name": "Percy Ivan Miranda Moreano",
            "id": "10210243926669375"
        },
        "permalink_url": "https://www.facebook.com/LaravelCommunity/posts/10210239095868608",
        "full_picture": "https://external.xx.fbcdn.net/safe_image.php?d=AQD.."
    },
    {
        "id": "365155643537871_1268903959829697",
        "message": "i want to store the dropdown value in a variable, when selecting the value from dropdown list",
        "created_time": "2017-03-23T09:09:40+0000",
        "from": {
            "name": "Kumar Gundla",
            "id": "161803951007026"
        },
        "permalink_url": "https://www.facebook.com/LaravelCommunity/posts/1268903959829697"
    },
    {
        "id": "365155643537871_1268883043165122",
        "message": "Hello, I am looking for some help. I started to use Laravel and it is time to share it over my LAN. Struggling with it and need some advice if it is a virtualbox issue or more like vagrant setting on VM. thx",
        "created_time": "2017-03-23T08:29:53+0000",
        "from": {
            "name": "Konrad Tuszkowski",
            "id": "1257271121025042"
        },
        "permalink_url": "https://www.facebook.com/LaravelCommunity/posts/1268883043165122"
    }
]
```

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)
