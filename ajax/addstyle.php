<?php
session_start();
require_once(dirname(__FILE__) . "/inc/db.php");

$imageId = $_REQUEST['imageid'];
$description = $_REQUEST['description'];
$pathToFile = $_REQUEST['pathToFile'];
$initialRating = 1;

$imagesstylestable = 'sb_styles_images';
$stlyestable = 'sb_styles';

if( isset($_SESSION['stylebattle_user']) ){
	$id = $_SESSION['stylebattle_user'];
	$pathToFile

	$query = "INSERT INTO $stlyestable (description, rating, owner) VALUES (?,?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('sii', $description, $initialRating, $id); // i= integer, s= string ... combine as "is..."
	$stmt->execute();
	$styleId = $mysqli->insert_id;

	$query = "INSERT INTO $imagesstylestable (style,image) VALUES (?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ii', $styleId, $imageId); // i= integer, s= string ... combine as "is..."
	$stmt->execute();

	$error = $mysqli->error;
} else {
	$error = "Not logged in.";
}

$result = new StdClass();
if(!$error)
	$result->success = true;
else {
	$success = false;
	$result->error = $error;
}
$result->id = $mysqli->insert_id;
$result->description = $description;
$result->image = $pathToFile;
$result->rating = 1;

$mysqli->close();

$json = json_encode($result);
echo $json;
?>