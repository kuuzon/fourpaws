<?php
  // 9. Check User Clicked Edit-Submit Button + Logged In
  session_start();
  if(isset($_POST['edit-submit']) && isset($_SESSION['userId'])){
    // 11. Connect to DB
    require 'connect.inc.php';

    // 12. Collect & store POST data
    // NOTE: We extract EVERYTHING, including the id of the edited post (which we escape).  We want the id, as we want to UPDATE an existing row in our DB, not create a new one! 
    $id = mysqli_real_escape_string($conn, $_GET['id']); 
    $id = intval($id);
    $title = $_POST['title'];
    $imageURL = $_POST['imageurl'];
    $comment = $_POST['comment'];
    $websiteURL = $_POST['websiteurl'];
    $websiteTitle = $_POST['websitetitle'];

    // 13. VALIDATION: Check if any fields are empty (v. similar to createpost / login)
    // NOTE: We could make more validation - but we will keep simple for timebeing!
    if (empty($id) || empty($title) || empty($imageURL) || empty($comment) || empty($websiteURL) || empty($websiteTitle)) {
      // ERROR: Redirect + error via GET
      header("Location: ../editpost.php?id=$id&error=emptyfields");
      exit();

    // 14. Save (BY UPDATE) Edited Post to DB using Prepared Statements
    } else {
      // (i) Declare Template SQL with ? Placeholders to update values for row in posts table (6 PLACEHOLDERS!)
      $sql = "UPDATE posts SET title=?, imageurl=?, comment=?, websiteurl=?, websitetitle=? WHERE id=?"; 

      // (ii) Init SQL statement
      $statement = mysqli_stmt_init($conn);

      // (iii) Prepare + send statement to database to check for errors
      if(!mysqli_stmt_prepare($statement, $sql))
      {
        // ERROR: Something wrong when preparing the SQL
        header("Location: ../editpost.php?id=$id&error=sqlerror"); 
        exit();
      } else {
        // (iv) SUCCESS: Bind our user data with statement + escape strings
        // NOTE: We bind FIVE strings and ONE integer!
        mysqli_stmt_bind_param($statement, "sssssi", $title, $imageURL, $comment, $websiteURL, $websiteTitle, $id);

        // (v) Execute the SQL Statement with user data
        mysqli_stmt_execute($statement);

        // (vi) SUCCESS: Edited post is updated for specific row in "posts" table - redirect with success message
        header("Location: ../posts.php?id=$id&edit=success"); 
        exit();
      }
    }


  // 10. Restrict Access to Edit Script Page
  // NOTE: For example, to access this script, user MUST be LOGGED IN & HIT SUBMIT
  } else {
    header("Location: ../signup.php");
    exit();
  }
?>