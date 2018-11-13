<?php
//	$allow = "ADMIN;LIBSTAFF";
$allow = "ADMIN";
	$PageTitle = "Add New Member";
	require('../inc/init.php');
	
	$mem = new Members;
	
	//[Add book] ------------------------------------------
	if(isset($_REQUEST['BtnAdd'])){
		//[Fill a member data object] ------------------------------		
		$member['surname'] = $_REQUEST['surname'];
		$member['firstnames'] = $_REQUEST['firstnames'];
		$member['title'] = $_REQUEST['title'];
		$member['address'] = $_REQUEST['address'];
		$member['nic'] = $_REQUEST['nic'];
		$member['reg_no'] = $_REQUEST['reg_no'];
        $member['sex'] = $_REQUEST['sex']; //
        $member['phone'] = $_REQUEST['phone'];
		$member['email'] = $_REQUEST['email'];
		$member['index_no'] = $_REQUEST['index_no'];
        //username, password are updated separately
		$member['username'] = 'Not Specified';
		$member['password'] = 'Not Specified';

		$member['type'] = $_REQUEST['type'];
		if($member['type'] == 'NEWTYPE') {
			if(!($_REQUEST['type2'] =='')){
				$member['type'] = $_REQUEST['type2'];
			} else {
				$member['type'] = 'N/A';
			}
		} 
		$member['login_type'] = $_REQUEST['login_type'];
        $member['category'] = $_REQUEST['category'];
		$member['barcode'] = 'Not Specified';
		$member['expired'] = 0;
		
		$mem->add($member);		
	}

	// Check member /////////////////////////////////////////////////////
	if(isset($_REQUEST['BtnCheck'])){
		$type = $_REQUEST['type'];
		$surname = $_REQUEST['surname'];
		$firstnames = $_REQUEST['firstnames'];
		$title = $_REQUEST['title'];
		$address = $_REQUEST['address'];
		$nic = $_REQUEST['nic'];
		$reg_no = $_REQUEST['reg_no'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$index_no = $_REQUEST['index_no'];
	
		  $sqlMembers = sprintf("SELECT * FROM member WHERE (nic LIKE '%%%s%%' AND surname LIKE '%%%s%%' AND firstnames LIKE '%%%s%%') ORDER BY surname, firstnames", 
		  	$nic, $surname, $firstnames);
		  $rsMembers = executeSqlQuery($sqlMembers);
		  $NoOfMembers = mysqli_num_rows($rsMembers);
	}

?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%" border="0">
      <tr>
        <td><img src="images/icon-MembersAdd-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
           &nbsp;
          </td>
        </tr>
      </table>
      <table width="100%"  border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    <td class="content">

<?php //[Display Message]-------------------------- ?>
<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { ?>
<h1><?php echo $_SESSION['msg']['titl']; ?></h1>
<table border="0">
  <tr>
    <td class="msg">
		<?php echo stripcslashes($_SESSION['msg']['msg']); ?>
		<?php echo '<br>---------------------------------------------'; ?>
		<?php
			if($_SESSION['msg']['backlink']!=''){ 
				echo "<a href='" . $_SESSION['msg']['backlink'] . "'>Back</a>";
			}
		?>
	</td>
  </tr>
</table>
<?php } ?>


<?php if(isset($_REQUEST['BtnCheck'])) {?>
<table width="100%"  border="0">
  <tr>
    <td class="SimilarRecords">

<?php 
	if($NoOfMembers <> 0){
		echo "<strong>There is/are " . $NoOfMembers . " similar member(s).</strong>";
		while($member = mysqli_fetch_assoc($rsMembers)){
			echo "<br>&nbsp;&nbsp;&nbsp;<a href='member_edit.php?ID=" . $member['mid'] ."'>" . $member['title'] . " " . $member['surname'] . ", " . $member['firstnames'] . "</a> [Mem#: " . $member['mid'] . "] | Old#" . $member['mem_no'];
		}
	} else {
		echo "There are no simillar members.";
	}
?>

	</td>
  </tr>
</table>
<?php } ?>	
	
	<h1>Specify Member Details</h1>
<form action="member_add.php" method="post" name="member_add" class="formNormal" id="member_add">
                  <table border="0" cellpadding="0" cellspacing="0" class="edit_master">
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;
					  
					  </td>
                      <td>Title</td>
                      <td>
                        <select name="title" id="title">

                          <option value="Mr">Mr</option>
                          <option value="Ms">Ms</option>
                          <option value="Mrs">Mrs</option>
                          <option value="Dr">Dr</option>
                          <option value="Prof">Prof</option>
                          <option value="Ven">Ven</option>
                      </select>
                        Sex&nbsp;					    <select name="sex" id="sex">
                          <option value="M">M</option>
                          <option value="F">F</option>
                      </select>
                      </td>
                      <td>&nbsp;&nbsp;Surname&nbsp;</td>
                      <td colspan="3" align="left"><input name="surname" type="text" id="surname" size="50"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Firstnames&nbsp;</td>
                      <td colspan="5"><input name="firstnames" type="text" id="firstnames" size="90">
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Group</td>
                      <td colspan="5"><select name="type" id="type">
                        <option value='N/A'>N/A</option>
						<?php
						$sqlT = sprintf("SELECT DISTINCT type FROM member ORDER BY type");
						$rsT = executeSqlQuery($sqlT);
						while($rowT = mysqli_fetch_array($rsT)){
							echo "<option value='" . $rowT[0] . "'";
														
							echo ">" . $rowT[0] . "</option>";
						}	
						?>
                        <option value='NEWTYPE'>Define New Type</option>
                      </select>                        &nbsp;If the group is not in the list, please select "Define new type", and add it here: 
                      <input name="type2" type="text" id="type2" size="35"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td colspan="5">Lending Category&nbsp;
                        <select name="category" id="select">
                          <?php
						$sqlT = sprintf("SELECT DISTINCT member_type FROM lending_settings ORDER BY member_type DESC");
						$rsT = executeSqlQuery($sqlT);
						while($rowT = mysqli_fetch_array($rsT)){
							echo "<option value='" . $rowT[0] . "'";

							echo ">" . $rowT[0] . "</option>";
						}	
						?>
                        </select>
                        &nbsp;&nbsp;Login Type&nbsp;
                        <select name="login_type" id="select2">
                          <option value='ADMIN'>ADMIN</option>
                          <option value='LIBSTAFF' >LIBSTAFF</option>
                          <option value='PATRON' selected>PATRON</option>
                        </select>
                        <input name="BtnCheck" type="submit" id="BtnCheck" value="Check"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Address</td>
                      <td colspan="5"><input name="address" type="text" id="address" size="90"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Phone</td>
                      <td>
                        <input name="phone" type="text" id="phone" size="15"></td>
                      <td>&nbsp;&nbsp;Email</td>
                      <td colspan="3" align="left"><input name="email" type="text" id="email" size="50"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>NIC</td>
                      <td><input name="nic" type="text" id="nic" size="15">
                      </td>
                      <td>&nbsp;&nbsp;Reg#</td>
                      <td><input name="reg_no" type="text" id="reg_no" size="15"></td>
                      <td>Index#</td>
                      <td align="left"><input name="index_no" type="text" id="index_no" size="15"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="BtnAdd" type="submit" id="BtnAdd" value="Add Member"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
      </form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("member_add");
	frmvalidator1.addValidation("surname","req","Surname: is required.");
	//frmvalidator1.addValidation("firstnames","req","Firstname: is required.");
	//frmvalidator1.addValidation("nic","req","National Identity Card Number: is required.");
	//frmvalidator1.addValidation("","req",": is required.");
</script>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
