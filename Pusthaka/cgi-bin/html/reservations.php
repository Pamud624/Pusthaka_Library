<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Reservations";
	include('../inc/init.php');
	include('../classes/Reservations.php');
  

	$re = new Reservations;
	$rs = $re->GetReservedBooks();
	$rsNo = mysqli_num_rows($rs);
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Reservations-200x150.jpg"></td>
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
    </td>
    <td>

<?php if((isset($_REQUEST['msg'])) && ($_REQUEST['msg'] != "")) { ?>
<table border="0">
  <tr>
    <td class="msg">
		<?php echo stripcslashes($_REQUEST['msg']); ?>
	</td>
  </tr>
</table>
<?php } ?>
<h1>Active Reservations</h1>
<div class="contentEm">
<div class="bg2">
<?php
if($rsNo <> 0){
	echo "<strong>There is/are reservations for " . $rsNo . " book(s).</strong><br>";
	while($book = mysqli_fetch_assoc($rs)){
			echo "<a href='reservations_book.php?ID=" . $book['bid'] ."'>" . $book['title'] . "</a> [ISBN: " . $book['isbn'] . "] by " . $book['authors'] . "(" . $book['publisher'] . "&nbsp;|&nbsp;" . $book['published_year'] . "&nbsp;|&nbsp;" . $book['edition'] . "&nbsp;|&nbsp;" . $book['pages'] ."p)<br>";
	}
} else {
	echo "<strong>There are no reservations.";
}
?>		
</div>
</div>
</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
