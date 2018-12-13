<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Circulation";
	include('../inc/init.php');

	// Set Time Period
	if(isset($_REQUEST['period'])){
		$p = $_REQUEST['period'];
		if($p == 'Today'){
			$y = date("Y"); $m = date("m"); $d = date("d");		
			$date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
			$date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
		} elseif($p == 'Yesterday'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m,$d-1,$y);
			$date_endTS = mktime(23,59,59,$m,$d-1,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'ThisMonth'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m,1,$y);
			$date_endTS = mktime(23,59,59,$m+1,0,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'LastMonth'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,$m-1,1,$y);
			$date_endTS = mktime(23,59,59,$m,0,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'ThisYear'){
			$y = date("Y"); $m = date("m"); $d = date("d");
			$date_startTS = mktime(0,0,0,1,1,$y);
			$date_endTS = mktime(23,59,59,12,31,$y);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} elseif($p == 'InYear'){
			$Year = $_REQUEST['Year'];
			if($Year <2000){$Year = $Year+2000;}
			$date_startTS = mktime(0,0,0,1,1,$Year);
			$date_endTS = mktime(23,59,59,12,31,$Year);
			$date_start = date("Y-m-d G:i:s",$date_startTS);
			$date_end = date("Y-m-d G:i:s",$date_endTS);
		} else {
			$date_start = '1900-01-01';
			$date_end =  '2900-12-31';
		}
	} else {
			$y = date("Y"); $m = date("m"); $d = date("d");		
			$date_start = $y . '-' . $m . '-' . $d . " 00:00:00";
			$date_end =  $y . '-' . $m . '-' . $d . " 23:59:59";
	}
	
	if($_REQUEST['IR']=='Issues'){
		$sql = sprintf("select l.lid, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
		"l.loaned_by, c.access_no, b.*, m.mid, concat(m.title,  ' ', m.firstnames, ' ',  m.surname) AS member_name  " .
		"FROM ( ((loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) LEFT JOIN member m ON m.mid =l.member ) " .
		"WHERE (l.date_loaned > '%s' AND l.date_loaned < '%s') ORDER BY date_loaned DESC", $date_start, $date_end);
		$rs1 = executeSqlQuery($sql);
		$rs1count = mysqli_num_rows($rs1);
	} elseif($_REQUEST['IR']=='Returns'){
		$sql = sprintf("select l.lid, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
		"l.loaned_by, c.access_no, b.*, m.mid, concat(m.title,  ' ', m.firstnames, ' ',  m.surname) AS member_name  " .
		"FROM ( ((loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) LEFT JOIN member m ON m.mid =l.member ) " .
		"WHERE (l.date_returned > '%s' AND l.date_returned < '%s') ORDER BY date_returned DESC", $date_start, $date_end);
		$rs2 = executeSqlQuery($sql);
		$rs2count = mysqli_num_rows($rs2);	
	}	
	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Circulation-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
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
<h1>Choose Time Period</h1><form action="circulation_irReport.php" method="post" name="form1" class="formNormal">
    <table border="0" cellpadding="0">
      <tr>
        <td colspan="2"><div class="contentEm">
          <label>
          <input name="period" type="radio" value="Today" <?php if( !isset($_REQUEST['period']) || ($_REQUEST['period']=='Today')){ echo 'checked';} ?>>
  Today</label>
          <label>
          <input type="radio" name="period" value="Yesterday" <?php if($_REQUEST['period']=='Yesterday'){ echo 'checked';} ?>>
  Yesterday</label>
          <label>
          <input type="radio" name="period" value="ThisMonth" <?php if($_REQUEST['period']=='ThisMonth'){ echo 'checked';} ?>>
  This Month</label>
          <label>
          <input type="radio" name="period" value="LastMonth" <?php if($_REQUEST['period']=='LastMonth'){ echo 'checked';} ?>>
  Last Month</label>
          <label>
          <input type="radio" name="period" value="ThisYear" <?php if($_REQUEST['period']=='ThisYear'){ echo 'checked';} ?>>
  This Year</label>
          <label>
          <input type="radio" name="period" value="InYear" <?php if($_REQUEST['period']=='InYear'){ echo 'checked';} ?>>
  In Year</label>
          <input name="Year" type="text" id="Year2" size="4" maxlength="4" value='<?php if($_REQUEST['period']=='InYear'){ echo $Year;} ?>'>
        </div></td>
        </tr>
      <tr>
        <td>
		<div class="contentEm">
		<label>
		  <input name="IR" type="radio" value="Issues" <?php if(!isset($_REQUEST['IR']) || ($_REQUEST['IR']=='Issues') ){ echo 'checked';} ?>> Issues Only</label>
		<label>
		  <input type="radio" name="IR" value="Returns" <?php if($_REQUEST['IR']=='Returns'){ echo 'checked';} ?>> Returns Only</label>
		</div>
		</td>
        <td><input name="BtnOk" type="submit" value="Display"></td>
      </tr>
    </table>
</form>

<?php if(isset($_REQUEST['IR']) && $_REQUEST['IR']=='Issues'){ ?>
<h1><?php echo $rs1count; ?>&nbsp;book(s) issued during [<?php echo $date_start; ?> to <?php echo $date_end; ?>]</h1>
<div class="contentEm">
  <table border="0" cellpadding="0" cellspacing="0">
    <?php 
	$x = 0;
	while($row1 = mysqli_fetch_assoc($rs1)){ ?>
    <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
      <td><?php 
		echo "[" . $row1['date_loaned'] . "]&nbsp;<a target='_blank' href='member_view.php?ID=" . $row1['mid'] ."'>" . $row1['member_name'] . "</a>";
		if($row1['date_returned'] != '0000-00-00 00:00:00'){
			echo "&nbsp;<strong>Issued:</strong> (" . $row1['date_returned'] . ")<br>";
		} else { echo "<br>"; }
		echo ".......[" . $row1['access_no'] . "]&nbsp;<a target='_blank' href='book_view.php?ID=" . $row1['bid'] . "'>" . $row1['title'] . "</a>&nbsp;by&nbsp;" . $row1['authors'] . "<br>";

		?>
	  </td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } ?>
<?php if(isset($_REQUEST['IR']) && $_REQUEST['IR']=='Returns'){ ?>
<h1><?php echo $rs2count; ?>&nbsp;book(s) returned during [<?php echo $date_start; ?> to <?php echo $date_end; ?>]</h1>
<div class="contentEm">
  <table border="0" cellpadding="0" cellspacing="0">
    <?php 
	$x = 0;
	while($row2 = mysqli_fetch_assoc($rs2)){ ?>
    <tr class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
      <td><?php 
		echo "[" . $row2['date_loaned'] . "]&nbsp;<a target='_blank' href='member_view.php?ID=" . $row2['mid'] ."'>" . $row2['member_name'] . "</a>";
		if($row2['date_returned'] != '0000-00-00 00:00:00'){
			echo "&nbsp;<strong>Returned:</strong> (" . $row2['date_returned'] . ")<br>";
		} else { echo "<br>"; }
		echo ".......[" . $row2['access_no'] . "]&nbsp;<a target='_blank' href='book_view.php?ID=" . $row2['bid'] . "'>" . $row2['title'] . "</a>&nbsp;by&nbsp;" . $row2['authors'] . "<br>";

		?>
	  </td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } ?>

</td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
