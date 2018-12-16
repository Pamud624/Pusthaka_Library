 <?php
 session_start();
  $allow = "ADMIN;LIBSTAFF";
   $PageTitle = "Backups";
  //include('../inc/init.php');


  //  if ($allow != "ALL"){
  // $found = false;
  // $arr = explode(";",$allow);

  // foreach($arr as $val){
  //   if( isset($_SESSION['CurrentUser']) && ($_SESSION['CurrentUser']['login_type'] == $val)){
  //     $found = true;
  //   }
  // }

  // if (!$found) {
  //   $_SESSION['BackLink'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
  //   $msg = 'You tried to access a page that requires you to login.<br>' . 
  //     'Please go to the <a href=index.php>home page</a> and login.<br>' . 
  //     '(you will be automatically redirected to the original page)';
  //   $title = 'Login Required';
  //   displayMsg($msg, $title);
  // }
//}

 ?>
<?php include("../inc/top.php"); ?>
 
<!DOCTYPE html>
<html>
 <head>
  <title>Pusthaka Library Backups</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 </head>
 <body>
  <br />
  <div class="container">
   <div class="row">
   <u><b> <h1 align="center"><font color="blue">Pusthaka Library Backups</font></h1></b></u>
    <br />

 <h3 align="center">Please click below image button for full data backup </h3>
<div align="center" >

   <a  href="download.php" download>
  <img align="center" src="images/backup.jpg" name="backup" alt="W3Schools" width="104" height="142">
</a>
</div>
    
  </body>
</html>
  
   </div>
  </div>
 </body>
</html>

</br>
</br>
</br>

<?php include("../inc/bottom.php"); 
//session_unset();
?>