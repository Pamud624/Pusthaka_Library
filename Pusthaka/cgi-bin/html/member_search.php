<?php
	$allow = "ADMIN;LIBSTAFF";
	$PageTitle = "Member Search";
	require('../inc/init.php');
	
	if((isset($_REQUEST['do'])) && ($_REQUEST['do']=='reset')){
		if(isset($_SESSION['page_state']['member_search'])) 
			unset($_SESSION['page_state']['member_search']);
	}
	
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="margin">
<table width="100%"  border="0">
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
         &nbsp;
        </td>
      </tr>
    </table>
	<table width="100%"  border="0">
      <tr>
        <td><table width="100%"  border="0">
            <tr>
              <td class="marginHelpTitle">Finding a  member </td>
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
<h1>Locate a Library Member</h1>		
<table border="0">
  <tr>
    <td>
<form action="member_browse.php" method="post" name="member_search" class="formNormal" id="member_search">
                          <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="left" valign="top" class="search"><table  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" scope="col">&nbsp;</td>
                                  <td align="left" scope="col"><strong>Search:</strong></td>
                                  <td scope="col">&nbsp;</td>
                                  <td width="10" scope="col">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" scope="col">&nbsp;</td>
                                  <td align="left" scope="col">Surname&nbsp;</td>
                                  <td scope="col"><input name="surname" type="text" id="surname" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['surname']; }?>"></td>
                                  <td width="10" scope="col">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Firstnames</td>
                                  <td><input name="" type="text" id="firstnames" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['firstnames'];} ?>"></td>
                                  <td width="10">&nbsp;firstnames</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Type&nbsp; </td>
                                  <td>
								  <select name="type" id="type">
								    <?php
										if( (!isset($_SESSION['page_state']['member_search']['type'])) || ($_SESSION['page_state']['member_search']['type']=='')){
											echo '<option value="" selected>Show All</option>';
										} else{
											echo "<option value='" . $_SESSION['page_state']['member_search']['type'] . "' selected>" . $_SESSION['page_state']['member_search']['type'] . "</option>";
										}
									?>									
	<?php
		$sql = sprintf("SELECT DISTINCT type FROM member ORDER BY type");
		$rs = executeSqlQuery($sql);
		while($row = mysqli_fetch_array($rs)){
			echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
		}
	?>

                                  </select></td>
                                  <td width="10">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Title</td>
                                  <td><input name="title" type="text" id="title" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['title'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">NIC</td>
                                  <td><input name="nic" type="text" id="nic" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['nic'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Index No. </td>
                                  <td><input name="index_no" type="text" id="index_no" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['index_no'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Reg No. </td>
                                  <td><input name="reg_no" type="text" id="reg_no" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['reg_no'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Address</td>
                                  <td><input name="address" type="text" id="address" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['address'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Phone</td>
                                  <td><input name="phone" type="text" id="phone" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['phone'];} ?>">                                  </td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Email</td>
                                  <td><input name="email" type="text" id="email" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['email'];} ?>"></td>
                                  <td width="10">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Member ID </td>
                                  <td><input name="mid" type="text" id="mid" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['mid'];} ?>"></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">Member No&nbsp;&nbsp; </td>
                                  <td><input name="mem_no" type="text" id="mem_no" value="<?php if(isset($_SESSION['page_state']['member_search'])){echo $_SESSION['page_state']['member_search']['mem_no'];} ?>"></td>
                                  <td width="10">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">&nbsp;</td>
                                  <td><input name="BtnSearchMembers" type="submit" id="BtnSearchMembers" value="Search">
&nbsp;<a href="member_search.php?do=reset">Reset Form</a> <br></td>
                                  <td width="10">&nbsp;</td>
                                </tr>
                              </table></td>
                              <td>&nbsp;</td>
                              <td align="left" valign="top" class="search"><table  border="0" align="left" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="10" scope="col">&nbsp;</td>
                                  <td scope="col"><strong>Sort By: </strong></td>
                                  <td scope="col">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10" scope="col">&nbsp;</td>
                                  <td scope="col">
                                    <select name="Sort1" id="Sort1">
                                      <option value="surname" selected>Surname</option>
                                      <option value="firstnames">Firstnames</option>
                                      <option value="title">Title</option>
                                      <option value="type">Type</option>
                                      <option value="mid">Member ID</option>
                                      <option value="mem_no">Old Number</option>
                                      <option value="nic">NIC</option>
                                      <option value="index_no">Index Number</option>
                                      <option value="reg_no">Registration Number</option>
                                    </select>
                                  </td>
                                  <td scope="col">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10">&nbsp;</td>
                                  <td><select name="Sort2" id="Sort2">
                                    <option value="surname">Surname</option>
                                    <option value="firstnames" selected>Firstnames</option>
                                    <option value="title">Title</option>
                                    <option value="type">Type</option>
                                    <option value="mid">Member ID</option>
                                    <option value="mem_no">Old Number</option>
                                    <option value="nic">NIC</option>
                                    <option value="index_no">Index Number</option>
                                    <option value="reg_no">Registration Number</option>
                                                                    </select></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10">&nbsp;</td>
                                  <td><select name="Sort3" id="Sort3">
                                    <option value="surname">Surname</option>
                                    <option value="firstnames">Firstnames</option>
                                    <option value="title">Title</option>
                                    <option value="type" selected>Type</option>
                                    <option value="mid">Member ID</option>
                                    <option value="mem_no">Old Number</option>
                                    <option value="nic">NIC</option>
                                    <option value="index_no">Index Number</option>
                                    <option value="reg_no">Registration Number</option>
                                                                    </select></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10">&nbsp;</td>
                                  <td><select name="Sort4" id="Sort4">
                                    <option value="surname">Surname</option>
                                    <option value="firstnames">Firstnames</option>
                                    <option value="title">Title</option>
                                    <option value="type">Type</option>
                                    <option value="mid" selected>Member ID</option>
                                    <option value="mem_no">Old Number</option>
                                    <option value="nic">NIC</option>
                                    <option value="index_no">Index Number</option>
                                    <option value="reg_no">Registration Number</option>
                                                                    </select></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10">&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="10">&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td align="left" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="3" align="left" valign="top" class="search"><table  border="0" align="left" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="10" scope="col">&nbsp;</td>
                                  <td scope="col"><strong>Number of results per page :&nbsp;&nbsp; </strong></td>
                                  <td scope="col"><select name="LimitN" id="LimitN">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>
                                    <option value="2000">2000</option>
                                    <option value="5000">5000</option>
                                                                                                      </select>
                                  <input name="LimitI" type="hidden" id="LimitI" value="0"></td>
                                </tr>
                                <tr>
                                  <td width="10" scope="col">&nbsp;</td>
                                  <td scope="col">&nbsp;
                                  </td>
                                  <td scope="col">&nbsp;</td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td align="left" valign="top">&nbsp;</td>
                            </tr>
                          </table>
                  </form>	
 <script language="JavaScript">
	var frmvalidator = new Validator("member_search");
	frmvalidator.addValidation("mid","num","Member Number: must be numeric.");
</script>				  
	</td>
  </tr>
</table>
		
                </td>
          </tr>
        </table>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
