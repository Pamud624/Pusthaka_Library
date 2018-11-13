
<head>
	<?php
		$allow = "ADMIN;LIBSTAFF";
		$PageTitle = "book status report";	
		require('../inc/init.php');
	?>

	<script>
	function showData(str) {
		
	  if (str=="") {
		document.getElementById("txtHint").innerHTML="";
		return;
	  }
	  if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	  } else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
		  document.getElementById("txtHint").innerHTML=this.responseText;
		}
	  }
	  xmlhttp.open("GET","getbookstatusdata.php?q="+str,true);
	  xmlhttp.send();
	}
	
	
	
	</script>
</head>

<body>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-eventlog-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
    <td>

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

<form method="post" name="form1" class="formNormal">
    <table border="0" cellpadding="0">
		<tr>
			<td colspan="2"><h1>Choose book status </h1></td>
		</tr>
		
		<tr>
			<td><div class="contentEm">
			<select id ="bookStatus" name="bookStatus" onChange="showData(this.value)" >
			<option value="Select" > Select:</option>
			<option value="l" > Lost </option>
			<option value="twa" >  Temp Withdrawn & archieved</option>
			<option value="w" >  Withdrawn</option>
			<option value="tw" >  Temp Withdrawn</option>
			<option value="lst" >  Lost by staff</option>
			<option value="d" >  Damaged</option>
			<option value="lfs" >  Lost by former staff</option>
			<option value="ro" >  Right Off</option>
			<option value="ls" > Lost by student</option>
			</td>
			</select></div>
		</tr>		
		<tr>
			<td> <div id="txtHint">Related copies will be listed here...</div></td>
		</tr>
    </table>
</form>





    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
</body>
