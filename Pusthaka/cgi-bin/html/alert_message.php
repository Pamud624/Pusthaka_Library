<?php
	$allow = "";
	$PageTitle = "Alert_Message";
	include('../inc/init.php');
?>


<?php include("../inc/top.php"); ?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
      <tr>
        <td><img src="images/alert-message.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>

      </tr>
    </table>
    
 
  

<td>
<h1>Sending the alert massage</h1>
<form method="post" name="form1" class="formNormal">
    <table border="0" cellpadding="0">

      <tr> 

       <td> &nbsp;</td>
        <td> &nbsp;</td>
         <td> &nbsp;</td>
      </tr>

      <tr>
        <td colspan="2">
          <div class="contentEm">
          <label>
          <input name="period" type="radio" value="Today" > Lectures</label>
          <label>
          <input type="radio" name="period" value="Yesterday" > Research students</label>
          <label>
          <input type="radio" name="period" value="ThisMonth" > Students</label>
          <label>
          <input type="radio" name="period" value="LastMonth" > All</label>
        
  
        </div>
      </td>

         <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>
         <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>


      <td>
        
            <div class="col-xs-12 col-sm-6 col-md-4" style="margin:15px;">
      <form role="form" method="post" action="add_holiday_func.php">
        <div class="form-group">
          <label for="inputWord">Date</label>
          <input type="date" class="form-control" name="date" id="date" placeholder="Select Date">
        </div>
        <!--<div class="form-group">
          <label for="inputHint">Hint</label>
          <input type="text" class="form-control" name="english_hint" id="english_hint" placeholder="English Hint">
        </div>-->
        
        <button type="submit" class="btn btn-success">Add</button>
      </form>
    </div>
      </td>


      <td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>
        <td>  &nbsp;</td>

        <input name="Year" type="text" id="Year" size="10000" style=" width: 1000px; height: 100px" >
      </td>
        </tr>
      <tr>
        <td>  &nbsp;</td>
        
        <tr> </tr>
        <td><input name="BtnOk" type="submit" value="Send"></td>


      </tr>


    </table>

</form>
</td>

  



</tr>
</table>
<?php include("../inc/bottom.php"); ?>
