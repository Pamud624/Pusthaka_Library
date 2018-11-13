<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Books";
	include('../inc/init.php');

	//Calculate the stats on books
	$sql = "SELECT count(cid) FROM copy";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noCopies = $r[0];

	$sql = "SELECT count(bid) FROM book";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noTitles = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE lending_type='L'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noL = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE lending_type='LC'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noLC = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE lending_type='PR'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noPR = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE NOT(lending_type='PR' OR lending_type='LC' OR lending_type='L')";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$noOther = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='L'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_L = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='SL'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_SL = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='S3L'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_S3L = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='S2'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_S2 = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='S3'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_S3 = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='S'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_S = $r[0];

	$sql = "SELECT count(cid) FROM copy WHERE copy_status='S1'";
	$rs = executeSqlQuery($sql);
	$r = mysqli_fetch_array($rs);
	$no_S1 = $r[0];		
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Books-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
<table width="100%" border="0" align='center' class="menu">
      <tr>
        <td align="center" valign="top"><a href="book_add.php" class="menuLink">add book</a> </td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="book_search.php" class="menuLink">opac</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="_newarrivals.php" class="menuLink">new books</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="circulation.php" class="menuLink">circulation</a></td>
      </tr>
      <tr>
        <td align="center" valign="top"><a href="purchase_requests.php" class="menuLink">purchase requests</a>  </td>
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
	<h1>Stats</h1>
	<div class='contentEm'>
<p>There are <strong><?php echo $noCopies; ?></strong> books (<strong><?php echo $noTitles; ?></strong> titles) <br>
        &nbsp;&nbsp;&nbsp;Lending (L): <strong><?php echo $noL; ?></strong> books <br>
        &nbsp;&nbsp;&nbsp;Lecture Copy (LC): <strong><?php echo $noLC; ?></strong> books <br>
        &nbsp;&nbsp;&nbsp;Permenant Reference (PR): <strong><?php echo $noPR; ?></strong> books<br>
        &nbsp;&nbsp;&nbsp;Other (?): <strong><?php echo $noOther; ?></strong> books</p>
      <p>  <br>
          During the period from 199x to 2005 July (before Pusthaka)<br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_L; ?></strong> books went missing (probably stolen) <em>[L]</em><br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_SL; ?></strong> books were lost by academic staff <em>[SL]</em> <br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_S3L; ?></strong> books were lost by students <em>[S3L]</em> <br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_S2; ?></strong> books are still with academic staff who has left the organization <em>[S2]</em> <br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_S3; ?></strong> books are still with students <em>[S3]</em> <br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_S; ?></strong> books are still with academic staff <em>[S]</em> <br>
          <strong>&nbsp;&nbsp;&nbsp;<?php echo $no_S1; ?></strong> books are with academic staff on study leave <em>[S1]</em> </p>	
	</div>
	</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
