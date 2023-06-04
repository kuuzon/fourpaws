<?php
  session_start();
  if(isset($_POST['edit-submit']) && isset($_SESSION['userId'])){
    require '../lib/connect.inc.php';

    // Form variables
    $pid = $conn->real_escape_string($_GET['pid']); 
    $pid = intval($pid);
    $title = $_POST['title'];
    $imageURL = $_POST['imageurl'];
    $comment = $_POST['comment'];
    $websiteURL = $_POST['websiteurl'];
    $websiteTitle = $_POST['websitetitle'];

    // VALIDATION: 
    if (empty($pid) || empty($title) || empty($imageURL) || empty($comment) || empty($websiteURL) || empty($websiteTitle)) {
      header("Location: ../../editpost.php?pid=$pid&error=emptyfields");
      exit();
    }

    // UPDATE POST 
    // (a) Template SQL Check
    $sql = "UPDATE posts SET title=?, imageurl=?, comment=?, websiteurl=?, websitetitle=? WHERE pid=?"; 
    $statement = $conn->stmt_init();
    if(!$statement->prepare($sql)){
      header("Location: ../../editpost.php?pid=$pid&error=sqlerror"); 
      exit();
    }

    // (b) Data Binding & Execution
    $statement->bind_param("sssssi", $title, $imageURL, $comment, $websiteURL, $websiteTitle, $pid);
    $statement->execute();
    if($statement->error){
      header("Location: ../../editpost.php?pid=$pid&error=servererror");
      exit();
    }

    // SUCCESS Edit Post
    header("Location: ../../posts.php?pid=$pid&edit=success"); 
    exit();

  } else {
    header("Location: ../../signup.php");
    exit();
  }
?>