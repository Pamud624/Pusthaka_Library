<?php
	$allow = "ADMIN";
	$PageTitle = "Add Holiday";
	include('../inc/init.php');
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-calendar.jpeg"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td class="content">
<h1>Add a Holiday</h1>
<div class="contentEm">

	
	

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

</div></td>
</tr>
</table>


<?php
		if(isset($_SESSION['success1']))
		{
			echo '	<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin:10px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Success! </strong>'. $_SESSION['success'].
					'</div>';
			unset($_SESSION['success1']);
		}
		elseif(isset($_SESSION['error1']))
		{
			echo '	<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin:10px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Warning! </strong>'. $_SESSION['error'].
					'</div>';
			unset($_SESSION['error']);
		}
	?>
<?php include("../inc/bottom.php"); ?>