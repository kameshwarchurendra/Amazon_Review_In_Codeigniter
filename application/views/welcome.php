<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Add Amazon Review</h2>
  <?php

if($this->session->flashdata('item')) {
$message = $this->session->flashdata('item');
?>
<div class="<?php echo $message['class'] ?>">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<?php echo $message['message']; ?>

</div>
<?php
}?>
  <form method="post" action="<?php echo base_url('Welcome/add');?>" enctype="multipart/form-data">
  <div class="form-group">
      <label for="name">Select CSV File:</label>
      <input type="file" name="csv_file[]"  id="csv_file" multiple  required>
    </div><br>
    <button type="submit" class="btn btn-primary">Add Review</button>
  </form>
</div>

</body>
</html>
