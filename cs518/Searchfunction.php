<!DOCTYPE html>
<html>
<head>
</head>
<body><h3>Image Search</h3>
	<form name="myform" action="searchfunction.php" method="post">
		<input type="text" name="searchField1" placeholder="Search here" />
		<input type="submit" name="search" value="search" />
	</form>
</body>
</html>
<?php
require 'vendor/autoload.php';

if(isset($_POST['search'])) {//.  here is searchfield1
	$client = Elasticsearch\ClientBuilder::create()->build();


	$params = [
	    'index' => 'index-name',
	    'body'  => [
	        'query' => [
	            'bool' => [
	            	'should' => [
	            		'match' => ['patentID' => $_POST['searchField1']],
	            		'match' => ['figid' => $_POST['searchField1']],
	            		'match' => ['description' => $_POST['searchField1']],
	            		
	            		]
	                ]
                ]
	        ]
	    ];
	
	//echo "printing_param: ".$params;
	$response = $client->search($params);	

	//echo $params;				
	
	//print_r($response).'<br /><br />';// print response in to json

	$testJson = json_encode($response);//. enccode array into json
	$test = json_decode(json_encode($response), true);
	$hits = count($test['hits']['hits']);
	$params = [
	    'index' => 'index-name',
	    'body'  => [
	        'query' => [
	            'bool' => [
	            	'should' => [
	            		'match' => ['patentID' => $_POST['searchField1']],
	            		'match' => ['figid' => $_POST['searchField1']],
	            		'match' => ['description' => $_POST['searchField1']],
	                ]
	                
                ]
	        ],
  			
  			'size' =>  $response['hits']['total']['value'] 			
	    ]
	];

	echo 'Total results: ' . $response['hits']['total']['value'];	
	$response = $client->search($params);
	$testJson = json_encode($response);
	$test = json_decode(json_encode($response), true);
	$hits = count($test['hits']['hits']);

    echo '<table border="1">';
    echo '<tr>';
    //echo '<td>#</td>';
    echo '<td>Image</td>';
    echo '<td>FigId</td>';
    echo '<td>Description</td>';
    echo '</tr>';

	for ($x = 0; $x < $test['hits']['total']['value']; $x++) {

		//echo '-------------Printing result #: ' . $x;

		$abc = $test['hits']['hits'][$x]['_source']['patentID'];

		//echo '---------PatentId: ' . $test['hits']['hits'][$x]['_source']['patentID'];
    

    echo '<tr>';
    
    if(file_exists("jsonFiles/dataset/" . $test['hits']['hits'][$x]['_source']['patentID'] . "-D0000" . $test['hits']['hits'][$x]['_source']['figid'] . ".png")) {
    	echo '<td>';
    	//echo "<br /> <br/>==========No Image available======================<br /><br/>";
    	echo '<a href="jsonFiles/dataset/' . $test['hits']['hits'][$x]['_source']['patentID'] . '-D0000' . $test['hits']['hits'][$x]['_source']['figid'] . '.png">' . '<img src="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png"  width="50%"  height="50%" />' . '</a>';
    	echo '</td>';
    	echo '<td>';
    	echo $response['hits']['hits'][$x]['_source']['figid'];
    	echo '</td>';
    	echo '<td>';
    	echo $response['hits']['hits'][$x]['_source']['description'];
    	echo '</td>';
    }
    echo '</tr>';

}
	echo '</table';

	echo "<br>";
	
}
?>

