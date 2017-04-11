

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include 'database.php';
$pdo = Database::connect();
$sql = "SELECT * FROM recipes;";
foreach ($pdo->query($sql) as $row) {
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
					
					
					$badPhotos = str_replace(',', '" width="20%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', $badPhotos);
					$badPhotos = str_replace('/>.jpg" width="20%" onerror="this.src=\'http://foothillertech.com/student/globalit/2016/04_03/tinker/data/assets/images/plate.jpg\'" />,', ' />', $badPhotos);
					//$badPhotos = strstr($badPhotos, ",", false);
					$badPhotos = str_replace(',', '', $badPhotos);
	
	echo $row['chef']. '<br>';
	echo $badPhotos. '<br>';
	
	$badPhotos = str_replace('<', '&lt;', $badPhotos);
	$badPhotos = str_replace('<', '&gt;', $badPhotos);
	
	echo $badPhotos. '<br><br>';
}
?>
</body>
</html>
