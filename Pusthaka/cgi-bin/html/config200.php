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
         header("Location: config200.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config200.php");
        exit();
      }
  } //END: update my_info


  // Change Password ////////////////////////////////////////////////////

 // $row = $mem->getByID($_SESSION['CurrentUser']['mid']);
 

        //[Retrieve data] ------------------------------

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



?>

<?php
if(isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='STUDENT' ){
	
	 	if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config3.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config4.php");
	}
			# code...
		
	
	}

	elseif (isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='PER_LEC'){
		if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config2.php");
	}
			# code...
		}

	elseif (isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='RES_STUDENT'){
		if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config7.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config8.php");
	}
			# code...
		}

	elseif (isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='ASS_LEC'){
		if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config5.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config6.php");
	}
			# code...
		}

	elseif (isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='INSTRUCTER'){
		if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config9.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config10.php");
	}
			# code...
		}
	elseif (isset($_REQUEST['login_type']) && $_REQUEST['login_type']=='4TH_STUDENT'){
		if(isset($_REQUEST['BtnUpdate3'])){
		header("Location: config11.php");}
		elseif(isset($_REQUEST['BtnUpdate4'])){
		header("Location: config12.php");
	}
			# code...
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
	<form action="config200.php" method="post" name="my_info" class="formNormal" id="my_info" style="height: 206px; width: 456px;">
	

    <table border="0" cellpadding="0" cellspacing="0"  >

    

    	<tr>&nbsp; </tr>
    	<tr>&nbsp;</tr>
    	<tr>&nbsp;</tr>


 <tr>
 <u><p align="center" style="font-size: 20px;"> <b><font> Fine Payment Settings</font></b></p></u>
  <tr>

  	<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   <tr>
  

    <td><b><p ><font color="blue">Fine for first day (Rs)</font></p></b></td>
    <td colspan="5"><input name="value" type="text" id="value" class="value" value="<?php echo $row['value']; ?>" size="15"  style="width: 100px;"></td>
       
    </tr>

    <tr>

    <td><b><p><font color="blue">Fine per day   (Rs)</font></p></b></td>
    <td><input name="value2" type="text" id="value2" value="<?php echo $row['value2']; ?>" size="15"  style="width: 100px;"></td>
   <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>


        <td><input name="BtnReset" type="reset" id="BtnReset" value="Reset" style="width: 100px;"></td>
    

  </tr>

  <tr>
    <td><b> <p><font color="blue">Fine per hour(Rs)</font></p></b></td>
    <td><input name="value3" type="text" id="value3" value="<?php echo $row['value3']; ?>" size="15"  style="width: 100px;"></td>
     

  </tr>

  <tr>
    <td><b><p><font color="blue">Start time</font></p></b></td>
    <td><input name="value4" type="text" id="value4" value="<?php echo $row['value4']; ?>" size="15" style="width: 100px;"></td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
     
        <td><input name="BtnUpdate2" type="submit" class="submit" id="BtnUpdate2" value="Save Changes"></td>


  </tr>

<!-- <tr>
     <td>&nbsp;</td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
    <td><input name="BtnUpdate2" type="submit" class="submit" id="BtnUpdate2" value="Save Changes"></td>
    <td>&nbsp;</td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
   
  </tr> -->

</table>


</form>

</td>
<!-------------------------------------------------------------------------------------------------------->

	   
   

<!------------------------------------------------------------------------------------------------------------->
<td>
	
<form action="config200.php" method="post" name="my_info" class="formNormal" id="my_info" style="height: 206px; width: 450px;">
    <table border="0" cellpadding="0" cellspacing="0" >
    	<tr>&nbsp; </tr>
    	<tr>&nbsp;</tr>
    	<tr>&nbsp;</tr>


   <tr>

  <u><p align="center" style="font-size: 20px;"> <b><font> User priviledge Settings</font></b></p></u>
</tr>
<tr>&nbsp; </tr>
    	<tr>&nbsp;</tr>
    	<tr>&nbsp;</tr>

<tr >
<td>
	<p > <b><font color="blue">User Category</font></b></p>

<td>&nbsp;</td>
<td>&nbsp;</td>
    <td>&nbsp;</td>
    

<td>
	<p> <b><font color="blue">lending </font></b></p>

</td>

<td>&nbsp;</td>
<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

<td>
	<p> <b><font color="blue">lecture copy</font></b></p>

</td>

</tr>
<tr>

<td>
	<select name="login_type" id="select2" style="width: 150px;">
                          <option value='STUDENT'>STUDENT</option>
                          <option value='PER_LEC' >PER_LEC</option>
                          <option value='ASS_LEC' selected>ASS_LEC</option>
                          <option value='RES_STUDENT' selected>RES_STUDENT</option>
                          <option value='INSTRUCTER' selected>INSTRUCTER</option>
                          <option value='4TH_STUDENT' selected>4TH_STUDENT</option>
                         
                        </select>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>

	<td >
		<input name="BtnUpdate3" type="submit" class="submit" id="BtnUpdate3" value="lending">
	</td>



<td>&nbsp;</td>
<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>


	<td  >
		<input name="BtnUpdate4" type="submit" class="submit" id="BtnUpdate4" value="lecture copy">
	</td>



</tr>

<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>

</table>
</form>

</td>
<!---------------------------------------------------------------------------------------------------------->


</tr>
</table>
<script >
  jQuery('.value').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
</script>
<?php include("../inc/bottom.php"); ?>



<!------------------------------------------------------------------------------------------------------------------------------------>


	
