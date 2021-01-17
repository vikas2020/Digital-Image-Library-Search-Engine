<?php
// db connection  	
include 'config.php';
$output = '';
if(isset($_GET['search']) && $_GET['search'] != '') {

	if (!isset ($_GET['page']) ) {  
	    $page = 1;  
	} else {  
	    $page = $_GET['page'];  
	}  

	$limit = 5;  
	$start = ($page-1) * $limit; 

    //$stry = mysqli_real_escape_string($conn,$_POST['searchField1']);
    // Strip HTML Tags
	$stry = strip_tags($_GET['search']);
// Clean up things like &amp;
	//$stry = html_entity_decode($stry);
// Strip out any url-encoded stuff
	$stry = htmlspecialchars($stry);
// Replace non-AlNum characters with space
	$stry = preg_replace('/[^A-Za-z0-9]/', ' ', $stry);
// Replace Multiple spaces with single space
	$stry = preg_replace('/[^a-zA-Z0-9_<? %\[\]\.\(\)%&-]/s', '', $stry);
// Trim the string of leading/trailing space
	$stry = trim($stry);
	
	// $stry = filter_var($stry, FILTER_SANITIZE_STRING);
	require 'vendor/autoload.php';
	
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
	            		//'match' => ['_id' => $stry],
	            		
	            		]
	                ]
                ]
	        ],
	        'size' => $limit,
	        'from' => $start
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
	            		//'match' => ['_id' => $stry],
	                ]
	                
                ]
	        ],
  			
  			  'size' =>  $response['hits']['total']['value'] 			
	    ]
	];

	$response1 = $client->search($params);
	$testJson = json_encode($response1);
	$test1 = json_decode(json_encode($response1), true);
	$total_records = count($test1['hits']['hits']); 

	// $params = [
	//     'index' => 'index-name',
	//     'body'  => [
	//         'query' => [
	//             'bool' => [
	//             	'should' => [
	//             		//'match' => ['patentID' => $_POST['searchField1']],
	//             		//'match' => ['figid' => $_POST['searchField1']],
	//             		//'match' => ['description' => $_POST['searchField1']],
	//             		'match' => ['patentID' => $stry],
	//             		'match' => ['figid' => $stry],
	//             		'match' => ['description' => $stry],
	//             		//'match' => ['_id' => $stry],
	//                 ]
	                
 //                ]
	//         ],
  			
 //  			'size' =>  $response['hits']['total']['value'] 			
	//     ]
	// ];

	// $response = $client->search($params);
	// $testJson = json_encode($response);
	// $test = json_decode(json_encode($response), true);
	// $hits = count($test['hits']['hits']); 
	//print_r($test['hits']['hits']); 
	if(isset($hits) && $hits > 0){
		
		$output .= '<div class="row">
	    				<div class="col-md-12">
	    					<span class="total-rows">Total number of results for '.$stry.' : '. $total_records.'</span>
	    				</div>';

		for ($x = 0; $x < $hits; $x++) {

		//echo '-------------Printing result #: ' . $x;

		 $patent = $test['hits']['hits'][$x]['_source']['patentID'];
		 $figid = $test['hits']['hits'][$x]['_source']['figid'];
		 $description = $test['hits']['hits'][$x]['_source']['description'];
		 $id = $test['hits']['hits'][$x]['_id'];

		//echo '---------PatentId: ' . $test['hits']['hits'][$x]['_source']['patentID'];
	    
				if(file_exists("jsonFiles/dataset/" . $patent . "-D0000" . $figid . ".png")) {
				$output .= '<div class="col-lg-3 col-md-4 col-sm-6">
								<div class="result-grid">
									<a href="single.php?item='.$id.'" class="image-box">' . '<img src="jsonFiles/dataset/' . $patent . '-D0000' . $figid . '.png"  width="25%"  height="25%" />' . '</a>
									<div class="content-box">
										<h3 class="title">'.$figid.'</h3>
										<p class="description">'.preg_replace("/\w*?".preg_quote($stry)."\w*/i", "<span class='text-white bg-danger'>$0</span>", $description).'</p>
										<ul class="action-list">';
										if(!session_id()){ session_start(); }
										if(isset($_SESSION['email_address'])){
										$output .= '<li><a href="javascript:void(0)" data-id="'. $id.'" class="favourite"><i class="far fa-bookmark"></i></a></li>';
										}
										$output .= '<li><a href="jsonFiles/dataset/' . $patent . '-D0000' . $figid . '.png" download><i class="fa fa-download"></i></a></li>
											<li><a href="single.php?item='.$id.'">See More..</a></li>
										</ul>
									</div>
								</div>
							</div>';
				}
		
		}
		$output .= '</div>';
		
	$total_pages = ceil($total_records / $limit);
	$output .= '<ul class="pagination">';
      if($page > 1){
        $output .= '<li><a href="'.$_SERVER["PHP_SELF"].'?search='.$stry.'&page='.($page - 1).'">Prev</a></li>';
      }
      for($i = 1; $i <= $total_pages; $i++){
        if($i == $page){
          $active = "active";
        }else{
          $active = "";
        }
        $output .= '<li class="'.$active.'"><a href="'.$_SERVER["PHP_SELF"].'?search='.$stry.'&page='.$i.'">'.$i.'</a></li>';
      }
      if($total_pages > $page){
        $output .= '<li><a href="'.$_SERVER["PHP_SELF"].'?search='.$stry.'&page='.($page + 1).'">Next</a></li>';
      }

      $output .= '</ul>';
	}
	//echo $output;
}else{ $output = ''; } ?>
<!-- include header -->
<?php  include 'header.php'; ?>

		<div id="content" class="result-content">
			<div class="container">
		    	<div class="row">
		    		<div class="col-md-8 offset-md-2">
		    			<form id="form" class="form-horizontal" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		                    <div class="input-group">
		                      <input type="text" class="form-control" id="speechf" name="search" placeholder="What are you looking for?">
		                      <div class="input-group-append">
		                        <input type="submit" value="Search" class="btn btn-success" >
		                      </div>
		                      <div class="input-group-append">
		                        <button class="btn btn-warning"  type="button" onclick="startDictation()"><i class="fas fa-microphone"></i></button>
		                      </div>
		                    </div>
		                </form>
		    		</div>
		    	</div>
		    	<?php echo $output; ?>
			</div>
		</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
    	$(document).ready(function(){
    		$('.favourite').click(function(){
    			var el = $(this);
    			var id = $(this).attr('data-id');
    			$.ajax({
    				url: 'favourite.php',
    				method: 'POST',
    				data: {fav_id:id},
    				success: function(response){
       					el.addClass('active');
    				}
    			})
    		});
    	});

    	// HTML5 Speech Recognition API 

	  function startDictation() {

	    if (window.hasOwnProperty('webkitSpeechRecognition')) {

	      var recognition = new webkitSpeechRecognition();

	      recognition.continuous = false;
	      recognition.interimResults = false;

	      recognition.lang = "en-US";
	      recognition.start();
	      
	      recognition.onresult = function(e) {
	        document.getElementById('speechf').value
	                                 = e.results[0][0].transcript;
	        recognition.stop();
	        //document.getElementById('form').submit();
	      };

	      recognition.onerror = function(e) {
	        recognition.stop();
	      }

	    }
	  }
    </script>
</body>
</html>

<?php //} ?>




        


        
        
           
            
