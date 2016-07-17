<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Csv\Reader;
use App\Link;

class ImportCSVToDB extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $csv_file;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        set_time_limit(0);
        $this->csv_file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$this->writeProgress("Starting...");

    	$reader = Reader::createFromPath(
    		storage_path("app/" . $this->csv_file)
    	);
    	$total = $reader->each(function ($row) {
            return true;
        });
    	
    	foreach ($reader as $i => $row) {
    		if ($i == 0) continue;
    		
    		$link = new Link;
    		$link->Favorites = $row[0];
    		$link->FromURL = $row[1];
    		$link->ToURL = $row[2];
    		$link->AnchorText = $row[3];
    		$link->LinkStatus = $row[4];
    		$link->Type = $row[5];
    		$link->BLdom = intval(str_replace(',', '', $row[6]));
    		$link->DomPop = intval(str_replace(',', '', $row[7]));
    		$link->Power = $row[8];
    		$link->Trust = $row[9];
    		$link->PowerTrust = $row[10];
    		$link->Alexa = intval(str_replace(',', '', $row[11]));
    		$link->IP = $row[12];
    		$link->CNTRY = $row[13];
    		$link->save();
    	    
    		$this->writeProgress("Imported $i/$total");
    	}
    	
    	$this->writeProgress("Done.");
    }
    
    private function writeProgress($message)
    {
        file_put_contents(
            public_path() . "/progress/progress.json", json_encode($message)
        );
    }
}
