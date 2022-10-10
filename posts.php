<!-- HEADER.PHP -->
<?php 
  require "templates/header.php"
?>

  <main class="container p-4 bg-light mt-3" style="width: 1000px">
    <?php
      // 1. QUERY DATABASE for ALL POSTS
      // NOTE: No need for prepared statements - NOT posting data from users to DB.  Simply requesting data from DB to be displayed = NO SQL INJECTIONS POSSIBLE!

      // (i) Connect to Database
      require './includes/connect.inc.php';

      // (ii) Declare SQL command to DB to retrieve ALL rows from posts table in DB
      $sql = "SELECT id, title, imageurl ,comment, websiteurl, websitetitle FROM posts";

      // (iii) Call query & store result in variable
      $result = mysqli_query($conn, $sql);
    ?>

    <?php 
      // DYNAMIC MESSAGE FOR POST CREATION [CHALLENGE!] / UPDATE (15)
      if(isset($_GET['post']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post created!
        </div>';  
      } else if(isset($_GET['edit']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post edited!
        </div>'; 
      }

      // DYNAMIC ERROR/SUCCESS MESSAGES FOR DELETE
      if(isset($_GET['error'])){
        // (i) Internal server error 
        if ($_GET['error'] == "sqlerror") {
          $errorMsg = "An internal server error has occurred - please try again later";
        }

        // (ii) Dynamic Error Alert based on Variable Value 
        echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';

      // (iii) Display SUCCESS message for correct login!
      } else if (isset($_GET['delete']) == "success"){
        echo '<div class="alert alert-success" role="alert">
          Post successfully deleted!
        </div>';    
      }
    ?>

    <?php
      // 2. CHECK FOR POSTS RETURNED RESULT & DISPLAY ON SUCCESS
      // (2.i) Success: Display Posts
      if(mysqli_num_rows($result) > 0){

        // 3. LOOP DATA INTO OUR BOOTSTRAP CARD TEMPLATE
        // (3.i) New variable with default state
        $output = "";

        // (3.ii) Take result -> convert to array & then insert into While Loop
        // NOTE: For EACH row in the array, we will execute this loop, so long as there are new rows - i.e. will execute for as many rows / positions in the array there are
        // NOTE: We make the output = our looping card HTML!
        while($row = mysqli_fetch_assoc($result)) {

          // (3.iv) Join output cards together with .=
          // NOTE: We can test - as now we have multiple cards (just needs to be dynamic!)
          $output .= 

          // (3.v) Dynamic Data into Cards using Concatenation of Variables
          // ORDER (from easiest): title, comment, id, image, alt (with title), websiteurl, websitetitle
          '
            <div class="card border-0 mt-3" id="' . $row['id'] . '">
              <img src="' . $row['imageurl'] . '" class="card-img-top post-image" alt="' . $row['title'] . '">
              <div class="card-body">
                <h5 class="card-title">' . $row['title'] . '</h5>
                <p class="card-text">' . $row['comment'] . '</p>
                <a href="' . $row['websiteurl'] . '" class="btn btn-primary w-100" target="_blank">' . $row['websitetitle'] . '</a>';
                
                // (3.vi) Restrict Edit/Delete Button ONLY to logged in users (can see by default!)
                // NOTE: We will use if statement to check userId set inside SESSION

                // (3.vii) Setup Edit/ Delete Pages
                // NOTE: Whilst pages don't function yet - we can set it up by passing in id of the post to the href url via query string
                if(isset($_SESSION['userId'])){
                  $output .= '
                  <div class="admin-btn">
                    <a href="editpost.php?id=' . $row['id'] . '" class="btn btn-secondary mt-2">Edit</a>
                    <a href="includes/deletepost.inc.php?id=' . $row['id'] . '" class="btn btn-danger mt-2">Delete</a>
                  </div>';
                }

            $output .= 
            '
              </div>
            </div>
            ';
        }
        // (3.iii) Echo out the result of the loop
        echo $output;
        // NOTE: PROBLEM - Every time loop runs, we essentially assign HTML to output.  This means, everytime we loop, we keep replacing output over and over with the latest card. 
        // FIXED: We can tell php to JOIN each card onto each other above in (iv) using ".="


      // (2.ii) Error: Template Error Message
      // NOTE: You can probably render a better error message
      } else {
        echo "0 results";
      }
      // (2.iii) Close Connection
      mysqli_close($conn);
    ?>
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "templates/footer.php"
?>