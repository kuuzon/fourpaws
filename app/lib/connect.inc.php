<?php         
  // REQ: Convert to ENV
  $hostname = "localhost";
  $username = "root";
  $password = "root";
  $database = "feastable"; 

  // Connection var
  $conn = new mysqli($hostname, $username, $password, $database);
  if ($conn->connect_error) {
    die('<div class="alert alert-warning mt-3" role="alert"><h4>Connection Failed<h4>' . $conn->connect_error . '</div>');
  }
?>

<!-- FIX UP FOUR PAWS (cat adoption service)
- Image uploading in createpost refactoring
- Image uploader for edit
- Fix signup to allow for EITHER username or email
- Allow for editing & deleting of user profiles
- Create user dashboard
- Auth-link posts to userids ONLY
- ENV setup for php
- Expand directory to setup index routing (https://docs.php.earth/faq/misc/structure/)
-->