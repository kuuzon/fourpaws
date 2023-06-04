<?php 
  session_start();
  if(isset($_POST['post-submit']) && isset($_SESSION['userId'])){
    require '../lib/connect.inc.php';

    // Form variables
    $title = $_POST['title'];
    $imageURL = $_POST['imageurl'];
    $comment = $_POST['comment'];
    $websiteURL = $_POST['websiteurl'];
    $websiteTitle = $_POST['websitetitle'];

    // VALIDATION: 
    if (empty($title ) || empty($imageURL) || empty($comment) || empty($websiteURL) || empty($websiteTitle)) {
      header("Location: ../../createpost.php?error=emptyfields");
      exit();
    }

    // SAVE POST 
    // (a) Template SQL Check
    $sql = "INSERT INTO posts VALUES (NULL, ?, ?, ?, ?, ?)"; 
    $statement = $conn->stmt_init();
    if(!$statement->prepare($sql)){
      header("Location: ../../createpost.php?error=sqlerror"); 
      exit();
    }

    // (b) Data Binding & Execution
    $statement->bind_param("sssss", $title, $imageURL, $comment, $websiteURL, $websiteTitle);
    $statement->execute();
    if($statement->error){
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
