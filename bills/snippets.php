<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bills</title>
	<link href="https://fonts.googleapis.com/css?family=Covered+By+Your+Grace|Permanent+Marker" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>

<?php
	
	//session_start();
	require_once('config.php'); 

	if(!isset($_SESSION['email']) || !isset($_SESSION['username']) || !isset($_SESSION['password'])){
		$message = "Valid email (or) password not received";
		header("Location: login_signup.php");
		$_SESSION['reg_msg'] = $message;

	}

	$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


	$email = $_SESSION['email'];
	$name = $_SESSION['name'];
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	//the user to whom the code belongs
	$user = NULL;
	$id = NULL;
	$codeid = NULL;

	echo "<div class = 'heading'>
		<h1>Bills
		<span class = 'home'><i title='home' class='material-icons' onClick='window.location.href =`homepage.php`;'>home</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Logout' class='material-icons' onClick='window.location.href =`logout.php`;'>power_settings_new</i>&nbsp;&nbsp;</span>
		</h1>
		</div>";

	echo "<div class = 'greeting'>
		<span class = 'greeting_span'>Hello, ".$name." (".$username.")!</span>
	</div>";

	echo "<form id = 'mycodesform' method='post' action='snippets.php'>
			<input type = 'hidden' name = 'user' value ='".$username."'>
	</form>";
	/*echo "<form id = 'browsecodesform' method='post' action='snippets.php'>
	</form>";*/
	echo "<div class = 'greeting'>
			<span><input type='submit' value='View My Bills' form='mycodesform'></span></div>";
			/*
			<span><input type='submit' value='Browse Latest Codes' form='browsecodesform'></span>
		</div>";*/
	echo "<div class = greeting>Enter the date<br>
		<form action='snippets.php' method ='post'>
		<br>Year : <select name = 'yyyy'>";
		for($i=date("Y"); $i>=2003;$i--){
			echo "<option value='".$i."'>".$i."</option>";
		}
	echo "</select>";
	echo "<br>Month : <select name = 'mm'>";
		for($i=1; $i<= 12;$i++){
			echo "<option value='".$i."'>".$i."</option>";
		}
	echo "</select>";
	echo "<br>Day : <select name = 'dd'>";
		for($i=1; $i< 31;$i++){
			echo "<option value='".$i."'>".$i."</option>";
		}
	echo "</select>";
	echo "<br><input type='submit' value='Submit'>";
		echo"</form></div>";
		if (!isset($_POST['yyyy']) && !isset($_POST['mm']) && !isset($_POST['dd'])) {
			echo "<div class = 'View'><p>View My Bills</p>";
			$sql = "SELECT * FROM `".$username."` ORDER BY `".$username."`.`date` DESC";
			$query = mysqli_query($link,$sql);
			if (!$query) {
				echo "<br> Error :" . mysqli_error($link);
			}
			while ($result = mysqli_fetch_array($query)) {
				echo "<div class='Code_description'>";
				echo "<p>" . $result['description']." : Rs " . $result['cost']."</p>";
				echo "</div>";
			} 
			echo "</div>";
		}		
	//SELECT * FROM `users` WHERE name LIKE "R%"	
		else{
		//display the item with the same id and codeid
		if (strlen($_POST['mm']) == 1) {
			$month = "0".$_POST['mm'];
		}
		else{
			$month = $_POST['mm'];
		}

		if (strlen($_POST['dd']) == 1) {
			$day = "0" . $_POST['dd'];
		}
		else{
			$day = $_POST['dd'];
		}
		$sqldate =date('Y-m-d', strtotime($_POST['yyyy']."-".$month."-".$day));
		echo "<div class = 'View'><p>View My Bills</p>";
		//echo $sqldate;
		//echo strtotime($_POST['yyyy']."-".$_POST['mm']."-".$_POST['dd']);
			$sql = "SELECT * FROM `".$username."` where  DATE(date) = '".$sqldate."' ORDER BY `".$username."`.`date` DESC";
			$query = mysqli_query($link,$sql);
			if (!$query) {
				echo "<br> Error :" . mysqli_error($link);
			}
			while ($result = mysqli_fetch_array($query)) {
				echo "<div class='Code_description'>";
				echo "<p>" . $result['description']." : Rs " . $result['cost']."</p>";
				echo "</div>";
			} 
			echo "</div>";
	}
?>
</body>
</html>