<?php
    // db connection
    include 'config.php';

    /* Start the session */
    session_start();
      // check session exists
    if(!isset($_SESSION['email_address'])){
        header("location: :".$URL."login.php");
    }
    include 'header.php';
?>
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1>Profile</h1>
        <?php
        $user = $_SESSION['email_address'];

        $sql = "SELECT * FROM users WHERE email_address ='".$user."'";
        $result = mysqli_query($conn,$sql);

        ?>
        <table class="table bg-white">
            <?php
                while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td>Name</td>
                    <td><?php echo $row['first_name']. ' ' .$row['last_name']; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $row['email_address']; ?></td>
                </tr>
                <tr>
                    <td>Mobile</td>
                    <td><?php echo $row['mobile']; ?></td>
                </tr>
            <?php } ?>
           
           
        </table>
        <span class="modify"><a class="btn btn-success" href="modify.php">Modify Details</a></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h1>Saved Files</h1>
                </div>
                <?php

                $q = "SELECT item_id FROM favourites WHERE user_id='{$_SESSION['user']}'";
                    $res = mysqli_query($conn,$q);
                    if(mysqli_num_rows($res) > 0){
                        $items = [];
                        while($row = mysqli_fetch_assoc($res)){
                            array_push($items,$row['item_id']);
                        }

                       require 'vendor/autoload.php';

                       $client = Elasticsearch\ClientBuilder::create()->build();

                       $params = [
                         'index' => 'index-name',
                            'body' => [
                                'query' => [ // (5)
                                    'terms' => [
                                     '_id' => $items
                                 ]
                                ],
                                'size' => 1000,
                             ]
                         ];
                         // 'body'  => [
                         //     'query' => [
                         //         'bool' => [
                         //             'should' => [
                         //                 //'match' => ['patentID' => $_POST['searchField1']],
                         //                 //'match' => ['figid' => $_POST['searchField1']],
                         //                 //'match' => ['description' => $_POST['searchField1']],
                         //                // for($i=0;$i < count($items);$i++){
                         //                 ['match' => ['_id' => $items]],
                         //                // }
                         //                 // 'match' => ['figid' => $stry],
                         //                 // 'match' => ['description' => $stry],
                                           
                         //                 ]
                         //             ]
                         //            ]
                         //     ]
                         // ];
                       
                     //echo "printing_param: ".$params;
                     $response = $client->search($params);  

                     //echo $params;            
                       
                     //print_r($response).'<br /><br />';// print response in to json

                     $testJson = json_encode($response);//. enccode array into json
                     $test = json_decode(json_encode($response), true);
                     $hits = count($test['hits']['hits']);
                     $params = [
                         'index' => 'index-name',
                     //     'body'  => [
                     //         'query' => [
                     //             'bool' => [
                     //                 'should' => [
                     //                     //'match' => ['patentID' => $_POST['searchField1']],
                     //                     //'match' => ['figid' => $_POST['searchField1']],
                     //                     //'match' => ['description' => $_POST['searchField1']],
                     //                     ['match' => ['_id' => $items]],
                     //                     // 'match' => ['figid' => $stry],
                     //                     // 'match' => ['description' => $stry],
                     //                 ]
                                       
                     //                ]
                     //         ],
                               
                     //             'size' =>  $response['hits']['total']['value']          
                     //     ]
                     // ];



                            'body' => [
                                'query' => [ // (5)
                                    'terms' => [
                                     '_id' => $items
                                 ]
                                ],
                                'size' => 1000,
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
                    echo '</div>
                             <div class="row">';

                     for ($x = 0; $x < $test['hits']['total']['value']; $x++) {

                         //echo '-------------Printing result #: ' . $x;

                         $abc = $test['hits']['hits'][$x]['_source']['patentID'];

                         //echo '---------PatentId: ' . $test['hits']['hits'][$x]['_source']['patentID'];
                       

                        echo '<div class="col-lg-3 col-md-4 col-sm-6">
                                     <div class="result-grid">';
                       
                        if(file_exists("jsonFiles/dataset/" . $test['hits']['hits'][$x]['_source']['patentID'] . "-D0000" . $test['hits']['hits'][$x]['_source']['figid'] . ".png")) {
                         // echo '<td>';
                         //echo "<br /> <br/>==========No Image available======================<br /><br/>";
                         echo '<a href="single.php?item='.$response['hits']['hits'][$x]['_id'].'" class="image-box">' . '<img src="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png"  width="25%"  height="25%" />' . '</a>';
                         // echo '</td>';
                         echo '<div class="content-box">';
                         echo '<h3 class="title">'.$response['hits']['hits'][$x]['_source']['figid'].'</h3>';
                         // echo '</td>';
                         // echo '<td>';
                         echo '<p class="description">'.$response['hits']['hits'][$x]['_source']['description'].'</p>
                                 <ul class="action-list">';
                                 
                                 echo '<li><a href="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png" download><i class="fa fa-download"></i></a></li>
                                     <li><a href="single.php?item='.$response['hits']['hits'][$x]['_id'].'">See More..</a></li>
                                 </ul>
                                 <button class="btn btn-danger remove" data-id="'.$response['hits']['hits'][$x]['_id'].'"><i class="fa fa-times"></i></button>';
                         echo '</div>';
                        }
                        echo '       </div>
                             </div>';

                    }
                     echo '</div>';

                     

                       
                    }


                ////////////////////////////////
//                     $row1 = mysqli_fetch_assoc($result);
//                     // $fav_list = array_filter(explode(',',$row1['favourite']));


//              require 'vendor/autoload.php';

// // if(isset($_POST['search'])) {//.  here is searchfield1

//  $stry = $row1['favourite'];
//  filter_var($stry, FILTER_SANITIZE_STRING);
//  // print_r($stry); exit;
   
   
//  $client = Elasticsearch\ClientBuilder::create()->build();


//  $params = [
//      'index' => 'index-name',
//      'body'  => [
//          'query' => [
//              'bool' => [
//                  'should' => [
//                      //'match' => ['patentID' => $_POST['searchField1']],
//                      //'match' => ['figid' => $_POST['searchField1']],
//                      //'match' => ['description' => $_POST['searchField1']],
//                      [ 'match' => ['_id' => '5624']],
//                     [ 'match' => ['_id' => '5619']],
//                      // 'match' => ['figid' => "1"],
//                      // 'match' => ['description' => $stry],
                       
//                      ]
//                  ]
//                 ]
//          ]
//      ];
   
//  //echo "printing_param: ".$params;
//  $response = $client->search($params);  

//  //echo $params;            
   
//  //print_r($response).'<br /><br />';// print response in to json

//  $testJson = json_encode($response);//. enccode array into json
//  $test = json_decode(json_encode($response), true);
//  $hits = count($test['hits']['hits']);
//  $params = [
//      'index' => 'index-name',
//      'body'  => [
//          'query' => [
//              'bool' => [
//                  'should' => [
//                      //'match' => ['patentID' => $_POST['searchField1']],
//                      //'match' => ['figid' => $_POST['searchField1']],
//                      //'match' => ['description' => $_POST['searchField1']],
//                      //'match' => [['_id' => '5619']['_id' => '5623']],
//                     [ 'match' => ['_id' => '5624']],
//                     [ 'match' => ['_id' => '5619']],
//                      // 'match' => ['figid' => $stry],
//                      // 'match' => ['description' => $stry],
//                  ]
                   
//                 ]
//          ],
           
//              'size' =>  $response['hits']['total']['value']          
//      ]
//  ];

//  echo '<span class="total-rows">Total number of results for '.$stry.' : ' . $response['hits']['total']['value'].'</span>';  
//  $response = $client->search($params);
//  $testJson = json_encode($response);
//  $test = json_decode(json_encode($response), true);
//  $hits = count($test['hits']['hits']);
//  //print_r($test['hits']['hits']);

//     // echo '<table border="1">';
//     // echo '<tr>';
//     // //echo '<td>#</td>';
//     // echo '<td>Image</td>';
//     // echo '<td>FigId</td>';
//     // echo '<td>Description</td>';
//     // echo '</tr>';
// echo '</div>
//          <div class="row">';
// //echo $test['hits']['hits']['0']['_source']['patentID'].'-D0000' . $test['hits']['hits']['0']['_source']['figid'];
//  for ($x = 0; $x < $test['hits']['total']['value']; $x++) {

//      //echo '-------------Printing result #: ' . $x;

//      $abc = $test['hits']['hits'][$x]['_source']['patentID'];

//      //echo '---------PatentId: ' . $test['hits']['hits'][$x]['_source']['patentID'];
   

//     echo '<div class="col-lg-3 col-md-4 col-sm-6">
//                  <div class="result-grid">';
   
//     if(file_exists("jsonFiles/dataset/" . $test['hits']['hits'][$x]['_source']['patentID'] . "-D0000" . $test['hits']['hits'][$x]['_source']['figid'] . ".png")) {
//         echo '12';
//      // echo '<td>';
//      //echo "<br /> <br/>==========No Image available======================<br /><br/>";
//      echo '<a href="jsonFiles/dataset/' . $test['hits']['hits'][$x]['_source']['patentID'] . '-D0000' . $test['hits']['hits'][$x]['_source']['figid'] . '.png" class="image-box">' . '<img src="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png"  width="25%"  height="25%" />' . '</a>';
//      // echo '</td>';
//      echo '<div class="content-box">';
//      echo '<h3 class="title">'.$response['hits']['hits'][$x]['_source']['figid'].'</h3>';
//      // echo '</td>';
//      // echo '<td>';
//      echo '<p class="description">'.$response['hits']['hits'][$x]['_source']['description'].'</p>
//              <ul class="action-list">';
//              if(!session_id()){ session_start(); }
//              if(isset($_SESSION['email_address'])){
//                  echo '<li><a href="javascript:void(0)"><i class="far fa-bookmark"></i></a></li>';
//              }
//              echo '<li><a href="javascript:void(0)"><i class="far fa-heart"></i> (10)</a></li>
//                  <li><a href="jsonFiles/dataset/' . $abc . '-D0000' . $response['hits']['hits'][$x]['_source']['figid'] . '.png" download><i class="fa fa-download"></i></a></li>
//                  <li><a href="">See More..</a></li>
//              </ul>';
//      echo '</div>';
//     }else{
//         echo '13';
//     }
//     echo '       </div>
//          </div>';

// }
//  echo '</div>';

 

   

                ?>
                    
                   

                </div>
        </div>
       
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script>

$('.remove').click(function(){
                var el = $(this);
                var id = $(this).attr('data-id');
                $.ajax({
                    url: 'favourite.php',
                    method: 'POST',
                    data: {remove_fav:id},
                    success: function(response){
                        // alert(response);
                        console.log(response);
                        el.parent().parent().parent().remove();
                    }
                })
            });
           





   </script>>
  </body>
</html>

   



