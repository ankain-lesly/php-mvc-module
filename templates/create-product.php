<form action="" method="post">
  <br>
  <input type="text" name="title" placeholder="Post title">
  <br>
  <input type="text" name="category" placeholder="Category">
  <br>
  <textarea name="body" placeholder="Post body..." cols="30" rows="5"></textarea>
  <br>
  <button>SUBMIT</button>
</form>
<br>

<?php
$errors = $errors ?? false;
$message = $message ?? false;
if ($errors) {
  $key = array_keys($errors)[0];
?>
  <div class="alert alert-danger" role="alert">

    <?= $message ?>
  </div>
<?php } ?>