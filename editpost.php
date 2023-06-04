<!-- HEADER.PHP -->
<?php 
  require "./templates/header.php"
?>

  <main class="container p-4 bg-light mt-3">
    <?php
      // Check user logged in + post id present
      if(isset($_SESSION['userId']) && isset($_GET['pid'])){
        require './app/lib/connect.inc.php';
  
        // Page variables
        $row;
        $pid = $conn->real_escape_string($_GET['pid']); 
        $pid = intval($pid);
  
        // PRE-POPULATE POST 
        // (a) Template SQL Check
        $sql = "SELECT title, imageurl ,comment, websiteurl, websitetitle FROM posts WHERE pid=?";
        $statement = $conn->stmt_init();
        if(!$statement->prepare($sql)){
          header("Location: ./editpost.php?pid=$pid&error=sqlerror"); 
          exit();
        }

        // (b) Data Binding & Execution
        $statement->bind_param("i", $pid);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        
      // NON-LOGGED IN USER / FAILED PID RETRIEVAL
      } else {
        header("Location: ./index.php");
        exit();
      }
    ?>

    <form action="./app/controllers/editpost.inc.php?pid=<?php echo $pid ?>" method="POST">
      <h2>Edit Post</h2>
      <?php 
        // DYNAMIC ERROR ALERTS FOR EDIT POST
        if(isset($_GET['error'])){
          // (i) Empty fields validation 
          if($_GET['error'] == "emptyfields"){
            $errorMsg = "Please fill in all fields";

          // (ii) Internal server error 
          } else if ($_GET['error'] == "sqlerror" || $_GET['error'] == "servererror") {
            $errorMsg = "An internal server error has occurred - please try again later";
          }

          // (iii) Dynamic Error Alert based on Variable Value 
          echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
        }
      ?>

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
  require "./templates/footer.php"
?>