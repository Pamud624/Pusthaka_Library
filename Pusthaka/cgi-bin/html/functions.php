 

<?php if(isset($_REQUEST['Export'])) {
	header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=data.csv');  
      $output = fopen("php://output", "w") or die ("error");  
      fputcsv($output, array('no.', 'Book ID', 'Copy ID', 'Access no', 'Book Title', 'Authors', 'Barcode', 'ISBN', 'Publisher', 'Language'));  
      /*$query = "SELECT * from employeeinfo ORDER BY emp_id DESC";  
      $result = mysqli_query($con, $query);  */
      while($r = mysqli_fetch_assoc($rs))  
      {  
           fputcsv($output, $r);  
      }  
      fclose($output);  
	
}?>