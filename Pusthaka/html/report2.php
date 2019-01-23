<?php


  $allow = "ADMIN;LIBSTAFF";
    $PageTitle = "Bulk upload2";
    include('../inc/init.php'); 








    if(isset($_REQUEST['cancel'])){
   header("Location: index.php");
 }

   if(isset($_REQUEST['issue'])){
   header("Location: date.php");
 }

  if(isset($_REQUEST['return'])){
   header("Location: date1.php");
 }

 if(isset($_REQUEST['fine'])){
   header("Location: fine_report.php");
 }

 if(isset($_REQUEST['lost'])){
   header("Location: lostbook.php");
 }

  if(isset($_REQUEST['tw'])){
   header("Location: tempwithdrw.php");
 }

 if(isset($_REQUEST['damage'])){
   header("Location: damage.php");
 }

 if(isset($_REQUEST['miss'])){
   header("Location: missing.php");
 }



 if(isset($_REQUEST['final'])){

$sql = "UPDATE loan LEFT JOIN copy ON loan.copy=copy.cid SET copy.copy_status='B' WHERE loan.returned='0'";
$a = executeSqlQuery($sql);

$sql1="UPDATE copy SET Availability='Y' WHERE copy_status='OK'";
$a1 = executeSqlQuery($sql1);

$sql2="UPDATE copy SET Availability='N' WHERE copy_status='TWA'OR copy_status='TW' OR copy_status='W' OR copy_status='Lst' OR copy_status='L' OR copy_status='B' OR copy_status='RO' OR copy_status='Damaged'  ";
$a2 = executeSqlQuery($sql2);


   header("Location: missing.php");
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

            <div class="well" align="center" style="border: solid black 2px">
              <div >
    <p class="h3" align="center"><b>Pusthaka Library Get Reports</b></p>   

  </div>




    <div class="well" >

 <!-------------------------------------------------------------------------------------------------------------------->    

   <div class="row">


 <div class="span3">
  <div class="well" style="border: solid black 2px">

         
    <button type="submit" class="btn btn-success btn-lg"name="issue" id="issue">Issued Books</button>
        
</div>
  </div>


<div class="span3">
  <div class="well" style="border: solid black 2px">
   

    <button type="submit" class="btn btn-success btn-lg"name="return" id="return"> Returned books </button>

      
</div>
</div>


<div class="span3">
  <div class="well" style="border: solid black 2px">
  

    <button type="submit" class="btn btn-success btn-lg"name="tw" id="tw">Temp Withdrawn</button>


      
</div>
</div>
  


       </div>


<!-------------------------------------------------------------------------------------------------------------------------->



   <div class="row">


 <div class="span3">
  <div class="well"style="border: solid black 2px">

     
     
    
    <button type="submit" class="btn btn-success btn-lg"name="lost" id="lost" style="width: 159px;">Lost</button>
        
</div>
  </div>


<div class="span3">
  <div class="well" style="border: solid black 2px">
   

    <button type="submit" class="btn btn-success btn-lg"name="damage" id="damage">Damaged</button>

      
</div>
</div>


<div class="span3">
  <div class="well" style="border: solid black 2px">
   

    <button type="submit" class="btn btn-success btn-lg"name="fine" id="fine">Fine Payment</button>


      
</div>
</div>
  


       </div>

<!------------------------------------------------------------------------------------------------------------->


<div class="row">


 <div class="span3">
  <div class="well" style="border: solid black 2px">

         
    <button type="submit" class="btn btn-success btn-lg"name="miss" id="miss">Inventory Missing</button>
        
</div>
  </div>



  <div class="span3">
  <div class="well" style="border: solid black 2px">
   

    <button type="submit" class="btn btn-success btn-lg"name="final" id="final">Final Report</button>


      
</div>
</div>
  






       </div>





<!------------------------------------------------------------------------------------------------------------------>

  <div class="row">
    <div class="span10">
       <div class="span6" style="width: 1450px;">
       
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
