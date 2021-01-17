<!-- db connection -->
<?php  include 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Search</title>
    <link rel="stylesheet" href="css/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <div id="header">
            <div class="header-links">
            <?php
                  session_start();
                if(isset($_SESSION['email_address'])){ ?>
                    <span>Hi, <?php echo $_SESSION['first_name']; ?> </span> |
                    <a href="<?php echo $URL.'profile.php'; ?>">Profile</a> |
                    <a href="<?php echo $URL.'logout.php'; ?>">Logout</a>
                <?php }else{ ?>
                    <a href="<?php echo $URL.'login.php'; ?>">Login</a> |
                    <a href="<?php echo $URL.'register.php'; ?>">Register</a>
                <?php } ?>            
            </div>
        </div>
        <div id="content" class="result-content">
        	<div class="container">
	        	<div class="row">
	        		<div class="col-md-8 offset-md-2">
	        			<form class="form-horizontal" method="POST" action="searchfunction.php">
			                <div class="input-group mb-3">
			                  <input type="text" class="form-control" name="searchField1" placeholder="What are you looking for?">
			                  <div class="input-group-append">
			                    <input type="submit" value="Search" class="btn btn-success" type="submit">
			                  </div>
			                </div>
			            </form>
	        		
	        	<?php 
	        	require 'vendor/autoload.php';

if(isset($_POST['search'])) {//.  here is searchfield1

	$stry = $_POST['searchField1'];
	filter_var($stry, FILTER_SANITIZE_STRING);
	//print_r($stry);
	
	
	$client = Elasticsearch\ClientBuilder::create()->build();


	$params = [
	    'index' => 'index-name',
	    'body'  => [
	        'query' => [
	            'bool' => [
	            	'should' => [
	            		//'match' => ['patentID' => $_POST['searchField1']],
	            		//'match' => ['figid' => $_POST['searchField1']],
	            		//'match' => ['description' => $_POST['searchField1']],
	            		'match' => ['patentID' => $stry],
	            		'match' => ['figid' => $stry],
	            		'match' => ['description' => $stry],
	            		
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
	            		//'match' => ['patentID' => $_POST['searchField1']],
	            		//'match' => ['figid' => $_POST['searchField1']],
	            		//'match' => ['description' => $_POST['searchField1']],
	            		'match' => ['patentID' => $stry],
	            		'match' => ['figid' => $stry],
	            		'match' => ['description' => $stry],
	                ]
	                
                ]
	        ],
  			
  			'size' =>  $response['hits']['total']['value'] 			
	    ]
	];

	echo '<span class="total-rows">Total number of results for '.$stry.' : ' . $response['hits']['total']['value'].'</span>';	
	$response = $client->search($params);
	$testJson = json_encode($response);
	$test = json_decode(json_encode($response), true);
	$hits = count($test['hits']['hits']);

    // echo '<table border="1">';
    // echo '<tr>';
    // //echo '<td>#</td>';
    // echo '<td>Image</td>';
    // echo '<td>FigId</td>';
    // echo '<td>Description</td>';
    // echo '</tr>';

	for ($x = 0; $x < $test['hits']['total']['value']; $x++) {

		//echo '-------------Printing result #: ' . $x;

		$abc = $test['hits']['hits'][$x]['_source']['patentID'];

		//echo '---------PatentId: ' . $test['hits']['hits'][$x]['_source']['patentID'];
    

    echo '</div>
        	</div>
        	<div class="row">
    			<div class="col-lg-3 col-md-4 col-sm-6">
    				<div class="result-grid">';
    
    if(file_exists("jsonFiles/dataset/" . $test['hits']['hits'][$x]['_source']['patentID'] . "-D0000" . $test['hits']['hits'][$x]['_source']['figid'] . ".png")) {
    	// echo '<td>';
    	//echo "<br /> <br/>==========No Image available======================<br /><br/>";
    	echo '<a href="jsonFiles/dataset/' . $test['hits']['hits'][$x]['_source']['patentID'] . '-D0000' . $test['hits']['hits'][$x]['_source']['figid'] . '.png" class="image-box">' . '<img src="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png"  width="25%"  height="25%" />' . '</a>';
    	// echo '</td>';
    	echo '<div class="content-box">';
    	echo '<h3 class="title">'.$response['hits']['hits'][$x]['_source']['figid'].'</h3>';
    	// echo '</td>';
    	// echo '<td>';
    	echo '<p class="description">'.$response['hits']['hits'][$x]['_source']['description'].'</p>
				<ul class="action-list">
					<li><a href=""><i class="far fa-bookmark"></i></a></li>
					<li><a href=""><i class="far fa-heart"></i> (10)</a></li>
					<li><a href=""><i class="fa fa-download"></i></a></li>
					<li><a href="">See More..</a></li>
				</ul>';
    	echo '</div>';
    }
    echo '		</div>
    		</div>
		</div>';

}
// 	echo '</table';

// 	echo "<br>";
// 	echo '<ul><li><a href="1"></li>
// 	<li><a href="2"></li>
// 	<li><a href="3"></li>
// </ul>';

	
}
	        	 ?>
	        	<!-- <div class="row">
	        		<div class="col-lg-3 col-md-4 col-sm-6">
	        			<div class="result-grid">
	        				<a href="" class="image-box">
	        					<img src="images/image.png" alt="">
	        				</a>
	        				<div class="content-box">
	        					<h3 class="title"><a href="">Lorem ipsum, dolor.</a></h3>
	        					<p class="description">Lorem ipsum dolor sit amet.</p>
	        					<ul class="action-list">
	        						<li><a href=""><i class="far fa-bookmark"></i></a></li>
	        						<li><a href=""><i class="far fa-heart"></i> (10)</a></li>
	        						<li><a href=""><i class="fa fa-download"></i></a></li>
	        						<li><a href="">See More..</a></li>
	        					</ul>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="col-lg-3 col-md-4 col-sm-6">
	        			<div class="result-grid">
	        				<a href="" class="image-box">
	        					<img src="images/image.png" alt="">
	        				</a>
	        				<div class="content-box">
	        					<h3 class="title"><a href="">Lorem ipsum, dolor.</a></h3>
	        					<p class="description">Lorem ipsum dolor sit amet.</p>
	        					<ul class="action-list">
	        						<li><a href=""><i class="far fa-bookmark"></i></a></li>
	        						<li><a href=""><i class="far fa-heart"></i> (10)</a></li>
	        						<li><a href=""><i class="fa fa-download"></i></a></li>
	        						<li><a href="">See More..</a></li>
	        					</ul>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="col-lg-3 col-md-4 col-sm-6">
	        			<div class="result-grid">
	        				<a href="" class="image-box">
	        					<img src="images/image.png" alt="">
	        				</a>
	        				<div class="content-box">
	        					<h3 class="title"><a href="">Lorem ipsum, dolor.</a></h3>
	        					<p class="description">Lorem ipsum dolor sit amet.</p>
	        					<ul class="action-list">
	        						<li><a href=""><i class="far fa-bookmark"></i></a></li>
	        						<li><a href=""><i class="far fa-heart"></i> (10)</a></li>
	        						<li><a href=""><i class="fa fa-download"></i></a></li>
	        						<li><a href="">See More..</a></li>
	        					</ul>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="col-lg-3 col-md-4 col-sm-6">
	        			<div class="result-grid">
	        				<a href="" class="image-box">
	        					<img src="images/image.png" alt="">
	        				</a>
	        				<div class="content-box">
	        					<h3 class="title"><a href="">Lorem ipsum, dolor.</a></h3>
	        					<p class="description">Lorem ipsum dolor sit amet.</p>
	        					<ul class="action-list">
	        						<li><a href=""><i class="far fa-bookmark"></i></a></li>
	        						<li><a href=""><i class="far fa-heart"></i> (10)</a></li>
	        						<li><a href=""><i class="fa fa-download"></i></a></li>
	        						<li><a href="">See More..</a></li>
	        					</ul>
	        				</div>
	        			</div>
	        		</div>

	        	</div> -->
        	</div>
        </div>
    </div>
</body>
</html>
            
            
