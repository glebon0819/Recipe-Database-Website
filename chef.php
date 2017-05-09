<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <title>Top Chech</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container">
            <div class="row">
                <h2>Recipes by: <?php echo $_GET['chef'] ?></h2>
            </div>
            <div class="row">
            	<p>
                	<a href="javascript:history.back()" class="btn btn-success">Go Back</a>
                  
                
                
              <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      
                      <th>Chef</th>
                      <th>Recipe Name</th>
                      <th>Description</th>
                      <!--<th>Main Ingredients</th>-->
                      <!--<th>Prep Time (:60)</th>
                      <th>Cook Time (:60)</th>-->
                      <!--<th>Steps</th>-->
                      <th>Photos</th>
                      <!--<th>Difficulty Scale</th>-->
                      <!--<th>Diet Type</th>-->
                      
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                   include 'database.php';
                   $pdo = Database::connect();
                   $sql = "SELECT * FROM recipes WHERE chef = '". $_GET['chef'] ."'";
				   $count = 0;
                   foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
							
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
							
							$badPhotos = $row['photos'];
							$badPhotos = str_replace(" ", ",", $badPhotos);
							$badPhotos = $badPhotos . ",";
							$badPhotos = str_replace(",,", ",", $badPhotos);
							$badPhotos = str_replace('http://', '<img src="http://', $badPhotos);
							$badPhotos = str_replace(',', '.jpg,', $badPhotos);
							$badPhotos = str_replace('.png.jpg', '.png', $badPhotos);
							$badPhotos = str_replace(',', '" width="100%" onerror="this.style.display=\'none\'" />', $badPhotos);
							$badPhotos = str_replace('/>.jpg" width="100%" onerror="this.style.display=\'none\'" />', ' />', $badPhotos);
							
							//echo '<td><a href="recipe.php?id='. $row['id'] .'">'. $row['id'] . '</a></td>';
                            echo '<td>'. $row['chef'] . '</td>';
                            echo '<td><a href="recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></td>';
							echo '<td><em>'. $row['description'] . '</em></td>';
							//echo '<td>'. $ing . '</td>';
							
							// $timePrepArray = unserialize($row['timePrep']);
							//$timePrepMin = $timePrepArray[0];
							//$timePrepHour = $timePrepArray[1];
							
							//echo '<td>'. $timePrepMin . ' Minutes, '. $timePrepHours . ' Hours</td>';
							
							//$timeCookArray = unserialize($row['timeCook']);
							//$timeCookMin = $timeCookArray[0];
							//$timeCookHour = $timeCookArray[1];
							
							//echo '<td>'. $timeCookMin . ' Minutes, '. $timeCookHours . ' Hours</td>';
							//echo '<td>'. $badSteps . '</td>';
							echo '<td>'. $badPhotos . '</td>';
                            //echo '<td>'. $row['difficulty'] . '</td>';
							//echo '<td>'. $row['mealDiet'] . '</td>';
							
                            echo '</tr>';$count++;
                   }
				   echo 'Results found: ' . $count . '';
                   Database::disconnect();
                  ?>
                  </tbody>
            </table>
        </div>
    </div>
  </body>
</html>