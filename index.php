<!-- HEADER.PHP -->
<?php 
  require "templates/header.php"
?>

  <main class="container mt-3" style="width: 1000px">
    <!-- 1. CONDITIONAL Logged In/Logged Out Alerts -->
    <?php 
      // Checks the $_SESSION for user variable
      if(isset($_SESSION['userId'])){
        echo '<div class="alert alert-success" role="alert">You are logged in!</div>';
      }
      else
      {
        echo '<div class="alert alert-warning" role="alert">You are not logged in</div>';
      }
    ?>
  </main>
  <main class="container p-4 bg-light mt-3" style="width: 1000px">
    Welcome to Rocket<b>Posts</b> home page
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "templates/footer.php"
?>