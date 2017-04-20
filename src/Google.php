<?php

namespace LeadThread\GoogleShortener;

use Exception;
use LeadThread\GoogleShortener\Exceptions\GoogleAuthException;
use LeadThread\GoogleShortener\Exceptions\GoogleErrorException;
use LeadThread\GoogleShortener\Exceptions\GoogleRateLimitException;
use GuzzleHttp\Client;

class Google
{
    const V1 = 'v1';

    protected $token;
    protected $host;
    protected $version;
    protected $client;

    /**
     * Creates a Calendly instance that can register and unregister webhooks with the API
     * @param string $token   The API token to use
     * @param string $version The API version to use
     * @param string $host    The Host URL
     * @param string $client  The Client instance that will handle the http request
     */
    public function __construct($token, $version = self::V1, $host = "www.googleapis.com", Client $client = null){
        $this->client = $client;
        $this->token = $token;
        $this->version = $version;
        $this->host = $host;
    }

    public function shorten($url, $encode = true)
    {
        if (empty($url)) {
            throw new GoogleErrorException("The URL is empty!");
        }

        $url = $this->fixUrl($url, $encode);

        $data = $this->exec($url);
            
        return $data['id'];
    }

    /**
     * Returns the response data or throws an Exception if it was unsuccessful
     * @param  string $raw The data from the response
     * @return array
     */
    protected function handleResponse($raw){
        $data = json_decode($raw,true);

        if(!empty($data["error"])){
            $reason = $data["error"]["errors"][0]["reason"];
            $msg    = $data["error"]["errors"][0]["message"];

            switch ($reason) {
                case 'keyInvalid':
                    throw new GoogleAuthException;
                    break;

                default:
                    throw new GoogleErrorException("Reason: {$reason}. Message: {$msg}.");
                    break;
            }
        }

        return $data;
    }

    /**
     * Returns a corrected URL
     * @param  string  $url    The URL to modify
     * @param  boolean $encode Whether or not to encode the URL
     * @return string          The corrected URL
     */
    protected function fixUrl($url, $encode){
        if(strpos($url, "http") !== 0){
            $url = "http://".$url;
        }

        if($encode){
            // Google does not support an encoded url
        }

        return $url;
    }

    /**
     * Builds the request URL to the Google API for a specified action
     * @param  string $action The long URL
     * @param  string $action The API action
     * @return string         The URL
     */
    protected function buildRequestUrl($action = "url"){
        return "https://{$this->host}/urlshortener/{$this->version}/{$action}?key={$this->token}";
    }

    /**
     * Returns the Client instance
     * @return Client
     */
    protected function getRequest(){
        $client = $this->client;
        if(!$client instanceof Client){
            $client = new Client();
        }
        return $client;
    }

    /**
     * Executes a CURL request to the Google API
     * @param  string $url    The URL to shorten
     * @return mixed          The response data
     */ 
    protected function exec($url)
    {
        $client = $this->getRequest();
        try{
            $response = $client->request('POST',$this->buildRequestUrl(),[
                'json' => [
                    'longUrl' => $url
                ]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e){
            $response = $e->getResponse();
        }
        return $this->handleResponse($response->getBody());
    }
}
