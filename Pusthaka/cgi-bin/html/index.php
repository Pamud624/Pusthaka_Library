<?php
	$allow = "ALL";
	$PageTitle = "Home";
	require('../inc/init.php');
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0">
  <tr>
    <td class="marginNB">
<table width="100%"  border="0">
  <tr>
    <td>
                    <a href="book_search.php">
                        <img src="images/icon-opac-200x150.jpg" alt="OPAC" style="border-style: none" />
                    </a>
    </td>
  </tr>
</table></td>
    <td class="content">
	<?php if (!isset($_SESSION['CurrentUser'])){  ?>
	<table width="100%"  border="0">
      <tr>
        <td class="contentNormal">
		<p>Pusthaka Integrated Library System</p>

                <p>Library specific text here. Make it user configurable.</p>	
		
                <p><a href="book_search.php">OPAC</a>. </p>

        </td>
      </tr>
    </table>
	<?php } ?>
	<?php if(isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))){ ?>
	
	<table border="0">
        <tr>
          <td class="contentNormal">
            <table border="0">
              <tr>
                <td>
<h1>Front Desk</h1>
<div class="contentEm">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img src="images/icon-FrontDesk-100x75.jpg" width="100" height="75"></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="ir1.php">Issue/Return</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img src="images/icon-Circulation-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="circulation.php">Circulation</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-Reservations-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="reservations.php">Reservations</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img src="images/icon-opac-100x75.jpg" width="100" height="75"></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="book_search.php">opac</a></td>
      </tr>
    </table></td>
      <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/download.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="report2.php">Get Reports </a></td>
      </tr>
    </table></td>
  </tr>
</table>

</div>				
<h1>Members and Books</h1>				
<div class="contentEm">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-Books-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="book_search.php">Books</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-BooksAdd-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="book_add.php">Add Book </a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img src="images/icon-Members-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="member_browse.php">Members</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-MembersAdd-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="member_add.php">Add Member</a> </td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/configur.gif" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="config300.php">Config Settings</a> </td>
      </tr>
    </table></td>

     <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/csvupload.png" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="bulk_upload.php">Bulk Add</a> </td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
<h1>Admin Tasks</h1>
<div class='contentEm'>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-eventlog-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="eventlog.php">Event Log</a> </td>
      </tr>
    </table></td>
    <td><!-- <table border="0">
      <tr>
        <td align="center" valign="middle"><img src="images/icon-PurchaseRequests-100x75.jpg" width="100" height="75"></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="purchase_requests.php"></a>Purchase Requests</td>
      </tr>
    </table> !--> </td>
    <td><table border="0">
      <tr>
        <td width="100" height="75" align="center" valign="middle"><img src="images/icon-PurchaseRequests-100x75.jpg" width="100" height="75"> </td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="ir1-inventory.php">Inventory Check 2019</a></td>
      </tr>
    </table></td>
	
	<td><table border="0">
      <tr>
        <td width="100" height="75" align="center" valign="middle"><img src="images/icon-calendar.jpeg" width="100" height="75"> </td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="_add_holiday.php">Add Holidays</a></td>
      </tr>
    </table></td>

<!-- Pamud edited  !-->

 <td><table border="0">
      <tr>
        <td width="100" height="75" valign="middle"><img name="" src="images/alert-message.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="alert_message.php">Allert Message</a></td>
      </tr>
    </table></td>

    <td><table border="0">
      <tr>
        <td width="100" height="75" valign="middle"><img name="" src="images/backup.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="backup3.php"> Backups</a></td>
      </tr>
    </table></td>

     <!-- Pamud edited  !-->
	
    <td><table border="0">
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
<h1>My Library</h1>
<div class="contentEm">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-MyLoans-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="my_loans.php">My Loans</a> </td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-MyReservations-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="my_reservations.php">My Reservations</a></td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-MyInfo-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="my_info.php">My Info</a> </td>
      </tr>
    </table></td>
    <td><table border="0">
      <tr>
        <td align="center" valign="middle"><img name="" src="images/icon-MyHistory-100x75.jpg" width="100" height="75" alt=""></td>
      </tr>
      <tr>
        <td align="center" valign="middle"><a href="my_history.php">My History</a></td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
                </td>
              </tr>
          </table>            </td>
        </tr>
      </table>
	  <?php } ?>
	  <?php if ( isset($_SESSION['CurrentUser']) && ($_SESSION['CurrentUser']['login_type'] == 'PATRON')){ ?>
<table border="0">
                    <tr>
                      <td class="contentNormal">
					<table border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table border="0">
      <tr>
        <td><table border="0">
          <tr>
            <td align="center" valign="middle"><img src="images/icon-opac-100x75.jpg" width="100" height="75"></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="book_search.php">opac</a></td>
          </tr>
        </table></td>
        <td><table border="0">
          <tr>
            <td align="center" valign="middle"><img src="images/icon-MyLoans-100x75.jpg" width="100" height="75" alt=""></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="my_loans.php">My Loans</a> </td>
          </tr>
        </table></td>
        <td><table border="0">
          <tr>
            <td align="center" valign="middle"><img name="" src="images/icon-MyReservations-100x75.jpg" width="100" height="75" alt=""></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="my_reservations.php">My Reservations</a></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0">
          <tr>
            <td align="center" valign="middle"><img name="" src="images/icon-MyInfo-100x75.jpg" width="100" height="75" alt=""></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="my_info.php">My Info</a> </td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
        <td><table border="0">
          <tr>
            <td align="center" valign="middle"><img name="" src="images/icon-MyHistory-100x75.jpg" width="100" height="75" alt=""></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><a href="my_history.php">My History</a></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>				      </td>
                    </tr>
      </table>
				 <?php } ?>	  
    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>

