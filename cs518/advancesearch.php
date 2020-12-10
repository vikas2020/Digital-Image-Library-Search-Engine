<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
		
	<form name="myform" action="advancesearch.php" method="post">
		<tr><h3>Search Bar</h3>
			<td><input type="text" name="searchField1" placeholder="search here" size="50" /></td>
		</tr><br></br>
		<tr> <h4>Advanced search</h4> 
		<!--	<td><input type="text" name="searchField2" placeholder="pid: like p-0001"/></td>-->
			<td><input type="text" name="searchField3" placeholder="patentID"/></td>
			<td><input type="text" name="searchField4" placeholder="aspect" /></td>
		</tr>
		
		<br></br>
	
		<input type="submit" name="search" value="search" />		
	</form>

</body>
</html>


<?php
require 'vendor/autoload.php';

if(isset($_POST['search'])) {
	$client = Elasticsearch\ClientBuilder::create()->build();
	$params = [
	    'index' => 'index-name',
	    'body'  => [
	        'query' => [
	        	'bool' => [
	        		'must' =>[
	                   [ 'match' => ['description' => $_POST['searchField1']]],
	                   //[ 'match' => ['pid' => $_POST['searchField2']]],
	                   [ 'match' => ['patentID' => $_POST['searchField3']]],

	                   [ 'match' => ['aspect' => $_POST['searchField4']]],

                   ]
                ]
	         ]
	       ]
	    ];
	
	//echo "printing_param: ".$params;
	$response = $client->search($params);
	
	echo 'total number of search results: '. $response['hits']['total']['value']. '<br /><br />';

	$test = json_decode(json_encode($response), true);
	$hits = count($test['hits']['hits']);



echo '<table border="1">';
    echo '<tr>';
    //echo '<td>#</td>';
    echo '<td>Image</td>';
    echo '<td>FigId</td>';
    echo '<td>Description</td>';
    echo '</tr>';


	for ($x = 0; $x < $hits; $x++) {

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












/*

    echo 'final patent ID: '. $response['hits']['hits'][$x]['_source']['patentID']. '<br /><br />';
    echo 'final description: '. $response['hits']['hits'][$x]['_source']['description']. '<br /><br />';
    //echo 'final object: '. $response['hits']['hits'][$x]['_source']['pid']. '<br /><br />';
    echo 'final aspect: '. $response['hits']['hits'][$x]['_source']['aspect']. '<br /><br />';*/

}
	
	echo "<br>";
	

?>




