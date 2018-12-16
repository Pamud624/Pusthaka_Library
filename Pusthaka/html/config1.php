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
         header("Location: config1.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config1.php");
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
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    </td><td>

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

<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<h1>Configuration Settings</h1>
<form action="config1.php" method="post" name="my_info" class="formNormal" id="my_info">  

<table border="0" cellpadding="0" cellspacing="0">




  <td>
<tr>

  <u><p> <b><font> User priviledge Settings</font></b></p></u>




</tr>
<tr>
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
</tr>


  
  <td><p> <b><font color="blue">User Category</font></b></p></td>
   
  
 <td ><p><b><font color="blue">Book Type(L)</font></b></p></td>   
 
    <td>&nbsp;</td>
    
    <!-- <td align="center" valign="middle"><a href="config4.php">Lecture Copy</a></td> -->
    <td ><p><b><font color="blue">Book Type(LC)</font></b></p></td>   
  

  <td>&nbsp;</td>

  </tr>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

  <tr>



<td> Student</td>


  
    <td align="center" valign="middle"><a href="config3.php">Lending</a></td>
     <td>&nbsp;</td>
   <!--  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> -->

    
    <!-- <td align="center" valign="middle"><a href="config4.php">Lecture Copy</a></td> -->
    <td><p>Not allowed for this category</p></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

  <tr>
<td> Assitant Lecture</td>


  
<td align="center" valign="middle"><a href="config5.php">Lending</a></td>
 <td>&nbsp;</td>
   <!--  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> -->
<td align="center" valign="middle"><a href="config6.php">Lecture Copy</a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

  <tr>
<td> Permanant Lecture</td>


  
<td align="center" valign="middle"><a href="config.php">Lending</a></td>
    <td>&nbsp;</td>
  
  <!--   <td>&nbsp;</td>
    <td>&nbsp;</td>
  -->
<td align="center" valign="middle"><a href="config2.php">Lecture Copy</a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>


  <tr>
<td>Research Student</td>

  
<td align="center" valign="middle"><a href="config7.php">Lending</a></td>
 <td>&nbsp;</td>
   <!--  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> -->
    
<!-- <td align="center" valign="middle"><a href="config8.php">Lecture Copy</a></td> -->
<td><p>Not allowed for this category</p></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>


  <tr>
<td>Instructor</td>


  
<td align="center" valign="middle"><a href="config9.php">Lending</a></td>
 <td>&nbsp;</td>
    <!-- <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> -->
   
<!-- <td align="center" valign="middle"><a href="config10.php">Lecture Copy</a></td> -->
<td><p>Not allowed for this category</p></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

</tr>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  <tr>
<td>4rth Year Students</td>


  
<td align="center" valign="middle"><a href="config11.php">Lending</a></td>
 <td>&nbsp;</td>
    <!-- <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> -->
   
<!-- <td align="center" valign="middle"><a href="config12.php">Lecture Copy</a></td> -->
<td><p>Not allowed for this category</p></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

</tr>

<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

    <!----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
  <tr>
<td><b><u><font>Fine Payment Settings</font></u></b>
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
    <td>Fine for first day (Rs)</td>
    <td colspan="5"><input name="value" type="text" id="value" value="<?php echo $row['value']; ?>" size="15"  style="width: 100px;"></td>
    </tr>


  <tr>
    <td>Fine per day   (Rs)</td>
    <td><input name="value2" type="text" id="value2" value="<?php echo $row['value2']; ?>" size="15"  style="width: 100px;"></td>
  </tr>

  <tr>
    <td>Fine per hour(Rs)</td>
    <td><input name="value3" type="text" id="value3" value="<?php echo $row['value3']; ?>" size="15"  style="width: 100px;"></td>
  </tr>

  <tr>
    <td>Start time</td>
    <td><input name="value4" type="text" id="value4" value="<?php echo $row['value4']; ?>" size="15" style="width: 100px;"></td>
  </tr>

  <tr>
   
    <td><input name="BtnUpdate2" type="submit" id="BtnUpdate2" value="Save Changes"></td>
    <td>&nbsp;</td>

      <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   <td align="center" valign="middle"><a href="index.php">Back</a></td>
  </tr>


</td>
<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

  

</tr>

</table>
</form>


<?php include("../inc/bottom.php"); ?>