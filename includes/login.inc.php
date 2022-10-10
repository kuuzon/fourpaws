<?php
  // 1. Check user clicked submit button on Login Form
  if(isset($_POST['login-submit'])){

    // 2. Connect to database
    require 'connect.inc.php';

    // 3. Collect & store the POST username + password in variables
    $mailuid = $_POST['mailuid'];
    $password = $_POST['pwd'];

    // 4. Check username and password fields are not blank
    if(empty($mailuid) || empty($password)){
      // Send emptyfields error
      header("Location: ../index.php?loginerror=emptyfields"); 
      exit(); 
    
    // 5. Check if User Exists in Database WHERE No Form Error (Preapred Statements)
    } else {
      // (i) Declare Template SQL with ? Placeholders to find user/email in DB
      $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?"; 

      // (ii) Init SQL statement
      $statement = mysqli_stmt_init($conn);

      // (iii) Prepare + send statement to database to check for errors
      if(!mysqli_stmt_prepare($statement, $sql)) {
        // ERROR: Something wrong when preparing the SQL 
        header("Location: ../index.php?loginerror=sqlerror"); 
        exit(); 
      } else {
        // (iv) SUCCESS: Bind our user data with statement + escape strings
        // NOTE: Pass in mailuid twice as one checks against uidUsers and then against emailUsers (filling in ? for $sql)
        mysqli_stmt_bind_param($statement, "ss", $mailuid, $mailuid);

        // (v) Execute the SQL Statement with user data
        mysqli_stmt_execute($statement);

        // (vi) Return result & store in variable
        $result = mysqli_stmt_get_result($statement);       

        // 6. Check $result to see if a user EXISTS in DB
        if($row = mysqli_fetch_assoc($result)){
          // (i) Compare form password vs. encrypted password in DB
          $pwdCheck = password_verify($password, $row['pwdUsers']);

          // (ii) User exists - BUT Password is NOT a match using bcrypt
          if($pwdCheck == false){
            header("Location: ../index.php?loginerror=wrongpwd");
            exit(); 

          // (iii) User exists - Password match & init session
          } else if ($pwdCheck == true) {
            // Start session
            session_start();
            // Add user data to session variable 
            $_SESSION['userId'] = $row['idUsers']; 
            $_SESSION['userUid'] = $row['uidUsers']; 
            header("Location: ../index.php?login=success");
            exit(); 
          
          // (iv). Catch all for unexpected error (very remote!)
          } else {
            header("Location: ../index.php?loginerror=wrongpwd");
            exit(); 
          }
        
        // (v). Error if no user was found in DB
        } else {
          header("Location: ../index.php?loginerror=nouser");
          exit(); 
        }
      }
    }
  // 7. Restrict Access to Script Page
  } else {
    header("Location: ../index.php");
    exit();
  }

?>