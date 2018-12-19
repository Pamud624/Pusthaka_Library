 <?php
  $allow = "ADMIN";
  $PageTitle = "Configuration";
  include('../inc/init.php');


   if(isset($_REQUEST['BtnUpdate2'])){
    $value = $_REQUEST['value'];
    $value2 = $_REQUEST['value2'];
    $value3 = $_REQUEST['value3'];
    $value4 = $_REQUEST['value4'];
    
  
      $sql = sprintf("update config1 set value=%f ,value2=%f ,value3=%f ," .
        "value4=%f WHERE id=1", $value, $value2, $value3, $value4);
      $a = executeSqlNonQuery($sql);
      $rowcount = $a['rows'];
      if ($rowcount <> 1) {
       // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
        //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
         header("Location: config300.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config300.php");
        exit();
      }
  } //END: update my_info


  // Change Password ////////////////////////////////////////////////////

 // $row = $mem->getByID($_SESSION['CurrentUser']['mid']);
 

        //[Retrieve data] ------------------------------




/////////////////////////////////////////////////////////////////////////////////////////////////////

        $sql = "SELECT * FROM config1 WHERE id =1";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row = mysqli_fetch_assoc($recordset);




         $sql = "SELECT * FROM lending_settings WHERE id =12";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row1 = mysqli_fetch_assoc($recordset);

          $sql = "SELECT * FROM lending_settings WHERE id =3";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row2 = mysqli_fetch_assoc($recordset);



  $sql = "SELECT * FROM lending_settings WHERE id =4";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row3 = mysqli_fetch_assoc($recordset);


  $sql = "SELECT * FROM lending_settings WHERE id =22";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row4 = mysqli_fetch_assoc($recordset);



  $sql = "SELECT * FROM lending_settings WHERE id =10";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row5 = mysqli_fetch_assoc($recordset);


  $sql = "SELECT * FROM lending_settings WHERE id =9";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row6 = mysqli_fetch_assoc($recordset);


  $sql = "SELECT * FROM lending_settings WHERE id =13";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row7 = mysqli_fetch_assoc($recordset);


  $sql = "SELECT * FROM lending_settings WHERE id =14";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row8 = mysqli_fetch_assoc($recordset);


  $sql = "SELECT * FROM lending_settings WHERE id =15";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row9 = mysqli_fetch_assoc($recordset);

  $sql = "SELECT * FROM lending_settings WHERE id =16";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row10 = mysqli_fetch_assoc($recordset);

  $sql = "SELECT * FROM lending_settings WHERE id =23";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row11 = mysqli_fetch_assoc($recordset);

  $sql = "SELECT * FROM lending_settings WHERE id =24";
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
        $row12 = mysqli_fetch_assoc($recordset);




?>

<?php
   if(isset($_REQUEST['pll'])){
   header("Location: config.php");
  }elseif (isset($_REQUEST['pllc'])) {
    header("Location: config2.php");  
  }elseif (isset($_REQUEST['all'])) {
    header("Location: config5.php");  
  }elseif (isset($_REQUEST['allc'])) {
    header("Location: config6.php");  
  }elseif (isset($_REQUEST['inl'])) {
    header("Location: config9.php");  
  }elseif (isset($_REQUEST['inlc'])) {
    header("Location: config10.php");  
  }elseif (isset($_REQUEST['rsl'])) {
    header("Location: config7.php");  
  }elseif (isset($_REQUEST['rslc'])) {
    header("Location: config8.php");  
  }elseif (isset($_REQUEST['4yl'])) {
    header("Location: config11.php");  
  }elseif (isset($_REQUEST['4ylc'])) {
    header("Location: config12.php");  
  }elseif (isset($_REQUEST['stl'])) {
    header("Location: config3.php");  
  }elseif (isset($_REQUEST['stlc'])) {
    header("Location: config4.php");  
  }
    




if(isset($_REQUEST['cancel'])){
   header("Location: index.php");
 }


?>






<?php include("../inc/top.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><b> <font color="blue"><p style="font-size: 35px;" align="center" >Configuration Settings Pusthaka Library</p></font><b></tr>
		<tr>&nbsp;</tr>
		<tr>&nbsp;</tr>
		<tr>&nbsp;</tr>
		
		

  <tr>
    <td class="margin"><img src="images/configur.gif" width="200" height="150">
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

<td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td>
    <td>&nbsp;</td>    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>



<!----------------------------------------------------------------------------------------------------->

<td>


	<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
    <?php 
      echo stripcslashes($_SESSION['msg']);
      unset($_SESSION['msg']);
    ?>
  </td>
  </tr>

</table>
<?php } ?>




<!--------------------------------------------------------------------------------------->
<div class="row" align="center">

  <div class="span11">
    <form form action="config300.php" method="post" name="my_info"  id="my_info">
<div class="well">

  <div>
     <p class="h3" align="center"><b>Fine Payment Settings</b></p>     
   </div>
   <div class="well">
   <div class="row">
 <div class="span3"> <b><label><font color="blue">Fine for first day (Rs)</font></label> </b></div> 
     <div class="span1">
    <input name="value" type="text" id="value" class="value" value="<?php echo $row['value']; ?>" size="15"  style="width: 100px;"></div>
   </div>

    <div class="row">
 <div class="span3">  <b><label><font color="blue">Fine per day   (Rs)</font></label> </b></div>

   <div class="span1"> <input name="value2" type="text" id="value2" class="value2" value="<?php echo $row['value2']; ?>" size="15"  style="width: 100px;"></div>
   <div class="span3"><input name="BtnReset" class="btn btn-info" type="reset" id="BtnReset" value="Reset" style="width: 100px;"></div>
   <div class="span1"><input name="BtnUpdate2" type="submit"  class="btn btn-info" id="BtnUpdate2" value="Save Changes"></div>

   </div>
   <div class="row">
 <div class="span3">  <b><label><font color="blue">Fine per hour(Rs)</font></label> </b></div>

    <div class="span1"><input name="value3" type="text" id="value2" class="value3" value="<?php echo $row['value3']; ?>" size="15"  style="width: 100px;"></div>



   </div>

   <div class="row">
  <div class="span3"> <b><label><font color="blue">Start time</font></label> </b></div>

   <div class="span1"> <input name="value4" type="text" id="value4" class="value4" value="<?php echo $row['value4']; ?>" size="15"  style="width: 100px;"></div>
   </div>
</div>

  
</div>
</form>
</div>
</div>

<!------------------------------------------------------------------------------------------------------------->



<!------------------------------------------------------------------------------------------------------------->



<div class="row" align="center">

<!------------------------------------------------------------------------------------------------------------->

<!------------------------------------------------------------------------------------------------------------->






<!------------------------------------------------------------------------------------------------------------->





  <div class="span11">

    <form form action="config300.php" method="post" name="my_info"  id="my_info">
<div class="well">

   <div>
     <p class="h3" align="center"><b>User Privilege Settings</b></p>     
   </div>

     <div class="well">
   <table class="table table-striped">
  <thead>
    <tr>
      
      <th scope="col">User Category</th>
      <th scope="col">Lending</th>
      <th scope="col">Lecture Copy</th>
    </tr>
  </thead>
  <tbody>
   
    <tr>
      
      <td>Permenant Lecture</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row2['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="pll" id="pll"  >Set</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row10['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info"name="pllc" id="pllc">Set</button></td>
    </tr>
    <tr>
      
      <td>Assistant Lecture</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row3['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="all" id="all">Set</button></td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row9['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="allc" id="allc">Set</button></td>
    </tr>

    <tr>
      
      <td>Instructor</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row5['privile']; ?>" size="90" style="width: 100px;" readonly> <button type="submit" class="btn btn-info" name="inl" id="inl">Set</button></td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row7['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="inlc" id="inlc" >Set</button></td>
    </tr>

    <tr>
      
      <td>Reserch Student</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row4['privile']; ?>" size="90" style="width: 100px;" readonly> <button type="submit" class="btn btn-info" name="rsl" id="rsl">Set</button></td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row8['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="rslc" id="rslc">Set</button></td>
    </tr>
    <tr>
      
      <td>4th Year Student</td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row11['privile']; ?>" size="90" style="width: 100px;" readonly> <button type="submit" class="btn btn-info" name="4yl" id="4yl">Set</button></td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row12['privile']; ?>" size="90" style="width: 100px;" readonly>  <button type="submit" class="btn btn-info" name="4ylc" id="4ylc">Set</button></td>
    </tr>

     <tr>
      
      <td>Student</td>
      <td ><input name="privile" type="text" id="privile" value="<?php echo $row6['privile']; ?>" size="90" style="width: 100px;" readonly> <button type="submit" class="btn btn-info" name="stl" id="stl">Set</button></td>
      <td><input name="privile" type="text" id="privile" value="<?php echo $row1['privile']; ?>" size="90" style="width: 100px;" readonly> <button type="submit" class="btn btn-info" name="stlc" id="stlc">Set</button></td>
    </tr>
  </tbody>
</table>


<div class="row">
    <div class="span10">
       <div class="span6" style="width: 1300px;">
       
        <input type="submit" name="cancel" id="cancel" class="btn btn-info" value="Cancel" />
     </div>
  </div>
</div>
</div>


</div>


</form>

</div>
</div>




<!-- <script >
  
  $('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('New message to ' + recipient)
  modal.find('.modal-body input').val(recipient)
})
</script> -->





<script >
  jQuery('.value').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
</script>





<?php include("../inc/bottom.php"); ?>



<!------------------------------------------------------------------------------------------------------------------------------------>


	
