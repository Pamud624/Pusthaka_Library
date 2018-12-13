<?php
	$allow = "ADMIN";
	$PageTitle = "All Holidays";
	include('../inc/init.php');
?>
<?php include("../inc/top.php"); ?>

<?php

if(isset($_POST[YearValue])){
	
	$_SESSION["YearValue"] = $_POST[YearValue];
}

if(isset($_POST[MonthValue])){
	
	$_SESSION["MonthValue"] = $_POST[MonthValue];
}

?>
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
	

<h1>List of all Holidays</h1>
<div style="float: right; margin-right:20px;"> 

<form action= "_holidays.php" method = "post">
Year : 

		<select name = "YearValue">
		<?php
			if(isset($_SESSION[YearValue])){
				echo "<option value='". $_SESSION["YearValue"]."'>". $_SESSION["YearValue"]."</option>";
			}
			
			?>
		<option value="2018">2018</option>
		  <option value="2017">2017</option>
		  <option value="2016">2016</option>
		  <option value="2015">2015</option>
		  <option value="2014">2014</option>
		  <option value="2013">2013</option>
		  <option value="2012">2012</option>
		  <option value="2011">2011</option> 	
		  <option value="2010">2010</option>
		</select>
		
Month :		
		
		<select name ="MonthValue">		
		<?php 
		if(isset($_SESSION[MonthValue])){
				echo "<option value='". $_SESSION["MonthValue"]."'>". $_SESSION["MonthValue"]."</option>";
			}
			
			
			?>
			<option value = "All"> All </option>
			<option value = "Jan"> Jan</option>
			<option value = "Feb"> Feb </option>
			<option value = "Mar"> Mar </option>
			<option value = "Apr"> Apr </option>
			<option value = "May"> May </option>
			<option value = "May"> May </option>
			<option value = "Jul"> Jul </option>
			<option value = "Aug"> Aug </option>
			<option value = "Sep"> Sep </option>
			<option value = "Oct"> Oct </option>
			<option value = "Nov"> Nov </option>
			<option value = "Dec"> Dec </option>
		</select>
		
<input type="submit" value ="Search" style=" padding: 10px 20px; margin: 4px 0; box-sizing: border-box; border-radius: 6px ;">

</form>		
</div>
<div class="contentEm">

	<?php
		if(isset($_SESSION['success1']))
		{
			echo '	<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin:10px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Success! </strong>'. $_SESSION['success1'].
					'</div>';
			unset($_SESSION['success1']);
		}
		elseif(isset($_SESSION['error1']))
		{
			echo '	<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin:10px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Warning! </strong>'. $_SESSION['error1'].
					'</div>';
			unset($_SESSION['error1']);
		}
	?>
	
	<?php
	//Customized query
	
	
	if(isset($_SESSION[YearValue])){
		if(isset($_SESSION[MonthValue]) && $_SESSION[MonthValue]!= "All"){
			
			$sql = "SELECT * FROM holidays WHERE Year = ".$_SESSION["YearValue"]." &&  `month` = '".$_SESSION["MonthValue"]."' ORDER BY `date`";
	
		}else{
			$sql = "SELECT * FROM holidays WHERE Year = ".$_SESSION["YearValue"]." ORDER BY `date`";
		}
			
				
			}
	else{
				
				$sql = "SELECT * FROM holidays WHERE Year = '2017' ORDER BY `date` ";
		}

	
		$result = executeSqlQuery($sql);	
		if (mysqli_num_rows($result)) 
		{
			echo '<div style="margin:15px;"><table class="table table-striped">
					<thead>
					  <tr><th>Year</th><th>Month</th><th>Day</th><th>Full Date</th><th>Actions</th></tr>
					</thead>
					<tbody>';
			
		// output data of each row
			while($row = mysqli_fetch_assoc($result)){
				
				echo '<td>'.$row['year'].'</td>';
				echo '<td>'.$row['month'].'</td>';
				echo '<td>'.$row['day'].'</td>';
				echo '<td>'.$row['date'].'</td>';
				echo '<td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm'.$row['id'].'">Delete</button></td>';
				echo '</form>';
				echo '<div class="modal fade bs-example-modal-sm" id="confirm'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								</div>
								<div class="modal-body">
									<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
									<a href="delete_holiday.php?dateId='.$row['id'].'" class="btn btn-success">Yes</a>
								</div>
							</div>
						</div>
					  </div>';
				echo '</form></tr>';
			}
			echo '</tbody>
				</table></div>';
		} 
		else {
			echo '<br> <br> <br><div class="alert alert-info" style="margin:15px;">
					<strong>Info!</strong> No holidays are added yet.
				  </div>';
		}
				
	?>
</div></td>
</tr>
</table>
<?php include("../inc/bottom.php"); ?>
