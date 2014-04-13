<?php
session_start();
require_once(dirname(__FILE__) . "/inc/db.php");

$usertable = "sb_users";

$query = "SELECT id, email, password, nickname FROM $usertable WHERE email = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $email); // i= integer, s= string ... combine as "is..."

$email = $_REQUEST['email'];

$stmt->execute();
$stmt->bind_result($id, $email, $password, $nickname);
$stmt->store_result();

if( $stmt->num_rows > 0 ){
	$stmt->fetch();
	if($_REQUEST['password']==$password){
		if( !isset($_SESSION['stylebattle_user']) ){
			$_SESSION['stylebattle_user'] = $id;
		}
		$success = true;
	} else
		$success = false;
} else {
	$success = false;
}

$mysqli->close();

$result = new StdClass();
$result->success = $success;
$result->id = $id;
$result->email = $email;
$result->password = $password;
$result->nickname = $nickname;

$json = json_encode($result);
echo $json;
?>