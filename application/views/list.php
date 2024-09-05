<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Member List</h2>
  <a href="<?php echo base_url('/');?>"> <button class="btn btn-success" style="float:right">Add Member</button></a>
 
  <?php

if($this->session->flashdata('item')) {
$message = $this->session->flashdata('item');
?>
<div class="<?php echo $message['class'] ?>"><?php echo $message['message']; ?>

</div>
<?php
}

?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($user as $i => $val){ ?>
      <tr>
        <td><?php echo $val->name; ?></td>
        <td><?php echo $val->email; ?></td>
        <td><?php echo $val->mobile; ?></td>
        <td><a href="<?php echo base_url('Welcome/edit?id=').$val->id; ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
        <a href="<?php echo base_url('Welcome/delete?id=').$val->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i>
</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>
