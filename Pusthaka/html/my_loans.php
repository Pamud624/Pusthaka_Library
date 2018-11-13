<?php
	$allow = "ADMIN;LIBSTAFF;PATRON";
	$PageTitle = " My Loans";
	include('../inc/init.php');

	$sql = sprintf("select l.lid, l.member mid, l.copy cid, l.date_loaned, l.date_due, " .
	"l.loaned_by, c.access_no, b.*  " .
	"FROM ( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) " .
	"WHERE (l.returned=0 AND l.member=%d)", $_SESSION['CurrentUser']['mid']);
	$rsL = executeSqlQuery($sql);
	$rowcountL = mysqli_num_rows($rsL);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-MyLoans-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="my_reservations.php" class="menuLink">my reservations </a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_info.php" class="menuLink">my info</a> </td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="my_history.php" class="menuLink">my history </a></td>
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
    </td>
    <td>

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
<?php if($rowcountL >0){ ?>
<h1>You have&nbsp;<?php echo $rowcountL; ?>&nbsp;book(s) on loan </h1>
<div class="contentEm">
  <table border="0" cellpadding="0" cellspacing="0">
    <?php 
	$x = 0;
	while($rowL = mysqli_fetch_assoc($rsL)){ ?>
    <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
      <td><?php 
	  	echo "<a target='_blank' href='book_view.php?id=" . $rowL['bid'] . "'>" . $rowL['title'] . "</a>&nbsp;by&nbsp;" . $rowL['authors'] . "<br>";		
		echo "<strong>Edition:&nbsp;</strong>" .$rowL['edition'] . "&nbsp;|&nbsp;<strong>Publisher:&nbsp;</strong>" .$rowL['publisher'] . "&nbsp;|&nbsp;<strong>Year:&nbsp;</strong>" . $rowL['published_year'] . "<br>";
		echo "<strong>ISBN:&nbsp;</strong>" . $rowL['isbn'] . " | <strong>Class:&nbsp;</strong>" . $rowL['class'] .  " | (<strong>Location:&nbsp;</strong>" . $rowL['location'] . ") | <strong>Subject(s):&nbsp;</strong>" . $rowL['subjects'] . "<br>";
		echo "<strong>Access Number:&nbsp;</strong>" . $rowL['access_no'] . "<br>";
		echo "<strong>Date Loaned:&nbsp;</strong>" . $rowL['date_loaned'] . " | <strong>Date Due:&nbsp;</strong>" . $rowL['date_due'] . "<br>";				
		?>
	  </td>
    </tr>
	<tr><td>&nbsp;</td></tr>
    <?php } ?>
  </table>
</div>
<?php } else { ?>
<div class='contentNormal'>
You do not currently have any books on loan.
</div>
<?php } ?>
</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
