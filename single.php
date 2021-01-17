<?php
    // db connection
    include 'config.php';

    
    include 'header.php';
?>
<div id="content">
    <div class="container">
        <div class="row">
                <?php
                   
               		$stry = $_GET['item'];
               		$sql = "SELECT item_id FROM likes WHERE item_id='{$stry}'";
               		$result = mysqli_query($conn,$sql);
               		if(mysqli_num_rows($result) > 0){
               			$count = '(<span class="like-count">'.mysqli_num_rows($result).'</span>)';
               		}else{
               			$count = '';
               		}

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
                                    // for($i=0;$i < count($items);$i++){
                                     'match' => ['_id' => $stry],
                                    // }
                                     // 'match' => ['figid' => $stry],
                                     // 'match' => ['description' => $stry],
                                        
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
                                         'match' => ['_id' => $stry],
                                         // 'match' => ['figid' => $stry],
                                         // 'match' => ['description' => $stry],
                                     ]
                                        
                                    ]
                             ],
                                
                                 'size' =>  $response['hits']['total']['value']          
                         ]
                     ];

                     //echo '<span class="total-rows">Total number of results for '.$stry.' : ' . $response['hits']['total']['value'].'</span>';   
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
                        

                        echo '<div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3">
                                     <div class="result-grid">';
                        
                        if(file_exists("jsonFiles/dataset/" . $test['hits']['hits'][$x]['_source']['patentID'] . "-D0000" . $test['hits']['hits'][$x]['_source']['figid'] . ".png")) {
                         // echo '<td>';
                         //echo "<br /> <br/>==========No Image available======================<br /><br/>";
                         echo '<a href="jsonFiles/dataset/' . $test['hits']['hits'][$x]['_source']['patentID'] . '-D0000' . $test['hits']['hits'][$x]['_source']['figid'] . '.png" class="image-box">' .'<img src="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png"  width="25%"  height="25%" />' . '</a>';
                         // echo '</td>';
                         echo '<div class="content-box">';
                         echo '<h3 class="title">figid :'.$response['hits']['hits'][$x]['_source']['figid'].'</h3>';
                         // echo '</td>';
                         // echo '<td>';
                         echo '<p class="description"><h4>Description :</h4>'.$response['hits']['hits'][$x]['_source']['description'].'</p>
                                 <ul class="action-list">';
                                if(!session_id()){ session_start(); }
								if(isset($_SESSION['email_address'])){
					 			echo '<li><a href="javascript:void(0)" data-id="'. $response['hits']['hits'][$x]['_id'].'" class="favourite"><i class="far fa-bookmark"></i></a></li>';
								}
                                 echo '<li><a href="javascript:void(0)" class="likes" data-id="'.$response['hits']['hits'][$x]['_id'].'"><i class="far fa-heart"></i> '.$count.'</a></li>
                                      <li><a href="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png" download><i class="fa fa-download"></i></a></li>
                                     
                                 </ul>';
                         echo '</div>';
                        }
                        echo '       </div>
                             </div>';

                    //	}
                   }
                 ?>
        		</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
    	$(document).ready(function(){
    		$('.likes').click(function(){
    			var el = $(this);
    			var id = $(this).attr('data-id');
    			$.ajax({
    				url: 'like.php',
    				method: 'POST',
    				data: {fav_id:id},
    				success: function(response){
    					window.location.reload();
    					// el.addClass('text-danger');
    				}
    			})
    		});
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
    </script>
	</body>
</html>
    



