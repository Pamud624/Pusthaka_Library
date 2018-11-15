<?php

   // $allow = "ADMIN";
   //  $PageTitle = "Bulk upload";
   //     // include('../inc/init.php'); 


   $connect=mysqli_connect("localhost","root","","test");
   $msg='';
   $query="SELECT * FROM users";
   $result= mysqli_query($connect,$query);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Members</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
  <br/>

  <div class="container">
    <h2 align="center">  Add List of Members </h2>

  </br>
  </br>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
     <tr>
    <th>Username</th>
    <th>Email</th>
    <th>Address</th>

    <tr>
    

</table>

  </div>


  </div>



</body>
</html>
