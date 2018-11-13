<?php
	$allow = "ALL";
	$PageTitle = "New Arrivals";
	include('../inc/init.php');


	/// Get book details into $row ////////////////////////////////////////////
	$sql = "SELECT * FROM book ORDER BY bid DESC LIMIT 0,50 ";
	$recordset = executeSqlQuery($sql);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-newarrivals-200x150.jpg"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td class="content">
<h1>New Entries to the Library</h1>
<div class="contentEm">
<table width="100%"  border="0" cellpadding="0">
  <tr>
    <td>
<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
      <?php
  $x = 0;
  while ($row = mysqli_fetch_assoc($recordset)) { ?>
      <tr align="left" class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
        <td>&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><strong><a href='book_view.php?ID=<?php echo $row['bid']; ?>'><?php echo  $row['title']; ?></a></strong><?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>&nbsp;&nbsp;&nbsp;(<a href="book_edit.php?ID=<?php echo  $row['bid']; ?>">Edit</a>)<?php } ?><br>
            <?php echo "<strong>Authors:&nbsp;</strong>" .$row['authors'] . "&nbsp;|&nbsp;<strong>Edition:&nbsp;</strong>" .$row['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$row['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" .$row['published_year'] ?><br>
            <?php echo  "<strong>ISBN:&nbsp;</strong>" . $row['isbn'] . " | <strong>Class:&nbsp;</strong>" . $row['class'] . " | (<strong>Location:&nbsp;</strong>" . $row['location'] . ") | <strong>Subject(s):&nbsp;</strong>" . $row['subjects']; ?><br>

<?php // Display copies and availabilty
        $sqlCopies = sprintf("SELECT * FROM copy WHERE bid=%d ORDER BY access_no",$row['bid']);
        $recordsetCopies = executeSqlQuery($sqlCopies);
        $NoOfCopies = mysqli_num_rows($recordsetCopies);
        $CopiesString = "<strong>Copies:</strong>";
        while($rowCopies = mysqli_fetch_assoc($recordsetCopies)){
                $cid = $rowCopies['cid'];
                $CopiesString = $CopiesString . " " . $rowCopies['access_no'];

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
                                $CopiesString = $CopiesString . "(with " . $MemberName . ")";
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
		<a href='reservations_book.php?ID=<?php $row['bid']; ?>'>Reserve this book</a><?php } ?>
        </td>
      </tr>
      <tr align="left">
        <td>&nbsp;</td>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <?php } ?>
    </table>	
	</td>
  </tr>
</table>

</div></td>
</tr>
</table>
<?php include("../inc/bottom.php"); ?>
