<?php
namespace Tests;

require_once __DIR__ .'/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
/**
* RequestTest.php
* to test function in Request class
*/
class RequestTest extends TestCase
{
	protected $fbPageName;
	protected $fbAccessToken;

	protected function setUp() :void
	{
		try {
			$env = new Dotenv(__DIR__ . '/../');
			$env->load();
		}
		catch (\Exception $e){
			echo $e->getMessage();
		}

		// set page
        $this->fbAccessToken = getenv('FB_ACCESS_TOKEN');
		$this->fbPageName = getenv('FB_PAGENAME');
	}

    function testGetPageFeedWithoutEnv()
    {
        // check default feed request
        $response = fb_feed()
            ->fetch();

        // check if got 500 caused by fb Apps dont have approval
        if ($this->isAppsApproved($response)) {
            $this->assertFalse($response['error']);
            $this->assertEquals(200, $response['status_code']);
        }
    }

    function testGetPageFeedWithEnv()
    {
        // check default feed request
        $response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertFalse($response['error']);
            $this->assertEquals(200, $response['status_code']);
        }
    }

	function testGetPageFeedSuccess() 
	{
		// check default feed request
		$response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertFalse($response['error']);
            $this->assertEquals(200, $response['status_code']);
        }
	}

    function testGetPageFeedUsingConstructorSuccess()
    {
        // check default feed request
        $response = fb_feed()
            ->setCredential($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertFalse($response['error']);
            $this->assertEquals(200, $response['status_code']);
        }
    }

	function testGetPageFeedLimitFivePost() 
	{
		// feed request with maximum 5 post
        $response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->feedLimit(5)
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertFalse($response['error']);
            $this->assertEquals(5, count($response['data']));
        }
	}

	function testGetPageFeedEmptyPageNameFailed() 
	{
		// make pagename empty
        $response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage(null)
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertTrue($response['error']);
            $this->assertEquals(500, $response['status_code']);
        }
	}

    function testGetPageFeedByKeywordSuccess()
    {
        // by keyword
        $response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->findKeyword("#tutorial")
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertTrue(true);
            $this->assertEquals(200, $response['status_code']);
        }
    }

    function testGetPageFeedByKeywordArraySuccess()
    {
        // by array of keywords
        $response = fb_feed()
            ->setAccessToken($this->fbAccessToken)
            ->setPage($this->fbPageName)
            ->findKeyword(['#tutorial', '#laravel'])
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertTrue(true);
            $this->assertEquals(200, $response['status_code']);
        }
    }

    function testGetPageFeedByKeywordWihtoutEnvSuccess()
    {
        // by array of keywords without env
        $response = fb_feed()
            ->findKeyword(['#tutorial', '#laravel'])
            ->fetch();

        if ($this->isAppsApproved($response)) {
            $this->assertTrue(true);
            $this->assertEquals(200, $response['status_code']);
        }
    }

    private function isAppsApproved($response)
    {
        // check if got 500 caused by fb Apps dont have approval
        $fbErrorMessage = ['Page Public Content Access', 'app secret proof or an app token', 'This endpoint requires'];
        if ($response['status_code'] != 200) {
            foreach ($fbErrorMessage as $needle) {
                if (stripos(strtolower($response['message']), strtolower($needle)) !== false) {
                    return false;
                }
            }
        }

        return true;
    }
}