<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if (isset($_GET['searchTerm'])) { echo '"'. $_GET['searchTerm'] .'" '; } ?>Search | VG Recipes</title>
    <?php 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/ratings.html';
	?>
</head>
 
<body class="view">
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container">
    	<?php if(isset($_GET['searchTerm'])) { echo '<h1>Search: "'. $_GET['searchTerm'] .'"</h1>'; } else { echo '<h1>Search</h1>'; } ?>
        <form action="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/searchNew.php?" method="get">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="searchTerm">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit">
                <i class="glyphicon glyphicon-search"></i>
              </button>
            </div>
          </div>
        </form>
        <br>
        
        <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php"><button width="100%" type="button" class="btn btn-link btn-block" id="advancedButton">Go To Advanced Search</button></a>
        <br>
        
        <?php
		
		if (strlen($_GET['searchTerm']) > 0)
		{
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php';
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database2.php';
			
			// ********************************************* S T A R T   #   R E S U L T S *********************************************
			
			$pdo = Database::connect();
			
			$chef_test = array(); // this array will be used to check if there are chef results, stores ID #'s of recipes
			$users = array(); // this array will store the names of chefs from the chef results
			$results = 0;
			
			//counts up the number of chef results
			$sql = "SELECT * FROM `recipes` WHERE chef LIKE '%" . $_GET['searchTerm'] . "%';";
			foreach ($pdo->query($sql) as $row) {
				$chef_test[] = $row['id']; // adds the current ID to the array of ID's
				$users[] = $row['chef']; // adds the current chef name to the array of chef names
			}
			
			//counts up the number of recipe results
			$sql = "SELECT * FROM `recipes` WHERE CONCAT_WS('|',`name`,`description`,`ingredientsAll`,`ingredientsMain`) LIKE '%" . $_GET['searchTerm'] . "%';";
			foreach ($pdo->query($sql) as $row) {
				$results++;
			}
			
			$users_min = array_unique($users); // eliminates repeats in the array of users
			$results += count($users_min); // counts up the number of unique users
			echo '<p>'. $results .' Results Found</p>';
			
			// ********************************************* E N D   #   R E S U L T S *********************************************
			
			if (!empty($chef_test)) {
				echo '<h2>Chefs:</h2>';
			}
			
			// ********************************************* S T A R T   C H E F   R E S U L T S *********************************************
			
			$user_count = array_count_values($users); // returns an array using the values of array as keys and their frequency in array as values -php.net
			//print_r(array_values($new));
			//if (!empty($test)) {
			foreach ($users_min as $user) {
				
				$view_array = array();
				$recipeIDArray = array();
				$view_array = reset($view_array);
				$recipeIDArray = reset($recipeIDArray);
				$num2 = 0;
				$num_of_rated_recipes = 0; //records the # of recipes that have been rated
				
				
				// ****************************** S T A R T   T O P   T H R E E   R E C I P E S ******************************
				
				
				$sql = "SELECT * FROM `recipes` WHERE chef = '" . $user . "';";
				foreach ($pdo->query($sql) as $row) {
					$pdo1 = Database1::connect1();
					$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
					foreach ($pdo1->query($sql1) as $row1) {
						$view_array[] = $row1['view_count']; //adds current view_count to the array of view_counts
						$recipeIDArray[] = $row['id']; // adds current ID to the array of ID's from the Recipe Data DB
						
						// checks to see if the chef's recipe has been rated
						if (strlen($row1['rating_array']) > 0) {
							$num2Array = unserialize($row1['rating_array']);
							$num2 += (array_sum($num2Array)/count($num2Array)); //
							$num_of_rated_recipes++; // adds this recipe to the count of recipes that have been rated
						}
					}
					Database1::disconnect1();
				}
				
				$combo = array_combine($recipeIDArray, $view_array); // combines the recipe ID array with the view_count array in order to order them by view count
				arsort($combo); // rearranges the array view count
				
				$recipeIDArrayNew = array_keys($combo); //creates an array containing only the recipe ID's (in order of view count)
				
				array_splice($combo, 3);
				array_splice($recipeIDArrayNew, 3);
				
				// retrieves the names of the top 3 recipes, since we only have their ID's at the moment
				$recipe_names = array();
				foreach ($recipeIDArrayNew as $recipeIDs) {
					$sql = "SELECT * FROM `recipes` WHERE id = '" . $recipeIDs . "';";
					foreach ($pdo->query($sql) as $row) {
						$recipe_names[] = $row['name'];
					}
				}
				
				// ****************************** E N D   T O P   T H R E E   R E C I P E S ****************************** 
				
				if ($num_of_rated_recipes > 0) {
					$avg_chef_rating_rounded = round(($num2 / $num_of_rated_recipes), 1); // rounds the avg chef rating to one decimal place
					$message = '<p>Average recipe rating: '. $avg_chef_rating_rounded .' / 5 stars</p>';
				}
				else {
					$avg_chef_rating_rounded = 0;
					$message = "<p>This chef's recipes have not yet been rated.</p>";
				}
	
				echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
				echo '<div class="col-lg-4" style="padding:10px;">';
				echo '<img src="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/yee.jpg" width="100%" />';
				echo '</div>';
				echo '<div class="col-lg-8" style="padding:10px;">';
				echo '<h2><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chefNew.php?chef='. $user .'">'. $user .'</a></h2>';
				echo '<p>'. $user_count[$user] .' recipes made so far</p>';
				
				echo $message;
	echo '<input id="overall_rating" name="overall_rating" value="'. $avg_chef_rating_rounded .'" step=".1" class="rating rating-loading" data-show-caption="false" data-show-clear="false" data-display-only="true" data-size="sm">';
				
				echo '<p><u>Most Popular Recipes:</u></p>';
				echo '<ol>';
				
				// echoes chef's top 3 recipes
				if (isset($recipe_names[0])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $recipeIDArrayNew[0] .'">'. $recipe_names[0] .'</a> - '. $combo[0] .' views</p></li>'; }
				if (isset($recipe_names[1])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $recipeIDArrayNew[1] .'">'. $recipe_names[1] .'</a> - '. $combo[1] .' views</p></li>'; }
				if (isset($recipe_names[2])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $recipeIDArrayNew[2] .'">'. $recipe_names[2] .'</a> - '. $combo[2] .' views</p></li>'; }
				echo '</ol>';
				echo '</div>';
				echo '</div><br>';
			}
			//}
			Database::disconnect();
			
			// ********************************************* E N D   C H E F   R E S U L T S *********************************************
			
			$idArray = array();
			$pointsArray = array();
			$num = 1;
			
			$pdo = Database::connect();
			$sql = "SELECT * FROM `recipes` WHERE CONCAT_WS('|',`name`,`description`,`ingredientsAll`,`ingredientsMain`) LIKE '%" . $_GET['searchTerm'] . "%';";
			foreach ($pdo->query($sql) as $row) {
				$views = 0;
				$rate = 0;
				$pdo1 = Database1::connect1();
				$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
				foreach ($pdo1->query($sql1) as $row1) {
					$views = $row1['view_count']; // retrieves views of current recipe from Recipe Data DB
					$id = $row1['recipe_id'];
					//$ratings = $row1['rating_array']; // retrieves ratings array of current recipe from Recipe Data DB
					if (strlen($row1['rating_array']) > 0) {
						$ratings = unserialize($row1['rating_array']);
						$rate = round((array_sum($ratings)/count($ratings)), 1); //
						//$num_of_rated_recipes++; // adds this recipe to the count of recipes that have been rated
					}
				}
				
				// finds the number of views of the most-viewed recipe
				$sql2 = "SELECT * FROM popularity ORDER BY view_count DESC LIMIT 1";
				foreach ($pdo1->query($sql2) as $row2) {
					$view_max = $row2['view_count'];
				}
				Database1::disconnect1();
				
				//********************************************* S T A R T   P O I N T   C A L C U L A T I O N *********************************************
				
				$idArray[] = $row['id'];
				$points = 0;
				$term = strtolower($_GET['searchTerm']);
				$numyee = 0;
				
				
				
				/*
				if (strlen($ratings) > 0) {
					$numyeeArray = unserialize($ratings);
					$numyee = (array_sum($numyeeArray)/count($numyeeArray));
					$points += $numyee;
					$tacoMom = "yee";
				}
				*/
				
				//For every occurence of the search term in the description, 1 point is awarded
				$desc = strtolower($row['description']);
				if (substr_count($desc, $term) > 0) {
					$points += substr_count($desc, $term);
					if ($points > 3) {
						$points += 3;
					}
				}
				
				if ($rate > 0) {
					$points += $rate;
				}
				
				$name = strtolower($row['name']);
				if (substr_count($name, $term) > 0) {
					//For every occurence of the search term in the title, 2 points are awarded
					$points += 3;
				}
				
				$ingredientsAll = strtolower($row['ingredientsAll']);
				if (substr_count($ingredientsAll, $term) > 0) {
					//For every occurence of the search term in the ingredients, 1 point is awarded 
					$points += 3;
				}
				
				$base = round(($view_max / 3), 1);
				if ($views > 0 && $views <= $base) {
					$points += 1;
				}
				elseif ($views > $base && $views <= ($base * 2)) {
					$points += 2;
				}
				elseif ($views > ($base * 2) && $views <= $view_max) {
					$points += 3;
				}
				
				$num++;
				$pointsArray[] = $points;
			}
			
			//********************************************* E N D   P O I N T   C A L C U L A T I O N *********************************************
		
			//*************** combines recipe ID and point arrays, sorts them in order of point value, and effectively separates them again ***************
			
			$arr3 = array_combine($idArray, $pointsArray);
			arsort($arr3);
			
			$idArrayNew = array_keys($arr3);
			$pointsArrayNew = array_values($arr3);
			
			$testNum = 0;
			
			//*************** displays the recipes in order of point value ***************
			
			if (count($idArray) > 0) {
			   echo '<h2>Recipes:</h2>';
			}
			$i = 0;
			
			foreach ($idArrayNew as $id) {
				/*if ($i > 10) {
					break;
				}*/
				$i++;
				$rate = 0;
				$pdo = Database::connect();
				//$sql = "SELECT * FROM recipes WHERE id = '". array_search($value, $arr3) ."';";
				$sql = "SELECT * FROM recipes WHERE id = '$id';";
				foreach ($pdo->query($sql) as $row) {
					
					$pdo1 = Database1::connect1();
					$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
					foreach ($pdo1->query($sql1) as $row1) {
						if (strlen($row1['rating_array']) > 0) {
							$ratings = unserialize($row1['rating_array']);
							$rate = round((array_sum($ratings)/count($ratings)), 1); //
							$mess = 'Average rating: '. $rate .' / 5 stars';
						}
						else {
							$mess = 'This recipe has not yet been rated';
						}
					}
					
					$badSteps = $row['steps'];
					$badSteps = str_replace("STEP", "<h5>STEP", $badSteps);
					$badSteps = str_replace("|", "</h5><p>", $badSteps); 
					$badSteps = str_replace("^", "</p>", $badSteps); 
					
					$ing = $row['ingredientsMain'];
					$ing = str_replace("pork", "pork<br />", $ing);
					$ing = str_replace("chicken", "chicken<br />", $ing);
					$ing = str_replace("broccoli", "pork<br />", $ing);
					$ing = str_replace("beef", "beef<br />", $ing);
					$ing = str_replace("fish", "fish<br />", $ing);
					$ing = str_replace("pasta", "pasta<br />", $ing);
					$ing = str_replace("rice", "rice<br />", $ing);
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
					//$diet = str_replace('Omnivore(normal)', 'None', $diet);
					
					
					
					echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
					echo '<div class="col-lg-6" style="padding:10px;">';
					echo $badPhotos;
					echo '</div>';
					
					echo '<div class="col-lg-6" style="padding:10px;">';
					echo '<h3><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></h3>';
					echo '<p>By <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chefNew.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
					echo '<p><em>'. $row['description'] . '</em></p>';
					echo '<p>Diet Type: <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php?category=mealDiet&search='. $diet .'">'. $diet . '</a></p>';
					//echo '<p>'. $id .'</p>';
					echo '<p>Points: '. $pointsArrayNew[$testNum] .'</p>';
					//echo '<p>Base:'. $base .'</p>';
					//echo '<p>View Max: '. $view_max .'</p>';
					echo '<p>'. $mess .'</p>';
					echo '<input id="overall_rating" name="overall_rating" value="'. $rate .'" step=".1" class="rating rating-loading" data-show-caption="false" data-show-clear="false" data-display-only="true" data-size="sm">';
					echo '</div>';
					echo '</div><br>';
					$testNum++;
				}
				Database::disconnect();
			}
		}
		?>
        <?php
		/*$arr = array("799", "831", "1045");
		$arr2 = array("14.5", "10.7", "12.0");
		$arr3 = array_combine($arr, $arr2);
		arsort($arr3);
		
		foreach ($arr3 as $value) {
			echo "<div>Key: " . array_search($value, $arr3) . "</div>";
			echo "<div>Points: $value</div>";
			echo "<div>SELECT * FROM recipes WHERE id = $value</div>";
		}
		*/
		?>
    </div>
</body>
</html>