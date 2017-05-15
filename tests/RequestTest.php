<?php
namespace Tests;

require_once __DIR__ .'/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Xmhafiz\FbFeed\Request;
/**
* RequestTest.php
* to test function in Request class
*/
class RequestTest extends TestCase
{
	protected $fbPageName;
	protected $fbPageId;
	protected $fbSecretKey;

	protected function setUp() 
	{
		try {
			$env = new Dotenv(__DIR__ . '/../');
			$env->load();
		}
		catch (\Exception $e){
			echo $e->getMessage();
		}

		// set page
		$this->fbSecretKey =  $_SERVER['FB_SECRET_KEY'];
		$this->fbAppId = $_SERVER['FB_APP_ID'];
		$this->fbPageName = $_SERVER['FB_PAGENAME'];
	}

	function testGetPageFeedSuccess() 
	{
		// check default feed request
		$response = Request::getPageFeed($this->fbPageName, $this->fbSecretKey, $this->fbAppId);

	    $this->assertFalse($response['error']);
	    $this->assertEquals(200, $response['status_code']);
	}

	function testGetPageFeedLimitFivePost() 
	{
		// feed request with maximum 5 post
		$response = Request::getPageFeed($this->fbPageName, $this->fbSecretKey, $this->fbAppId, 5);

	    $this->assertFalse($response['error']);
	    $this->assertEquals(5, count($response['data']));
	}

	function testGetPageFeedEmptyPageNameFailed() 
	{
		// make pagename empty
		$response = Request::getPageFeed($this->fbPageName, $this->fbSecretKey, '');

	    $this->assertTrue($response['error']);
	    $this->assertEquals(500, $response['status_code']);
	}
}