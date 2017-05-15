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
		$env = new Dotenv(__DIR__ . '/../');
		$env->load();

		// set page
		$this->fbSecretKey =  getenv('FB_SECRET_KEY');
		$this->fbAppId = getenv('FB_APP_ID');
		$this->fbPageName = getenv('FB_PAGENAME');
	}

	function testGetPageFeedSuccess() 
	{

		$response = Request::getPageFeed($this->fbPageName, $this->fbSecretKey, $this->fbAppId);

	    $this->assertFalse($response['error']);
	    $this->assertEquals(200, $response['status_code']);
	}

	function testGetPageFeedEmptyPageNameFailed() 
	{
		// make pagename empty
		$response = Request::getPageFeed($this->fbPageName, $this->fbSecretKey, '');

	    $this->assertTrue($response['error']);
	    $this->assertEquals(500, $response['status_code']);
	}
}