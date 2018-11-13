<?php
    $allow = "ADMIN;LIBSTAFF";
    $PageTitle = "Members";
	require('../inc/init.php');
	

    //[Save Msearch preferences in the session] ------------------------------
    if(isset($_REQUEST['BtnSearchMembers']) or isset($_REQUEST['TotalRecords'])){
        $_SESSION['page_state']['member_search']['mid']=$_REQUEST['mid'];
        $_SESSION['page_state']['member_search']['mem_no']=$_REQUEST['mem_no'];
        $_SESSION['page_state']['member_search']['type']=$_REQUEST['type'];
        $_SESSION['page_state']['member_search']['surname']=$_REQUEST['surname'];
        $_SESSION['page_state']['member_search']['firstnames']=$_REQUEST['firstnames'];
        $_SESSION['page_state']['member_search']['title']=$_REQUEST['title'];
        $_SESSION['page_state']['member_search']['address']=$_REQUEST['address'];
        $_SESSION['page_state']['member_search']['nic']=$_REQUEST['nic'];
        $_SESSION['page_state']['member_search']['reg_no']=$_REQUEST['reg_no'];
        $_SESSION['page_state']['member_search']['phone']=$_REQUEST['phone'];
        $_SESSION['page_state']['member_search']['email']=$_REQUEST['email'];
        $_SESSION['page_state']['member_search']['index_no']=$_REQUEST['index_no'];
    }

    //[Check if request came from either the search page or the browse page] ------------------------------
    if( !(isset($_REQUEST['BtnSearchMembers']) or isset($_REQUEST['TotalRecords'])) ){
        header("Location: member_search.php");
        exit();
    }

    $Sort1 = $_REQUEST['Sort1'];
    $Sort2 = $_REQUEST['Sort2'];
    $Sort3 = $_REQUEST['Sort3'];
    $Sort4 = $_REQUEST['Sort4'];

    $mid = $_REQUEST['mid'];
	$mem_no = $_REQUEST['mem_no'];
	$type = $_REQUEST['type'];
	$surname = $_REQUEST['surname'];
	$firstnames = $_REQUEST['firstnames'];
	$title = $_REQUEST['title'];
	$address = $_REQUEST['address'];
	$nic = $_REQUEST['nic'];
	$reg_no = $_REQUEST['reg_no'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$index_no = $_REQUEST['index_no'];


    $LimitN = $_REQUEST['LimitN']; // Number of records per page
    $LimitI = $_REQUEST['LimitI']; // Number of records per page

    /// Retrieve a recordset from the database //////////////////////////////
    // Build the WHERE clause
    $WhereClause = "WHERE 1=1 AND"; // '1=1 AND' is for situations when there's no value in any field
    if($mid != ""){
            $WhereClause = $WhereClause . " mid  LIKE '%" . $mid . "%' AND";
    }
    if($mem_no != ""){
            $WhereClause = $WhereClause . " mem_no LIKE '%" . $mem_no . "%' AND";
    }
    if($type != ""){
            $WhereClause = $WhereClause . " type LIKE '%" . $type . "%' AND";
    }
    if($surname != ""){
            $WhereClause = $WhereClause . " surname LIKE '%" . $surname . "%' AND";
    }
    if($firstnames != ""){
            $WhereClause = $WhereClause . " firstnames LIKE '%" . $firstnames . "%' AND";
    }
    if($title != ""){
            $WhereClause = $WhereClause . " title LIKE '%" . $title . "%' AND";
    }
    if($address != ""){
            $WhereClause = $WhereClause . " address LIKE '%" . $address . "%' AND";
    }
    if($nic != ""){
            $WhereClause = $WhereClause . " nic LIKE '%" . $nic . "%' AND";
    }
    if($reg_no != ""){
            $WhereClause = $WhereClause . " reg_no LIKE '%" . $reg_no . "%' AND";
    }
    if($phone != ""){
            $WhereClause = $WhereClause . " phone LIKE '%" . $phone . "%' AND";
    }
    if($email != ""){
            $WhereClause = $WhereClause . " email LIKE '%" . $email . "%' AND";
    }
    if($index_no != ""){
            $WhereClause = $WhereClause . " index_no LIKE '%" . $index_no . "%' AND";
    }

    // remove the last AND
    $WhereClause = substr($WhereClause,0,strlen($WhereClause)-3);

    // Calculate $TotalRecords
    if (!isset($_REQUEST['TotalRecords'])){
            $sqlTotal = sprintf("SELECT COUNT(mid) FROM member %s", $WhereClause);
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
    $sql = sprintf("SELECT * FROM member %s ORDER BY %s, %s, %s, %s LIMIT %d, %d",
            $WhereClause, $Sort1, $Sort2, $Sort3, $Sort4, $LimitI, $LimitN);
    $recordset = executeSqlQuery($sql);
    $rowcount = mysqli_num_rows($recordset);


    // Calculate: $TotalPages, $CurrentPage, $SearchCriteria
    $TotalPages = ceil($TotalRecords / $LimitN);
    if($LimitI == 0){
            $CurrentPage = 1;
    } else{
            $CurrentPage = floor($LimitI/$LimitN) +1;
    }

    //Build search criteria string for displaying in the page
    $SearchCriteria = "";
    if ($mid != ""){
            $SearchCriteria = $SearchCriteria . "mid = " . $mid . ", ";
    }
    if ($mem_no != ""){
            $SearchCriteria = $SearchCriteria . "mem_no = " . $mem_no . ", ";
    }
    if ($type != ""){
            $SearchCriteria = $SearchCriteria . "type = " . $type . ", ";
    }
    if ($surname != ""){
            $SearchCriteria = $SearchCriteria . "surname = " . $surname . ", ";
    }
    if ($firstnames != ""){
            $SearchCriteria = $SearchCriteria . "firstnames = " . $firstnames . ", ";
    }
    if ($title != ""){
            $SearchCriteria = $SearchCriteria . "title = " . $title . ", ";
    }
    if ($address != ""){
            $SearchCriteria = $SearchCriteria . "address = " . $address . ", ";
    }
    if ($nic != ""){
            $SearchCriteria = $SearchCriteria . "nic = " . $nic . ", ";
    }
    if ($reg_no != ""){
            $SearchCriteria = $SearchCriteria . "reg_no = " . $reg_no . ", ";
    }
    if ($phone != ""){
            $SearchCriteria = $SearchCriteria . "phone = " . $phone . ", ";
    }
    if ($email != ""){
            $SearchCriteria = $SearchCriteria . "email = " . $email . ", ";
    }
    if ($index_no != ""){
            $SearchCriteria = $SearchCriteria . "index_no = " . $index_no . ", ";
    }

    // remove the last two characters
    $SearchCriteria = substr($SearchCriteria,0,strlen($SearchCriteria)-2);
    if($SearchCriteria == "") $SearchCriteria = "Show All";
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" lass="margin"><table width="100%"  border="0">
      <tr>
        <td><img src="images/icon-Members-200x150.jpg" width="200" height="150"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
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
          <td><table width="100%"  border="0">
              <tr>
                <td class="marginHelpTitle">Browsing members</td>
              </tr>
              <tr>
                <td class="marginHelp"><ol>
                    <li>Type your search criteria in the Search boxes. (Separate words by a single space) </li>
                    <li>Specify how you want the results to be sorted. (You may choose the default)</li>
                    <li>Press the search button</li>
                </ol></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    <td>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
                <td class="contents">
<?php if($TotalRecords>0){ ?>		
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">
                <?php include('member_browse_inc1.php'); ?>	
	</td>
  </tr>
</table>
<table width="100%"  border="0">
  <tr>
    <td class="contentEm">&nbsp;
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
      <?php
  $x = 0;
  while ($row = mysqli_fetch_assoc($recordset)) { ?>
      <tr align="left" class="<?php if( ( $x % 2) == 0 ){ echo "td1"; $x += 1; } else {echo "td2"; $x +=1; }?>">
        <td>&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top">		
			<a href='member_view.php?ID=<?php echo $row['mid']; ?>'><?php echo  $row['title'] . " " . $row['firstnames'] . " " . $row['surname'] . "&nbsp;<strong></a>&nbsp;(Member ID:" . $row['mid'] . '</strong> | Member No:' . $row['mem_no'] .  ")"; ?>&nbsp;(<a href="member_edit.php?ID=<?php echo  $row['mid']; ?>">Edit</a>)<br>
			<?php echo "<strong>Group:&nbsp;</strong>" .$row['type'] . "&nbsp;|&nbsp;<strong>Lending Category:&nbsp;</strong>" .$row['category'] . "&nbsp;|&nbsp;<strong>Login Category:&nbsp;</strong>" . $row['login_type']; ?><br>
			<?php echo "<strong>Barcode:&nbsp;</strong>" .$row['barcode'] . "&nbsp;|&nbsp;<strong>Reg#:&nbsp;</strong>" .$row['reg_no'] . "&nbsp;|&nbsp;<strong>Index#:&nbsp;</strong>" .$row['index_no'] . "&nbsp;|&nbsp;<strong>NIC#:&nbsp;</strong>" .$row['nic'] . "&nbsp;|&nbsp;<strong>Sex:&nbsp;</strong>" .$row['sex'] . "&nbsp;|&nbsp;<strong>Username:&nbsp;</strong>" .$row['username']; ?><br>
			<?php echo "<strong>Email:&nbsp;</strong>" . $row['email'] . "&nbsp;|&nbsp;<strong>Phone:&nbsp;</strong>" . $row['phone']; ?><br>
			<?php echo "<strong>Address:&nbsp;</strong>" . $row['address']; ?>
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
    <td class="contentEm">
                <?php include('member_browse_inc1.php'); ?>	
	</td>
  </tr>
</table>
<?php } else {//If there are no results ?>
<h1>There are no members matching the criteria you specified</h1>
Search criteria: (<?php echo $SearchCriteria; ?>)<br>
<a href="member_search.php">Search Again</a>
<?php } ?>

                </td>
          </tr>
        </table>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
