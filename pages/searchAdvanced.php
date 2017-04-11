<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if (isset($_GET['searchTerm'])) { echo '"'. $_GET['searchTerm'] .'" '; } ?>Search | VG Recipes</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container">
    	<?php if(isset($_GET['searchTerm'])) { echo '<h1>Advanced Search: "'. $_GET['searchTerm'] .'"</h1>'; } else { echo '<h1>Advanced Search</h1>'; } ?>
        
        <form action="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/searchAdvanced.php?advanced=true&" method="get">
            <div class="input-group">
                <label for="searchTerm">Search Term:</label>
                <input type="text" class="form-control" placeholder="Search" id="searchTerm" name="searchTerm">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                    	<i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div> 
            <div>Search In:</div>
            <div class="checkbox">
              <label><input type="checkbox" name="chefName" id="chefName"> Chef Names </label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="recipeName" id="recipeName"> Recipe Names </label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="recipeDesc" id="recipeDesc"> Recipe Descriptions </label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="ingred" id="ingred"> Ingredients </label>
            </div>
            <br>
            <div class="input-group">
                <label for="dietType">Diet Type:</label>
                <select class="form-control" id="dietType" name="dietType">
                    <option selected disabled>Select</option>
                    <option value="none">Omnivore(normal)</option>
                    <option>Carnivore</option>
                    <option>Vegitarian</option>
                    <option>Vegan</option>
                </select>
            </div>
            <br>
            <div class="input-group">
            	<label for="time">Time</label>
                <input type="number" class="form-control" name="time" id="time" min="0" maxlength="6" placeholder="No more than...">
                
            </div>
            <br>
            <div class="input-group">
            	<button class="btn btn-default" type="submit">Search</button>
            </div>
            <input type="hidden" name="advanced" id="advanced" value="true">
        </form> 
        <br>
		
        
        <?php
		
		if (isset($_GET['searchTerm']))
		{
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php';
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database2.php';
			
			// somewhere up here we want a '____ results found' message
			
			$pdo = Database::connect();
			$sql = "SELECT * FROM `recipes` WHERE chef LIKE '%" . $_GET['searchTerm'] . "%';";
			$test = array();
			$users = array();
			
			$results = 0;
			foreach ($pdo->query($sql) as $row) {
				$test[] = $row['id'];
				$users[] = $row['chef'];
				
				
			}
			
			$sql = "SELECT * FROM `recipes` WHERE CONCAT_WS('|',`name`,`description`,`ingredientsAll`) LIKE '%" . $_GET['searchTerm'] . "%';";
			foreach ($pdo->query($sql) as $row) {
				
				
				
				$results++;
			}
			
			$users_min = array_unique($users);
			$results += count($users_min);
			echo '<p>'. $results .' Results Found</p>';
			
			if ((!empty($test) && $_GET[advanced] == false) || (!empty($test) && $_GET[advanced] == true && $_GET[chefName] == 'on')) {
				echo '<h3>Chefs:</h3>';
				/*foreach ($pdo->query($sql) as $row) {
					$users[] = $row['chef'];
				}*/
			}
			$user_count = array_count_values($users);
			//print_r(array_values($new));
			//if (!empty($test)) {
				foreach ($users_min as $user) {
					/*$sql = "SELECT * FROM `recipes` WHERE chef LIKE '%" . $newer . "%';";
					foreach ($pdo->query($sql) as $row) {
						echo '<p>'. $row['chef'] .'</p>';
					}*/
					
					$array = array();
					$recipeID = array();
					$array = reset($array);
					$recipeID = reset($recipeID);
					// here, we are trying to create an array of the chef's top three most popular recipes
					$sql = "SELECT * FROM `recipes` WHERE chef = '" . $user . "';";
					foreach ($pdo->query($sql) as $row) {
						$pdo1 = Database1::connect1();
						$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
						foreach ($pdo1->query($sql1) as $row1) {
							$array[] = $row1['view_count'];
							$recipeID[] = $row['id'];
							/*$fun = array_combine($recipeID, $array);
							arsort($fun);
							array_splice($fun, 3);*/
						}
						Database1::disconnect1();
					}
					
					$fun = array_combine($recipeID, $array);
					arsort($fun);
					
					$array2 = array_keys($fun);
					
					array_splice($fun, 3);
					array_splice($array2, 3);
					
					$recipe_names = array();
					foreach ($array2 as $recipeIDs) {
						$sql = "SELECT * FROM `recipes` WHERE id = '" . $recipeIDs . "';";
						foreach ($pdo->query($sql) as $row) {
							$recipe_names[] = $row['name'];
						}
					}
					if (($_GET[advanced] == true && $_GET[chefName] == 'on') || $_GET[advanced] == false) {
						echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
						echo '<div class="col-lg-4" style="padding:10px;">';
						echo '<img src="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/yee.jpg" width="100%" />';
						echo '</div>';
						echo '<div class="col-lg-8" style="padding:10px;">';
						echo '<h2><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chef.php?chef='. $user .'">'. $user .'</a></h2>';
						echo '<p>'. $user_count[$user] .' recipes made so far</p>';
						echo '<p><u>Most Popular Recipes:</u></p>';
						/*echo '<p>'. print_r(array_values($fun)) .'</p>';
						echo '<p>'. print_r(array_keys($fun)) .'</p>';
						echo '<p>'. print_r(array_values($array2)) .'</p>';*/
						echo '<ol>';
						if (isset($recipe_names[0])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $array2[0] .'">'. $recipe_names[0] .'</a> - '. $fun[0] .' views</p></li>'; }
						if (isset($recipe_names[1])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $array2[1] .'">'. $recipe_names[1] .'</a> - '. $fun[1] .' views</p></li>'; }
						if (isset($recipe_names[2])) { echo '<li><p><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $array2[2] .'">'. $recipe_names[2] .'</a> - '. $fun[2] .' views</p></li>'; }
						echo '</ol>';
						echo '</div>';
						echo '</div><br>';
					}
				}
			//}
			Database::disconnect();
			
			$idArray = array();
			$pointsArray = array();
			$num = 1;
			
			$pdo = Database::connect();
			$sql = "SELECT * FROM `recipes` WHERE CONCAT_WS('|',`name`,`description`,`ingredientsAll`) LIKE '%" . $_GET['searchTerm'] . "%';";
			foreach ($pdo->query($sql) as $row) {
				
				$pdo1 = Database1::connect1();
				$sql1 = "SELECT * FROM popularity WHERE recipe_id = '". $row['id'] ."'";
				foreach ($pdo1->query($sql1) as $row1) {
					$view_count = $row1['view_count'];
				}
				Database1::disconnect1();
				
				$idArray[] = $row['id'];
				$points = 0;
				$term = strtolower($_GET['searchTerm']);
				
				$desc = strtolower($row['description']);
				if (substr_count($desc, $term) > 0) {
					//For every occurence of the search term in the description, 1 point is awarded
					$points = substr_count($desc, $term);
					if ($points > 3) {
						$points = 3;
					}
				}
				
				$name = strtolower($row['name']);
				if (substr_count($name, $term) > 0) {
					//For every occurence of the search term in the title, 2 points are awarded
					$points2 = (substr_count($name, $term) * 2); 
					$points += $points2;
				}
				
				$ingredientsAll = strtolower($row['ingredientsAll']);
				if (substr_count($ingredientsAll, $term) > 0) {
					//For every occurence of the search term in the ingredients, 1 point is awarded 
					$points += (substr_count($ingredientsAll, $term) * 3);
				}
				
				if ($view_count > 0) {
					//For every view the recipe has, .25 points is awarded
					$points += ($view_count / 4);
				}
				
				$num++;
				$pointsArray[] = $points;
			}
		
			//*************** combines recipe ID and point arrays, sorts them in order of point value, and effectively separates them again ***************
			
			$arr3 = array_combine($idArray, $pointsArray);
			arsort($arr3);
			
			$idArrayNew = array_keys($arr3);
			$pointsArrayNew = array_values($arr3);
			
			$testNum = 0;
			
			//*************** displays the recipes in order of point value ***************
			
			if (count($idArray) > 0) {
			   echo '<h3>Recipes:</h3>';
			}
			$i = 0;
			foreach ($idArrayNew as $id) {
				if ($i > 10) {
					break;
				}
				$i++;
				$pdo = Database::connect();
				//$sql = "SELECT * FROM recipes WHERE id = '". array_search($value, $arr3) ."';";
				$sql = "SELECT * FROM recipes WHERE id = '$id';";
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
					//$diet = str_replace('Omnivore(normal)', 'None', $diet);
					
					
					
					echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
					echo '<div class="col-lg-6" style="padding:10px;">';
					echo $badPhotos;
					echo '</div>';
					
					echo '<div class="col-lg-6" style="padding:10px;">';
					echo '<h3><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></h3>';
					echo '<p>By <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chef.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
					echo '<p><em>'. $row['description'] . '</em></p>';
					echo '<p>Diet Type: <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php?category=mealDiet&search='. $diet .'">'. $diet . '</a></p>';
					echo '<p>'. $id .'</p>';
					echo '<p>Points: '. $pointsArrayNew[$testNum] .'</p>';
					//echo '<p>'. $test .'</p>';
					//echo '<p>'. $test2 .'</p>';
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
<script>
function change() // no ';' here
{
    var elem = document.getElementById("advancedButton");
    if (elem.innerHTML == "Show Advanced Search") elem.innerHTML = "Hide Advanced Search";
    else elem.innerHTML = "Show Advanced Search";
}
</script>