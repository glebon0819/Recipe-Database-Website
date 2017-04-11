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
    	<?php if(isset($_GET['searchTerm'])) { echo '<h1>Search: "'. $_GET['searchTerm'] .'"</h1>'; } else { echo '<h1>Search</h1>'; } ?>
        <form action="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/search.php?" method="get">
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
        <button width="100%" type="button" class="btn btn-default btn-lg btn-block" data-toggle="collapse" data-target="#demo" onClick="change()" id="advancedButton">Show Advanced Search</button>
        <form action="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/search.php?advanced=true&" method="get" id="demo" class="collapse">
            <h2>Advanced Search</h2>
            <div class="input-group">
                <label for="searchTerm">Search Term:</label>
                <input type="text" class="form-control" placeholder="Search" id="searchTerm" name="searchTerm">
                <!--<div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                    	<i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>-->
            </div>
            <!--<div>Search In:</div>-->
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
        $pdo = Database::connect();
		//$sql = "SELECT * FROM recipes WHERE name LIKE '%". $_GET['searchTerm'] ."%';";
		$sql = "SELECT * FROM `recipes` WHERE CONCAT_WS('|',`name`,`description`,`ingredientsAll`,`chef`) LIKE '%" . $_GET['searchTerm'] . "%';";
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
			echo '<p>Diet Type: <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php?category=mealDiet&search='. $diet .'">'. $diet . '</p>';
			echo '</div>';
			echo '</div><br>';
		}
		/*
		echo '<h2>Test</h2>';
		$sql = "SELECT * FROM recipes WHERE 'name' NOT IN (SELECT * FROM recipes WHERE name LIKE '%". $_GET['searchTerm'] ."%');";
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
			
			echo '<div class="row-fluid clearfix" style="background-color:#f2f2f2;">';
			echo '<div class="col-lg-6" style="padding:10px;">';
			echo $badPhotos;
			echo '</div>';
			
			echo '<div class="col-lg-6" style="padding:10px;">';
			echo '<h3><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $row['id'] .'">'. $row['name'] . '</a></h3>';
			echo '<p>By <a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/chef.php?chef='. $row['chef'] .'">'. $row['chef'] . '</a></p>';
			echo '<p><em>'. $row['description'] . '</em></p>';
			echo '<p>'. $row['mealDiet'] . '</p>';
			echo '</div>';
			echo '</div><br>';
		}
		*/
		
		Database::disconnect();
		}
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