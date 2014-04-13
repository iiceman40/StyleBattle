<?php
session_start();
require_once(dirname(__FILE__) . "/inc/db.php");
$stylestable = "sb_styles";

$styleId = $_REQUEST['styleid'];

$stmt = $mysqli->prepare("DELETE FROM $stylestable WHERE id = ?");
$stmt->bind_param('i', $styleId);
$stmt->execute();
$stmt->close();

// delete style
// delete image
// delete relation
?>