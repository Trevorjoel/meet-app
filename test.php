<?php
include_once('php_functions.php');
// settings
$max_file_size = 1024*200; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
$sizes = array(100 => 100, 150 => 150, 250 => 250);

if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_FILES['new_picture'])) {
  if( $_FILES['new_picture']['size'] < $max_file_size ){
    // get file extension
    $ext = strtolower(pathinfo($_FILES['new_picture']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $valid_exts)) {
      /* resize image */
      foreach ($sizes as $w => $h) {
        $files[] = resize($w, $h);
      }

    } else {
      $msg = 'Unsupported file';
    }
  } else{
    $msg = 'Please upload image smaller than 200KB';
  }
}
?>
<html>
<head>
  <title>Image resize while uploadin</title>
<head>
<body>
  <!-- file uploading form -->
  <form action="" method="post" enctype="multipart/form-data">
    <label>
      <span>Choose image</span>
      <input type="file" name="image" accept="image/*" />
    </label>
    <input type="submit" value="Upload" />
  </form>
</body>
</html>