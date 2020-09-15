<?php
/**
 * Description of FbFeed
 *
 * @author hafiz
 */
namespace Xmhafiz\FbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class FbFeed {

    private $app_id = null;
    private $secret_key = null;
    private $page_name = null;
    private $keyword = null;
    private $locale = null;
    private $limit = 100; // all of it
    private $fields = 'id,message,created_time,from,permalink_url,full_picture';
    private $access_token = null;
    private $module = 'posts';

    /**
     * FbFeed constructor.
     */
    public function __construct()
    {
        $this->secret_key =  getenv('FB_SECRET_KEY');
        $this->app_id = getenv('FB_APP_ID');
        $this->page_name = getenv('FB_PAGENAME');
        $this->access_token = getenv('FB_ACCESS_TOKEN');
    }
  
    public static function init()
    {
        return new FbFeed();
    }
  
    /**
     * @param $app_id
     * @return $this
     */
    function setAppId($app_id) {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * @param $app_id
     * @param $secret_key
     * @return $this
     */
    function setCredential($app_id, $secret_key) {
        $this->app_id = $app_id;
        $this->secret_key = $secret_key;
        return $this;
    }

    /**
     * @param $secret_key
     * @return $this
     */
    function setSecretKey ($secret_key) {
        $this->secret_key = $secret_key;
        return $this;
    }
    
    /**
     * @param $access_token
     * @return $this
     */
    function setAccessToken ($access_token) {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    function fields($fields) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $page_name
     * @return $this
     */
    function setPage($page_name) {
        $this->page_name = $page_name;
        return $this;
    }

    /**
     * @param string $module
     */
    public function setModule(string $module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param $keyword
     * @return $this
     */
    function findKeyword($keyword) {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @param int $total
     * @return $this
     */
    function feedLimit($total = 20) {
        $this->limit = $total;
        return $this;
    }

    /**
     * @param $locale
     * @return $this
     */
    function setLocale($locale) {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return array
     */
    function fetch() {
        $client = new Client();

        if (!$this->page_name) {
            return $this->returnFailed('Page Name is needed');
        }

        if (!$this->app_id &&! $this->access_token) {
            return $this->returnFailed('Facebook App ID is needed. Please refer to https://developers.facebook.com');
        }

        if (!$this->secret_key &&! $this->access_token) {
            return $this->returnFailed('Facebook Secret Key is needed. Please refer to https://developers.facebook.com');
        }
        
        // this is how to construct access token using secret key and app id
        $accessToken = $this->access_token ? $this->access_token : $this->app_id . '|' . $this->secret_key;

        // make request as stated in https://developers.facebook.com/docs/graph-api/using-graph-api
        $url = "https://graph.facebook.com/{$this->page_name}/{$this->module}";

        // error handler when status code not 200
        try {
            $query = [
                'query' => [
                    'access_token' => $accessToken,
                    'fields' => $this->fields,
                ]
            ];

            if ($this->limit > -1) {
                $query['query']['limit'] = $this->limit;
            }

            if ($this->locale) {
                $query['query']['locale'] = $this->locale;
            }

            // start request
            $response = $client->get($url, $query);

            $json = $response->getBody();
            if ($response->getStatusCode() == 200) {

                $dataArray = json_decode($json, true);

                // reformat data
                $feeds = $dataArray['data'];

                if ($this->keyword) {
                    $newFeeds = [];
                    foreach ($feeds as $feed) {
                        if (isset($feed['message'])) {
                            if (!is_array($this->keyword) && stripos($feed['message'], $this->keyword) !== false) {
                                $newFeeds[] = $feed;
                            } elseif (is_array($this->keyword) && $this->contains($this->keyword, $feed['message'])) {
                                $newFeeds[] = $feed;
                            }
                        }
                    }

                    $feeds = $newFeeds;
                }

                return $this->returnSuccess($feeds);
            }
            else {
                return $this->returnFailed('Unable to fetch. Client Error', $response->getStatusCode());
            }
        }
       catch (ClientException $e) {
            return $this->returnFailed(json_decode($e->getResponse()->getBody()->getContents())->error);
        }
    }

    private function returnSuccess($feeds = null, $code = 200) {
        return [
            'error' => false,
            'status_code' => $code,
            'data' => $feeds
        ];
    }

    private function returnFailed($message = null, $code = 500) {
        return [
            'error' => true,
            'status_code' => $code,
            'message' => ($message) ? $message : 'Unexpected error occurred'
        ];
    }

    private function contains($keywords, $string) {
        foreach ($keywords as $needle) {
            if (stripos($string, $needle) === false) {
                return false;
            }
        }

        return true;
    }
}
