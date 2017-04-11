<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="1077527292180-rqubb91asn2shhe46e7fen1le01rn0lr.apps.googleusercontent.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact | VG Recipes</title>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/links.html'; ?>
</head>
 
<body>
    <?php include '/home/benrud/public_html/student/globalit/2016/04_03/tinker/data/assets/includes/nav.html'; ?>
    <div class="container">
    	<h1>Contact Us</h1>
    </div>
    <div class="container">
    	Questions? Comments? Concerns? Submit a query:
        <?php
		if(!isset($_POST['message'])) {
			echo '
			<form action="contact.php" method="post">
				<textarea name="message"></textarea>
				<button class="btn btn-success" type="submit">Submit</button>
			</form>';
		}
		?>
    </div>
</body>
</html>