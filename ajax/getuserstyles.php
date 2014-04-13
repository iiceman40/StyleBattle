<?php
session_start();
require_once(dirname(__FILE__) . "/inc/db.php");
$stylestable = 'sb_styles';
$jointable = 'sb_styles_images';
$imagestable = 'sb_images';

//$id = $_REQUEST['id'];

// check if user already exists
$query = "SELECT t1.id, t1.description, t1.rating, t3.filename FROM $stylestable AS t1
			LEFT JOIN $jointable AS t2 ON t1.id=t2.style
			LEFT JOIN $imagestable AS t3 ON t3.id=t2.image
			WHERE t1.owner = ?";
//echo($query);
$stmt = $mysqli->prepare($query);
//$id = $_REQUEST['userid'];
if( isset($_SESSION['stylebattle_user']) ){
	$id = $_SESSION['stylebattle_user'];

	$stmt->bind_param('i', $id); // i= integer, s= string ... combine as "is..."
	$stmt->execute();
	$stmt->bind_result($id, $description, $rating, $image);
	$stmt->store_result();

	$success = false;
	$msg = 'Error';
	$resultArray = [];
	if( $stmt->num_rows > 0 ){
		while($stmt->fetch()) {
			$result = new StdClass();
			$result->success = $success;
			$result->id = $id;
			$result->image = $image;
			$result->rating = $rating;
			$result->description = $description;
			$result->msg = $msg;

			$resultArray[] = $result;
		}
		$success = true;
	} else {
		$success = false;
		$msg = "No style for user with id {$_REQUEST['userid']} found in database";
	}

	$mysqli->close();
} else {
	$success = false;
	$msg = "Not logged in";
}

$result = new StdClass();
$result->success = $success;
$result->styles = $resultArray;
$result->description = $description;
$result->msg = $msg;

$json = json_encode($result);
echo $json;

?>