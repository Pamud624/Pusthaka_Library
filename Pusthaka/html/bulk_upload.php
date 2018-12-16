

<?php


  $allow = "ADMIN";
    $PageTitle = "Bulk upload";
    include('../inc/init.php'); 

$connect=mysqli_connect("localhost","root","","test");

$message = '';

if(isset($_POST["upload"]))
{
 if($_FILES['member_file']['name'])
 {
  $filename = explode(".", $_FILES['member_file']['name']);
  if(end($filename) == "csv")
  {
   $handle = fopen($_FILES['member_file']['tmp_name'], "r");
   while($data = fgetcsv($handle))
       while ($data =fgetcsv($handle)) {
                
                $mid=mysqli_real_escape_string($connect,$data[0]);
              $username=mysqli_real_escape_string($connect,$data[1]);
              $email=mysqli_real_escape_string($connect,$data[2]);
              $address=mysqli_real_escape_string($connect,$data[3]);

             $query = "UPDATE users SET username='$username', email='$email', address='$address' WHERE mid= '$mid '";
             mysqli_query($connect,$query);
              
            }
   fclose($handle);
 header("location:bulk_upload.php?updation=1");
  }
  else
  {
   $message = '<label class="text-danger">Please Select CSV File only</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">Please Select File</label>';
 }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">Product Updation Done</label>';
}

$query="SELECT * FROM users";
$result = mysqli_query($connect, $query);
?>
<?php include("../inc/top.php"); ?>
<!DOCTYPE html>
<html>
 <head>
  <title>Update Mysql Database through Upload CSV File using PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <u><h2 align="center"><b>Add List of Members</b></a></h2></u>
   <br />
   <form method="post" enctype='multipart/form-data'>
    <p><label>Please Select File(Only CSV Formate)</label>
    <input type="file" name="member_file" /></p>
    <br />
    <input type="submit" name="upload" class="btn btn-info" value="Upload" />
   </form>
   <br />
   <?php echo $message; ?>
   <h3 align="center">Deals of the Members</h3>
   <br />
   <div class="table-responsive">
    <table class="table table-bordered table-striped">
     <tr>
      <th>ID</th>
       <th>Username</th>
    <th>Email</th>
    <th>Address</th>
     </tr>
     <?php
     while($row = mysqli_fetch_array($result))
     {
     echo '
           <tr>
             <td>'.$row["mid"].'</td>
             <td>'.$row["username"].'</td>
             <td>'.$row["email"].'</td>
              <td>'.$row["address"].'</td>

           </tr>
         ';
     }
     ?>
    </table>
   </div>
  </div>
 </body>
</html>

<?php include("../inc/bottom.php"); ?>