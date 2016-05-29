<?php

namespace Zenapply\GoogleShortener\Tests;

use Zenapply\GoogleShortener\Google;
use Zenapply\GoogleShortener\Exceptions\GoogleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

class GoogleTest extends TestCase
{
    protected $request;

    public function testItCreatesAnInstanceOfHttpRequest(){
        $r = new Google("user","pass");
        $this->assertInstanceOf(Google::class,$r);
    }

    public function testItBuildsCorrectRequestUrl(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"status_code":200,"data":{"url":"short.com"}}');
        $result = $this->invokeMethod($fixture,'buildRequestUrl',['testAction']);
        $this->assertEquals("https://foo.com/urlshortener/v1/testAction?key=token",$result);
    }

    public function testItCorrectsAUrlByAddingAProtocolToIt(){
        $r = new Google("token");
        $result = $this->invokeMethod($r,'fixUrl',['google.com',false]);
        $this->assertEquals("http://google.com",$result);
    }

    public function testItDoesntAddAProtocolOnToAUrlWithAProtocol(){
        $r = new Google("token");
        $result = $this->invokeMethod($r,'fixUrl',['https://google.com',false]);
        $this->assertEquals("https://google.com",$result);
    }

    public function testMethodShorten(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"id":"short.com"}');
        $result = $fixture->shorten("long.com");
        $this->assertEquals("short.com",$result);
    }

    public function testMethodShortenAddsOnProtocol(){
        $fixture = $this->getBitlyWithMockedHttpRequest('{"id":"short.com"}');
        $result = $fixture->shorten("long.com");
        $this->assertEquals("short.com",$result);
    }

    public function testMethodShortenThrowsExceptionWhenUrlIsEmpty(){
        $this->setExpectedException(GoogleException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"id":"short.com"}');
        $result = $fixture->shorten("");
    }

    public function testMethodShortenThrowsExceptionWhenStatusCodeIsNot200(){
        $this->setExpectedException(GoogleException::class);
        $fixture = $this->getBitlyWithMockedHttpRequest('{"error": {"errors": [{ "domain": "usageLimits", "reason": "keyInvalid", "message": "Bad Request" }], "code": 400, "message": "Bad Request" }}');
        $result = $fixture->shorten("long.com");
    }

    protected function getBitlyWithMockedHttpRequest($data){
        $http = $this->getMock(Client::class);

        $resp = $this->getMock(Response::class);

        $resp->expects($this->any())
             ->method('getBody')
             ->will($this->returnValue($data));

        $http->expects($this->any())
             ->method('request')
             ->will($this->returnValue($resp));

        $obj = new Google("token","v1","foo.com",$http);

        // create class under test using $http instead of a real CurlRequest
        return $obj;
    }

}
