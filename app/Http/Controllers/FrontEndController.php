<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Jobs\ImportCSVToDB;
use App\Link;
use App\App;
use Illuminate\Support\Facades\DB;

class FrontEndController extends Controller
{
    public function import() 
    {
    	$csv_file = "testData--www-linkresearchtools-com.csv";
        $this->dispatch(new ImportCSVToDB($csv_file));
    }
    
    public function generateChart(Request $request)
    {
    	$type = $request->input("type");
    	$this->getChart($type);

    	return view("ajax.chart", ["type" => $type]);
    }
    
    /*
        “Anchor Text” grouped by values converted to lowercase (word / tag cloud chart)
        “Link Status” (pie or donut chart)
        “From URL” grouped by host (pie or donut chart)
        “BLdom” grouped by defined classes [0|1 - 10|11 - 100|\< 1,000|\< 10,000|\< 100,000|\> 100,000] (bar chart)
     */
    private function getChart($type)
    {    	
    	if ($type == "link-status") {
    		$link_status = Link::raw(function($collection){
    		    return $collection->aggregate(array(
    		        array(
    		            '$group' => [
    		                '_id' => '$LinkStatus',
    		                'count' => [
    		                    '$sum' => 1
    		                ]
    		            ]
    		        )
    		    ));
    		});

    		$data = \Lava::DataTable();
    		$data->addStringColumn('Link Status')
    		     ->addNumberColumn('Count');

    		foreach ($link_status as $ls) {
    			$data->addRow([$ls->_id, $ls->count]);
    		}

    		return \Lava::PieChart("Chart", $data, [
				'title'  => 'Link Status',
				'is3D'   => true
    		]);
    	}
    	
    	
    }
}
