<?php 
  session_start();
  if(isset($_POST['post-submit']) && isset($_SESSION['userId'])){
    require '../lib/connect.inc.php';

    // 1. FILE VARIABLES
    // File variables
    $fileName = $_FILES['imageFile']['name'];
    $fileTempName = $_FILES['imageFile']['tmp_name'];
    $fileError = $_FILES['imageFile']['error'];
    $fileSize = $_FILES['imageFile']['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // File restrictions
    $allowedFiles = array('jpg', 'jpeg', 'png', 'gif');
    $maxSize = 1024 * 1024 * 2;  // 2MB

    // File upload path variables
    $fileUploadPath = str_replace('/', DIRECTORY_SEPARATOR, '../../public/uploads/posts');
    $fileUploadName = $_SESSION['userId'] . "_$fileName";
    $fileUploadUrl = $fileUploadPath . DIRECTORY_SEPARATOR . $fileUploadName;

    // File download path variables
    $fileDownloadUrl = str_replace('/', DIRECTORY_SEPARATOR, './public/uploads/posts/' . $fileUploadName);

    // 2. FILE VALIDATION
    // (i) PHP File Error
    if($fileError){
      $phpFileErrors = array(
        1 => 'ini-size',
        2 => 'form-size',
        3 => 'partial',
        4 => 'no-file',
        6 => 'tmp-dir',
        7 => 'cant-write',
        8 => 'extension',
      ); 
      $fileUploadError = $phpFileErrors[$fileError];
      header("Location: ../../createpost.php?uploaderror=$fileUploadError"); 
      exit();
    }

    // (ii) Incorrect file extension
    if(!in_array($fileExt, $allowedFiles)){
      header("Location: ../../createpost.php?uploaderror=bad-ext"); 
      exit();
    }

    // (iii) Exceeds max file size
    if($fileSize > $maxSize){
      header("Location: ../../createpost.php?uploaderror=file-size"); 
      exit();
    }

    // (iv) File Already Exists
    if(file_exists($fileUploadUrl)){
      header("Location: ../../createpost.php?uploaderror=file-exists"); 
      exit();
    }

    // 3. FORM VARIABLES
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    // 4. FORM VALIDATION
    if (empty($name) || empty($breed) || empty($gender) || empty($description) || empty($location)) {
      header("Location: ../../createpost.php?error=emptyfields");
      exit();
    }

    // 5. UPLOAD IMAGE TO SERVER
    $uploadResult = move_uploaded_file($fileTempName, $fileUploadUrl);
    if(!$uploadResult){
      header("Location: ../../createpost.php?uploaderror=system-error"); 
      exit();
    } 

    // SAVE POST 
    // (a) Template SQL Check
    $sql = "INSERT INTO posts VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)"; 
    $statement = $conn->stmt_init();
    if(!$statement->prepare($sql)){
      unlink($fileUploadUrl);
      header("Location: ../../createpost.php?error=sqlerror"); 
      exit();
    }

    // (b) Data Binding & Execution
    $statement->bind_param("sssssss", $name, $breed, $gender, $fileUploadName, $fileDownloadUrl, $description, $location);
    $statement->execute();
    if($statement->error){
      unlink($fileUploadUrl);
      header("Location: ../../createpost.php?error=servererror");
      exit();
    }

    // (vi) SUCCESS Post Submission
    header("Location: ../../posts.php?post=success"); 
    exit();

  } else {
    header("Location: ../../createpost.php?error=forbidden");
    exit();
  }
?>