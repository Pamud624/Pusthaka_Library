
<?php
 include('../inc/util.php');
$p = $_GET['q'];
// echo $p;
	$sql_cs = "";

	/*cs active students */
	switch($p){
		case "l": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='L') ORDER BY bid ASC";
			$value='"Lost (L)"';
			break;
		case "twa": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='TWA') ORDER BY bid ASC";
			$value='"Temporary Withdrawn and Archieved (TWA)"';
			break;
		case "w": 
			$sql = "SELECT c.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='W') ORDER BY bid ASC";
			$value='"Withdrawn (W)"';
			break;
		case "tw": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='TW') ORDER BY bid ASC";
			$value='"Temporary Withdrawn (TW)"';
			break;
		case "lst": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='LSt') ORDER BY access_no ASC";
			$value='"Lost by Staff (LSt)"';
			break;
		case "d": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='Damaged') ORDER BY access_no ASC";
			$value='"Damaged (D)"';
			break;
		case "lfs": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='LFS') ORDER BY access_no ASC";
			$value='"Lost by Former Staff (LFS)"';
			break;
		case "ro": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='RO') ORDER BY access_no ASC";
			$value='"Right Off (RO)"';
			break;
		case "ls": 
			$sql = "SELECT b.bid, c.cid, c.access_no, b.title, b.authors, c.barcode, b.isbn, b.publisher, b.lang FROM `book` b, copy c WHERE (c.bid=b.bid AND c.copy_status='LS') ORDER BY access_no ASC";
			$value='"Lost by Student (LS)"';
			break;
		
	}
	
	$rs = executeSqlQuery($sql);
	$count = mysqli_num_rows($rs);
	
?>

<h1><?php echo $count; ?>&nbsp;copies are in <?php echo $value;?>&nbsp;status.</h1> 

<!--
//******* export to EXCEL*****
<div>
     <form class="form-horizontal" action="function.php" method="post" name="upload_excel" enctype="multipart/form-data">
     <input name="Export" id="Export" type="submit"  value="export to excel">              
    </form>        
</div>-->
<!--<input name="Export" id="Export" type="submit"  value="export to excel" onclick="exportcsv()">   -->
       
<?php 

	/*function exportcsv(){
		if(isset($_REQUEST['Export'])) {
			echo "test";
			header('Content-Type: text/csv; charset=utf-8');  
			  header('Content-Disposition: attachment; filename=data.csv');  
			  $output = fopen("php://output", "w") or die ("error");  
			  fputcsv($output, array('no.', 'Book ID', 'Copy ID', 'Access no', 'Book Title', 'Authors', 'Barcode', 'ISBN', 'Publisher', 'Language'));  
			  $query = "SELECT * from employeeinfo ORDER BY emp_id DESC";  
			  $result = mysqli_query($con, $query); 
			  while($r = mysqli_fetch_assoc($rs))  
			  {  
				   fputcsv($output, $r);  
			  }  
			  fclose($output);  
			
		}
	}*/

?>




<table  border="1" cellspacing="10" cellpadding="3">
	<tr>
		<td><strong>no.</strong></td>
		<td><strong>Book ID</strong></td>
		<td><strong>Copy ID</strong></td>
		<td><strong>Access no</strong></td>
		<td><strong>Book Title</strong></td>
		<td><strong>Authors</strong></td>
		<td><strong>Barcode</strong></td>
		<td><strong>ISBN</strong></td>
		<td><strong>Publisher</strong></td>
		<td><strong>Language</strong></td>
		<!--<td><strong>email</strong></td>
		<td><strong>barcode</strong></td>-->
	</tr>
	<?php
	$i=0;
	while($r=mysqli_fetch_assoc($rs)){ 
		echo '<tr>';
			echo '<td>' . ++$i.'</td>';
			echo '<td>' .  $r['bid'] . '</td>';
			echo '<td>' .  $r['cid'] . '</td>';
			echo '<td>' .  $r['access_no'] . '</td>';
			echo '<td>' .  $r['title'] . '</td>';
			echo '<td>' .  $r['authors'] . '</td>';
			echo '<td>' .  $r['barcode'] . '</td>';
			echo '<td>' .  $r['isbn'] . '</td>';
			echo '<td>' .  $r['publisher'] . '</td>';
			echo '<td>' .  $r['lang'] . '</td>';
			/*eecho '<td>' .  $r['email'] . '</td>';
			echo '<td>' .  $r['barcode'] . '</td>';*/
		echo '</tr>';
	}
	?>
</table>
