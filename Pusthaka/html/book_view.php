<?php
	$allow = "ALL";
	$PageTitle = "Book Info";
	include('../inc/init.php');

	$bks = new Books;

	// Get book details into $row ////////////////////////////////////////////
	if( (!isset($_REQUEST['ID'])) && (!isset($_REQUEST['ano'])) ){
		header("Location: book_browse.php");
		exit();
	}
	
	// Get the BID
	if (isset($_REQUEST['ano'])){
		$ano = $_REQUEST['ano'];
		$sql = "select 	* FROM copy WHERE access_no = '" . $ano . "'";
		$rs = executeSqlQuery($sql);
		$rowCount = mysqli_num_rows($rs);
		if($rowCount == 0){
			trigger_error($ano . " is an invalid access number", E_USER_ERROR);
			exit();
		}
		$row = mysqli_fetch_assoc($rs);
		$id = $row['bid'];
	} else {
		$id = $_REQUEST['ID'];
	}
	
	
	$sql = "SELECT * FROM book WHERE bid=" . $id;
	$recordset = executeSqlQuery($sql);
	$rowcount = mysqli_num_rows($recordset);
	if ($rowcount == 0) {
			trigger_error("There is no book with ID =" . $id, E_USER_ERROR);
			exit();
	} else if ($rowcount >1) {
			trigger_error("DATA ERROR: There is more than one book with ID =" . $id, E_USER_ERROR);
			exit();
	}
	$row = mysqli_fetch_assoc($recordset);    
	
	/// Get previous loans details int $rsLH ///////////////////////
	// Get the access numbers for this book
	$sql = "SELECT cid from copy WHERE bid =" . $id;
	$rsCopies = executeSqlQuery($sql);
	$rowcountCopies = mysqli_num_rows($rsCopies);
	if(!($rowcountCopies >= 1)){
			trigger_error("DATA ERROR: There is no copies of book with  ID =" . $id, E_USER_ERROR);
			exit();		
	}
	$crit = "";
	while($r = mysqli_fetch_assoc($rsCopies)){ // Build WHERE clause
		$crit = $crit . " l.copy=" . $r['cid'] . " OR";
	}
	// Remove the last OR
	$crit = substr($crit,0,strlen($str)-2);

	// Get the data
	$sqlLH = sprintf("select l.lid, l.member mid, concat(m.title, ' ',  m.firstnames, ' ', m.surname)  membername, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
	" c.copy_status, l.loaned_by, c.access_no, b.bid, b.title, b.authors  " .
	"FROM (( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid)) " .
	"LEFT JOIN member m ON l.member = m.mid " .
	"WHERE (l.returned=1 AND (%s)) ORDER BY l.date_loaned, c.access_no", $crit);
	$rsLH = executeSqlQuery($sqlLH);
	$rowcountLH = mysqli_num_rows($rsLH);	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-opac-200x150.jpg"></td>
      </tr>
      <tr>
        <td align="center" valign="top">&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>
	  <tr>
        <td align="center" valign="top"><a href="book_edit.php?ID=<?php echo $row['bid'] ?>" class="menuLink">edit book</a></td>
      </tr>	  
      <tr>
        <td align="center" valign="top"><a href="book_add.php" class="menuLink">add new book</a></td>
      </tr>	  
      <tr>
        <td align="center" valign="top"><a href="book_.php" class="menuLink">books</a></td>
      </tr>
	  <?php } ?>
      <tr>
        <td align="center" valign="top"><a href="book_search.php" class="menuLink">opac</a></td>
      </tr>
    </table>	
      <table width="100%"  border="0">
        <tr>
          <td align="center" class="marginLogin">
            <?php if (!isset($_SESSION['CurrentUser'])){ ?>
            <form action="_login.php" method="post" name="login" class="marginLogin" id="login">
              <table width="100%"  border="0">
                <tr>
                  <td>Username</td>
                  <td><input name="Username" type="text" class="marginLoginText" id="Username3" size="10"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><input name="Password" type="password" class="marginLoginText" id="Password4" size="10"></td>
                  <td><input name="btnLogin" type="submit" class="marginLoginButton" id="btnLogin5" value="Login"></td>
                </tr>
              </table>
            </form>
            New user? Please <a href="#">register</a>.
            <?php } else { 
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
    <td align="left" valign="top">

<table width="100%"  border="0">
  <tr>
    <td class="content">
<h1><?php echo $row['title']; ?></h1>	
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">
<div class="bg1"><strong><?php echo  $row['title']; ?></strong><?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>&nbsp;&nbsp;&nbsp;(<a href="book_edit.php?ID=<?php echo  $row['bid']; ?>">Edit</a>)<?php } ?><br>
            <?php echo "<strong>Authors:&nbsp;</strong>" .$row['authors'] . "&nbsp;|&nbsp;<strong>Edition:&nbsp;</strong>" .$row['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$row['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" .$row['published_year'] ?><br>
            <?php echo  "<strong>ISBN:&nbsp;</strong>" . $row['isbn'] . " | <strong>Class:&nbsp;</strong>" . $row['class'] .  " | (<strong>Location:&nbsp;</strong>" . $row['location'] . ") | <strong>Subject(s):&nbsp;</strong>" . $row['subjects']; ?><br>

<?php // Display copies and availabilty
        $sqlCopies = sprintf("SELECT * FROM copy WHERE copy_status='OK' AND bid=%d ORDER BY access_no",$row['bid']);
        $recordsetCopies = executeSqlQuery($sqlCopies);
        $NoOfCopies = mysqli_num_rows($recordsetCopies);
        $CopiesString = "<strong>Copies:</strong>";
        while($rowCopies = mysqli_fetch_assoc($recordsetCopies)){
                $cid = $rowCopies['cid'];
                $CopiesString = $CopiesString . " " . $rowCopies['access_no'] . '<strong>[' . stripslashes($rowCopies['lending_type']) . ']</strong>';
                // Check availability
                $sqlLoans = sprintf("SELECT * FROM loan WHERE copy=%d AND returned=0",$cid);
                $recordsetLoans = executeSqlQuery($sqlLoans);
                $NoOfLoans = mysqli_num_rows($recordsetLoans);

                if($NoOfLoans == 0) {
                        $CopiesString = $CopiesString . "(available)";
                } else if ($NoOfLoans == 1 ){
                        $rowLoan = mysqli_fetch_assoc($recordsetLoans);
                        $mid = $rowLoan['member'];
                        $due = $rowLoan['date_due'];

                        // Get member info
                        $sqlMember = sprintf("SELECT * FROM member WHERE mid=%d",$mid);
                        $recordsetMembers = executeSqlQuery($sqlMember);
                        $rowMember = mysqli_fetch_assoc($recordsetMembers);
                        if($rowMember) {
                                $MemberName = $rowMember['title'] . " " . $rowMember['firstnames'] . " " . $rowMember['surname'];
                                $CopiesString = $CopiesString . "(<strong><em>with " . $MemberName . "</em></strong>)";
                        }
                        mysqli_free_result($recordsetMembers);
                } else if ($NoOfLoans > 1 ){
                        $CopiesString = $CopiesString . "(<strong>Data Error</strong>)";
                }

                mysqli_free_result($recordsetLoans);

        }
        mysqli_free_result($recordsetCopies);
?>
        <br>
        <?php echo $CopiesString; ?><br>
		<?php if (isset($_SESSION['CurrentUser'])){ ?>
		<a href='reservations_book.php?ID=<?php echo $row['bid']; ?>'>Reserve this book</a><?php } ?>
</div>	</td>
  </tr>
</table>
<form class="formNormal" name="form1" method="post" action="book_view.php">
 Locate a book by access number: �
 <input name="ano" type="text" id="ano">
<input name="BtnFindByAccNo" type="submit" id="BtnFindByAccNo2" value="Find">
</form>
<script language="JavaScript">
	var frmvalidator1 = new Validator("form1");
	frmvalidator1.addValidation("ano","req","Please enter an access number.");
	frmvalidator1.addValidation("ano","num","Access number must be numeric.");
</script>

<h1><span class="td1h">Book Loan History&nbsp;(<?php echo $rowcountLH; ?> times)</span></h1>
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">
<?php if($rowcountLH>0){ ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="contents"><table width="100%"  border="0" class="td1">
              <?php $x=0; while($rowLH = mysqli_fetch_assoc($rsLH)){ ?>
              <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
                <td><?php echo $rowLH['access_no']; ?>:<a target="_blank" href='member_view.php?ID=<?php echo $rowLH['mid']; ?>'><?php echo substr($rowLH['membername'],0,30) . "..."; ?></a>&nbsp;[<?php echo $rowLH['date_loaned']; ?>/<?php echo $rowLH['date_returned']; ?>]&nbsp;&nbsp;
				</td>
              </tr>
              <?php } ?>
            </table>
<?php } else {
	echo "There are no record of this book being lent in the past.";
	}
 ?>				
			</td>
          </tr>
    </table>	
	</td>
  </tr>
</table>


	</td>
  </tr>
</table>

	
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
