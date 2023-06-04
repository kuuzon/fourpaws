<!-- HEADER.PHP -->
<?php 
  require "./templates/header.php"
?>

  <main class="container p-4 bg-light mt-3">
    <?php
      // 1. QUERY DATABASE for ALL POSTS
      require './app/lib/connect.inc.php';
      $sql = "SELECT pid, title, imageurl ,comment, websiteurl, websitetitle FROM posts";
      $result = $conn->query($sql);
    ?>

    <?php 
      // ERROR: ON DELETION OF POST 
      if(isset($_GET['error'])){
        // (i) Internal server error 
        if ($_GET['error'] == "sqlerror" || $_GET['error'] == "servererror") {
          $errorMsg = "An internal server error has occurred - please try again later";
        }

        // (ii) Dynamic Error Alert based on Variable Value 
        echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
      
      // SUCCESS: POST CREATE
      } else if(isset($_GET['post']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post created!
        </div>';  

      // SUCCESS: POST EDIT 
      } else if(isset($_GET['edit']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post edited!
        </div>'; 

      // SUCCESS: POST DELETE
      } else if (isset($_GET['delete']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post successfully deleted!
        </div>';    
      }
    ?>

    <?php
      // 2. CHECK FOR POSTS RETURNED RESULT & DISPLAY LOOP ON SUCCESS
      if($result->num_rows > 0){
        $output = "";
        while($row = $result->fetch_assoc()) {
          $output .= 
          '
            <div class="card border-0 mt-3" pid="' . $row['pid'] . '">
              <img src="' . $row['imageurl'] . '" class="card-img-top post-image" alt="' . $row['title'] . '">
              <div class="card-body">
                <h5 class="card-title">' . $row['title'] . '</h5>
                <p class="card-text">' . $row['comment'] . '</p>
                <a href="' . $row['websiteurl'] . '" class="btn btn-primary w-100" target="_blank">' . $row['websitetitle'] . '</a>';
                
                // ADMIN FEATURES:
                if(isset($_SESSION['userId'])){
                  $output .= '
                  <div class="admin-btn">
                    <a href="editpost.php?pid=' . $row['pid'] . '" class="btn btn-secondary mt-2">Edit</a>
                    <a href="./app/controllers/deletepost.inc.php?pid=' . $row['pid'] . '" class="btn btn-danger mt-2">Delete</a>
                  </div>';
                }

            $output .= 
            '
              </div>
            </div>
            ';
        }
        echo $output;
      } else {
        echo '<div class="alert alert-warning" role="alert">
          Nothing posted? Create a new post <a href="./createpost.php">here</a>!
        </div>';
      }
      $conn->close();
    ?>
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "./templates/footer.php"
?>