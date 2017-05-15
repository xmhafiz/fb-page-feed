<?php
/**
 * Description of FbFeed
 *
 * @author hafiz
 */
namespace Xmhafiz\FbFeed;

use GuzzleHttp\Client;

class Request {

    static function getPageFeed($fbPageName, $fbSecretKey, $fbAppId, $maxPost = 20) {
        $client = new Client();

        // this is how to construct access token using secret key and app id
        $accessToken = $fbAppId . '|' . $fbSecretKey;

        // make request as stated in https://developers.facebook.com/docs/graph-api/using-graph-api
        $url = 'https://graph.facebook.com/' . $fbPageName . '/feed';

        // error handler when status code not 200
        try {

            // start requet
            $response = $client->request('GET', $url, [
                'query' => [
                    'access_token' => $accessToken,
                    'limit' => $maxPost,
                    // fields that we intended to get
                    'fields' => 'id,message,created_time,from,permalink_url,full_picture',
                ]
            ]);
            $json = $response->getBody();

            if ($response->getStatusCode() == 200) {

                $dataArray = json_decode($json, true);

                // reformat data
                $feeds = $dataArray['data'];

                return [
                    'error' => false,
                    'status_code' => 200,
                    'data' => $feeds
                ];
            }
            else {
                return [
                    'error' => true,
                    'status_code' => $response->getStatusCode(),
                    'message' => 'Unexpecetd error occurred'
                ];
            }

        }
        catch (\Exception $e) {
            return [
                'error' => true,
                'status_code' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
