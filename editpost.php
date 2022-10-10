<!-- HEADER.PHP -->
<?php 
  require "templates/header.php"
?>

  <main class="container p-4 bg-light mt-3" style="width: 1000px">
    <?php
      // 1. Check User is Logged In + Id passed in via GET
      if(isset($_SESSION['userId']) && isset($_GET['id'])){
        // 3. Connect to DB
        require './includes/connect.inc.php';
  
        // 4. Declare variable called $row to store array with our DB data to display (later)
        $row;
  
        // 5. Collect, escape string & store POST data
        $id = mysqli_real_escape_string($conn, $_GET['id']); 
        $id = intval($id);
  
        // 6. Declare SQL command to extract data from DB relating to post id (Prepared Statements)
        // (i) Declare Template SQL with ? Placeholders to select values for SPECIFIC post id
        $sql = "SELECT title, imageurl ,comment, websiteurl, websitetitle FROM posts WHERE id=?";
  
        // (ii) Init SQL statement
        $statement = mysqli_stmt_init($conn);
  
        // (iii) Prepare + send statement to database to check for errors
        if(!mysqli_stmt_prepare($statement, $sql))
        {
          // ERROR: Something wrong when preparing the SQL
          // NOTE: Need to pass in the id BACK to url & the error message.  IMPORTANT - NOTE we are NOT going up a directory!
          header("Location: editpost.php?id=$id&error=sqlerror"); 
          exit();
        } else {
          // (iv) SUCCESS: Bind our user data with statement
          // NOTE: We bind ONE integer!
          mysqli_stmt_bind_param($statement, "i", $id);
  
          // (v) Execute the SQL Statement (to run in DB)
          mysqli_stmt_execute($statement);
  
          // (vi) SUCCESS: Store result & convert to array ($row declared above at 2.)
          $result = mysqli_stmt_get_result($statement);
          $row = mysqli_fetch_assoc($result);
  
        // 7. NOW: PRE-POPULATE data IF we have it from the $row variable in form below
        // NOTE: No need for an if statement, as you can ONLY access the editpost page, if you have passed the id & fired the script!
        }

      // 2. Restrict Access to Edit Page
      // NOTE: For example, to access this script, user MUST be LOGGED IN
      // IMPORTANT - NOTE we are NOT going up a directory!
      } else {
        header("Location: index.php");
        exit();
      }
    ?>

    <?php 
      // 15. DYNAMIC ERROR ALERTS FOR EDIT POST
      // NOTE: Very similar to createpost.php (as we also redirect successful post to posts.php!)
      if(isset($_GET['error'])){
        // (i) Empty fields validation 
        if($_GET['error'] == "emptyfields"){
          $errorMsg = "Please fill in all fields";

        // (ii) Internal server error 
        } else if ($_GET['error'] == "sqlerror") {
          $errorMsg = "An internal server error has occurred - please try again later";
        }

        // (iii) Dynamic Error Alert based on Variable Value 
        echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';

        // (iv). SUCCESS MESSAGE: Post updated successfully to DB -> NOT on this page.  We redirect them to posts.php, so we will need to add it there LATER!
      }
    ?>

    <!-- 8. Send ID via GET ALONG with our POST form data -->

    <!-- CURRENTLY: On hitting submit button, name of "edit-submit", the POST data will go to editpost.inc.php to call the script to update the DB -->

    <!-- HOWEVER: We have NOT passed along the id with the form data - currently only stored in a variable of $id in editpost.php   -->

    <!-- SOLUTION: We attach it to the action using php to make it dynamic -->
    <form action="includes/editpost.inc.php?id=<?php echo $id ?>" method="POST">
      <h2>Edit Post</h2>

      <!-- 1. TITLE -->
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" placeholder="Title" value="<?php echo $row['title'] ?>">
      </div>  

      <!-- 2. IMAGE URL -->
      <div class="mb-3">
        <label for="imageurl" class="form-label">Image URL</label>
        <input type="text" class="form-control" name="imageurl" placeholder="Image URL" value="<?php echo $row['imageurl'] ?>" >
      </div>

      <!-- 3. COMMENT SECTION -->
      <div class="mb-3">
        <label for="comment" class="form-label">Comment</label>
        <textarea class="form-control" name="comment" rows="3" placeholder="Comment"><?php echo $row['comment'] ?></textarea>
      </div>

      <!-- 4. WEBSITE URL -->
      <div class="mb-3">
        <label for="websiteurl" class="form-label">Website URL</label>
        <input type="text" class="form-control" name="websiteurl" placeholder="Website URL" value="<?php echo $row['websiteurl'] ?>" >
      </div>

      <!-- 5. WEBSITE TITLE -->
      <div class="mb-3">
        <label for="websitetitle" class="form-label">Website Title</label>
        <input type="text" class="form-control" name="websitetitle" placeholder="Website Title" value="<?php echo $row['websitetitle'] ?>" >
      </div>

      <!-- 6. SUBMIT BUTTON -->
      <button type="submit" name="edit-submit" class="btn btn-primary w-100">Edit</button>
    </form>
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "templates/footer.php"
?>