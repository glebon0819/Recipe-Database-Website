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
    	
        <?php
		if(isset($_GET['message'])) {
			echo '<p>Email: '. $_GET['email'] .'</p>';
			echo '<p>Type: '. $_GET['type'] .'</p>';
			echo '<p>Message: '. $_GET['message'] .'</p>';
		}
		else {
			echo '<form action="contact.php" method="GET">
            
                <h4>Email:</h4>
                <input type="email" name="email" id="email">
                <br />
            	<br />
                <h4>Type of Message</h4>
                <select name="type">
                	<option disabled="disabled" selected="selected">Select</option>
                    <option>Question</option>
                    <option>Comment</option>
                    <option>Concern</option>
                    <option>Complaint</option>
                </select>
                <br />
            	<br />
                <h4>Your message:</h4>
                <textarea name="message"></textarea><br />
                <input class="btn" type="submit" />
                
            
        </form>';
		}

		?>
</div>
</body>
</html>