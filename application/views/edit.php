<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php

if($this->session->flashdata('item')) {
$message = $this->session->flashdata('item');
?>
<div class="<?php echo $message['class'] ?>"><?php echo $message['message']; ?>

</div>
<?php
}
?>
<?php 
 if(!empty($user)){
?>
<div class="container">
  <h2>Inline form</h2>
  <p>Make the viewport larger than 768px wide to see that all of the form elements are inline, left aligned, and the labels are alongside.</p>
  <form method="post" action="<?php echo base_url('Welcome/update');?>" enctype="multipart/form-data">
  <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" value="<?php echo $user[0]->name; ?>" placeholder="Enter name" name="name">
    </div><br>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" value="<?php echo $user[0]->email; ?>" id="email" placeholder="Enter email" name="email">
    </div><br>
    <div class="form-group">
      <label for="pwd">Mobile:</label>
      <input type="number" class="form-control" id="number" value="<?php echo $user[0]->mobile; ?>" placeholder="Enter Mobile Number" name="mobile">
    </div><br>
    <div class="form-group">
      <label for="image">Image:</label>
      <input type="file" class="form-control" id="image" value="<?php echo $user[0]->image; ?>" placeholder="Enter Mobile Number" name="image">
    </div><br>
   
    <button type="submit" class="btn btn-primary">Update</button>
    <?php } ?>
  </form>
</div>

</body>
</html>
