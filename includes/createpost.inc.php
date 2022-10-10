<?php
  // 1. Start Session: 
  // NOTE: We only want users to create posts if they're logged in
  session_start();

  // 2. Check user clicked submit button from createpost form + user is logged in
  if(isset($_POST['post-submit']) && isset($_SESSION['userId'])){
    // 3. Connect to database
    require 'connect.inc.php';

    // 4. Collect & store POST data
    $title = $_POST['title'];
    $imageURL = $_POST['imageurl'];
    $comment = $_POST['comment'];
    $websiteURL = $_POST['websiteurl'];
    $websiteTitle = $_POST['websitetitle'];

    // 5. VALIDATION: Check if any fields are empty (v. similar to login)
    // NOTE: We could make more validation - but we will keep simple for timebeing!
    if (empty($title ) || empty($imageURL) || empty($comment) || empty($websiteURL) || empty($websiteTitle)) {
      // ERROR: Redirect + error via GET
      header("Location: ../createpost.php?error=emptyfields");
      exit();

    // 6. Save Post to DB using Prepared Statements
    } else {
      // (i) Declare Template SQL with ? Placeholders to save values to table
      // NOTE: Null first value, as it is PK, meaning it auto-increments
      $sql = "INSERT INTO posts VALUES (NULL, ?, ?, ?, ?, ?)"; 

      // (ii) Init SQL statement
      $statement = mysqli_stmt_init($conn);

      // (iii) Prepare + send statement to database to check for errors
      if(!mysqli_stmt_prepare($statement, $sql))
      {
        // ERROR: Something wrong when preparing the SQL
        header("Location: ../createpost.php?error=sqlerror"); 
        exit();
      } else {
        // (iv) SUCCESS: Bind our user data with statement + escape strings
        // NOTE: We bind FIVE strings!
        mysqli_stmt_bind_param($statement, "sssss", $title, $imageURL, $comment, $websiteURL, $websiteTitle);

        // (v) Execute the SQL Statement with user data
        mysqli_stmt_execute($statement);

        // (vi) SUCCESS: Post is saved to "posts" table - redirect with success message
        header("Location: ../posts.php?post=success"); 
        exit();
      }
    }

  // 7. Restrict Access to Script Page
  // NOTE: For example, to access this script, user MUST be LOGGED IN & MUST CLICK SUBMIT 
  } else {
    header("Location: ../index.php");
    exit();
  }
?>
