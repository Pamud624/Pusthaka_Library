<?php
//	$allow = "ADMIN;LIBSTAFF";
$allow = "ADMIN";
	$PageTitle = "Edit Book";
	include('../inc/init.php');

	$bks = new Books;

	/** Update 200701 inventory correctness check */
	$iBID = isset($_REQUEST['ID'])?$_REQUEST['ID']:$_REQUEST['bid'];
	// check the status of this book
	$sql3 = "SELECT * FROM book_check WHERE name='200701' AND bid=" . $iBID;
	$rs3 = executeSqlQuery($sql3);
	$rowcount3 = mysqli_num_rows($rs3);
	if($rowcount3>0){ // already checked-in
		$already_checked = true;
		$checkRow = mysqli_fetch_assoc($rs3);
	} else {
		$already_checked = false;
	}
	
	// set this book as checked
	if(isset($_POST['BtnCheckBook'])){
		$current_time = date("Y-m-d G:i:s");
		$sql4 = sprintf("INSERT into book_check (name, datetime, bid, checked, mid, comments) VALUES ('200701','%s',%d,1,%d,'')",
			$current_time, $iBID, $_SESSION['CurrentUser']['mid']);
		$a = executeSqlNonQuery($sql4);
		$rowsUpdated = $a['rows'];
		if($rowsUpdated == 1){			
//			header("Location: book_edit.php?ID=$iBID");
//			exit();		
			//update book
			$book['bid'] = $_POST['bid'];
			$book['isbn'] = $_POST['isbn'];
			$book['class'] = $_POST['class'];
			$book['location'] = $_POST['location'];
			$book['title'] = $_POST['title'];
			$book['authors'] = $_POST['authors'];
			$book['edition'] = $_POST['edition'];
			$book['publisher'] = $_POST['publisher'];
			$book['published_year'] = $_POST['published_year'];
			$book['subjects'] = $_POST['subjects'];
			$book['lang'] = $_POST['lang'];
			$book['series'] = $_POST['series'];
			$book['pages'] = $_POST['pages'];
			
			$bks->updateBook($book);
		}
	}
	
	
	// DELETE Book
	if(isset($_POST['BtnDeleteBook'])){
		$bid = $_POST['bid'];		
		$book = $bks->getBookByID($bid);
		$bks->deleteBook($book);
	}

	// Update book /////////////////////////////////////////////////////
	if(isset($_POST['BtnUpdateBook'])){
		$book['bid'] = $_POST['bid'];
		$book['isbn'] = $_POST['isbn'];
		$book['class'] = $_POST['class'];
		$book['location'] = $_POST['location'];
		$book['title'] = $_POST['title'];
		$book['authors'] = $_POST['authors'];
		$book['edition'] = $_POST['edition'];
		$book['publisher'] = $_POST['publisher'];
		$book['published_year'] = $_POST['published_year'];
		$book['subjects'] = $_POST['subjects'];
		$book['lang'] = $_POST['lang'];
		$book['series'] = $_POST['series'];
		$book['pages'] = $_POST['pages'];
		
		$bks->updateBook($book);		
	}

	//Insert copy /////////////////////////////////////////////////////
	if(isset($_POST['BtnAddCopy'])){
		$copy['bid'] = $_POST['bid'];
		$copy['access_no'] = $_POST['access_no'];
		$copy['reference'] = $_POST['reference'];
		$copy['lending_type'] = $_POST['lending_type'];
		$copy['acquired_on'] = $_POST['acquired_on'];
		$copy['notes'] = $_POST['notes'];
		$copy['price'] = $_POST['price'];
		$copy['currancy'] = $_POST['currancy'];
		
		$bks->addCopy($copy);		
	}

	// Get book details into $row ////////////////////////////////////////////
	if(!isset($_REQUEST['ID'])){
		header("Location: book_browse.php");
		exit();
	}
	$id = $_REQUEST['ID'];	
	$row = $bks->getBookByID($id);	

	// Get details of copies in to a recordset: $rsCopies //////////////////////////	
	$rsCopies = $bks->getCopies($row);
	$NoOfCopies = mysqli_num_rows($rsCopies);        
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="marginNB"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-book-edit-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center" valign="top">&nbsp;</td>
      </tr>
    </table>
	<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="book_view.php?ID=<?php echo $row['bid'] ?>" class="menuLink">view full details</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_add.php" class="menuLink">add new book</a> </td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_.php" class="menuLink">books</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_search.php" class="menuLink">opac</a></td>
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
    <td>
<?php //[Display Message]-------------------------- 
	echoDisplayMsgInSamePage();
?>
<h1>Edit book details</h1>
<form action="book_edit.php" method="post" name="book_edit" class="formNormal" id="book_edit">
                
				<table width="600" border="0" cellpadding="0" cellspacing="0" class="edit_master">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="emphtext">
      <input name="bid" type="hidden" value="<?php echo $row['bid']; ?>">
    </span></td>
    <td>Title</td>
    <td colspan="5"><input name="title" type="text" id="title" value="<?php echo $row['title']; ?>" size="100"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Author(s)</td>
    <td colspan="5"><input name="authors" type="text" id="authors" value="<?php echo $row['authors']; ?>" size="100"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Publisher</td>
    <td colspan="5"><input name="publisher" type="text" id="publisher" value="<?php echo $row['publisher']; ?>" size="100"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Edition</td>
    <td colspan="5"><input name="edition" type="text" id="edition2" value="<?php echo $row['edition']; ?>" size="5">
      &nbsp;&nbsp;Year&nbsp;
      <input name="published_year" type="text" id="published_year" value="<?php echo $row['published_year']; ?>" size="5">
      &nbsp;&nbsp;Class&nbsp;
      <input name="class" type="text" id="class2" value="<?php echo $row['class']; ?>" size="15">
      &nbsp;&nbsp;ISBN&nbsp;
      <input name="isbn" type="text" id="isbn" value="<?php echo $row['isbn']; ?>" size="15">
&nbsp;&nbsp;&nbsp;      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Location</td>
    <td colspan="5"><input name="location" type="text" id="location2" value="<?php echo $row['location']; ?>" size="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Series</td>
    <td colspan="5"><?php echo $row['class']; ?>      <input name="series" type="text" id="series" value="<?php echo $row['series']; ?>" size="100"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Subjects</td>
    <td colspan="5"><input name="subjects" type="text" id="subjects" value="<?php echo $row['subjects']; ?>" size="100"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Pages</td>
    <td colspan="5"><input name="pages" type="text" id="pages" value="<?php echo $row['pages']; ?>" size="5">
      &nbsp;Language&nbsp;
      <input name="lang" type="text" id="lang" value="<?php echo $row['lang']; ?>" size="15">
      &nbsp;&nbsp;</td>
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
    <td><input name="BtnUpdateBook" type="submit" id="BtnUpdateBook" value="Update Book"></td>
    <!--<td class="msg">-->
		<?php
//			if($already_checked){ 
//				echo "Checked<br>";
//				echo $checkRow['datetime'];
//			} else { 
		?>
			<!--<input name="BtnCheckBook" type="submit" id="BtnCheckBook" value="Check Book">-->		
		<?php                 
//                      }
                ?>
	<!--</td>-->
    <td><?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?><input name="BtnDeleteBook" type="submit" id="BtnDeleteBook" value="Delete Book"><?php } ?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<strong>There are <?php echo $NoOfCopies; ?> copies of this book:</strong> (L=Lending, LC=Lecturer Copy, R=Reference)
<table border="0">
  <tr>
    <td class="contentEm">
<?php
$cls = "td4";
while($rowCopy = mysqli_fetch_assoc($rsCopies)){ 
	if ($cls == "td3"){
		 $cls = "td4";
	} else {
		$cls = "td3";
	}
	$str = "&nbsp;&nbsp;<span class='" . $cls . "'>";
	$str = $str . "acc#: <a href='book_copy_edit.php?ID=" . $rowCopy['cid'] . "'>" . $rowCopy['access_no'] . "</a>" . '<strong>' . $rowCopy['lending_type'] . '</strong>';
	if(($rowCopy['notes']!="") && ($rowCopy['notes']!=" ")){
		$str = $str . "<I>" . $rowCopy['notes'] . "</I> ";
	}
	$str = $str . "</span>";
	echo $str;
}
?>	
	</td>
  </tr>
</table>
</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("book_edit");
	frmvalidator1.addValidation("published_year","num","Published Year: must be a number.");
	frmvalidator1.addValidation("pages","num","Pages: must be a number.");
</script>
<h1>Add a new copy for this book</h1>
<form action="book_edit.php" method="post" name="copy_insert" class="formNormal">
              <table border="0" cellpadding="0" cellspacing="0" class="edit_insert">
                <tr>
                  <td><input name="bid" type="hidden" value="<?php echo $row['bid']; ?>">Access Number&nbsp; </td>
                  <td><input name="access_no" type="text" id="access_no" size="10">
                  <?php if(1==2){ ?> Reference&nbsp;
				  <select name="reference" id="reference">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                  </select><?php } ?>&nbsp;&nbsp;
				  Type&nbsp;
				  <select name="lending_type" id="lending_type">
                                    <option value="L" selected>L</option>                    
                                    <option value="PR">PR</option>
                                  </select>
                  &nbsp;&nbsp;Date Acquired&nbsp;
                  <input name="acquired_on" type="text" id="acquired_on" value="<?php echo substr($row['acquired_on'],0,10)?>" size="15">
                  &nbsp;(yyyy-mm-dd)</td>
                </tr>
                <tr>
                  <td>Notes</td>
                  <td><input name="notes" type="text" id="notes" size="80"></td>
                </tr>
				<tr>
					<td> Currancy/Donation </td>
					<td><input name="currancy" type="text" id="currancy" value="LKR" size="15" style="width:60px">	Price <input name="price" type="number" id="price" value="0" size="15"> </td>
				</tr>
				
				
				
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="BtnAddCopy" type="submit" id="BtnAddCopy" value="Add Copy"></td>                  
                </tr>
              </table>
      </form>
<script language="JavaScript">
	var frmvalidator2 = new Validator("copy_insert");
	frmvalidator2.addValidation("access_no","req","Access Number is required.");
</script>
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
