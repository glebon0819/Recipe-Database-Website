<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | VG Recipes</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container">
            <div class="row">
                <h3>Victoria and Gabriel's Recipe Website</h3>
            </div>
            <div class="row">
                <h3>Popular Recipes</h3>
                <h3>Start Browsing</h3>
                  <?php
                   include 'database.php';
                   $pdo = Database::connect();
                   $sql = "SELECT * FROM recipes";
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
							$badPhotos = str_replace(" ", ",", $badPhotos);
							$badPhotos = $badPhotos . ",";
							$badPhotos = str_replace(",,", ",", $badPhotos);
							$badPhotos = str_replace('http://', '<img src="http://', $badPhotos);
							$badPhotos = str_replace(',', '.jpg,', $badPhotos);
							$badPhotos = str_replace('.png.jpg', '.png', $badPhotos);
							$badPhotos = str_replace(',', '" width="50%" onerror="this.style.display=\'none\'" />', $badPhotos);
							$badPhotos = str_replace('/>.jpg" width="50%" onerror="this.style.display=\'none\'" />', ' />', $badPhotos);
							
							echo '<p><a href="pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></p>';
                            echo '<p><a href="pages/chef.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
							echo '<p><em>'. $row['description'] . '</em></p>';
							
							echo '<div>'. $badPhotos . '</div>';
							
                            $count++;
                   }
				   echo 'Results found: ' . $count . '';
                   Database::disconnect();
                  ?>
        </div>
    </div>
  </body>
</html>