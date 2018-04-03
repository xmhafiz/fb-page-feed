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
	protected $fbAppId;
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
		$this->fbSecretKey =  getenv('FB_SECRET_KEY');
		$this->fbAppId = getenv('FB_APP_ID');
		$this->fbPageName = getenv('FB_PAGENAME');
	}

    function testGetPageFeedWithoutEnv()
    {
        // check default feed request
        $response = fb_feed()
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertEquals(200, $response['status_code']);
    }

    function testGetPageFeedWithEnv()
    {
        // check default feed request
        $response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertEquals(200, $response['status_code']);
    }

	function testGetPageFeedSuccess() 
	{
		// check default feed request
		$response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->fetch();

	    $this->assertFalse($response['error']);
	    $this->assertEquals(200, $response['status_code']);
	}

    function testGetPageFeedUsingConstructorSuccess()
    {
        // check default feed request
        $response = fb_feed()
            ->setCredential($this->fbAppId, $this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->fetch();

        $this->assertFalse($response['error']);
        $this->assertEquals(200, $response['status_code']);
    }

	function testGetPageFeedLimitFivePost() 
	{
		// feed request with maximum 5 post
        $response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->feedLimit(5)
            ->fetch();

	    $this->assertFalse($response['error']);
	    $this->assertEquals(5, count($response['data']));
	}

	function testGetPageFeedEmptyPageNameFailed() 
	{
		// make pagename empty
        $response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage(null)
            ->fetch();

	    $this->assertTrue($response['error']);
	    $this->assertEquals(500, $response['status_code']);
	}

    function testGetPageFeedByKeywordSuccess()
    {
        // make pagename empty
        $response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->findKeyword("#AirSelangor")
            ->fetch();

        $this->assertTrue(true);
        $this->assertEquals(200, $response['status_code']);
    }

    function testGetPageFeedByKeywordArraySuccess()
    {
        // make pagename empty
        $response = fb_feed()
            ->setAppId($this->fbAppId)
            ->setSecretKey($this->fbSecretKey)
            ->setPage($this->fbPageName)
            ->findKeyword(['#AirSelangor', '#JimatAir'])
            ->fetch();

        $this->assertTrue(true);
        $this->assertEquals(200, $response['status_code']);
    }

    function testGetPageFeedByKeywordWihtoutEnvSuccess()
    {
        // make pagename empty
        $response = fb_feed()
            ->findKeyword(['#AirSelangor', '#JimatAir'])
            ->fetch();

        $this->assertTrue(true);
        $this->assertEquals(200, $response['status_code']);
    }
}