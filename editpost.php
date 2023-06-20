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
        $sql = "SELECT name, breed, gender, imagename, imagepath, description, location FROM posts WHERE pid=?";
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

      <!-- 1. NAME -->
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $row['name'] ?>">
      </div>  

      <!-- 2. BREED -->
      <div class="mb-3">
        <label for="breed" class="form-label">Breed</label>
        <input type="text" class="form-control" name="breed" placeholder="Breed" value="<?php echo $row['breed'] ?>">
      </div>  

      <!-- 3. GENDER -->
      <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" aria-label="Select gender" name="gender">
          <option selected>Choose gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>        

      <!-- 4. IMAGE UPLOADER: Disabled until built -->
      <div class="alert alert-info" role="alert">Image Uploader disabled until built</div>
      <div class="text-center">
        <img style="width:200px;" src="<?php echo $row['imagepath'] ?>" alt="<?php echo $row['name'] ?>">
      </div>

      <!-- 5. DESCRIPTION -->
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="3" placeholder="Description"><?php echo $row['description'] ?></textarea>
      </div>

      <!-- 6. LOCATION -->
      <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" name="location" placeholder="Location" value="<?php echo $row['location'] ?>">
      </div>

      <!-- 7. SUBMIT BUTTON -->
      <button type="submit" name="edit-submit" class="btn btn-primary w-100">Edit</button>
    </form>
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "./templates/footer.php"
?>