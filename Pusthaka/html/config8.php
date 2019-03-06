<?php
  $allow = "ADMIN";
  $PageTitle = "Configuration";
  include('../inc/init.php');
  //$mem = new Members;

  // Update my_info /////////////////////////////////////////////////////
  if(isset($_REQUEST['BtnUpdate1'])){
    $days_allowed = $_REQUEST['days_allowed'];
    $num_allowed = $_REQUEST['num_allowed'];
    $allowed=$_REQUEST['allowed'];
    $abc='Allowed';
    $cdf='Not_Allowed';




    if(isset($_REQUEST['allowed']) && $_REQUEST['allowed']== 1){
  
      $sql = sprintf("update lending_settings set allowed=%d, num_allowed=%d," .
        "days_allowed=%d , privile='%s' WHERE id=14",1, $num_allowed, $days_allowed, $abc);
      $a = executeSqlNonQuery($sql);

      $rowcount = $a['rows'];
      if ($rowcount <> 1) {
       // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
        //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
         header("Location: config8.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config8.php");
        exit();
        
      }
    }elseif (isset($_REQUEST['allowed']) && $_REQUEST['allowed']==0) {
      $sql = sprintf("update lending_settings set allowed=%d, num_allowed=%d," .
        "days_allowed=%d, privile='%s' WHERE id=14",0, 0, 0,$cdf);
      $a = executeSqlNonQuery($sql);

      $rowcount = $a['rows'];
      if ($rowcount <> 1) {
       // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
        //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
         header("Location: config8.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config8.php");
      exit();
      }
      
    }
    else
       $sql = sprintf("update lending_settings set  num_allowed=%d," .
        "days_allowed=%d  WHERE id=14", $num_allowed, $days_allowed);
      $a = executeSqlNonQuery($sql);

      $rowcount = $a['rows'];
      if ($rowcount <> 1) {
       // $_SESSION['BackLink'] = $_SERVER['PHP_SELF'];
        //trigger_error("An error occured while updating member: ID=" . $mid, E_USER_ERROR);
         header("Location: config8.php");
        exit();
      } else {
        $_SESSION['msg'] = "Your information was updated.";
        header("Location: config8.php");
        exit();
        
      }
  } //END: update my_info


  // Change Password ////////////////////////////////////////////////////

 // $row = $mem->getByID($_SESSION['CurrentUser']['mid']);
 

        //[Retrieve data] ------------------------------

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
        $row = mysqli_fetch_assoc($recordset);


        //[Return] ------------------------------
        // if($row){
        //     print_r($row) ;
        // } else {
        //   trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
        //   exit();
        // }
      
      if(isset($_REQUEST['Cancel'])){

        header("Location: config300.php");
      }
 





?>
<?php include("../inc/top.php"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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



<div class="row" align="center">
 
  <div class="span11" align="center">
<div class="well" align="center">
   <u><b> <p  class="h1" align="center"><font color="blue">Pusthaka Library Configuration</font></p></b></u>
 </div>
   </div>
</div>


<div class="row" align="center">

  <div class="span11">
    <form form action="config8.php" method="post" name="my_info"  id="my_info">
<div class="well" align="center">

  <div>
     <p class="h3" align="center"><b>Research Student(LC) Settings</b></p>     
   </div>
   <div class="well">
   <div class="row">
 <div class="span3">  <b><label><font color="blue">Allowance</font></label> </b></div>

   <div class="span1"> <input name="privile" type="text" id="privile" value="<?php echo $row['privile']; ?>" size="90" style="width: 100px;" readonly></div>
   <input type="radio" name="allowed" id="one" value=1 ><b> Allowed</b>

   <input type="radio" name="allowed" value=0  id="zero"> <b>Not_Allowed</b>
 

   </div>

    <div class="row">
 <div class="span3">  <b><label><font color="blue">Allowed_books</font></label> </b></div>

   <div class="span1"> <input name="num_allowed" type="text" id="num_allowed"  value="<?php echo $row['num_allowed']; ?>" size="15"  style="width: 100px;"></div>

   </div>
   <div class="row">
 <div class="span3">  <b><label><font color="blue">Allowed days</font></label> </b></div>

    <div class="span1"><input name="days_allowed" type="text" id="otherPosition" value="<?php echo $row['days_allowed']; ?>" size="15" style="width: 100px; color: " ></div>
   </div>

   <div class="row">
    <div class="span5"></div>
  
   <div > <button name="BtnUpdate1" type="submit" class="btn btn-primary" id="BtnUpdate1" >Save changes</button>

    <button name="Cancel" type="submit" class="btn btn-primary" id="cancel" >Cancel</button></div>
   </div>
</div>

  
</div>
</form>
</div>
</div>


<!-- <script >
 $("input[name=allow][value=" + value + "]").attr('checked', 'checked');
</script> -->
 
<?php include("../inc/bottom.php"); ?>