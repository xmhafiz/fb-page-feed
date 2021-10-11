<?php
/**
 * Description of FbFeed
 *
 * @author hafiz
 */
namespace Xmhafiz\FbFeed;


use GuzzleHttp\Client;

class FbFeed {

    private $app_id;
    private $secret_key;
    private $page_name;
    private $headers;
    private $keyword = null;
    private $locale = null;
    private $limit = 100; // all of it
    private $fields = 'id,message,created_time,from,permalink_url,full_picture';
    private $access_token;
    private $module = 'posts';

    /**
     * FbFeed constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->secret_key = $config['secret_key'] ?? getenv('FB_SECRET_KEY');
        $this->app_id = $config['app_id'] ??  getenv('FB_APP_ID');
        $this->page_name = $config['page_name'] ??  getenv('FB_PAGENAME');
        $this->access_token = $config['access_token'] ??  getenv('FB_ACCESS_TOKEN');

        $this->headers = [
            'accept' => 'application/json'
        ];
    }
  
    public static function make($config = null)
    {
        return new self($config);
    }
  
    /**
     * @param $app_id
     * @return FbFeed
     */
    function setAppId($app_id) : FbFeed
    {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * @param $app_id
     * @param $secret_key
     * @return FbFeed
     */
    function setCredential($app_id, $secret_key = null)  : FbFeed
    {
        if ($secret_key != null) {
            $this->app_id = $app_id;
            $this->secret_key = $secret_key;
        } else {
            $this->access_token = $app_id;
        }

        return $this;
    }

    /**
     * @param $secret_key
     * @return FbFeed
     */
    function setSecretKey ($secret_key)  : FbFeed
    {
        $this->secret_key = $secret_key;
        return $this;
    }
    
    /**
     * @param $access_token
     * @return FbFeed
     */
    function setAccessToken ($access_token) : FbFeed
    {
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * @param string[] $headers
     * @return FbFeed;
     */
    public function setHeaders(array $headers) : FbFeed
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param $fields
     * @return FbFeed
     */
    function fields($fields)  : FbFeed
    {
        if (is_array($fields)) {
            $fields = join(",", $fields);
        }
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param $page_name
     * @return FbFeed
     */
    function setPage($page_name)  : FbFeed
    {
        $this->page_name = $page_name;
        return $this;
    }

    /**
     * @param string $module
     * @return FbFeed
     */
    public function setModule(string $module) : FbFeed
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param $keyword
     * @return FbFeed
     */
    function findKeyword($keyword)  : FbFeed
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @param int $total
     * @return FbFeed
     */
    function feedLimit($total = 20)  : FbFeed
    {
        $this->limit = $total;
        return $this;
    }

    /**
     * @param $locale
     * @return FbFeed
     */
    function setLocale($locale)  : FbFeed
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return array
     */
    function fetch() {

        if (!$this->page_name) {
            return $this->returnFailed('Page Name is needed');
        }

        if (!$this->app_id &&! $this->access_token) {
            return $this->returnFailed('Facebook App ID is needed. Please refer to https://developers.facebook.com');
        }

        if (!$this->secret_key &&! $this->access_token) {
            return $this->returnFailed('Facebook Secret Key is needed. Please refer to https://developers.facebook.com');
        }

        $data = [
            'access_token' => $this->getTokenKey(),
            'fields' => $this->fields,
        ];

        if ($this->limit > -1) {
            $data['limit'] = $this->limit;
        }

        if ($this->locale) {
            $data['locale'] = $this->locale;
        }

        $client = new Client();

        // start request
        $response = $client->request('GET', "https://graph.facebook.com/{$this->page_name}/{$this->module}", [
            'query' => $data,
            'headers' => $this->headers
        ]);

        if ($response->getStatusCode() >= 200  && $response->getStatusCode() < 300) {

            $feeds = json_decode($response->getBody(), true)['data'];

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

        } else {
            return $this->returnFailed($response->json(), $response->status());
        }
    }

    private function getTokenKey()
    {
        return $this->access_token ?? ($this->app_id . '|' . $this->secret_key);
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
            'message' => ($message) ? $message['error']['message'] ?? $message : 'Unexpected error occurred'
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
