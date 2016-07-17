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

    	return view("ajax.chart", [
    		"type" => $type, 
    		"chart" => $this->getChart($type)
    	]);
    }
    
    private function getChart($type)
    {    	
    	switch ($type) {
    	    case "link-status":
    	    	return $this->generateLinkStatusChart();
    	    	break;
        	case "from-url":
        	    return $this->generateFromUrlChart();
        	    break;
    	    case "bldom":
    	        return $this->generateBLdomChart();
    	        break;
    	    case "anchor-text":
    	    	return $this->generateAnchorTextChart();
    	    	break;
    	}
    	
    }
    
    private function generateAnchorTextChart()
    {
    	$anchor_texts = Link::raw(function($collection){
    	    return $collection->aggregate(array(
    	        [
    	            '$group' => [
    	                '_id' => ['$toLower' => '$AnchorText'],
    	                'count' => [
    	                    '$sum' => 1
    	                ]
    	            ]    	        		
    	        ],
    	    	[
    	         	'$sort' => [
    	        		'count' => -1
    	        	]
    	        ]
    	    ));
    	});
    	
    	$max = $anchor_texts[0]->count;
	
	    foreach ($anchor_texts as $i => $at) {
	    	if ($at->count >= ($max / 5 * 4)) 
	    		$anchor_texts[$i]->display_class = "largest";
	    	elseif ($at->count >= ($max / 5 * 3)) 
                $anchor_texts[$i]->display_class = "large";
	    	elseif ($at->count >= ($max / 5 * 2)) 
                $anchor_texts[$i]->display_class = "medium";
	    	elseif ($at->count >= ($max / 5 * 1)) 
                $anchor_texts[$i]->display_class = "small";
	    	else $anchor_texts[$i]->display_class = "smallest";
	    }
	
	    return $anchor_texts;
    }
    
    private function generateBLdomChart()
    {
    	$data = \Lava::DataTable();
    	$data->addStringColumn('Class')
    	     ->addNumberColumn('BLdom');
    		
	    $data->addRow([
	    	'0',
	    	Link::where('BLdom', '=', 0)->count(),
	    ]);
	    
	    $data->addRow([
	        '1 - 10',
	    	Link::where('BLdom', '>=', 1)
	    		->where('BLdom', '<=', 10)
	    		->count()
	    ]);
	    
	    $data->addRow([
	        '11 - 100',
	    	Link::where('BLdom', '>=', 11)
	    		->where('BLdom', '<=', 100)
	    		->count()
	    ]);
	    
	    $data->addRow([
	        '101 - 1000',
	    	Link::where('BLdom', '>=', 101)
	    		->where('BLdom', '<=', 1000)
	    		->count()
	    ]);
	    
	    $data->addRow([
	        '1001 - 10000',
	    	Link::where('BLdom', '>=', 1001)
	    		->where('BLdom', '<=', 10000)
	    		->count()
	    ]);
	    
	    $data->addRow([
	        '10001 - 100000',
	    	Link::where('BLdom', '>=', 10001)
	    		->where('BLdom', '<=', 100000)
	    		->count()
	    ]);
	    
	    $data->addRow([
	        '> 100001',
	    	Link::where('BLdom', '>=', 100001)
	    		->count()
	    ]);
	
	    return \Lava::BarChart("Chart", $data, [
	        'title'  => 'From URL',
	        'is3D'   => true
	    ]);
    }
    
    private function generateFromUrlChart() 
    {
        $from_url = Link::raw(function($collection){
            return $collection->aggregate(array(
                array(
                    '$group' => [
                        '_id' => '$IP',
                        'count' => [
                            '$sum' => 1
                        ]
                    ]
                )
            ));
        });
    
        $data = \Lava::DataTable();
        $data->addStringColumn('IP')
             ->addNumberColumn('Count');

        foreach ($from_url as $fu) {
            $data->addRow([$fu->_id, $fu->count]);
        }

        return \Lava::PieChart("Chart", $data, [
            'title'  => 'IP',
            'is3D'   => true
        ]);
    }
    
    private function generateLinkStatusChart() 
    {
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
