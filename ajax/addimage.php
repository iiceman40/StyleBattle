<?php
session_start();
require_once(dirname(__FILE__) . "/inc/db.php");

$imagestable = 'sb_images';
$ds = DIRECTORY_SEPARATOR;

if (!empty($_FILES) && isset($_SESSION['stylebattle_user']) ){
	$id = $_SESSION['stylebattle_user'];

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if( (  ($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		//&& ($_FILES["file"]["size"] < 20000)
		&& in_array($extension, $allowedExts) ){

		$relativeFolder = 'ajax';
		$storeFolder = 'uploads'.$ds.$id;

		$tempFile = $_FILES['file']['tmp_name'];

		$targetPath = dirname( __FILE__ ) . $ds . $storeFolder . $ds;
		$targetFile =  $targetPath . $_FILES['file']['name'];

		$targetFileRel =  $ds . $relativeFolder . $ds . $storeFolder . $ds . $_FILES['file']['name'];
		while (file_exists($targetFile)) {
			$fileNameAsArray = explode('.', $_FILES['file']['name']);
			$newFileName =  $fileNameAsArray[0]. "_" . rand() . "." . $fileNameAsArray[count($fileNameAsArray)-1];
			$targetFile =  $targetPath . $newFileName;
			$targetFileRel =  $ds . $relativeFolder . $ds . $storeFolder . $ds . $newFileName;
		}

		// create directory if needed and move file to new location
		if (!file_exists($targetPath) and !is_dir($targetPath)) {
			mkdir($targetPath);
		}

		move_uploaded_file($tempFile,$targetFile);

		include(dirname(__FILE__) . "/inc/resize-class.php");

		// *** 1) Initialize / load image
		$resizeObj = new resize($targetFile);
		// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
		$resizeObj -> resizeImage(700, 1000, 'auto');
		// *** 3) Save image
		$resizeObj -> saveImage($targetFile, 100);

		// insert into database
		$query = "INSERT INTO $imagestable (filename, owner) VALUES (?,?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('si', $targetFileRel, $id); // i= integer, s= string ... combine as "is..."
		$stmt->execute();
		$error = $mysqli->error;

		$result = new StdClass();
		if(!$error)
			$result->success = true;
		else {
			$success = false;
			$result->error = $error;
		}
		$result->id = $mysqli->insert_id;
		$result->pathToFile = $targetFileRel;
		$result->filename = $_FILES['file']['name'];

		$mysqli->close();

		$json = json_encode($result);
		echo $json;
	}
} else echo "No Image found or you are not logged in.";

?>