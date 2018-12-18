<?php


  $allow = "ADMIN;LIBSTAFF";
    $PageTitle = "Bulk upload2";
    include('../inc/init.php'); 


if(isset($_REQUEST['period'])){
    $p = $_REQUEST['period'];
    if($p == 'Today'){
      $y = date("Y"); $m = date("m"); $d = date("d");   
      $date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
      $date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
    } elseif($p == 'Yesterday'){
      $y = date("Y"); $m = date("m"); $d = date("d");
      $date_startTS = mktime(0,0,0,$m,$d-1,$y);
      $date_endTS = mktime(23,59,59,$m,$d-1,$y);
      $date_start = date("Y-m-d G:i:s",$date_startTS);
      $date_end = date("Y-m-d G:i:s",$date_endTS);
    } elseif($p == 'ThisMonth'){
      $y = date("Y"); $m = date("m"); $d = date("d");
      $date_startTS = mktime(0,0,0,$m,1,$y);
      $date_endTS = mktime(23,59,59,$m+1,0,$y);
      $date_start = date("Y-m-d G:i:s",$date_startTS);
      $date_end = date("Y-m-d G:i:s",$date_endTS);
    } elseif($p == 'LastMonth'){
      $y = date("Y"); $m = date("m"); $d = date("d");
      $date_startTS = mktime(0,0,0,$m-1,1,$y);
      $date_endTS = mktime(23,59,59,$m,0,$y);
      $date_start = date("Y-m-d G:i:s",$date_startTS);
      $date_end = date("Y-m-d G:i:s",$date_endTS);
    } elseif($p == 'ThisYear'){
      $y = date("Y"); $m = date("m"); $d = date("d");
      $date_startTS = mktime(0,0,0,1,1,$y);
      $date_endTS = mktime(23,59,59,12,31,$y);
      $date_start = date("Y-m-d G:i:s",$date_startTS);
      $date_end = date("Y-m-d G:i:s",$date_endTS);
    } elseif($p == 'InYear'){
      $Year = $_REQUEST['Year'];
      if($Year <2000){$Year = $Year+2000;}
      $date_startTS = mktime(0,0,0,1,1,$Year);
      $date_endTS = mktime(23,59,59,12,31,$Year);
      $date_start = date("Y-m-d G:i:s",$date_startTS);
      $date_end = date("Y-m-d G:i:s",$date_endTS);
    } else {
      $date_start = '1900-01-01';
      $date_end =  '2900-12-31';
    }
  } else {
      $y = date("Y"); $m = date("m"); $d = date("d");   
      $date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
      $date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
  }
  
  if($_REQUEST['IR']=='Issues'){
    $sql = sprintf("select l.lid, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
    "l.loaned_by, c.access_no, b.*, m.mid, concat(m.title,  ' ', m.firstnames, ' ',  m.surname) AS member_name  " .
    "FROM ( ((loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) LEFT JOIN member m ON m.mid =l.member ) " .
    "WHERE (l.date_loaned > '%s' AND l.date_loaned < '%s') ORDER BY date_loaned DESC", $date_start, $date_end);
    $rs1 = executeSqlQuery($sql);
    $rs1count = mysqli_num_rows($rs1);
  } elseif($_REQUEST['IR']=='Returns'){
    $sql = sprintf("select l.lid, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
    "l.loaned_by, c.access_no, b.*, m.mid, concat(m.title,  ' ', m.firstnames, ' ',  m.surname) AS member_name  " .
    "FROM ( ((loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) LEFT JOIN member m ON m.mid =l.member ) " .
    "WHERE (l.date_returned > '%s' AND l.date_returned < '%s') ORDER BY date_returned DESC", $date_start, $date_end);
    $rs2 = executeSqlQuery($sql);
    $rs2count = mysqli_num_rows($rs2);  
  } 





    if(isset($_REQUEST['cancel'])){
   header("Location: index.php");
 }


    ?>





<?php include("../inc/top.php"); ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  
    <tr>&nbsp;</tr>
    <tr>&nbsp;</tr>
    <tr>&nbsp;</tr>
    
    

  <tr>
    <td class="margin"><img src="images/download.jpg" width="200" height="150">
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
    <td>&nbsp;</td>


    <td>
<!--------------------------------------------------------------------------------------------------------------------------->
      <div class="row" align="center">


        <form action="report2.php">

          <div class="span13">

            <div class="well" align="center">
              <div >
    <p class="h3" align="center"><b><font color="blue" > Pusthaka Library Get Reports Issue Books</font></b></p>   
 
  </div>




    <div class="well">



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

 <!-------------------------------------------------------------------------------------------------------------------->    

   <div class="row">
<div>
  <p class="h4"><b>Choose your time period</b></p>

  </div>
  <div>
   <form action="circulation_irReport.php" method="post" name="form1" class="formNormal">
    <div class="well">
   <!--  <table border="0" cellpadding="0">
      <tr>
        <td colspan="2"><div class="contentEm"> -->
          <div align="left">
          <label>
          <input name="period" type="radio" value="Today" <?php if( !isset($_REQUEST['period']) || ($_REQUEST['period']=='Today')){ echo 'checked';} ?>>
  Today</label>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp     

                 <label>
          <input type="radio" name="period" value="Yesterday" <?php if($_REQUEST['period']=='Yesterday'){ echo 'checked';} ?>>
  Yesterday</label>

             &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp     


  <label>
          <input type="radio" name="period" value="ThisMonth" <?php if($_REQUEST['period']=='ThisMonth'){ echo 'checked';} ?>>
  This Month</label>
     
                  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp     

  <label>
          
          <input type="radio" name="period" value="LastMonth" <?php if($_REQUEST['period']=='LastMonth'){ echo 'checked';} ?>>
  Last Month</label>

    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

  <label>
          <input type="radio" name="period" value="ThisYear" <?php if($_REQUEST['period']=='ThisYear'){ echo 'checked';} ?>>
  This Year</label>
         
         &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

           <label>
          <input type="radio" name="period" value="InYear" <?php if($_REQUEST['period']=='InYear'){ echo 'checked';} ?>>
  In Year</label>

  

    <input name="Year" type="text" id="Year2" size="4" maxlength="4"  style="width: 150px;" value='<?php if($_REQUEST['period']=='InYear'){ echo $Year;} ?>'>



            </div>

        <!--   <div align="left">
          <label>
          <input type="radio" name="period" value="Yesterday" <?php if($_REQUEST['period']=='Yesterday'){ echo 'checked';} ?>>
  Yesterday</label>
         </div>
           
           <div align="left">
          <label>
          <input type="radio" name="period" value="ThisMonth" <?php if($_REQUEST['period']=='ThisMonth'){ echo 'checked';} ?>>
  This Month</label></div>
         
         <div align="left">
          <label>
          
          <input type="radio" name="period" value="LastMonth" <?php if($_REQUEST['period']=='LastMonth'){ echo 'checked';} ?>>
  Last Month</label>
         </div>

         <div align="left">
          <label>
          <input type="radio" name="period" value="ThisYear" <?php if($_REQUEST['period']=='ThisYear'){ echo 'checked';} ?>>
  This Year</label>
          </div> -->
        </br>

        
    <div >
    <label>
      <input name="IR" type="radio" value="Issues" <?php if(!isset($_REQUEST['IR']) || ($_REQUEST['IR']=='Issues') ){ echo 'checked';} ?>> Issued Books</label>
    
    </div>
   
        <input class="btn btn-success btn-lg" name="BtnOk" type="submit" value="Display" >
    
  </div>
</form>
  
</div>


       </div>


<!-------------------------------------------------------------------------------------------------------------------------->



   
<!------------------------------------------------------------------------------------------------------------->

  <div class="row">
    <div class="span11">
       <div class="span5" style="width: 1500px;">
       
        <input type="submit" name="cancel" id="cancel" class="btn btn-info" value="Back" />
     </div>
  </div>
</div>





    </div>
  </div>
 </div>
 </form>
</div>

<?php include("../inc/bottom.php"); ?>
