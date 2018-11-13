<?php
/* -------------------------------------------------------------------------------------
	This page handles issues/returns.
	Expects $_REQUEST['mid']
	
   ------------------------------------------------------------------------------------- */

	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Issue/Return";
	include('../inc/init.php');
	include('ir2_func.php');

	// ------------------------------------------------------------------------------------
	// Make sure that all the required parameters are present
	if(!isset($_REQUEST['mid'])){
		header("Location: ir1.php");
		exit();
	} else{
		$mid = $_REQUEST['mid'];
		$_SESSION['irMid']=$mid;
	}

	// ------------------------------------------------------------------------------------
	// If CANCEL was selected, terminate this IR Session
	if(isset($_REQUEST['BtnTerminate'])){
		if( isset($_SESSION['TotalFine']) && is_numeric($_SESSION['TotalFine']) ){			
			$fine = $_SESSION['TotalFine'];
		} else {
			$fine =0;
		}
		if( isset($_REQUEST['finePaid']) && ($fine>0) ){ //There is a non-zero fine and it is paid
			// Make a payment entry
			$sql = sprintf("INSERT INTO payment (mid,dt,amount,reason) VALUES (%d,'%s',%d,'%s')", 
				$mid,date("Y-m-d G:i:s"), $fine, 'Fine');
			executeSqlNonQuery($sql);
		} elseif( (!isset($_REQUEST['finePaid'])) && ($fine>0) ){
			// Make a payable entry
			$sql = sprintf("INSERT INTO payable (mid,dt,amount,reason) VALUES (%d,'%s',%d,'%s')", 
				$mid,date("Y-m-d G:i:s"), $fine, 'Fine');
			executeSqlNonQuery($sql);
		}
	
		// Prepare for new patron 
		if(isset($_SESSION['Returned'])) unset($_SESSION['Returned']);
		if(isset($_SESSION['TotalFine'])) unset($_SESSION['TotalFine']);
		if(isset($_SESSION['irMid'])) unset($_SESSION['irMid']); // Now ok to go back
		header("Location: ir1.php");
	}

	// ------------------------------------------------------------------------------------	
	// Get member details into $rowMember
	if(!($mid>=1)){ // Ensure that MID is a number
		$msgT = "MID must be a number.<br>You supplied: " . $mid;
		trigger_error($msgT, E_USER_ERROR);
		exit();	
	}
	$sql = "SELECT * FROM member WHERE mid=" . $mid;
	$recordset = executeSqlQuery($sql);
	$rowcount = mysqli_num_rows($recordset);
	if ($rowcount == 0) {
		$msgT = $_SESSION['msg'] = "There is no member with MID = " . $mid;
		$_SESSION['msgIcon'] = 'ERROR';
		trigger_error($msgT, E_USER_ERROR);
		exit();
	} else if ($rowcount != 1) {
		$msgT = $_SESSION['msg'] = "DATA ERROR!<br>MID = " . $mid;
		$_SESSION['msgIcon'] = 'ERROR';
		trigger_error($msgT, E_USER_ERROR);
		exit();
	}
	$rowMember = mysqli_fetch_assoc($recordset); 

	// ------------------------------------------------------------------------------------	
	// Select the opeartion to perform based on user input and request a conformation
	if(isset($_REQUEST['BtnIR'])){
		$op = ChooseOperation($_REQUEST['Number'], $rowMember);
		if($op[0] == 'IssueBook'){
			$_SESSION['Confirm']['action'] = $op[0];
			$_SESSION['Confirm']['rowMember'] = $rowMember;
			$_SESSION['Confirm']['rowCopy'] = $op[1];
			$c = $_SESSION['Confirm']['rowCopy'];
			if(isset($_SESSION['Confirm']['rowLoan'])){
				$l = $_SESSION['Confirm']['rowLoan'];
			}
			$_SESSION['Confirm']['msg'] = 'You are about to <strong>issue</strong> the following book:<br> ' .
				"<a href='book_copy_edit.php?ID=" . $c['cid'] . "' target='_blank'>" .
				'[<strong>' . $c['access_no'] . '</strong>] ' . $c['title'] . "</a>" .
				' by ' . $c['authors'] . '<br>' .
				'Published in (' . $c['published_year'] . ') by ' . '(' . $c['publisher'] . ')<br>' .
				'Are you sure?' . '<br>';
						
			header('Location: ir2.php?mid=' . $rowMember['mid']);
			exit();
		} elseif($op[0] == 'ReturnBook'){
			$_SESSION['Confirm']['action'] = $op[0];
			$_SESSION['Confirm']['rowMember'] = $rowMember;
			$_SESSION['Confirm']['rowCopy'] = $op[1];
			$_SESSION['Confirm']['rowLoan'] = $op[2];
			
			
			$c = $_SESSION['Confirm']['rowCopy'];
			$l = $_SESSION['Confirm']['rowLoan'];
			$_SESSION['Confirm']['msg'] = 'You are about to <strong>return</strong> the following book:<br> ' .
				"<a href='book_copy_edit.php?ID=" . $c['cid'] . "' target='_blank'>" .
				'[<strong>' . $c['access_no'] . '</strong>] ' . $c['title'] . "</a>" .
				' by ' . $c['authors'] . '<br>' .
				'Published in (' . $c['published_year'] . ') by ' . '(' . $c['publisher'] . ')<br>'  .
				'Are you sure?' . '<br>';
			header('Location: ir2.php?mid=' . $rowMember['mid']);
			exit();		
		}else { 
			$_SESSION['msg'] = $op;
			$_SESSION['msgIcon'] = 'ERROR';
			header("Location: ir2.php?mid=" . $mid);
			exit();		
		}		
	}

	// ------------------------------------------------------------------------------------	
	// Process confirmation
	if(isset($_REQUEST['BtnConfirmYes'])){ //Confirmed 'Yes'
		$f = $_SESSION['Confirm']['action'];
		$c = $_SESSION['Confirm']['rowCopy'];
		$m = $_SESSION['Confirm']['rowMember'];
		if(isset($_SESSION['Confirm']['rowLoan'])){
			$l = $_SESSION['Confirm']['rowLoan'];
		}
		unset($_SESSION['Confirm']);
		if($f=='IssueBook'){			
			$f($c, $m);
		} elseif( $f=='ReturnBook'){
			$f($c, $l, $m);
		}
		
	} elseif(isset($_REQUEST['BtnConfirmNo'])){ //Confirmed 'No'
		$_SESSION['msg'] = 'Last issue/return was cancelled.';
		$_SESSION['msgIcon'] = 'INFO';
		$m = $_SESSION['Confirm']['rowMember'];
		unset($_SESSION['Confirm']);
		header('Location: ir2.php?mid=' . $m['mid']);
		exit();
	}


	// ------------------------------------------------------------------------------------	
	// Process outstanding payment
	if(isset($_REQUEST['BtnPay']) || isset($_REQUEST['BtnPayCancel'])){
		$fine = $_REQUEST['amount'];
		
		if(isset($_REQUEST['BtnPay'])){
			//TODO: need to enclose this in a transaction
			//1) Delere payable entries
			$sql = 'DELETE FROM payable WHERE mid=' . $mid;
			executeSqlNonQuery($sql);	
			
			//2) Add a single payment entry
			$sql = sprintf("INSERT INTO payment (mid,dt,amount,reason) VALUES (%d,'%s',%d,'%s')", 
				$mid,date("Y-m-d G:i:s"), $fine, 'LatePayment');
			executeSqlNonQuery($sql);
		}
		if(isset($_REQUEST['BtnPayCancel'])){
			$sql = 'DELETE FROM payable WHERE mid=' . $mid;
			executeSqlNonQuery($sql);	
		}
	}

	// ------------------------------------------------------------------------------------	
	// Get Loans details into $rsL 
	$sql = sprintf("select l.lid, l.member mid, l.copy cid, l.date_loaned, l.date_due, " .
	"l.loaned_by, c.*, b.bid, b.title, b.authors  " .
	"FROM ( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) " .
	"WHERE (l.returned=0 AND l.member=%d)", $mid);
	$rsL = executeSqlQuery($sql);
	$rowcountL = mysqli_num_rows($rsL);
	
	// ------------------------------------------------------------------------------------	
	// Get outstanding payments into $rsOP
	$sql = 'SELECT * FROM payable WHERE mid=' . $mid;
	$rsOP = executeSqlQuery($sql);	
	$outstandingPayments = 0;
	while($r = mysqli_fetch_assoc($rsOP)){
		$outstandingPayments += $r['amount'];
	}
	$rsOP = executeSqlQuery($sql);
?>
<body <?php if(!isset($_SESSION['Confirm'])) { echo "onload='ir2.Number.focus();'"; } else {echo "onload='ir2.BtnConfirmYes.focus();'";}?> >
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td>
		<img src="images/<?php if(isset($_SESSION['Confirm'])){ echo ($_SESSION['Confirm']['action']=='IssueBook')?'icon-ir2-issue-200x150.jpg':'icon-ir2-return-200x150.jpg';} else echo 'icon-ir2-200x150.jpg'; ?>" width="200" height="150">
		
		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top">use the 'complete and start new session' button to go back </td>
      </tr>
    </table>	
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            <?php if (isset($_SESSION['CurrentUser'])){ 
				echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";				
	 		} ?>
          </td>
        </tr>
      </table>
    </td>
    <td class="content">
<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg'] != "")) { ?>
<div class="contentEm">
<table border="0">
  <tr>
    <td><img src="images/<?php echo ($_SESSION['msgIcon'] == 'INFO')?"icon-notice-100x75.jpg":"icon-Error-100x75.jpg"?>"></td>
    <td align="left">
	<?php 
		echo stripcslashes($_SESSION['msg']);
		unset($_SESSION['msg']);
	?>	</td>
  </tr>
</table>
</div>
<?php } ?>
	
<form action="ir2.php" method="post" name="ir2" id="ir2">
<input name="mid" id ="mid" type="hidden" value="<?php echo $rowMember['mid']; ?>">
<div class="ir2_box">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td> <!-- box left -->
<?php if(!isset($_SESSION['Confirm'])){ ?>	
	<table width="100%"  border="0">
  <tr>
    <td class="barBox">
	
<h1>Scan Barcode </h1>
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td class="barAccNo">Access Number
	<input name="Number" type="text" id="Number" size=6>
      <input name="BtnIR" type="submit" id="BtnIR2" value="Issue/Return"></td>
  </tr>
</table>
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td class="barDate">Date/Time
      <input name="date" type="text" id="date" size="20">
      <br>
      (only if this is a past issue/return)<br>
Use the format: yy/mm/dd hh:mm</td>
  </tr>
</table>
<?php if( isset($_SESSION['TotalFine']) && ($_SESSION['TotalFine']>0)){ ?>
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td>Total Fine:&nbsp;<input name="totalFine" type="text" value="<?php echo $_SESSION['TotalFine'];?>" size="5">&nbsp;<input name="finePaid" type="checkbox" value="Paid"><label>Paid</label>
	
	</td>
  </tr>
</table>
<?php } ?>
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td><input name="BtnTerminate" type="submit" id="BtnTerminate" value="Complete and Start New Session"></td>
  </tr>
</table>

	</td>
  </tr>
</table>
<?php } else { ?>
<table width="100%"  border="0">
  <tr>
    <td class="barBox">	
<h1>Confirm your Action </h1>
<div class="bg1">
<?php echo $_SESSION['Confirm']['msg']; ?>
</div>
<div class="contentEm">
<input name="BtnConfirmYes" type="submit" id="BtnConfirmYes" value="Yes">&nbsp;&nbsp;&nbsp;
<input name="BtnConfirmNo" type="submit" id="BtnConfirmNo" value="No"><div>
</td>
  </tr>
</table>
<?php } ?>

	</td>
    <td> <!-- box right -->
<h1>Member Details</h1>	
	<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td>
<table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="memberBox"><a target="_blank" href='member_view.php?ID=<?php echo $rowMember['mid']; ?>'><?php echo  $rowMember['title'] . " " . $rowMember['firstnames'] . " " . $rowMember['surname'] . "&nbsp;</a><strong>(" .$rowMember['mem_no'] . ")</strong>"; ?>&nbsp;(</a><a href="member_edit.php?ID=<?php echo  $rowMember['mid']; ?>">Edit</a>)<br>
            <?php echo "<strong>Member#:&nbsp;</strong>" .$rowMember['mid'] . "&nbsp;|&nbsp;<strong>Type:&nbsp;</strong>" .$rowMember['type'] . "&nbsp;|&nbsp;<strong>Reg#:&nbsp;</strong>" .$rowMember['reg_no'] . "&nbsp;|&nbsp;<strong>Index#:&nbsp;</strong>" .$rowMember['index_no'] . "&nbsp;|&nbsp;<strong>NIC#:&nbsp;</strong>" .$rowMember['nic']; ?><br>
            <?php echo  "<strong>Email:&nbsp;</strong>" . $rowMember['email'] . " | <strong>Phone:&nbsp;</strong>" . $rowMember['phone'] . " | <strong>Address:&nbsp;</strong>" . $rowMember['address']; ?> </td>
      </tr>
    </table>	
	</td>
  </tr>
</table>

	<?php if( (isset($_REQUEST['msg'])) && ($_REQUEST['msg'] != "") ){
		echo $_REQUEST['msg'];
	}
	?></td>
  </tr>
</table>
</div>
<?php if($outstandingPayments>0){ ?>
<h1>This Member Has Payments Outstanding</h1>
<form name='formOP' method='POST'>
<div class='contentEm'>
<strong>Total Payable</strong>&nbsp;<?php echo $outstandingPayments . '/='; ?><br>
	<input type='hidden' name='amount' value="<?php echo $outstandingPayments; ?>">	
	<input type='hidden' name='mid' value="<?php echo $_REQUEST['mid']; ?>">
	<input type='submit' name='BtnPay' value="Pay">&nbsp;&nbsp;<input type='submit' name='BtnPayCancel' value="Cancel Payment"><br>
<strong>Payment Details</strong>
<table>
<?php while($r = mysqli_fetch_assoc($rsOP)){ ?>
<tr>
<td>[<?php echo $r['dt']; ?>]</td>
<td><?php echo $r['amount']; ?>/=</td>
<td>(<?php echo $r['reason']; ?>)</td>
</tr>
<?php } ?>
</table>
</div>
</form>
<?php } ?>
<?php if(isset($_SESSION['Returned'])){ ?>
<h1>Books Returned</h1>
<div class="ir2_Returnbox">
<table border="0">
      <tr>
        <td align="left" valign="top"><table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><strong>Title</strong></td>
            <td><strong>Due On</strong></td>
			<td><strong>Returned</strong></td>
            <td><strong>Fine</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php for($i=1;$i<=$_SESSION['Returned'][0];$i++){ ?>
          <tr>
            <td>[<?php echo $_SESSION['Returned'][$i]['copy']['lending_type']; ?>]&nbsp;<?php echo $_SESSION['Returned'][$i]['copy']['access_no']; ?>:&nbsp;<a href='book_view.php?ID=<?php echo $_SESSION['Returned'][$i]['copy']['bid']; ?>'><?php $s=$_SESSION['Returned'][$i]['copy']['title']; if(strlen($s)>30){echo substr($s,0,30) . "...";} else {echo $s;} ?></a>&nbsp;&nbsp;</td>
			<td><?php echo date('Y-m-d',strtotime($_SESSION['Returned'][$i]['loan']['date_due'])); ?>&nbsp;&nbsp;</td>
			<td><?php echo date('Y-m-d',strtotime($_SESSION['Returned'][$i]['loan']['date_returned'])); ?>&nbsp;&nbsp;</td>
            <td><?php echo $_SESSION['Returned'][$i]['loan']['fine']; ?>/=&nbsp;</td>
            <td>(<?php echo $_SESSION['Returned'][$i]['msg']; ?>)</td>
            <td>&nbsp;</td>
          </tr>
          <?php } ?>
        </table>
</td>
      </tr>
    </table>
</div>
<?php } ?>
 

<?php if($rowcountL >0){ ?>
<h1>Current Loans</h1>
<div class="ir2_CurrentLoansBox">   
<table border="0" cellpadding="0" cellspacing="0">
	<?php while($rowL = mysqli_fetch_assoc($rsL)){ ?>
	<tr>
	<td>[<?php echo $rowL['lending_type']; ?>]&nbsp;<?php echo $rowL['access_no']; ?>:&nbsp;<a target="_blank" href='book_view.php?ID=<?php echo $rowL['bid']; ?>'><?php if(strlen($rowL['title'])>30){ echo substr($rowL['title'],0,30) . "...";} else{ echo $rowL['title']; } ?></a>&nbsp;&nbsp;&nbsp;</td>
	<td>[<?php echo date('Y-m-d',strtotime($rowL['date_loaned'])); ?>&nbsp;to&nbsp;<?php echo date('Y-m-d',strtotime($rowL['date_due'])); ?>]</td>
	<td>&nbsp;</td>
	</tr>
	<?php } ?>
</table>
</div>
<?php } ?>   
</form> 
</td>
</tr>
</table>

<?php include("../inc/bottom.php"); ?>
