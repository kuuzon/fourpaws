<!-- HEADER.PHP -->
<?php 
  require "./templates/header.php"
?>

  <main class="container p-4 bg-light mt-3">
    <form action="./app/controllers/createpost.inc.php" method="POST" enctype="multipart/form-data">
      <h2>Create Post</h2>
      <?php
        // VALIDATION: Check that Error Message Type exists in GET superglobal
        if(isset($_GET['error'])){
          // (i) Empty fields validation 
          if($_GET['error'] == "emptyfields"){
            $errorMsg = "Please fill in all fields";
          // (ii) Forbidden request
          } else if ($_GET['error'] == "forbidden") {
            $errorMsg = "Please submit the form correctly";
          // (iii) 500 Internal server error (sql or server)
          } else if ($_GET['error'] == "sqlerror" || $_GET['error'] == "servererror") {
            $errorMsg = "An internal server error has occurred - please try again later";
          }
          // (iv) ERROR CATCH-ALL:
          echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';

        // VALIDATION FOR UPLOADING:
        } else if(isset($_GET['uploaderror'])){
          // (i) PHP Error (you would write this out in full) 
          if($_GET['uploaderror'] == "ini-size" || $_GET['uploaderror'] == "form-size" || $_GET['uploaderror'] == "partial" || $_GET['uploaderror'] == "no-file" || $_GET['uploaderror'] == "tmp-dir" || $_GET['uploaderror'] == "cant-write" || $_GET['uploaderror'] == "extension" ){
            $errorMsg = "PHP upload error";
          // (ii) Incorrect file extension
          } else if ($_GET['uploaderror'] == "bad-ext") {
            $errorMsg = "Incorrect file extension";
          // (iii) Exceeds max file size
          } else if ($_GET['uploaderror'] == "file-size") {
            $errorMsg = "File exceeds max allowable size (2MB)";
          // (iv) File Already Exists
          } else if ($_GET['uploaderror'] == "file-exists") {
            $errorMsg = "File has already been uploaded";
          // (v) Failed upload
          } else if ($_GET['uploaderror'] == "system-error") {
            $errorMsg = "File has not uploaded correctly - please try again later";
          }
        }
      ?>
      
      <!-- 1. NAME -->
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" placeholder="Name" value="">
      </div>  

      <!-- 2. BREED -->
      <div class="mb-3">
        <label for="breed" class="form-label">Breed</label>
        <input type="text" class="form-control" name="breed" placeholder="Breed" value="">
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

      <!-- 4. IMAGE URL [FILE UPLOAD] -->
      <div class="mb-3">
        <label for="imageFile" class="form-label">Image URL</label>
        <input type="file" class="form-control" id="imageFile" name="imageFile">
      </div>

      <!-- 5. DESCRIPTION -->
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="3" placeholder="Description" ></textarea>
      </div>

      <!-- 6. LOCATION -->
      <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" name="location" placeholder="Location" value="" >
      </div>

      <!-- 7. SUBMIT BUTTON -->
      <button type="submit" name="post-submit" class="btn btn-primary w-100">Post</button>
    </form>
  </main>

<!-- FOOTER.PHP -->
<?php 
  require "./templates/footer.php"
?>