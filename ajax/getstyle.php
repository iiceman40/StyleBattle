<?php
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once(dirname(__FILE__) . "/inc/db.php");
$stylestable = 'sb_styles';
$jointable = 'sb_styles_images';
$imagestable = 'sb_images';

//$id = $_REQUEST['id'];

// check if user already exists
$query = "SELECT t1.id, t1.description, t1.rating, t3.filename FROM $stylestable AS t1
			LEFT JOIN $jointable AS t2 ON t1.id=t2.style
			LEFT JOIN $imagestable AS t3 ON t3.id=t2.image";
			//WHERE t1.id = ?";
//$stmt = $mysqli->prepare($query);
//$stmt->bind_param('i', $id); // i= integer, s= string ... combine as "is..."
//$stmt->execute();
//$stmt->bind_result($id, $description, $rating, $image);
//$stmt->store_result();
$result = $mysqli->query($query);
$resultArray = [];
while($row = $result->fetch_assoc()) {
	$resultArray[] = $row;
}
//var_dump($resultArray);

$resultId = rand(0,$result->num_rows-1);
if($_REQUEST['idlooser'] && $_REQUEST['idwinner'])
	while($resultArray[$resultId]['id'] == $_REQUEST['idlooser'] ||
		$resultArray[$resultId]['id'] == $_REQUEST['idwinner']){
		$resultId = rand(0,$result->num_rows-1);
	}

$id = $resultArray[$resultId]['id'];
$rating = $resultArray[$resultId]['rating'];
$description = $resultArray[$resultId]['description'];
$image = $resultArray[$resultId]['filename'];

$success = false;
$msg = 'Error';

if( $stmt->num_rows > 0 ){
	$stmt->fetch();
	$success = true;
	$msg = $description."<br/> Rating: ".$rating;
} else {
	$success = false;
	$msg = "No style with id {$_REQUEST['id']} found in database";
}

$mysqli->close();

$result = new StdClass();
$result->success = $success;
$result->id = $id;
$result->image = $image;
$result->rating = $rating;
$result->description = $description;
$result->msg = $msg;

$json = json_encode($result);
echo $json;

?>