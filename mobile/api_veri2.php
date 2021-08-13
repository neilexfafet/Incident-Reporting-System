<?php
    include 'conn.php';

	$image = $_FILES['image']['name'];
	$email=$_POST['email'];
	$imagePath = '../uploads/'.$image;
	$savedb = "uploads/$image";

	$tmp_name = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp_name, $imagePath);
    
	
	$connect->query("UPDATE `users` SET image='$savedb' WHERE email='$email'");


?>