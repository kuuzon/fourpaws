<?php 
  // VAL: Empty inputs
  // NOTE: Need to create a dynamic loop to scale based on arguments
  function emptyInputs(){
    $args = func_get_args();
    // Loop here against $args array
  }

  // VAL: Invalid uid AND email
  function invalidUidAndEmail($username, $email){
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  // VAL: Invalid uid
  function invalidUsername($username){
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  // VAL: Invalid email
  function invalidEmail($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  // VAL: Password strength fail
  function pwdStrength($pwd){
    $pwdReg = "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/";    
    if(!preg_match($pwdReg, $pwd)){
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  // VAL: Password Match 
  function pwdMatch($pwd, $pwdRepeat){
    if($pwd !== $pwdRepeat){
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }
?>