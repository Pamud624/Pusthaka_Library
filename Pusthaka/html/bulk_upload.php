<?php

   // $allow = "ADMIN";
   //  $PageTitle = "Bulk upload";
   //     // include('../inc/init.php'); 


   $connect=mysqli_connect("localhost","root","","test");
   $msg='';

   if(isset($_POST["upload"]))
  {

     if ($_FILES['member_file']['name'])
      {
     	
           $filename = explode(".",$_FILES['member_file']['name']);
           if (end($filename)== "csv") 
           {
           	$handle = fopen($_FILES['member_file']['temp_name'], "r");
            while ($data =fgetcsv($handle)) {
                
                $id=mysqli_real_escape_string($connect,$data[0]);
            	$username=mysqli_real_escape_string($connect,$data[1]);
            	$email=mysqli_real_escape_string($connect,$data[2]);
            	$address=mysqli_real_escape_string($connect,$data[3]);

             $query = "UPDATE users SET username='$username', email='$email', address='$address' WHERE id= '$id '";
             mysqli_query($connect,$query)
            	
            }

           }
     

     else
     { 

       $message ='<label class ="text-danger"> Please select the csv file only</label>';
     }

  }




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

  <br/>
   
   <form method="form" enctype='mutipart/form-data'>
   	 <p><label>Please Select file(only csv format)</label>
   	 <input type="file" name="member_file"></p>
   	 <br/>
     <input type="submit" name="upload" class="btn btn-info" value="Upload">



   </form>

  <br/>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
     <tr>
    <th>Username</th>
    <th>Email</th>
    <th>Address</th>

    <tr>
      <?php

        while ($row= mysqli_fetch_array($result)) {


          echo '
           <tr>

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
