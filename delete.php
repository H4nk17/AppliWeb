<?php
header('location:espace-membre.php');
$db_host     = 'localhost';
$db_username = 'root';
$db_password = 'root';
$db_name     = 'membres';

try{
	$dataBaseConection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
	$dataBaseConection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "DELETE FROM `membres` WHERE `membres`.`id` = :id LIMIT 1";
	$sth = $dataBaseConection->prepare($sql);
	$sth->execute(array(
		':id' => $_GET['id']
	));
}
catch(PDOException $e){
	echo "Erreur : " . $e->getMessage();
}