 <?php
	$allow = "ADMIN";
	$PageTitle = "Alert_Message";
	include('../inc/init.php');
?>

<!-- 


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
        <div class="form-group">
          <label for="inputHint">Hint</label>
          <input type="text" class="form-control" name="english_hint" id="english_hint" placeholder="English Hint">
        </div>--> 
      <!--   
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
</table>-->
<?php
//index.php

$error = '';
$name = '';
$email = '';
$subject = '';
$message = '';

function clean_text($string)
{
 $string = trim($string);
 $string = stripslashes($string);
 $string = htmlspecialchars($string);
 return $string;
}

if(isset($_POST["submit"]))
{
 if(empty($_POST["name"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your Name</label></p>';
 }
 else
 {
  $name = clean_text($_POST["name"]);
  if(!preg_match("/^[a-zA-Z ]*$/",$name))
  {
   $error .= '<p><label class="text-danger">Only letters and white space allowed</label></p>';
  }
 }
 if(empty($_POST["email"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
 }
 else
 {
  $email = clean_text($_POST["email"]);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
   $error .= '<p><label class="text-danger">Invalid email format</label></p>';
  }
 }
 if(empty($_POST["subject"]))
 {
  $error .= '<p><label class="text-danger">Subject is required</label></p>';
 }
 else
 {
  $subject = clean_text($_POST["subject"]);
 }
 if(empty($_POST["message"]))
 {
  $error .= '<p><label class="text-danger">Message is required</label></p>';
 }
 else
 {
  $message = clean_text($_POST["message"]);
 }
 if($error == '')
 {
  require 'class/class.phpmailer.php';
  $mail = new PHPMailer;
  $mail->IsSMTP();        //Sets Mailer to send message using SMTP
  $mail->Host = 'smtpout.secureserver.net';  //Sets the SMTP hosts
  $mail->Port = '80';        //Sets the default SMTP server port
  $mail->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
  $mail->Username = 'xxxxxxxxxx';     //Sets SMTP username
  $mail->Password = 'xxxxxxxxxx';     //Sets SMTP password
  $mail->SMTPSecure = '';       //Sets connection prefix. Options are "", "ssl" or "tls"
  $mail->From = $_POST["email"];     //Sets the From email address for the message
  $mail->FromName = $_POST["name"];    //Sets the From name of the message
  $mail->AddAddress('info@find2rent.com', 'Name');//Adds a "To" address
  $mail->AddCC($_POST["email"], $_POST["name"]); //Adds a "Cc" address
  $mail->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
  $mail->IsHTML(true);       //Sets message type to HTML    
  $mail->Subject = $_POST["subject"];    //Sets the Subject of the message
  $mail->Body = $_POST["message"];    //An HTML or plain text message body
  if($mail->Send())        //Send an Email. Return true on success or false on error
  {
   $error = '<label class="text-success">Thank you for contacting us</label>';
  }
  else
  {
   $error = '<label class="text-danger">There is an Error</label>';
  }
  $name = '';
  $email = '';
  $subject = '';
  $message = '';
 }
}

?>
 <?php include("../inc/top.php"); ?>
<!DOCTYPE html>
<html>
 <head>
  <title>Send an Email on Form Submission using PHP with PHPMailer</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container" >
   <div class="row" style="border: solid black 2px">
    <div class="col-md-8" style="margin:0 auto; float:none;">
     <h3 align="center"><b>Pusthaka Library</b></h3>
     <br />
     <?php echo $error; ?>
     <form method="post">
     <!--  <div class="form-group">
       <label>Enter Name</label>
       <input type="text" name="name" placeholder="Enter Name" class="form-control" value="<?php echo $name; ?>" />
      </div> -->
      <div class="well">
      <div class="checkbox-inline">
  <label><input type="checkbox" class="checkbox_table" value="">Lectures</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" class="checkbox_table" value="">Instructors</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" class="checkbox_table" value="">Research Students</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" class="checkbox_table" value="">Student</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" class="checkbox_table" value="" id="select_all"> Select All</label>
</div>
</div>

<br />

      <div >
       <label>Enter date</label>
        <input type="date" name="bday">
<button type="button" class="btn btn-primary">Submit</button>


      </div>
      <div class="form-group">
       <label>Enter Subject</label>
       <input type="text" name="subject" class="form-control" placeholder="Enter Subject" value="<?php echo $subject; ?>" style="height: 60px;" />
      </div>
      <div class="form-group">
       <label>Enter Message</label>
       <textarea name="message" class="form-control" placeholder="Enter Message"   style="height: 100px;"><?php echo $message; ?></textarea>
      </div>
      <div class="form-group" align="center">
       <input type="submit" name="submit" value="Send" class="btn btn-info" />
      </div>
     </form>
    </div>
   </div>
  </div>
 </body>
</html>



<script >
  
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

<?php include("../inc/bottom.php"); ?>