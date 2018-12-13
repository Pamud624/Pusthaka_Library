<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Member Info";
    require('../inc/init.php');

	//[Is this a proper request?] ------------------------------
	if(!isset($_REQUEST['ID'])){
		header("Location: member_browse.php");
		exit();
	}

    //[Get member] ------------------------------
    $id = $_REQUEST['ID'];
    $mem = new Members;
    $row = $mem->getByID($id);


	//[Get loan info] ------------------------------
    require('../classes/Loans.php');
    $loans = new Loans;
    $rsL = $loans->getCurrentLoansByMember($row);
	$rowcountL = mysqli_num_rows($rsL);

	$rsLH = $loans->getPastLoansByMember($row);
	$rowcountLH = mysqli_num_rows($rsLH);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" lass="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Members-200x150.jpg" width="200" height="150"></td>
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
    <td>
<!-- Page Content -->	
<h1><?php echo  $row['title'] . " " . $row['firstnames'] . " " . $row['surname'] . "&nbsp;(" .$row['mid'] . ")"; ?></h1>
<!-- Member Info -->
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">
  <div class="bg1">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td align="left" valign="top">&nbsp;</td>
	<td align="left" valign="top">&nbsp;</td>
	<td align="left" valign="top" class="contents">
		<?php echo  "(Member No: " . $row['mid'] . '</strong> | Old No: ' . $row['mem_no'] .  ")"; ?>&nbsp;(<a href="member_edit.php?ID=<?php echo  $row['mid']; ?>">Edit</a>)<br>
		<?php echo "<strong>Group:&nbsp;</strong>" .$row['type'] . "&nbsp;|&nbsp;<strong>Lending Category:&nbsp;</strong>" .$row['category'] . "&nbsp;|&nbsp;<strong>Login Category:&nbsp;</strong>" . $row['login_type']; ?><br>
		<?php echo "<strong>Barcode:&nbsp;</strong>" .$row['barcode'] . "&nbsp;|&nbsp;<strong>Reg#:&nbsp;</strong>" .$row['reg_no'] . "&nbsp;|&nbsp;<strong>Index#:&nbsp;</strong>" .$row['index_no'] . "&nbsp;|&nbsp;<strong>NIC#:&nbsp;</strong>" .$row['nic'] . "&nbsp;|&nbsp;<strong>Sex:&nbsp;</strong>" .$row['sex'] . "&nbsp;|&nbsp;<strong>Username:&nbsp;</strong>" .$row['username']; ?><br>
		<?php echo "<strong>Email:&nbsp;</strong>" . $row['email'] . "&nbsp;|&nbsp;<strong>Phone:&nbsp;</strong>" . $row['phone']; ?><br>
		<?php echo "<strong>Address:&nbsp;</strong>" . $row['address']; ?>
	</td>
  </tr>
</table>
</div>		
	</td>
  </tr>
</table>
<!-- END: Member Info -->  	
<!-- Current Loans -->  	
<h1><span class="td1h">Current Loans&nbsp;(<?php echo $rowcountL; ?>)</span></h1>
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">
<?php if($rowcountL>0){ ?>	
<table>
	<?php $x=0; while($rowL = mysqli_fetch_assoc($rsL)){ ?>
      <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1a"; $x += 1; } else {echo "td1b"; $x +=1; }?>">
        <td><?php echo $rowL['access_no']; ?>:<a target="_blank" href='book_view.php?ID=<?php echo $rowL['bid']; ?>'><?php echo substr($rowL['title'],0,30) . "..."; ?></a>&nbsp;[<?php echo $rowL['date_loaned']; ?>/<?php echo $rowL['date_due']; ?>]&nbsp;&nbsp;</td>
      </tr>
	<?php } ?>
</table>
<?php } else {
	echo "There are no current loans for this member.";
	}
 ?>	
</td></tr>
</table>
<!-- END: Current Loans -->
<!-- Past Loans -->
<h1>Member Loan History&nbsp;(<?php echo $rowcountLH; ?>)</h1>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="contentEm">
&nbsp;
<?php if($rowcountLH>0){ ?>	
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <?php $x=0; while($rowLH = mysqli_fetch_assoc($rsLH)){ ?>
	  <tr class="<?php if( ( $x % 2) == 0 ){ echo "td2a"; $x += 1; } else {echo "td2b"; $x +=1; }?>">
		<td><?php echo $rowLH['access_no']; ?>:<a target="_blank" href='book_view.php?ID=<?php echo $rowLH['bid']; ?>'><?php echo substr($rowLH['title'],0,30) . "..."; ?></a>&nbsp;[<?php echo $rowLH['date_loaned']; ?>/<?php echo $rowLH['date_returned']; ?>]&nbsp;&nbsp;</td>
	  </tr>
	  <?php } ?>
</table>
<?php } else {
	echo "There are no past loan records for this member.";
	}
 ?>

	</td>
</tr>
</table>
<!-- END: Past Loans -->  	
<!-- END: Page Content -->	
	</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
