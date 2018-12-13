<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//	$allow = "ADMIN;LIBSTAFF";
$allow = "ADMIN";
	$PageTitle = "Edit Copy";
	include('../inc/init.php');
	$bks = new Books;

	//[Update copy] /////////////////////////////////////////////////////
	if(isset($_POST['BtnUpdate'])){
		  $copy['cid'] = $_POST['cid'];
		  $copy['bid'] = $_POST['bid'];
		  $copy['access_no'] = $_POST['access_no'];
		  $copy['reference'] = $_POST['reference'];
		  $copy['lending_type'] = $_POST['lending_type'];
		  $copy['acquired_on'] = $_POST['acquired_on'];
		  $copy['notes'] = $_POST['notes'];
		  $copy['copy_status'] = $_POST['copy_status'];
          $copy['lost_by'] = $_POST['lost_by'];
          $copy['lost_action'] = $_POST['lost_action'];
          $copy['amount_paid'] = $_POST['amount_paid'];
          $copy['replaced_book'] = $_POST['replaced_book'];
          $copy['action_date'] = $_POST['action_date'];

		  $bks->updateCopy($copy);
	}

    //[Delete Copy] -----------------------------------
	if(isset($_POST['BtnDelete'])){
        $cid = $_POST['cid'];
		$copy = $bks->getCopyByID($cid);
		$bks->deleteCopy($copy);
	}


    //[Change Barcode] -----------------------------------
	if(isset($_POST['BtnChangeBarcode'])){
       	$cid = $_POST['cid'];
	    $barcode1 = $_POST['barcode1'];
        
        $copy = $bks->getCopyByID($cid);
        $bid = $copy['bid'];
        
		if(substr($barcode1,0,1)!='A'){			
            $msg = "The barcode must start with the letter 'A'<hr>" .
			$bks->toStringCopy($copy) . '<hr>' .
			"<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
			$title = 'No Changes Made';
			displayMsg($msg, $title);		
		}		
		
		$bks->changeBarcode($copy, $barcode1);
	}

	/// Get copy details into $row2 ////////////////////////////////////////////
	$id = $_REQUEST['ID'];
	$row2 = $bks->getCopyByID($id);

	/// Get book details into $row ////////////////////////////////////////////
	$row = $bks->getBookByID($row2['bid']);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-book-copy-edit-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="book_edit.php?ID=<?php echo $row2['bid']; ?>" class="menuLink">back to book</a></td>
      </tr>
    </table>	
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            <?php if (isset($_SESSION['CurrentUser'])){ 
				echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
				echo "<a href='_login.php'>logout</a>";
	 		} ?>
          </td>
        </tr>
      </table>
      <table width="100%"  border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    <td class="content">
	<h1>Book Details</h1>
    <table width="100%"  border="0">
  <tr>
    <td class="contentEm">
	<div class="bg1">
	<strong><u><?php echo  $row['title']; ?></u></strong>&nbsp;&nbsp;&nbsp;(<a href="book_edit.php?ID=<?php echo  $row['bid']; ?>">Edit</a>)<br>
            <?php echo "<strong>Authors:&nbsp;</strong>" .$row['authors'] . "&nbsp;|&nbsp;<strong>Edition:&nbsp;</strong>" .$row['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$row['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" .$row['published_year'] ?><br>
            <?php echo  "<strong>ISBN:&nbsp;</strong>" . $row['isbn'] . " | <strong>Class:&nbsp;</strong>" . $row['class'] . " | <strong>Subject(s):&nbsp;</strong>" . $row['subjects']; ?>
	</div>
	</td>
  </tr>
</table>
<h1>Copy Details</h1>
<form action="book_copy_edit.php" method="post" name="copy_edit" class="formNormal">
              <table border="0" cellpadding="0" cellspacing="0" class="edit_insert">
                <tr>
                  <td>
				  <input name="cid" type="hidden" value="<?php echo $row2['cid']; ?>">
				  <input name="bid" type="hidden" value="<?php echo $row2['bid']; ?>">
				  Access Number </td>
                  <td colspan="2"><input name="access_no" type="text" id="access_no" size="10" value="<?php echo $row2['access_no']; ?>">
                  <!-- &nbsp;Reference&nbsp;                  <select name="reference" id="reference">
                    <option value="0" <?php if($row2['reference'] == 0) echo "selected"; ?>>No</option>
                    <option value="1" <?php if($row2['reference'] == 1) echo "selected"; ?>>Yes</option>
                  </select>-->                    Type&nbsp;
                                    <select name="lending_type" id="lending_type">
                                      <option value="L" <?php if($row2['lending_type'] == 'L') echo "selected"; ?>>L (Lending)</option>
                                      <option value="PR" <?php if($row2['lending_type'] == 'PR') echo "selected"; ?>>PR (Permanent Ref)</option>
                                      <option value="LC" <?php if($row2['lending_type'] == 'LC') echo "selected"; ?>>LC (Lecturer Copy)</option>
                                    </select>
&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td>Date Acquired</td>
                  <td colspan="2">
                    <input name="acquired_on" type="text" id="acquired_on2" value="<?php echo substr($row2['acquired_on'],0,10)?>" size="15">
&nbsp;(yyyy-mm-dd)&nbsp; </td>
                </tr>
                <tr>
                  <td>Notes</td>
                  <td><input name="notes" type="text" id="notes2" value="<?php echo $row2['notes']; ?>" size="80"></td>
                  <td>Status&nbsp;&nbsp;
                  <select name="copy_status" id="copy_status">				  
                    <option value="OK" <?php if($row2['copy_status']=='OK') echo 'selected'; ?>>OK (Book is present and not damaged)</option>
                    <option value="D" <?php if($row2['copy_status']=='Damaged' OR $row2['copy_status']=='D') echo 'selected'; ?>>D (Book is Damaged)</option>
                    <option value="L" <?php if($row2['copy_status']=='L') echo 'selected'; ?>>L (Lost by Library)</option>                    
                    <option value="W" <?php if($row2['copy_status']=='W') echo 'selected'; ?>>W (Withdrawn)</option>
		    <option value="TW" <?php if($row2['copy_status']=='TW') echo 'selected'; ?>>TW (Temporarily Withdrawn)</option>
			<option value="LFS" <?php if($row2['copy_status']=='LFS') echo 'selected'; ?>>LFS (Lost by Former Staff)</option>
			<option value="TWA" <?php if($row2['copy_status']=='TWA') echo 'selected'; ?>>TWA (Temporarily Withdrawn Archive)</option>
			<option value="NR" <?php if($row2['copy_status']=='NR') echo 'selected'; ?>>NR (Not Returned)</option>
			<option value="LS" <?php if($row2['copy_status']=='LS') echo 'selected'; ?>>LS (Lost by Students)</option>
			<option value="LSt" <?php if($row2['copy_status']=='LSt') echo 'selected'; ?>>LSt (Lost by Staff)</option>

                  </select>
                      
                  </td>
                </tr>
                
                                <tr>
                  <td>&nbsp;</td>
                  <td><input name="BtnUpdate" type="submit" id="BtnUpdate" value="Update">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                    
                  <td><?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?><input name="BtnDelete" type="submit" id="BtnDelete3" value="Delete Copy"><?php } ?></td>
                </tr>
</table>

    
      </form>

<script language="JavaScript">
	var frmvalidator2 = new Validator("copy_edit");
	frmvalidator2.addValidation("access_no","req","Access Number: is required.");
	//frmvalidator2.addValidation("access_no","num","Acees Number: must be numeric.");
</script>    
    
    
<h1>Barcode</h1>
<div>
<form action="book_copy_edit.php" method="post" name="copy_edit2" class="formNormal">
<table border="0" cellpadding="0" cellspacing="0" class="edit_insert">
                <tr>
                  <td>
                      <input name="cid" type="hidden" value="<?php echo $row2['cid']; ?>">
                      <input name="bid" type="hidden" value="<?php echo $row2['bid']; ?>">
                      Barcode
                  </td>
                  <td colspan="2"><strong><?php echo $row2['barcode']; ?></strong>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td>New Barcode </td>
                  <td colspan="2">
                      <input name="barcode1" type="text" class="msg" size="15">
                      <!--<input name="textfield" type="text" class="msg" size="1" maxlength="0">-->
                      <input name="BtnChangeBarcode" type="submit" id="BtnChangeBarcode" value="Change Barcode"></td>
                </tr>
</table>
</form>
</div>


    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
