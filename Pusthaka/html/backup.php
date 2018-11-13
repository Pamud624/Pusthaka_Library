<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Alert_Message";
  // include('../inc/init.php');
?>


<?php include("../inc/top.php"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
      <table width="100%"  border="0">
      <tr>
        <td><img src="images/backup.jpg" width="200" height="150"></td>
      </tr>
    </table>
    
 
  
<?php
$connect= new PDO("mysql:host=localhost;dbname=pusthaka", "root", "");
$get_all_table_query= "SHOW TABLES";
$statement = $connect->prepare($get_all_table_query);
$statement->execute();
$result= $statement->fetchAll();

if(isset($_POST['table']))
{

  $output ='';
  foreach ($_POST['table'] as $table) {
    $show_table_query = "SHOW CREATE TABLES" . $table . "";
    $statement =$connect -> prepare($show_table_query);
    $statement-> execute();
    $show_table_result = $statement->fetchAll();

    foreach ($show_table_result as $show_table_row) {

            $output .="\n\n".$show_table_row["Create Table"]. ";\n\n";
          
          }
            $select_query= "SELECT * FROM". $table . "";
            $statement =$connect-> prepare($select_query);
            $statement-> execute();
            $total_row = $statement-> rowCount();

            for($count=0; $count<$total_row ; $count++)
            {

             $single_result= $statement->fetch(PDO::FETCH_ASSOC);
             $table_column_array = array_keys($single_result);
             $table_value_array = array_values($single_result);
             $output .="\nINSERT INTO $table (";
             $output .= "". implode(",", $table_column_array) . ")
                    VALUES (";
             $output .= "'" . implode("','", $table_value_array) . "');
              \n";

            }

      }
      $file_name = 'database_backup_on_' . date('y-m-d') . '.sql'; 
      $file_handle = fopen($file_name, 'w+');
      fwrite($file_handle, $output);
      fclose($file_handle);
      header('Content-Discription : File Transfer');
      header('Content-Type: appliaction/octet-stream');
      header('Content-Discription :attachment; filename='. basename($file_name));
      header('Content-Transfer-Encoding : binary');
      header('Expires: 0');
      header('Catche-Control :must-revaidate');
      header('Pragma: public');
      header('Content-Length: ' .filesize($file_name));
      ob_clean();
      flush();
      readfile($file_name);
      unlink($file_name);

}

?>
<td>

<h1>Backup</h1>
<form method="post" id="export_form" >
      <!-- class="formNormal"  <table border="0" cellpadding="0"> -->

        <?php
          
          foreach ($result as $table)
           {
            ?>

        <div class="checkbox">
          <label>
            <input type="checkbox"  class="checkbox_table" name="table[]" value="<?php echo $table["Tables_in_pusthaka"]; ?>" /> <?php echo $table["Tables_in_pusthaka"]; ?> 
          </label>
          

        </div>
        <?php
      }
      ?>
             
      
         <!--<div class="form-group">
          <label for="inputHint">Hint</label>
          <input type="text" class="form-control" name="english_hint" id="english_hint" placeholder="English Hint">
        </div>-->
        
        <input type="submit" class="btn btn-info" name="submit" id="submit" value="Export" />
      </form>
    </div>
      </td>
</td>

        </tr>
      <tr>
        <td>  &nbsp;</td>
        
        <tr> </tr>
        

      </tr>


  <!--  </table> -->

</form>
</td>

  



</tr>
</table>


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
  }
 });
});
</script>
<?php include("../inc/bottom.php"); ?>
