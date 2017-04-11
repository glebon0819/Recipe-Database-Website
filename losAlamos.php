<?php 

$memes = $_POST['input-1'];
$memes2 = $_POST['input-2'];

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Los Alamos Testing Grounds</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/experimental/bootstrap-star-rating-master/css/star-rating.css" rel="stylesheet">
        <link href="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/experimental/bootstrap-star-rating-master/css/star-rating.min.css" rel="stylesheet">
        <script src="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/experimental/bootstrap-star-rating-master/js/star-rating.js"></script>
        <script src="http://foothillertech.com/student/globalit/2016/04_03/tinker/data/experimental/bootstrap-star-rating-master/js/star-rating.min.js"></script>
    </head>
    
    <body>
    	<form action="losAlamos.php" method="post">
        	<label for="input-1" class="control-label">Rate This</label>
			<input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="sm">
            
            <label for="input" class="control-label">Rate This</label>
			<input id="input" name="input" class="rating rating-loading" data-min="0" data-max="5" value="4" data-size="sm" data-display-only="true">
            
            <input type="submit">
        </form>
        <?php
		echo '<p>' . $memes . '</p>';
		echo '<p>' . $memes2 . '</p>';
		?>
    </body>
</html>
<script>
$(document).on('ready', function(){
    $('#input').rating({displayOnly: true, step: 0.1});
});
</script>