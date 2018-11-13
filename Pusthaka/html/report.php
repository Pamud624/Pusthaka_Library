<?php
//        $allow = "ADMIN;LIBSTAFF";
        $allow = "ADMIN";
		$PageTitle = "Report";
        include('../inc/init.php');	

  $connect1= mysqli_connect("localhost","root","","pusthaka");
  $query="SELECT * FROM lending_settings ORDER BY id desc";
  $result=mysqli_query($connect1 , $query );	

      
?>
<?php include("../inc/top.php"); ?>
<!DOCTYPE html>
<html>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
      <tr>
        <td><img src="images/download.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
           &nbsp;
          </td>
        </tr>
      </table>
	  
	  <table width="100%"  border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    <td>

<!-- <form action="report.php" method="post" name="book_add" class="formNormal" id="book_add">
 --><!-- <table border="0" cellpadding="0" cellspacing="0" class="edit_master">
 -->
  <div class="container" style="width: 900px;">
    <u><h1 align="center" >Get the reports</h1></u>
      <h3 align="center" >Lending data reports</h3>
      <form method="post" action="export.php">
        <input type="submit" name="export" value="CSV export" class="btn btn-info" />
        

      </form>
    </br></br>
   

    <div class="table-responsive" id="lending_settings">
      <table class="table table-bordered">
        <tr>
          <th width="5%"> id</th>
          <th width="5%"> allowed</th>
          <th width="5%"> book type</th>
          <th width="5%"> num allowed </th>
          <th width="5%"> days allowd</th>
          <th width="5%"> member type</th>


        </tr>
     <?php
     while ($row = mysqli_fetch_array($result))
      {
     ?>
         <tr>
          <th > <?php echo $row["id"]; ?></th>
          <th > <?php echo $row["allowed"]; ?></th>
          <th > <?php echo $row["book_type"]; ?></th>
          <th > <?php echo $row["num_allowed"]; ?></th>
          <th > <?php echo $row["days_allowed"]; ?></th>
          <th > <?php echo $row["member_type"]; ?></th>

         </tr>
         <?php
       }
       ?> 
      </table>



    
      



    </div>


  </div>
  
</table>
      </form>



    </td>
  </tr>
</table>
</html>
<?php include("../inc/bottom.php"); ?>

