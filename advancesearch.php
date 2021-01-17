<?php  include 'header.php'; ?>
<div id="content" class="result-content">
      <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <form name="myform" class="form-horizontal" action="advancesearch.php" method="post">
                <h3>Advance Search</h3>
                  <div class="form-group"><input type="text" name="searchField1" placeholder="desc"  /></div>
              
               
                  <div class="form-group"><input type="text" name="searchField3" placeholder="patentID"/></div><br>
                  <tdiv class="form-gorup"><input type="text" name="searchField4" placeholder="aspect" /></div>
                
                
                
              
                <input type="submit" class="btn btn-success" name="search" value="search" />    
              </form>
            </div>
          </div>
        </div>




<?php
require 'vendor/autoload.php';

if(isset($_POST['search'])) {
  $client = Elasticsearch\ClientBuilder::create()->build();
  $params = [
      'index' => 'index-name',
      'body'  => [
          'query' => [
            'bool' => [
              'should' =>[
                     [ 'match' => ['description' => $_POST['searchField1']]],
                     //[ 'match' => ['pid' => $_POST['searchField2']]],
                     [ 'match' => ['patentID' => $_POST['searchField3']]],

                     [ 'match' => ['aspect' => $_POST['searchField4']]],

                   ]
                ]
           ]]
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



}
  
  echo "<br>";
  

?>
</body>
</html>