<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_GET['chef']; ?>'s Recipes | VG Recipes</title>
    <?php 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html';
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/ratings.html';
	
	?>
</head>
 
<body class="view">
    <?php 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php'; 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database2.php';
	?>
    
    <div class="container mx-auto">
    
    <h1>Chef <?php echo $_GET['chef']; ?></h1>
    
    <aside>
        <h3>Metrics</h3>
        <h5>How Does <?php echo $_GET['chef']; ?> Stack Up?</h5>
        
        <?php
		// Metrics Data Retrieval and Display
		
		$num = 0;
		
		$num3 = 0;
		$num2 = 0;
		$recipes = 0;
		$recipes2 = 0;
	
		$pdo = Database::connect();
		$sql = "SELECT * FROM recipes WHERE chef = '". $_GET['chef'] ."';";
		foreach ($pdo->query($sql) as $row) {
			$pdo1 = Database1::connect1();
			$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
			foreach ($pdo1->query($sql1) as $row1) {
				$recipes++;
				$num3 += $row1['view_count'];
				if (strlen($row1['rating_array']) > 0) {
					$num2Array = unserialize($row1['rating_array']);
					$num2 += (array_sum($num2Array)/count($num2Array));
					$recipes2++;
				}
			}
			Database1::disconnect1();
		}
		Database::disconnect();
		
		if ($recipes2 > 0) {
			$yee = round(($num2 / $recipes2), 1);
			$message = '<p>Average recipe rating: '. $yee .' / 5 stars</p>';
		}
		else {
			$yee = 0;
			$message = "<p>This chef's recipes have not yet been rated.</p>";
		}
		
		$pdo = Database::connect();
		$sql = "SELECT * FROM recipes WHERE chef = '". $_GET['chef'] ."';";
		foreach ($pdo->query($sql) as $row) {
			$num++;
		}
		Database::disconnect();
		
		echo '<p>'. $num .' recipes made so far</p>';
		
		echo $message;
		echo '<input id="overall_rating" name="overall_rating" value="'. $yee .'" step=".1" class="rating rating-loading" data-show-caption="false" data-show-clear="false" data-display-only="true" data-size="sm">';
		echo '<p>Total recipe views: '. $num3 .'</p>';
		
		?>
    </aside>
    
    <section id="content"> 
        
        
        <h3><?php echo $_GET['chef']; ?>'s Recipes</h3>
            <?php
			
			// Recipe List Retrieval and Display
			
			$recipes_shown = 0;
			
			$view_count = $row['view_count'];
			$pdo = Database::connect();
			if ($_GET['showAll'] == true) {
				$sql = "SELECT * FROM recipes WHERE chef = '". $_GET['chef'] ."';";
			}
			else {
				$sql = "SELECT * FROM recipes WHERE chef = '". $_GET['chef'] ."' LIMIT 5;";
			}
			$count = 0;
			foreach ($pdo->query($sql) as $row) {
				$views = 0;
				$pdo1 = Database1::connect1();
				$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."';";
				foreach ($pdo1->query($sql1) as $row1) {
					$views = $row1['view_count'];
				}
				Database1::disconnect1();
				
				$recipes_shown++;
				
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
					if (substr_count($badPhotos, ",") > 0) {
						$badPhotosEx = explode(",", $badPhotos);
						$badPhotos = $badPhotosEx[0];
					}
					$badPhotos = str_replace(" ", "", $badPhotos);
					$badPhotos = str_replace(" ", ",", $badPhotos);
					$badPhotos = $badPhotos . ",";
					$badPhotos = str_replace(",,", ",", $badPhotos);
					$badPhotos = str_replace("i.imgur", "http://i.imgur", $badPhotos);
					$badPhotos = str_replace("images.media", "http://images.media", $badPhotos);
					$badPhotos = str_replace("http://http://", "http://", $badPhotos);
					$badPhotos = str_replace("https://http://", "http://", $badPhotos);
					$badPhotos = str_replace('http://', '<img src="http://', $badPhotos);
					$badPhotos = str_replace('https://', '<img src="https://', $badPhotos);
					$badPhotos = str_replace(',', '.jpg,', $badPhotos);
					$badPhotos = str_replace('.png.jpg', '.png', $badPhotos);
					
					
					$badPhotos = str_replace(',', '" width="100%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', $badPhotos);
					$badPhotos = str_replace('/>.jpg" width="100%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', ' />', $badPhotos);
					//$badPhotos = strstr($badPhotos, ",", false);
					$badPhotos = str_replace(',', '', $badPhotos);
				
				$diet = $row['mealDiet'];
				$diet = str_replace('Omnivore(normal)', 'None', $diet);
				
				echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
				echo '<div class="col-lg-6" style="padding:20px;">';
				echo $badPhotos;
				echo '</div>';
				
				echo '<div class="col-lg-6" style="padding:20px;">';
				echo '<h3><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></h3>';
				echo '<p>Viewed '. $views .' times</p>';
				echo '<p><em>'. $row['description'] . '</em></p>';
				echo '<p>Diet Type: '. $diet . '</p>';
				echo '</div>';
				echo '</div><br>';
				
			}
			
			Database::disconnect();
			
			
			?>
            
            <?php 
		
			if ($recipes > $recipes_shown) { 
				echo '<a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chefNew.php?chef='. $_GET['chef'] .'&showAll=true"><button width="100%" type="button" class="btn btn-lg btn-info btn-block" id="advancedButton">See All Recipes By '. $_GET['chef'] .'</button></a><br>'; 
			} 
			
			?>
            
    </section>
    
  	</div>
  </body>
</html>