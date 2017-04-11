<?php
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width">
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
	<?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container  mx-auto">
            
            
                <br>
                <!--<table class="table table-striped table-bordered">
                  <thead>
                    <tr>-->
                      <!--<th>Recipe Name</th>-->
                      <!--<th>Description</th>-->
                      <!--<th>Main Ingredients</th>-->
                      <!--<th>Prep Time (:60)</th>-->
                      <!--<th>Cook Time (:60)</th>-->
                      <!--<th>Steps</th>-->
                      <!--<th>Difficulty Scale</th>-->
                      <!-- <th>Diet Type</th> -->
                      <!--<th>Pictures</th>
                    </tr>
                  </thead>
                  <tbody>-->
                  <?php
                   include '../database.php';
                   $pdo = Database::connect();
                   $sql = "SELECT * FROM recipes WHERE id = '". $id ."'";
				   $count = 0;
                   foreach ($pdo->query($sql) as $row) {
                            //echo '<tr>';
							
							$badSteps = $row['steps'];
							$badSteps = str_replace("STEP", "<h5>STEP", $badSteps);
							$badSteps = str_replace("|", "</h5><p>", $badSteps); 
							$badSteps = str_replace("^", "</p>", $badSteps); 
							
							$ing = $row['ingredientsMain'];
							$ing = str_replace("pork", "pork<br>", $ing);
							$ing = str_replace("chicken", "chicken<br>", $ing);
							$ing = str_replace("broccoli", "pork<br>", $ing);
							$ing = str_replace("beef", "beef<br>", $ing);
							$ing = str_replace("fish", "fish<br>", $ing);
							$ing = str_replace("pasta", "pasta<br>", $ing);
							$ing = str_replace("rice", "rice<br>", $ing);
							$ing = str_replace(" ", "", $ing);
							$ing = str_replace("|", "", $ing);
							
							//$ingsAll = str_replace(",", "<br>", $row['ingredientsAll']);
							$ingsAll = "<li>" . $row['ingredientsAll'];
							//$ingsAll .= $row['ingredientsAll'];
							$ingsAll = $ingsAll . "</li>";
							$ingsAll = str_replace(",", "</li><li>", $ingsAll);
							$ingsAll = str_replace("<li></li>", "", $ingsAll);
							$ingsAll = str_replace("<li> </li>", "", $ingsAll);
							
							$badPhotos = $row['photos'];
							$badPhotos = str_replace(" ", ",", $badPhotos);
							$badPhotos = $badPhotos . ",";
							$badPhotos = str_replace(",,", ",", $badPhotos);
							$badPhotos = str_replace('http://', '<img src="http://', $badPhotos);
							$badPhotos = str_replace(',', '.jpg" width="50%" />', $badPhotos);
							
							echo "<h1>" . $row['name'] ."</h1>";
							echo '<div id="chef">By <a href="chef.php?chef='. $row['chef'] .'">Chef '. $row['chef'] .'</a></div><br><br>';
							echo ''. $badPhotos .'<br><br>';
							echo '<div id="description"><em>'. $row['description'] .'</em></div><br>';
							echo '
                                    <div class="row-fluid">
                                        <div class="col-lg-6">
                                            <h3 style="text-align:center;">Ingredients:</h3>
                                            <ul>'. $ingsAll .'</ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <h3 style="text-align:center;">Steps:</h3>
                                            <p>'. $badSteps .'</p>
                                        </div>
                                    </div>
                                ';
                            //echo '<td>'. $row['name'] . '</td>';
							//echo '<td><em>'. $row['description'] . '</em></td>';
							//echo '<td>'. $ing . '</td>';
							
							$timePrepArray = unserialize($row['timePrep']);
							$timePrepMin = $timePrepArray[0];
							$timePrepHour = $timePrepArray[1];
							
							//echo '<td>'. $timePrepMin . ' Minutes, '. $timePrepHours . ' Hours</td>';
							
							//$timeCookArray = unserialize($row['timeCook']);
							//$timeCookMin = $timeCookArray[0];
							//$timeCookHour = $timeCookArray[1];
							
							//echo '<td>'. $timeCookMin . ' Minutes, '. $timeCookHours . ' Hours</td>';
							
							//echo '<td>'. $badSteps . '</td>';
							//echo '<td>'. $badPhotos . '</td>';
                            //echo '<td>'. $row['difficulty'] . '</td>';
							//echo '<td>'. $row['mealDiet'] . '</td>';
							
                            //echo '</tr>';
                            $count++;
                   }
	
                   Database::disconnect();
                  ?>
                  <!--</tbody>
            </table>-->
        
    </div>
  </body>
</html>