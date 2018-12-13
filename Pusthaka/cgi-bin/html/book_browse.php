<?php
	$allow = "ALL";
	$PageTitle = "Catalog";
	include('../inc/init.php');

	//Save the last used search criteria
	if(isset($_REQUEST['btnSearchOPAC']) or isset($_REQUEST['TotalRecords'])){
			$_SESSION['page_state']['opac']['SearchTitle'] = $_REQUEST['SearchTitle'];
			$_SESSION['page_state']['opac']['SearchAuthors'] = $_REQUEST['SearchAuthors'];
			$_SESSION['page_state']['opac']['SearchSubjects'] = $_REQUEST['SearchSubjects'];
			$_SESSION['page_state']['opac']['SearchISBN'] = $_REQUEST['SearchISBN'];		
	}
		
	if(isset($_REQUEST['btnSearchOPAC']) or isset($_REQUEST['TotalRecords'])){
		// Make sure that the request comes from the search page or the browse page
			$SortSearchOPAC1 = $_REQUEST['SortSearchOPAC1'];
			$SortSearchOPAC2 = $_REQUEST['SortSearchOPAC2'];
			$SortSearchOPAC3 = $_REQUEST['SortSearchOPAC3'];
			$SortSearchOPAC4 = $_REQUEST['SortSearchOPAC4'];
	
			$SearchTitle = $_REQUEST['SearchTitle'];
			$SearchAuthors = $_REQUEST['SearchAuthors'];
			$SearchSubjects = $_REQUEST['SearchSubjects'];
			$SearchISBN = $_REQUEST['SearchISBN'];
	
			$LimitN = $_REQUEST['LimitN']; // Number of records per page
			$LimitI = $_REQUEST['LimitI']; // Number of records per page
	
			/// Retrieve a recordset from the database //////////////////////////////
			// Build the WHERE clause
			$WhereClause = "WHERE 1=1 AND"; // '1=1 AND' is for situations when there's no value in any field
			if($SearchTitle != ""){
					$WhereClause = $WhereClause . BuildSearchCriteriaString($SearchTitle,"title","AND") . " AND";
			}
			if($SearchAuthors != ""){
					$WhereClause = $WhereClause . BuildSearchCriteriaString($SearchAuthors,"authors","AND") . " AND";
			}
			if($SearchSubjects != ""){                        
					$WhereClause = $WhereClause . BuildSearchCriteriaString($SearchSubjects,"subjects","AND") . " AND";
			}
			if($SearchISBN != ""){
					$WhereClause = $WhereClause . " isbn LIKE '%" . $SearchISBN . "%' AND";
			}
	
			// remove the last AND
			$WhereClause = substr($WhereClause,0,strlen($WhereClause)-3);	
	
			// Calculate $TotalRecords
			if (!isset($_REQUEST['TotalRecords'])){
					$sqlTotal = sprintf("SELECT COUNT(bid) FROM book %s", $WhereClause);
					$recordsetTotal = executeSqlQuery($sqlTotal);
					$rowTotal = mysqli_fetch_array($recordsetTotal);
					$TotalRecords = $rowTotal[0];
			} else {
					$TotalRecords = $_REQUEST['TotalRecords'];
			}
	
			/// Recordset Paging
			// Navigate records: Set the start index from which to return records
			if(isset($_REQUEST['BtnFirst'])){
					$LimitI = 0;
			} else if (isset($_REQUEST['BtnLast'])) {
					$LimitI = floor($TotalRecords / $LimitN) * $LimitN;
			} else if (isset($_REQUEST['BtnPrevious'])) {
					$LimitI = $LimitI - $LimitN;
					if($LimitI <0) $LimitI = 0;
			} else if (isset($_REQUEST['BtnNext'])) {
					$LimitI = $LimitI + $LimitN;
					if($LimitI >= $TotalRecords) $LimitI = 0; // Wrap around to the first page
			}
	
			// Retrieve records
			$sql = sprintf("SELECT * FROM book %s ORDER BY %s, %s, %s, %s LIMIT %d, %d",
					$WhereClause, $SortSearchOPAC1, $SortSearchOPAC2, $SortSearchOPAC3, $SortSearchOPAC4, $LimitI, $LimitN);
			$recordset = executeSqlQuery($sql);
			$rowcount = mysqli_num_rows($recordset);
	
	} else  { // Directly opened
			header("Location: book_search.php");
			exit();
	}
?>
<?php
	// Calculate: $TotalPages, $CurrentPage, $SearchCriteria
	$TotalPages = ceil($TotalRecords / $LimitN);
	if($LimitI == 0){
			$CurrentPage = 1;
	} else{
			$CurrentPage = floor($LimitI/$LimitN) +1;
	}

	//Build search criteria string for displaying in the page
	$SearchCriteria = "";
	if ($SearchTitle != ""){
			$SearchCriteria = $SearchCriteria . "Title = " . $SearchTitle . ", ";
	}
	if ($SearchAuthors != ""){
			$SearchCriteria = $SearchCriteria . "Author = " . $SearchAuthors . ", ";
	}
	if ($SearchSubjects != ""){
			$SearchCriteria = $SearchCriteria . "Subject = " . $SearchSubjects . ", ";
	}
	if ($SearchISBN != ""){
			$SearchCriteria = $SearchCriteria . "ISBN = " . $SearchISBN . ", ";
	}
	// remove the last two characters
	$SearchCriteria = substr($SearchCriteria,0,strlen($SearchCriteria)-2);
	if($SearchCriteria == "") $SearchCriteria = "Show All";
	
				
	if(isset($_REQUEST['btnSearchOPAC'])){ //Log Event			
		$desBy = (isset($_SESSION['CurrentUser']))?$_SESSION['CurrentUser']['title'] . ' ' . $_SESSION['CurrentUser']['firstnames'] . ' ' . $_SESSION['CurrentUser']['surname']:"GUEST";
		$des = addslashes($SearchCriteria) . ' [<strong>' . $desBy . '</strong>]';
		logEvent('SEARCH_BOOKS', $_SESSION['CurrentUser']['mid'], $_SESSION['CurrentUser']['mid'], addslashes($des));
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
<!-- MARGIN WAS HERE -->
    <td>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
                <td class="contents">
<?php if($TotalRecords>0){ ?>				
<table width=100% border="0">
  <tr>
    <td class="contentEm">
	<?php include('book_browse_inc1.php'); ?>	</td>
  </tr>
</table>
<table width=100% border="0">
  <tr>
    <td class="contentEm">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
      <?php
  $x = 0;
  while ($row = mysqli_fetch_assoc($recordset)) { ?>
      <tr align="left" class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
        <td>&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><strong><a href='book_view.php?ID=<?php echo $row['bid']; ?>'><?php echo  $row['title']; ?></a></strong>&nbsp;&nbsp;(<strong>Class</strong>: <?php echo $row['class']; ?>)&nbsp;&nbsp;<?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>&nbsp;&nbsp;&nbsp;(<a href="book_edit.php?ID=<?php echo  $row['bid']; ?>">Edit</a>)<?php } ?><br>
            <?php echo "<strong>Authors:&nbsp;</strong>" .$row['authors'] . "&nbsp;|&nbsp;<strong>Edition:&nbsp;</strong>" .$row['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$row['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" .$row['published_year'] ?><br>
            <?php echo  "<strong>ISBN:&nbsp;</strong>" . $row['isbn']  . " | (<strong>Location:&nbsp;</strong>" . $row['location'] . ") | <strong>Subject(s):&nbsp;</strong>" . $row['subjects']; ?><br>

<?php // Display copies and availabilty
        $sqlCopies = sprintf("SELECT * FROM copy WHERE copy_status='OK' AND bid=%d ORDER BY access_no",$row['bid']);
        $recordsetCopies = executeSqlQuery($sqlCopies);
        $NoOfCopies = mysqli_num_rows($recordsetCopies);
        $CopiesString = "<strong>Copies:</strong>";
        while($rowCopies = mysqli_fetch_assoc($recordsetCopies)){
                $cid = $rowCopies['cid'];
                $CopiesString = $CopiesString . " " . $rowCopies['access_no'] . '-<strong>' . $rowCopies['lending_type'] . '</strong>';

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
                                $CopiesString = $CopiesString . "&nbsp;(with <strong>" . $MemberName . "</strong>)";
                        }
                        mysqli_free_result($recordsetMembers);
                
				} else if ($NoOfLoans > 1 ){
                        $CopiesString = $CopiesString . "(<strong>Data Error</strong>)";
                }

                mysqli_free_result($recordsetLoans);

        }
        mysqli_free_result($recordsetCopies);
?>
        <br><?php echo $CopiesString; ?><br>
		<?php if (isset($_SESSION['CurrentUser'])){ ?>
		<a href='reservations_book.php?ID=<?php echo $row['bid']; ?>'>Reserve this book</a><?php } ?>
        </td>
      </tr>
      <tr align="left">
        <td>&nbsp;</td>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
</table>	
	</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td class="contentEm"><?php include('book_browse_inc1.php'); ?></td>
  </tr>
</table>
<?php } else {//If there are no results ?>
<h1>There are no books matching the criteria you specified</h1>
Search criteria: (<?php echo $SearchCriteria; ?>)<br>
<a href="book_search.php">Search Again</a>
<?php } ?>


                </td>
          </tr>
        </table>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
