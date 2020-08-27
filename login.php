<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Christoff Botha">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				$userid = -1;
				if($row = mysqli_fetch_array($res)){
					$userid = $row['user_id'];
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

							if (isset($_FILES['picToUpload'])) {
								$total = count($_FILES['picToUpload']['name']);
								$uploadcode = 0;
								for( $i=0 ; $i < $total ; $i++ ) {
									$path = $_FILES['picToUpload']['tmp_name'][$i];
									$extent = strtolower(pathinfo($_FILES['picToUpload']['name'][$i],PATHINFO_EXTENSION));
							  	if ($_FILES["picToUpload"]["size"][$i] > 1048576) {
										$uploadcode = 1;
							  	}
									if ($extent != "jpg" && $extent != "jpeg") {
										$uploadcode = $uploadcode > 0 ? 3 : 2;
									}
									else {
								  	$newpath = "gallery/" . $_FILES['picToUpload']['name'][$i];
								    if(move_uploaded_file($path, $newpath)) {
											$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$userid', '$newpath');";
											$res = $mysqli->query($query);
								  	}
									}
								}
								if ($uploadcode == 1) {
									echo 	'<div class="alert alert-danger mt-3" role="alert">
													Some files were too large to upload to server. Must be under 1 MB.
												</div>';
								}
								elseif ($uploadcode == 2) {
									echo 	'<div class="alert alert-danger mt-3" role="alert">
													Some files were the wrong format to upload. Must be jpg or jpeg.
												</div>';
								}
								elseif ($uploadcode == 3) {
									echo 	'<div class="alert alert-danger mt-3" role="alert">
													Some files were the wrong format to upload. Must be jpg or jpeg. <br />
													Some files were too large to upload to server. Must be under 1 MB.
												</div>';
								}
							}

							echo 	"<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple /><br/>
									<input type='hidden' name='loginEmail' value='".$_POST["loginEmail"]."'  />
									<input type='hidden' name='loginPass' value='".$_POST["loginPass"]."'  />
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>
								<h2>Image Gallery</h2>
								<div class='row imageGallery'>";
							$query = "SELECT * FROM tbgallery WHERE user_id = '$userid'";
							$res = $mysqli->query($query);
							while ($row = mysqli_fetch_array($res)) {
								echo"<div class='col-3' style='background-image: url(". $row['filename'].")'> </div>";
							}
								echo "</div>";


				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}

			}
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

		?>
	</div>
</body>
</html>
