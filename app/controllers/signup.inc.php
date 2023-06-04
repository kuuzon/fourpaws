<?php 
  if(isset($_POST['signup-submit'])){
    require '../lib/connect.inc.php';
    require_once '../lib/validationFunctions.inc.php';

    // Form variables
    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    // VALIDATION: 
    // (i) Check if any fields are empty
    if(empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
      header("Location: ../../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
      exit(); 
    
    // (ii) Check for BOTH invalid email AND password syntax (uses A to Z & 0 to 9) 
    } else if(invalidUidAndEmail($username, $email)) {
      header("Location: ../../signup.php?error=invalidmailuid");
      exit(); 

    // (iii) Checks JUST if the username is invalid ONLY
    } else if(invalidUsername($username)) {
      header("Location: ../../signup.php?error=invaliduid&mail=".$email);
      exit();

    // (iv) Checks JUST if the email is invalid ONLY
    } else if(invalidEmail($email)) {
      header("Location: ../../signup.php?error=invalidmail&uid=".$username);
      exit(); 

    // (v) Password check
    } else if(pwdStrength($password)){
      header("Location: ../../signup.php?error=invalidpwd&uid=" . $username . "&mail=" . $email);
      exit();

    // (vi) Checks if password has NOT been confirmed correctly
    } else if(pwdMatch($password, $passwordRepeat)){
      header("Location: ../../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
      exit();  

    // -- VALIDATION COMPLETE - REMAINDER OF CODE RUNS --
    } else {
      // Check if User Exists in Database
      // (a) Template SQL Check
      $sql = "SELECT username FROM users WHERE username=?";
      $statement = $conn->stmt_init();
      if(!$statement->prepare($sql)){
        // ERROR: Something wrong when preparing the SQL
        header("Location: ../../signup.php?error=sqlerror"); 
        exit();
      }

      // (b) Data Binding & Execution
      $statement->bind_param("s", $username);
      $statement->execute();
      $statement->store_result();

      // Check if duplicate user based on row return
      $resultCheck = $statement->num_rows();
      if($resultCheck > 0){
        // ERROR: If User Already Exists
        header("Location: ../../signup.php?error=usertaken&mail".$email); 
        exit(); 

      // No user exists
      } else {
        // (a) Template SQL Check
        $sql = "INSERT INTO users (username, email, pwd) VALUES (?, ?, ?)";
        $statement = $conn->stmt_init();
        if(!$statement->prepare($sql)){
          header("Location: ../../signup.php?error=sqlerror");
          exit(); 
        } 
    
        // (b) Data Binding & Execution
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $statement->bind_param("sss", $username, $email, $hashedPwd);
        $statement->execute();
    
        // SUCCESS Signup
        header("Location: ../../signup.php?signup=success"); 
        exit();
      }
      // Close prepared statement & DB connection
      $statement->close(); 
      $conn->close(); 
    }
  } else {
    header("Location: ../../signup.php?error=forbidden");
    exit(); 
  }
?>