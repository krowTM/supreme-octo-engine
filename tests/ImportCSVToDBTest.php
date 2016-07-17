<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\ImportCSVToDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;


class ImportCSVToDBTest extends TestCase
{	
    public function testCsvImport()
    {
    	dispatch(new ImportCSVToDB(parent::TEST_CSV_FILE));
    	
    	$this->seeInDatabase("links", [
    		"FromURL" => "https://test-url.com/1", 
    		"BLdom" => 6482, 
    		"DomPop" => 16406
    	]);
    	$this->seeInDatabase("links", [
    		"FromURL" => "https://test-url.com/2", 
    		"BLdom" => 836482,
    		"DomPop" => 136406,
    	]);
    	
    	$progress = file_get_contents(public_path() . "/progress/progress.json");
    	$this->assertEquals(json_encode("Done."), $progress);
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->createApplication();
        $test_csv = '"Favorites","From URL","To URL","Anchor Text","Link Status","Type","BLdom","DomPop","Power","Trust","Power*Trust","Alexa","IP","CNTRY"
"N","https://test-url.com/1","http://www.linkresearchtools.com/case-studies/","http://www.linkresearchtools.com/case-studies/","NOFOLLOW","text","6,482","16,406","5","6","30","3,341","50.116.50.37","US"
"N","https://test-url.com/2","http://www.linkresearchtools.com/","christoph at linkresearchtools","FOLLOW","text","836,482","136,406","5","6","30","3,341","50.116.50.37","US"';
        Storage::put(parent::TEST_CSV_FILE, $test_csv);
    }
    
    public function tearDown()
    {
        Storage::delete(parent::TEST_CSV_FILE);
        DB::table("links")->where("FromURL", '=', "https://test-url.com/1")->delete();
        DB::table("links")->where("FromURL", '=', "https://test-url.com/2")->delete();
        parent::tearDown();
    }
}
