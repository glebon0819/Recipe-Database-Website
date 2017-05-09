<?php
/*if (isset($_POST['personal_rating'])) {
	setcookie('rating', $_POST['personal_rating']);
}*/

include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database2.php';

$id = $_GET['id'];
$rating = $_POST['personal_rating'];
//$rating = 5;

if (isset($rating)) {
	$dollar = "shave";
	$pdo = Database1::connect1();
	$sql = "SELECT * FROM popularity WHERE recipe_id = '". $id ."'";
	foreach ($pdo->query($sql) as $row) {
		$DBratings = $row['rating_array'];
		$id2 = $row['recipe_id'];
	}
	$ratingsArray = array();
	if (isset($DBratings)) {
		$ratingsArray = unserialize($DBratings);
		$ratingsArray[] = $rating;
		$final = serialize($ratingsArray);
		$sql = "UPDATE popularity SET rating_array = '". $final ."' WHERE recipe_id = '". $id ."';";
		$q = $pdo->prepare($sql);
		$q->execute();
		$dollar = 'HORY SHIT';
	} elseif (isset($id2) && !isset($DBratings)) {
		$ratingsArray[] = $rating;
		$final = serialize($ratingsArray);
		$sql = "UPDATE popularity SET rating_array = '". $final ."' WHERE recipe_id = '". $id ."';";
		$q = $pdo->prepare($sql);
		$q->execute();
		$dollar = 'woah, hey guys';
	} else {
		$ratingsArray[] = $rating;
		$final = serialize($ratingsArray);
		$sql = "INSERT INTO popularity (recipe_id, view_count, rating_array) VALUES (?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($id, 1, $ratingsArray));
		$dollar = 'please, god, no';
	}
	Database1::disconnect1();
	header('Location: http://www.foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/recipe.php?id='. $_GET['id'] .'');
}

$pdo = Database1::connect1();
$sql = "SELECT * FROM popularity WHERE recipe_id = '". $id ."'";
foreach ($pdo->query($sql) as $row) {
	$long = $row['view_count'];
}
if (isset($long)) {
	//updates the view_count by adding one to the existing number
	$long++;
	$sql = "UPDATE popularity SET view_count = view_count + 1 WHERE recipe_id = '". $id ."';";
	$q = $pdo->prepare($sql);
    $q->execute();
} else {
	//inserts a new row into the popularity table and sets the initial view_count to 1
	$sql = "INSERT INTO popularity (recipe_id, view_count) VALUES (?, ?)";
	$long++;
	$q = $pdo->prepare($sql);
    $q->execute(array($id, 1));
}
Database1::disconnect1();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width">
    <?php
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/database.php';
    $pdo = Database::connect();
       $sql = "SELECT * FROM recipes WHERE id = '". $id ."'";
       $count = 0;
       foreach ($pdo->query($sql) as $row) {
		   $title = $row['name'];
	   }
	?>
    <title><?php echo $title; ?> | VG Recipes</title>
    <?php 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/ratings.html';
	?>
</head>
 
<body class="view1">
	<?php 
	include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; 
	?>
    
    <div class="container  mx-auto">
    <?php
	echo $dollar;
	echo $rating;
	$pdo = Database::connect();
	$sql = "SELECT * FROM recipes WHERE id = '". $id ."'";
	foreach ($pdo->query($sql) as $row) {
		echo "<h1>" . $row['name'] ."</h1>";
		echo "<p>Views: " . $long ."</p>";
        echo '<p id="chef">By <a href="chefNew.php?chef='. $row['chef'] .'">Chef '. $row['chef'] .'</a></p>';
	}
	?>
    
   
    
	<section id="content">
    
	  <?php
       $pdo = Database::connect();
       $sql = "SELECT * FROM recipes WHERE id = '". $id ."'";
       $count = 0;
       foreach ($pdo->query($sql) as $row) {
                
                $badSteps = $row['steps'];
                $badSteps = str_replace("STEP", "<h5>STEP", $badSteps);
                $badSteps = str_replace("|", "</h5><p>", $badSteps); 
                $badSteps = str_replace("^", "</p>", $badSteps); 
                
                $ing = $row['ingredientsMain'];
                /*$ing = str_replace("pork", "<li>pork</li>", $ing);
                $ing = str_replace("chicken", "<li>chicken</li>", $ing);
                $ing = str_replace("broccoli", "<li>pork</li>", $ing);
                $ing = str_replace("beef", "<li>beef</li>", $ing);
                $ing = str_replace("fish", "<li>fish</li>", $ing);
                $ing = str_replace("pasta", "<li>pasta</li>", $ing);
                $ing = str_replace("rice", "<li>rice</li>", $ing);
				$ing = str_replace("vegetables", "<li><strong>vegetables</strong></li>", $ing);*/
                $ing = str_replace(" ", "", $ing);
                //$ing = str_replace("|", "", $ing);
				$street = explode('|', $ing);
				array_filter($street);
				$ingreds = "";
				foreach ($street as $se) { 
					$ingreds = $se;
				}
                
                /*$ingsAll = '<li><a href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/pages/category.php?category=ingredientsAll&search=' . 'pork' . '">' . $row['ingredientsAll'] . '</a></li>';
                $ingsAll = str_replace(",", "</li><li>", $ingsAll);
                $ingsAll = str_replace("<li></li>", "", $ingsAll);
                $ingsAll = str_replace("<li> </li>", "", $ingsAll);*/
				
                $ingyee = rtrim($row['ingredientsAll'], ',');
				$ingyee = rtrim($row['ingredientsAll'], ',');
                $ings = explode(',', $ingyee);
                foreach ($ings as &$value) {
                    $value = '<li>'. $value .'</li>';
                }
                unset($value);
                $ingsAll = $ings[0];
                $ingsAll = array_values($ings);
				
				$prep_time_array = unserialize($row['timePrep']);
                
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
                
				if($prep_time_array[3] > 0) { 
					$hours = $prep_time_array[3];
				} 
				else { 
					$hours = 0;
				}
				
				if($prep_time_array[0] > 0) { 
					$minutes = $prep_time_array[0];
				} 
				else { 
					$minutes = 0;
				}
				
				if($prep_time_array[2] > 0) { 
					$minutes2 = $prep_time_array[2];
				} 
				else { 
					$minutes2 = 0;
				}
				
				if($prep_time_array[1] > 0) { 
					$hours = $prep_time_array[1];
				} 
				else { 
					$hours2 = 0;
				}
				
				
				
				if (strlen($ingreds) > 0) {
					$ingreds = $ingreds;
					$link = '<a href="/student/globalit/2016/04_03/tinker/data/pages/category.php?category=ingredientsMain&search='. $ingreds .'">';
					$link2 = '</a>';
				}
				else {
					$ingreds = "None";
					$link = '';
					$link2 = '';
				}
                
                echo ''. $badPhotos .'<br><br>';
                echo '<div id="description"><em>'. $row['description'] .'</em></div><br>';
				echo '<div>Word Count: '. (str_word_count($row['description']) + 3) .'</div><br />';
                echo '
                        <div class="row-fluid">
                            <div class="col-lg-6">
								<h4><span class="glyphicon glyphicon-cutlery"></span> Meal Course Type:</h3>
								<p>' . $row['mealTypeCourse'] . '</p>
								<br />
								<h4><span class="glyphicon glyphicon-time"></span> Prep Time:</h3>
								<p>' . $hours2 . ' hours and ' . $minutes . ' minutes</p>
								<br />
								<h4><span class="glyphicon glyphicon-time"></span> Cook Time:</h3>
								<p>' . $hours . ' hours and ' . $minutes2 . ' minutes</p>
								<br />
                                <h4><span class="glyphicon glyphicon-apple"></span> Main Ingredient:</h3>
                                <ul><li>'. $link . $ingreds . $link2 . '</li></ul>
								<br />
								<h4><span class="glyphicon glyphicon-record"></span> Difficulty:</h3>
								<p>'. $row['difficulty'] .'</p>
								<br />
								<h4><span class="glyphicon glyphicon-grain"></span> Meal Diet:</h3>
								<p>'. $row['mealDiet'] .'</p>
								<br />
								
                            </div>
                            <div class="col-lg-6">
                                <h4><span class="glyphicon glyphicon-list-alt"></span> Ingredients:</h3>
                                <ul>';
								foreach ($ings as $item) {
                                    echo $item;
                                }
								echo '</ul>
								<br />
								<h4><span class="glyphicon glyphicon-tasks"></span> Steps:</h3>
                                <p>'. $badSteps .'</p>
								<br />
                            </div>
                        </div>
                    ';
                
                $timePrepArray = unserialize($row['timePrep']);
                $timePrepMin = $timePrepArray[0];
                $timePrepHour = $timePrepArray[1];
                
                $count++;
       }

       Database::disconnect();
      ?>
      </section>
      
      <?php
		$pdo = Database1::connect1();
		$sql = "SELECT * FROM popularity WHERE recipe_id = '". $id ."'";
		foreach ($pdo->query($sql) as $row) {
			if (isset($row['rating_array'])) {
				$rating_overall = unserialize($row['rating_array']);
				$rating_overall2 = round(array_sum($rating_overall) / count($rating_overall), 1);
				
			}
		}
		Database1::disconnect1();
	  ?>
      
      <aside>
      	<h3>Ratings</h3>
        <p><strong>Overall Rating</strong></p>
        <input id="overall_rating" name="overall_rating" value="<?php echo $rating_overall2; ?>" step=".5" class="rating rating-loading" data-show-caption="false" data-show-clear="false" data-display-only="true" data-size="sm">
        <p><?php if ($rating_overall2 > 0) { echo $rating_overall2; } else { echo 0; } ?> / 5 stars</p>
        <p><?php echo count($rating_overall); ?> Votes</p>
        <form action="recipe.php?id=<?php echo $_GET['id']; ?>" method="post">
        	<p><strong>Your Rating</strong></p>
            <input id="personal_rating" name="personal_rating" value="<?php echo $_POST['personal_rating']; ?>" step=".5" class="rating rating-loading" data-show-caption="false" data-show-clear="false" <?php if (isset($_POST['personal_rating'])) { echo 'data-display-only="true"'; } ?>  data-size="sm">
            <input type="submit" class="btn btn-info btn-block" value="Rate This Recipe" />
        </form>
        <br>
      </aside>
      
    </div>
  </body>
</html>