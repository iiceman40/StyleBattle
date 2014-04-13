<?php
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once(dirname(__FILE__) . "/inc/db.php");
$stylestable = 'sb_styles';

$query = "SELECT rating FROM $stylestable WHERE id = ?";
$stmt = $mysqli->prepare($query);

$stmt->bind_param('i', $_REQUEST['idlooser']);
$stmt->execute();
$stmt->bind_result($ratingLooser);
$stmt->fetch();

$stmt->bind_param('i', $_REQUEST['idwinner']);
$stmt->execute();
$stmt->bind_result($ratingWinner);
$stmt->fetch();

$stmt->close();

$newRating = $ratingWinner + intval($ratingLooser/10) + 1;

$query = "UPDATE $stylestable SET rating=$newRating WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $_REQUEST['idwinner']);
$stmt->execute();
$stmt->store_result();
$stmt->close();

$success = true;


$mysqli->close();

$result = new StdClass();
$result->success = $success;
$result->rating = $newRating;

$json = json_encode($result);
echo $json;

?>