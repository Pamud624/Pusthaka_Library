

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  
    <tr>&nbsp;</tr>
    <tr>&nbsp;</tr>
    <tr>&nbsp;</tr>
    
    

  <tr>
    <td class="margin"><img src="images/csvupload.png" width="200" height="150">
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="my_loans.php" class="menuLink">my loans </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_reservations.php" class="menuLink">my reservations </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_history.php" class="menuLink">my history </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_search.php" class="menuLink">opac</a></td>
      </tr>
    </table>
        <table width="100%"  border="0">
          <tr>
            <td align="center" class="marginLogin">
              <?php if (isset($_SESSION['CurrentUser'])){ 
        echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
        echo "<a href='_login.php'>logout</a>";
      } ?>
            </td>
          </tr>
        </table>
    </td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>



<td>

 <div class="row" align="center">
 

 <div class="well">
  <div><p class="h3"  align="center"><b>Pusthaka Library Bulk List Upload </b></p> </div>
</br>
  <form method="post" enctype="multipart/form-data">
   <div align="center">  

    <label>Select CSV File:</label>
    <input   type="file" name="file" />
    </br>
    <input type="submit" name="submit" value="Import" class="btn btn-info" />



     <div class="container">
   
   <br />
   <!-- <form method="post" enctype='multipart/form-data'>
    <p><label>Please Select File(Only CSV Formate)</label>
    <input type="file" name="member_file" /></p>
    <br />
    <input type="submit" name="upload" class="btn btn-info" value="Upload" />
   </form> -->
   <br />
   <?php echo $message; ?>
   <div class="well">
  <div><p class="h3"  align="center"><b> Details of the members </b></p> </div>
   
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
   </div>
  </form>
</div>
  </div>
















