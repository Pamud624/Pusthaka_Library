

<?php


  $allow = "ADMIN";
    $PageTitle = "Bulk upload";
    include('../inc/init.php'); 

$connect=mysqli_connect("localhost","root","","pusthaka");

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
                
                $mem_no=mysqli_real_escape_string($connect,$data[0]);
              $type=mysqli_real_escape_string($connect,$data[1]);
              $surname=mysqli_real_escape_string($connect,$data[2]);
              $firstnames=mysqli_real_escape_string($connect,$data[3]);
              $title=mysqli_real_escape_string($connect,$data[4]);
              $address=mysqli_real_escape_string($connect,$data[5]);
              $nic=mysqli_real_escape_string($connect,$data[6]);
              $reg_no=mysqli_real_escape_string($connect,$data[7]);
              $sex=mysqli_real_escape_string($connect,$data[8]);
              $phone=mysqli_real_escape_string($connect,$data[9]);
              $email=mysqli_real_escape_string($connect,$data[10]);
              $index_no=mysqli_real_escape_string($connect,$data[11]);
              $username=mysqli_real_escape_string($connect,$data[12]);
              $password=mysqli_real_escape_string($connect,$data[13]);
              $login_type=mysqli_real_escape_string($connect,$data[14]);
              $category=mysqli_real_escape_string($connect,$data[15]);
              $barcode=mysqli_real_escape_string($connect,$data[16]);
              $expired=mysqli_real_escape_string($connect,$data[17]);


             $query = "UPDATE member SET type='$type', surname='$surname', firstnames='$firstnames',title=$title , address='$address', nic='$nic', reg_no='$reg_no', sex='$sex',phone='$phone',email='$email', index_no='$index_no', username='$username',password='$password',login_type='$login_type',category='$category',barcode='$barcode',expired='$expired' WHERE mem_no= '$mem_no'";
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

$query="SELECT * FROM member ORDER BY mid DESC";
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
 

 <div class="well" style="border: solid black 2px">
  <div><p class="h3"  align="center"><b>Pusthaka Library Bulk List Upload </b></p> </div>
</br>
  <form method="post" enctype="multipart/form-data">
   <div align="center">  

    <label>Select CSV File:</label>
    <input   type="file" name="file" />
    </br>
    <input type="submit" name="submit" value="Upload" class="btn btn-info" />



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
   <div class="well" style="border: solid black 2px">
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
             <td>'.$row["firstnames"].' '.$row["surname"].'</td>
             <td>'.$row["email"].'</td>
              <td>'.$row["address"].'</td>
                           <td>'.$row["nic"].'</td>


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
















