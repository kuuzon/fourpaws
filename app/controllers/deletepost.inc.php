<?php
  session_start();
  if(isset($_SESSION['userId']) && isset($_GET['pid'])){
    require '../lib/connect.inc.php';

    // Form variables
    $pid = $conn->real_escape_string($_GET['pid']); 
    $pid = intval($pid);

    // DELETE POST 
    // (a) Template SQL Check
    $sql = "DELETE FROM posts WHERE pid=?"; 
    $statement = $conn->stmt_init();
    if(!$statement->prepare($sql)){
      header("Location: ../../posts.php?pid=$pid&error=sqlerror"); 
      exit();
    } 

    // (b) Data Binding & Execution
    $statement->bind_param("i", $pid);
    $statement->execute();
    if($statement->error){
      header("Location: ../../posts.php?error=servererror");
      exit();
    }
    
    // SUCCESS: Post deletion
    header("Location: ../../posts.php?pid=$pid&delete=success"); 
    exit();

  } else {
    header("Location: ../../signup.php");
    exit();
  }
?>