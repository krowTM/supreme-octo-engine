<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\FrontEndController;
use App\Jobs\ImportCSVToDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class FrontEndControllerTest extends TestCase
{
    public function testFrontPage()
    {
    	$this->visit('/')
    	   ->see("CSV Importer");
    }
    
    public function testImportStart()
    {
    	$this->expectsJobs([App\Jobs\ImportCSVToDB::class]);
    	
    	$this->action("POST", "FrontEndController@import");
    }
    
    public function testLinkStatusChartGeneration()
    {    	
    	$response = $this->call(
    		"POST",
    		"generate-chart",
    		[
    			"type" => "link-status"
    		]	
    	);
    	
    	$this->assertResponseStatus(200);
    	
    	$this->assertContains("PieChart", $response->content());
    }
    
    public function testAnchorTextChartGeneration()
    {
        $response = $this->call(
            "POST",
            "generate-chart",
            [
                "type" => "anchor-text"
            ]
        );
         
        $this->assertResponseStatus(200);
         
        $this->assertContains("christoph at linkresearchtools", $response->content());
    }

    public function setUp()
    {
        parent::setUp();
        $test_csv = '"Favorites","From URL","To URL","Anchor Text","Link Status","Type","BLdom","DomPop","Power","Trust","Power*Trust","Alexa","IP","CNTRY"
"N","https://test-url.com/1","http://www.linkresearchtools.com/case-studies/","http://www.linkresearchtools.com/case-studies/","NOFOLLOW","text","6,482","16,406","5","6","30","3,341","50.116.50.37","US"
"N","https://test-url.com/2","http://www.linkresearchtools.com/","christoph at linkresearchtools","FOLLOW","text","836,482","136,406","5","6","30","3,341","50.116.50.37","US"';
        Storage::put(parent::TEST_CSV_FILE, $test_csv);
        dispatch(new ImportCSVToDB(self::TEST_CSV_FILE));
    }
    
    public function tearDown()
    {
        Storage::delete(parent::TEST_CSV_FILE);
        DB::table("links")->where("FromURL", '=', "https://test-url.com/1")->delete();
        DB::table("links")->where("FromURL", '=', "https://test-url.com/2")->delete();
        parent::tearDown();
    }
}
