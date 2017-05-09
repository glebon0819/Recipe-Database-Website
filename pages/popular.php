<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Popular | VG Recipes</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body class="view">
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container mx-auto"> 
        <h1>Popular Recipes</h1>
            <?php
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database2.php';
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php';
			$pdo = Database1::connect1();
            $sql = "SELECT * FROM popularity ORDER BY view_count DESC LIMIT 35";
			foreach ($pdo->query($sql) as $row) {
				$view_count = $row['view_count'];
                $pdo = Database::connect();
                $sql = "SELECT * FROM recipes WHERE id = '". $row['recipe_id'] ."';";
                $count = 0;
                foreach ($pdo->query($sql) as $row) {
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
                    $badPhotos = str_replace(" ", "", $badPhotos);
                    $badPhotos = str_replace(" ", ",", $badPhotos);
                    $badPhotos = $badPhotos . ",";
                    $badPhotos = str_replace(",,", ",", $badPhotos);
                    $badPhotos = str_replace('http://', '<img src="http://', $badPhotos);
                    $badPhotos = str_replace(',', '.jpg,', $badPhotos);
                    $badPhotos = str_replace('.png.jpg', '.png', $badPhotos);
                    
                    
                    $badPhotos = str_replace(',', '" width="100%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', $badPhotos);
                    $badPhotos = str_replace('/>.jpg" width="100%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', ' />', $badPhotos);
                    $badPhotos = strstr($badPhotos, ",", false);
                    $badPhotos = str_replace(',', '', $badPhotos);
                    
					$diet = $row['mealDiet'];
					$diet = str_replace('Omnivore(normal)', 'None', $diet);
					
					echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
					echo '<div class="col-lg-6" style="padding:20px;">';
                    echo $badPhotos;
                    echo '</div>';
                    
                    echo '<div class="col-lg-6" style="padding:20px;">';
					echo '<h3><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></h3>';
					echo '<p>Viewed '. $view_count .' times</p>';
                    echo '<p>By <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chefNew.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
                    echo '<p><em>'. $row['description'] . '</em></p>';
					echo '<p>Diet Type: <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php?category=mealDiet&search='. $diet .'">'. $diet . '</p>';
                    echo '</div>';
					echo '</div><br>';
					
                }
                
                Database::disconnect();
			}
			Database1::disconnect1();
			
			?>
        
  	</div>
  </body>
</html>