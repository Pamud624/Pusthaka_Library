<?php
  $allow = "ADMIN";
  $PageTitle = "Configuration";
  include('../inc/init.php');
  //$mem = new Members;

  // Update my_info /////////////////////////////////////////////////////
  if(isset($_REQUEST['BtnUpdate1'])){
    $days_allowed = $_REQUEST['days_allowed'];
    $num_allowed = $_REQUEST['num_allowed'];
     $days_allowed1 = $_REQUEST['days_allowed1'];
    $num_allowed1 = $_REQUEST['num_allowed1'];
    
  
      $sql = sprintf("update lending_settings set num_allowed=%d days_allowed=%d WHERE id=4", $num_allowed, $days_allowed);  
      $sql1= sprintf("update lending_settings set num_allowed=%d,days_allowed=%d WHERE id=15", $num_allowed1, $days_allowed1);
      $a = executeSqlNonQuery($sql);
       $a1 = executeSqlNonQuery($sql1);
       $rowcount = $a['rows'];
       $rowcount1 = $a1['rows'];
      if ($rowcount <> 1 || $rowcount <> 1  ) {
       // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
        //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
         header("Location: config100.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config100.php");
        exit();
      }
      // $a = executeSqlNonQuery($sql);
      // $rowcount = $a['rows'];
      // if ($rowcount <> 1) {
      //  // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
      //   //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
      //    header("Location: config100.php");
      //   exit();
      // } else {
      //   $_SESSION['msg'] = "Your information was updated.";
      //   header("Location: config100.php");
      //   exit();
      // }


    }

    //   elseif (isset($_REQUEST['BtnUpdate2'])){
    // $days_allowed1 = $_REQUEST['days_allowed1'];
    // $num_allowed1 = $_REQUEST['num_allowed1'];
    
    
  
    //    $sql1 = sprintf("update lending_settings set num_allowed=%d," .
    //     "days_allowed=%d WHERE id=15", $num_allowed1, $days_allowed1);
    //   $a1 = executeSqlNonQuery($sql1);
    //   $rowcount1 = $a1['rows'];
    //   if ($rowcount1 <> 1) {
    //    // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
    //     //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
    //      header("Location: config100.php");
    //     exit();
    //   } else {
    //     $_SESSION['msg'] = "Your information was updated.";
    //     header("Location: config100.php");
    //     exit();
    //   }
    // }
   //END: update my_info


  // Change Password ////////////////////////////////////////////////////

 // $row = $mem->getByID($_SESSION['CurrentUser']['mid']);
 

        //[Retrieve data] ------------------------------

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
        $row = mysqli_fetch_assoc($recordset);




          $sql1 = "SELECT * FROM lending_settings WHERE id =15";
        $recordset1 = executeSqlQuery($sql1);
        $rowcount1 = mysqli_num_rows($recordset1);
        if ($rowcount1 == 0) {
          trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
          exit();
        } else if ( ($rowcount1 > 1) || ($rowcount1<0) ) {
          trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
          exit();
        }
           $row1 = mysqli_fetch_assoc($recordset1);

        //[Return] ------------------------------
        // if($row){
        //     print_r($row) ;
        // } else {
        //   trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
        //   exit();
        // }
      






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


<h1>Configuration Settings</h1>
<form action="config100.php" method="post" name="my_info" class="formNormal" id="my_info">  

<table border="0" cellpadding="0" cellspacing="0">

  <tr>
    <td><span class="emphtext">

      <input name="mid" type="hidden" value="<?php echo $row['mid']; ?>">
    </span></td>
    <td>Allowed books(L)&nbsp;</td>
    <td ><input name="num_allowed" type="text" id="num_allowed" value="<?php echo $row['num_allowed']; ?>" size="15"></td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td><input name="BtnUpdate1" type="submit" id="BtnUpdate1" value="Save Changes"></td>
    </tr>
  <tr>
 
  <tr>
    <td>&nbsp;</td>
    <td>Allowed days(L)</td>
    <td><input name="days_allowed" type="text" id="days_allowed" value="<?php echo $row['days_allowed']; ?>" size="15"></td>
  </tr>

   </tr>



    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>


   <tr>
    <td>&nbsp;</td>
    <td>Allowed books(LC)</td>
    <td><input name="num_allowed1" type="text" id="num_allowed1" value="<?php echo $row1['num_allowed']; ?>" size="15"></td>
       <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
      <td><input name="BtnUpdate2" type="submit" id="BtnUpdate2" value="Save Changes"></td>
  </tr>

   <tr>
    <td>&nbsp;</td>
    <td>Allowed days(LC)</td>
    <td><input name="days_allowed1" type="text" id="days_allowed1" value="<?php echo $row1['days_allowed']; ?>" size="15"></td>
  </tr>



    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
   <td align="center" valign="middle"><a href="config1.php">Back</a></td>
  </tr>
</table>
</form>
<!-- <script language="JavaScript">
  var frmvalidator1 = new Validator("my_info");
  frmvalidator1.addValidation("email","email","Please enter a valid email address.");
</script>
 -->
<?php include("../inc/bottom.php"); ?>