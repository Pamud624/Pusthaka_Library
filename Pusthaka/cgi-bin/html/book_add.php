<?php
//        $allow = "ADMIN;LIBSTAFF";
        $allow = "ADMIN";
		$PageTitle = "Add New Book";
        include('../inc/init.php');		

        // Add book /////////////////////////////////////////////////////
        if(isset($_POST['BtnAdd'])){
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
			// Validate data
			if($book['title']==""){
				displayMsgInSamePage('Title is required');
			}
			if($book['isbn']==""){
				displayMsgInSamePage('ISBN is required');
			}
			if($book['authors']==""){
				displayMsgInSamePage('Authors is required');
			}

			/*if(($acquired_on!="") && (strtotime($acquired_on)==-1)){
				  $msg = "The 'Acquired Date' should be in the format yyyy-mm-dd";
				header("Location: book_add.php?msg=" .$msg . "&" . $querystring);
				exit();
			} */

			//Add Book                  
			$bks = new Books;
			$bks->addBook($book);				  
        }

        // Check ISBN /////////////////////////////////////////////////////
		if(isset($_POST['BtnCheckISBN'])){
			$sqlBooks = sprintf("SELECT * FROM book WHERE isbn LIKE '%%%s%%' ORDER BY isbn", $_POST['isbn']);
			$rsBooks = executeSqlQuery($sqlBooks);
			$NoOfBooks = mysqli_num_rows($rsBooks);
			foreach($_POST as $key=>$val){ // Store page settings so that the form will be filled
				$_SESSION['page_state']['book_add'][$key] = $val;
			}
		}

        // Check Title /////////////////////////////////////////////////////
		if(isset($_POST['BtnCheckTitle'])){
			$sqlBooks = sprintf("SELECT * FROM book WHERE %s ORDER BY title", BuildSearchCriteriaString($_POST['title'],"title","AND"));
			$rsBooks = executeSqlQuery($sqlBooks);
			$NoOfBooks = mysqli_num_rows($rsBooks);
			foreach($_POST as $key=>$val){ // Store page settings so that the form will be filled
				$_SESSION['page_state']['book_add'][$key] = $val;
			}
		}
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-BooksAdd-200x150.jpg" width="200" height="150"></td>
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

<?php if((isset($_SESSION['msg'])) && ($_SESSION['msg']['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
		<?php echo $_SESSION['msg']['msg']; ?>
	</td>
  </tr>
</table>
<?php } unset($_SESSION['msg']); ?>
<?php if(isset($_REQUEST['BtnCheckISBN']) || isset($_REQUEST['BtnCheckTitle'])) {?>
<table width="100%"  border="0">
  <tr>
    <td class="SimilarRecords">      <?php
        if($NoOfBooks <> 0){
                echo "<strong>There is/are " . $NoOfBooks . " similar book(s).</strong>";
                while($book = mysqli_fetch_assoc($rsBooks)){
                        echo "<br><a href='book_edit.php?ID=" . $book['bid'] ."'>" . $book['title'] . "</a> [ISBN: " . $book['isbn'] . "] by " . $book['authors'] . "(" . $book['publisher'] . "&nbsp;|&nbsp;" . $book['published_year'] . "&nbsp;|&nbsp;" . $book['edition'] . "&nbsp;|&nbsp;" . $book['pages'] ."p)";
                }
        } else {
                echo "There are no simillar books.";
        }
?>
	</td>
  </tr>
</table>
<?php } ?>
<form action="book_add.php" method="post" name="book_add" class="formNormal" id="book_add">
<table border="0" cellpadding="0" cellspacing="0" class="edit_master">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td>Title</td>
    <td colspan="5"><input name="title" type="text" class="formNormalRequired" id="title" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['title']); ?>" size="90">
      <input name="BtnCheckTitle" type="submit" id="BtnCheckTitle" value="Check"></td>
    </tr>
  <tr>
    <td></td>
    <td>ISBN</td>
    <td colspan="5">
        <input name="isbn" type="text" id="isbn" size="15" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['isbn']); ?>">
        <input name="BtnCheckISBN" type="submit" id="BtnCheckISBN2" value="Check">
    </td>
  </tr>    

      
      
    
  <tr>
    <td>&nbsp;</td>
    <td>Author(s)</td>
    <td colspan="5"><input name="authors" type="text" id="authors" size="100" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['authors']); ?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Publisher</td>
    <td colspan="5"><input name="publisher" type="text" id="publisher" size="100" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['publisher']); ?>"></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>Edition</td>
    <td colspan="5"><input name="edition" type="text" id="edition" size="5" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['edition']); ?>">
      &nbsp;&nbsp;Year&nbsp;
      <input name="published_year" type="text" id="published_year" size="5" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['published_year']); ?>">
</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>Class</td>
    <td><input name="class" type="text" id="class" size="10" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['class']); ?>"></td>
    <td>Location</td>
    <td><input name="location" type="text" id="location" size="4" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['location']); ?>"></td>
    <td colspan="2">
        &nbsp;
    </td>
    </tr>
  <tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>Series</td>
    <td colspan="5"><input name="series" type="text" id="series" size="100" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['series']); ?>"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Subjects</td>
    <td colspan="5"><input name="subjects" type="text" id="subjects" size="100" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['subjects']); ?>"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Pages</td>
    <td colspan="5"><input name="pages" type="text" id="pages" size="4" value="<?php if(isset($_SESSION['page_state']['book_add'])) echo stripslashes($_SESSION['page_state']['book_add']['pages']); ?>">
      &nbsp;&nbsp;Language
      &nbsp;
      <input name="lang" type="text" id="lang3" size="5" value="<?php if (isset($_SESSION['page_state']['book_add'])){ echo stripslashes($_SESSION['page_state']['book_add']['lang']); } else {echo "E";} ?>">
      &nbsp;&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>      <br>      </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="BtnAdd" type="submit" id="BtnAdd" value="Add Book"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
</table>
      </form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("book_add");
	//frmvalidator1.addValidation("title","req","Title: is required.");
</script>
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
<?php
	if (isset($_SESSION['page_state']['book_add'])){
		unset($_SESSION['page_state']['book_add']);
	}
?>
