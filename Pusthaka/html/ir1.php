<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Select Member";
	include('../inc/init.php');

	//If the user came here by pressing the 'Back' button from ir2 send her back to ir2
	if(isset($_SESSION['irMid'])){
		header('Location: ir2.php?mid=' . $_SESSION['irMid']);
		exit();
	}
	
	//Locate by Name
	if(isset($_REQUEST['BtnLocateByName'])){
		$surname = $_REQUEST['surname'];
		$firstnames = $_REQUEST['firstnames'];
		$type = $_REQUEST['Mtype'];
		if($type == 'Any'){
			$sql = sprintf("SELECT * FROM member WHERE expired=0 AND surname LIKE '%%%s%%' AND firstnames LIKE '%%%s%%' ORDER BY surname, firstnames",
				$surname, $firstnames);
		} else {
			$sql = sprintf("SELECT * FROM member WHERE expired=0 AND surname LIKE '%%%s%%' AND firstnames LIKE '%%%s%%' AND type = '%s' ORDER BY surname, firstnames",
				$surname, $firstnames, $type);
		}
		$rsMembers = executeSqlQuery($sql);
		$cntMembers = mysqli_num_rows($rsMembers);
	}
	
	
	
	// Manual Entry
	if(isset($_REQUEST['ManualNumber'])){
		$n = $_REQUEST['ManualNumber'];
		if($_REQUEST['ManualType']=='Book'){
				//Identify book. (Get CID for the Access Number)
				$sql = sprintf("SELECT * FROM copy WHERE access_no='%s'", $n);
				$rs = executeSqlQuery($sql);
				$cnt = mysqli_num_rows($rs);
				if($cnt ==1){
					$r = mysqli_fetch_assoc($rs);
					$cid = $r['cid'];
					$acc_no = $r['access_no'];
				} else {
					$_SESSION['msg'] = "There is no book with the specified access number: " . $n;
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();
				}
	
				// Identify Member (Get MID from the loan table)
				$sqlL = sprintf("select member from loan where returned=0 AND copy=%d", $cid);
				$rsL = executeSqlQuery($sqlL);
				$numRowsL = mysqli_num_rows($rsL);
				if($numRowsL == 0) { // This copy is not curently on loan
					$_SESSION['msg'] = "This book is not curently on loan.<br>Access Number = " . $acc_no;
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();
				} elseif ($numRowsL > 1) { // Loaned to many people
					$_SESSION['msg'] = "DATA INTEGRITY ERROR (Multiple unreturned loan entries for the same book): " . $acc_no;
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();
				} elseif ($numRowsL == 1) { // OK:
					$rowL = mysqli_fetch_array($rsL);
					$mid = $rowL[0];
					header("Location: ir2.php?mid=" .$mid);
					exit();
				} else {
					$_SESSION['msg'] = "DATA ERROR: " . $acc_no;
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();				
				}
		} elseif ($_REQUEST['ManualType']=='Member'){
			
			if(!is_numeric($n)){ 
				$_SESSION['msg'] = 'Member number should be an integer.<br>You entered (<strong>' . $n . '</strong>)';
				header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
				exit();						
			}
			
			$mid = $n;			
			$sql = 'SELECT * FROM member WHERE mid=' . $mid;
			$rs = executeSqlQuery($sql);
			$cnt = mysqli_num_rows($rs);
			if($cnt==1){
				//Check if the membership is valid
				$r = mysqli_fetch_assoc($rs);
				if($r['expired']==1){
					$_SESSION['msg'] = "Membership has expired!<br>" . 
						$r['title'] . ' ' . $r['firstnames'] . ' ' . $r['surname'] . '<br>' . 
						"<a href='member_edit.php?ID=" . $r['mid'] . "'>Edit Member</a>";
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();
				} else {
					header("Location: ir2.php?mid=" .$mid);
					exit();
				}	
			} elseif($cnt==0){
				$_SESSION['msg'] = 'There is no member with the specified member number (<strong>' . $mid . '</strong>)';
				header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
				exit();			
			} else{
				$_SESSION['msg'] = 'DATA ACCESS ERROR: Accessing Member with Number: <strong>' . $mid . '</strong>';
				header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
				exit();
			}
		} elseif($_REQUEST['ManualType']=='OldNum'){
			$mem_no = $n;
			$sql = "SELECT * FROM member WHERE mem_no='" . $mem_no . "'";
			$rs = executeSqlQuery($sql);
			$count = mysqli_num_rows($rs);
			if($count ==1){
				$row = mysqli_fetch_assoc($rs);
				$mid = $row['mid'];
				header("Location: ir2.php?mid=". $mid);
				exit();
			} elseif ($count == 0){
				$_SESSION['msg'] = "There is no member with the specified old member number: " . $mem_no;
				header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
				exit();
			} else {
				$_SESSION['msg'] = "DATA ACCESS ERROR!<br> You enterd <strong>" . $mem_no . '</strong> as the old member number';
				header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
				exit();
			}				
		} //END: if ($_REQUEST['ManualType']=='Book'){
	} //END: if(isset($_REQUEST['BtnManualNext'])){
			

	// Barcode Entry
	if(isset($_REQUEST['Number'])){	
		$n = $_REQUEST['Number'];
		$mid = 0;
		if (strtoupper(substr($n,0,1)) == "A") { //This is the barcode of an access number
				//Identify book. (Get CID for the Barcode)
				$sql = sprintf("SELECT * FROM copy WHERE barcode='%s'", $n);
				$rs = executeSqlQuery($sql);
				$cnt = mysqli_num_rows($rs);
				if($cnt ==1){
					$r = mysqli_fetch_assoc($rs);
					$acc_no = $r['access_no'];
					$cid = $r['cid'];
				} else {
					$_SESSION['msg'] = "There is no book with the specified barcode: " . $n;
					header("Location: " . $_SERVER['PHP_SELF']);
					exit();
				}
	
				// Identify Member (Get MID from the loan table)
				$sqlL = sprintf("select member from loan where returned=0 AND copy=%d", $cid);
				$rsL = executeSqlQuery($sqlL);
				$numRowsL = mysqli_num_rows($rsL);
				if($numRowsL == 0) { // This copy is not curently on loan
					$_SESSION['msg'] = "This book is not curently on loan.<br>Access Number = " . $acc_no;
					header("Location: " . $_SERVER['PHP_SELF']);
					exit();
				} elseif ($numRowsL > 1) { // Loaned to many people
						$msg = "DATA INTEGRITY ERROR (Multiple unreturned loan entries for the same book): " . $acc_no;
				} elseif ($numRowsL == 1) { // OK:
						$rowL = mysqli_fetch_array($rsL);
						$mid = $rowL[0];
						header("Location: ir2.php?mid=" .$mid);
						exit();
				}
				
		} else /*if (strtoupper(substr($n,0,1)) == "M") */ { // This is the barcode on a membership card
			//Identify member. (Get MID for the Barcode)
			$sql = sprintf("SELECT * FROM member WHERE barcode='%s'", $n);
			$rs = executeSqlQuery($sql);
			$cnt = mysqli_num_rows($rs);
			if($cnt ==1){			
				//Check if the membership is valid
				$r = mysqli_fetch_assoc($rs);
				if($r['expired']==1){
					$_SESSION['msg'] = "Membership has expired!<br>" . 
						$r['title'] . ' ' . $r['firstnames'] . ' ' . $r['surname'] . '<br>' . 
						"<a href='member_edit.php?ID=" . $r['mid'] . "'>Edit Member</a>";
					header("Location: " . $_SERVER['PHP_SELF'] . '?ManualType=' . $_REQUEST['ManualType']);
					exit();
				} else {
					header("Location: ir2.php?mid=" .$r['mid']);
					exit();
				}				
			} else {
				$_SESSION['msg'] = "There is no member with the specified barcode: " . $n;
				header("Location: " . $_SERVER['PHP_SELF']);
				exit();
			}
		} /* else { // Error in input
			$_SESSION['msg'] = "The barcode is not in the expected format: " . $n;
			header("Location: " . $_SERVER['PHP_SELF']);
			exit();
		} */
	} //END: if(isset($_REQUEST['BtnNext'])){
?>
<?php
/*
	if(isset($_REQUEST['Number'])){
		echo "onload='ir1.Number.focus();'";
	} elseif(isset($_REQUEST['BtnLocateByName'])){
		echo "onload='formByName.surname.focus();'";
	} else {
		echo "onload='ir1b.ManualNumber.focus();'";
	}
*/	 
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
<table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-ir1-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>

	<table width="100%"  border="0">
      <tr>
        <td><table width="100%"  border="0">
            <tr>
              <td class="marginHelpTitle">selecting a member </td>
            </tr>
            <tr>
              <td class="marginHelp"><strong>Specify the member for the next transaction.</strong><br>
  You may:<br>
  1) Scan the barcode in a mem bership card.<br>
  2) OR: For returns only, scan the barcode in one of the borrowed books to show details of the corresponding member.<br>  <br>
  Manual Entry:<br>
  M&nbsp;&nbsp;for new member number <br>
  O&nbsp;&nbsp;for old member number <br>
  A&nbsp;&nbsp;for access number (add 10000 to the access number first) </td>
            </tr>
        </table></td>
      </tr>
    </table>
	</td>
    <td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
<td class="contents">
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
<h1>Scan Barcode</h1>
<form action="ir1.php" method="post" name="ir1" class="formNormal" id="ir1">
                <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Scan a barcode (membership card or borrowed book)</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="Number" type="text" id="Number"><input name="Number2" type="text" id="Number2" size="1" disabled>
              &nbsp;&nbsp;
              <input name="BtnNext" type="submit" id="BtnNext2" value="Locate Member"></td>
          </tr>
        </table>
            </form>
<h1>Manual Entry</h1>
<form action="ir1.php" method="post" name="ir1b" class="formNormal" id="ir1b">
                <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
            <td>This is a&nbsp;              <label>
              <input type="radio" name="ManualType" value="Member" checked>
  Member Number</label>              <label>
              <input type="radio" name="ManualType" value="Book">
  Access Number</label>
  <label>
              <input type="radio" name="ManualType" value="OldNum">
  Old Member Number</label>
  </td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="ManualNumber" type="text" id="ManualNumber">
              &nbsp;&nbsp;
              <input name="BtnManualNext" type="submit" id="BtnManualNext2" value="Locate Member"> </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
            </form>
<h1>Locate a Member by Name</h1>				
<form method='POST' name='formByName' class='formNormal'>
<table  border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td align="left" scope="col">Surname&nbsp;</td>
                                  <td scope="col"><input name="surname" type="text" id="surname" size='10'></td>
                                  <td scope="col">&nbsp;</td>
                                  <td scope="col">Firstnames&nbsp;</td>
                                  <td scope="col"><input name="firstnames" type="text" id="firstnames" size='10'></td>
                                  <td scope="col">&nbsp;</td>
                                  <td scope="col">Type&nbsp;</td>
                                  <td scope="col"><select name="Mtype" id="Mtype">
                                    <option value="Any" selected>Any</option>
                                    <?php
		$sql = sprintf("SELECT DISTINCT type FROM member ORDER BY type");
		$rs = executeSqlQuery($sql);
		while($row = mysqli_fetch_array($rs)){
			echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
		}	
	?>
                                  </select></td>
                                  <td scope="col">&nbsp;</td>
                                  <td scope="col"><input type="submit" name="BtnLocateByName" value="Locate"></td>
                                  </tr>
                              </table>
</form>
<?php if( isset($cntMembers) && ($cntMembers>0) ){ ?>
<h1>There are <?php echo $cntMembers; ?> members matching the supplied criteria</h1>
<div class='contentEm'>
<strong>Please choose the member by clicking on the member name</strong><br>
<?php
while($r=mysqli_fetch_assoc($rsMembers)){ 
	echo '[' . $r['mid'] . ']&nbsp;' . "<a href='ir2.php?mid=" . $r['mid'] . "'>" . $r['surname'] . ', ' . $r['firstnames'] . '</a>' . ' (' . $r['type'] . ')<br>';
}
?>
</div>
<?php } elseif(isset($cntMembers) && $cntMembers ==0) { ?>
<h1>There are no members matching the supplied criteria</h1>
<?php } ?>
			</td>
          </tr>
    </table>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
