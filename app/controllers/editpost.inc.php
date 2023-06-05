<?php
  session_start();
  if(isset($_POST['edit-submit']) && isset($_SESSION['userId'])){
    require '../lib/connect.inc.php';

    // Form variables
    // NOTE: Disabled image re-upload for timebeing
    $pid = $conn->real_escape_string($_GET['pid']); 
    $pid = intval($pid);
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $gender = $_POST['gender'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    // VALIDATION: 
    if (empty($name) || empty($breed) || empty($gender) || empty($description) || empty($location)) {
      header("Location: ../../editpost.php?pid=$pid&error=emptyfields");
      exit();
    }

    // UPDATE POST 
    // (a) Template SQL Check
    $sql = "UPDATE posts SET name=?, breed=?, gender=?, description=?, location=? WHERE pid=?"; 
    $statement = $conn->stmt_init();
    if(!$statement->prepare($sql)){
      header("Location: ../../editpost.php?pid=$pid&error=sqlerror"); 
      exit();
    }

    // (b) Data Binding & Execution
    $statement->bind_param("sssssi", $name, $breed, $gender, $description, $location, $pid);
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