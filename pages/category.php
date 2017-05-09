<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_GET['search']; ?> Recipes | VG Recipes</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container mx-auto"> 
        <h1>Category/Advanced Search</h1>
        <form action="category.php" method="get">
        	<h3>Find recipes:</h3>
            <p>Category</p>
            <div>
            <select id="category_select" name="category">
            	<option disabled="disabled" selected="selected">Select</option>
                <option value="ingredientsMain">Main Ingredients</option>
                <option value="ingredientsAll">All Ingredients</option>
            </select>
            </div>
            
            <div id="search_select"></div>
            <br />
            <input type="submit" value="search" />
        </form>
        <br />
            <?php
			
			include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php';
			
			$view_count = $row['view_count'];
			/*
			if(function_exists('curl_version')) {
				echo '<p>true</p>';
			}
			else {
				echo '<p>false</p>';
			}
			*/
			$pdo = Database::connect();
			if (isset($_GET['category'])) {
				$sql = "SELECT * FROM recipes WHERE ". $_GET['category'] ." LIKE '%". $_GET['search'] ."%';";
			}
			else {
				$sql = "SELECT * FROM recipes";	
			}
				
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
				echo '<p>Viewed '. $view_count .' times</p>';
				echo '<p>By <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chefNew.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
				echo '<p><em>'. $row['description'] . '</em></p>';
				echo '<p>Diet Type: '. $diet . '</p>';
				echo '</div>';
				echo '</div><br>';
				
			}
			
			Database::disconnect();
			
			
			?>
        
  	</div>
  </body>
</html>
<script>


$("select#category_select").on("change", function () {
    var category = $("#category_select").val();
	
    var main_search_select = '<br /><p>Which Main Ingredient?</p><select id="ing_main_select" name="search"><option value="fish">Fish</option><option value="beef">Beef</option></select>';
	var all_search_select = '<br /><p>Which Ingredient?</p><input type="text" name="search" />';
	
    var html = '';
    $("#search_select").html('');
    if (category == "ingredientsMain") {
		html += main_search_select
	}
	else if (category == "ingredientsAll") {
		html += all_search_select;
	}
	$("#search_select").html(html);
});
</script>