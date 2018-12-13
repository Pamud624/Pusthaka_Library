<?php
 session_start();
  $allow = "ADMIN;LIBSTAFF";
   $PageTitle = "Backups";
  //include('../inc/init.php');


  //  if ($allow != "ALL"){
  // $found = false;
  // $arr = explode(";",$allow);

  // foreach($arr as $val){
  //   if( isset($_SESSION['CurrentUser']) && ($_SESSION['CurrentUser']['login_type'] == $val)){
  //     $found = true;
  //   }
  // }

  // if (!$found) {
  //   $_SESSION['BackLink'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
  //   $msg = 'You tried to access a page that requires you to login.<br>' . 
  //     'Please go to the <a href=index.php>home page</a> and login.<br>' . 
  //     '(you will be automatically redirected to the original page)';
  //   $title = 'Login Required';
  //   displayMsg($msg, $title);
  // }
//}

?>
<?php include("../inc/top.php"); ?>
 <?php
$connect = new PDO("mysql:host=localhost;dbname=pusthaka", "root", "");
$get_all_table_query = "SHOW TABLES  ";
$statement = $connect->prepare($get_all_table_query);
$statement->execute();
$result = $statement->fetchAll();




//print_r($result);

if(isset($_POST['table']))
{
 $output = '';
 foreach($_POST["table"] as $table)
 {
  $show_table_query = "SHOW CREATE TABLE " . $table . "";
  //$show_table_query = "SHOW CREATE TABLE  lending_settings";
  $statement = $connect->prepare($show_table_query);
  $statement->execute();
  $show_table_result = $statement->fetchAll();

  foreach($show_table_result as $show_table_row)
  {
   $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
  }
  $select_query = "SELECT * FROM " . $table . "";
  $statement = $connect->prepare($select_query);
  $statement->execute();
  $total_row = $statement->rowCount();

  for($count=0; $count<$total_row; $count++)
  {
   $single_result = $statement->fetch(PDO::FETCH_ASSOC);
   $table_column_array = array_keys($single_result);
   $table_value_array = array_values($single_result);
   $output .= "\nINSERT INTO $table (";
   $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
   $output .= "'" . implode("','", $table_value_array) . "');\n";
  }
 }
 $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
 $file_handle = fopen($file_name, 'w+');
 fwrite($file_handle, $output);
 fclose($file_handle);
 header('Content-Description: File Transfer');
 header('Content-Type: application/octet-stream');
 header('Content-Disposition: attachment; filename=' . basename($file_name));
 header('Content-Transfer-Encoding: binary');
 header('Expires: 0');
 header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_name));
    ob_clean();
    flush();
    readfile($file_name);
    unlink($file_name);
}



?>



<!--------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
 <head>
  <title>Pusthaka Library Backups</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">










 </head>
 <body>

  <br />
  <div class="container">
   <div class="row">
    <div class="well">
   <u><b> <p  class="h1" align="center"><font color="blue">Pusthaka Library Backups</font></p></b></u></div>
    <br />
    <div class="row">
<!------------------------------------------------------------------------------------------------------------->
      <div class="span8">
      
    <form method="post" id="export_form">


<div class="well">
    <div>
     <p class="h3" align="center"><b>Pusthaka Library Manual Backup</b></p>     
   
 </div>
  
    <div class="well">
<div>
<div>

  <div>

     <p class="h4" align="center"><font color="blue"><b>Select table for download backups  </b></font></p>     
   </div>

<div>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>




      <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $result[0]['Tables_in_pusthaka']; ?>" />Book  </label>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>


      <label><input type="checkbox" class="checkbox_table"  id="select_all"/> Selecct All</label>
  </div>
  <div>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

   
       <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $result[8]['Tables_in_pusthaka']; ?>" />Lending  </label></div>
       <div>
         <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

        <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $result[2]['Tables_in_pusthaka']; ?>" />Config  </label></div>
       <div>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

         <label><input type="checkbox" class="checkbox_table" name="table[]" value="<?php echo $result[0]['Tables_in_pusthaka']; ?>" />Book  </label></div>
 
 
    
       </div>


<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

 
             <div class="form-group">
               <td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> 
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> 
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> 
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

      <input type="submit" name="submit" id="submit" class="btn btn-info" value="Download" />
       <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
      <input type="submit" name="cancel" id="cancel" class="btn btn-info" onclick="history.go(-1);" value="Cancel" />



     </div>


    </div>



   </div>
        <div class="well">
      
        <div>

     <p class="h4" align="center"><font color="blue"><b>Click here for full data backup  </b></font></p>     
   </div>
   <div align="center" >

   <a  href="download.php" download>
  <img align="center" src="images/database.png" name="backup" alt="W3Schools" width="104" height="142">
</a>
</div>

    </div>
      
    
    </div>
    </form>
        </div>
  



<!------------------------------------------------------------------------------------------------------------->

  
    
<!------------------------------------------------------------------------------------------------------>

   <div class="span6">
    <div class="well">
    <form method="post" id="export_form">

    <div >
     <p class="h3" align="center"><b>Pusthaka Library Auto Backup</b></p>     
   </div>
 
   </form>
      </div>
</div>
 

   </div>



  </div>
</div>
 </body>
</html>

<!----------------------------------------------------------------------------------------------->

<script>
$(document).ready(function(){
 $('#submit').click(function(){
  var count = 0;
  $('.checkbox_table').each(function(){
   if($(this).is(':checked'))
   {
    count = count + 1;
   }
  });
  if(count > 0)
  {
   $('#export_form').submit();
  } 
  else
  {
   alert("Please Select Atleast one table for Export");
   return false;
//    $.alert({
//     title: 'Alert!',
//     content: 'Please Select Atleast one table for Export',
// });

  }
 });
});

//select all checkboxes
$("#select_all").change(function(){  //"select all" change 
    $(".checkbox_table").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
});

//".checkbox" change 
$('.checkbox_table').change(function(){ 
  //uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
  //check "select all" if all checkbox items are checked
  if ($('.checkbox_table:checked').length == $('.checkbox_table').length ){
    $("#select_all").prop('checked', true);
  }
});

</script>

<!------------------------------------------------------------------------------------------------------------>

<?php include("../inc/bottom.php"); 
//session_unset();
?>