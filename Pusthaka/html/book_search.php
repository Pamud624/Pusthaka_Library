<?php
    $allow = "ALL";
    $PageTitle = "Catalog Search";
    include('../inc/init.php');
?>
<?php include("../inc/top.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="marginNB">
      <table width="100%"  border="0">
        <tr>
          <td><img src="images/icon-opac-200x150.jpg"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="100%"  border="0">
        <tr>
          <td><table width="100%"  border="0">
              <tr>
                <td class="marginHelpTitle">Search tips </td>
              </tr>
              <tr>
                <td class="marginHelp">                  <p>Type your search criteria in the Search boxes. (Separate words by a single space) </p>                <p>Specify how you want the results to be sorted. (You may choose the default)</p>                <p>Press the search button<br>
                    (Just pressing 'Enter' does the same)
                </p></td></tr>
          </table></td>
        </tr>
    </table>      </td>
    <td>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
                <td class="contents">
				<h1>OPAC (Online Public Access Catalogue)</h1>
<table border="0">
  <tr>
    <td><table border="0">
      <tr>
        <td class="contentNormal">
          <form action="book_browse.php" method="post" name="SearchOPAC" class="formNormal" id="SearchOPAC">
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
                      <td align="left" scope="col">Title&nbsp;</td>
                      <td scope="col"><input name="SearchTitle" type="text" id="SearchTitle" value="<?php if(isset($_SESSION['page_state']['opac'])) echo stripslashes($_SESSION['page_state']['opac']['SearchTitle']); ?>"></td>
                      <td width="10" scope="col">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">Author</td>
                      <td><input name="SearchAuthors" type="text" id="SearchAuthors" value="<?php if(isset($_SESSION['page_state']['opac'])) echo stripslashes($_SESSION['page_state']['opac']['SearchAuthors']); ?>"></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">Subject&nbsp; </td>
                      <td><input name="SearchSubjects" type="text" id="SearchSubjects" value="<?php if(isset($_SESSION['page_state']['opac'])) echo stripslashes($_SESSION['page_state']['opac']['SearchSubjects']); ?>"></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">ISBN&nbsp;</td>
                      <td><input name="SearchISBN" type="text" id="SearchISBN" value="<?php if(isset($_SESSION['page_state']['opac'])) echo stripslashes($_SESSION['page_state']['opac']['SearchISBN']); ?>"></td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                      <td><input name="btnSearchOPAC" type="submit" id="btnSearchOPAC" value="Search" class="btn">
                          <br>
                          <br></td>
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
                        <select name="SortSearchOPAC1" id="SortSearchOPAC1">
                          <option value="title" selected>Title</option>
                          <option value="authors">Author</option>
                          <option value="subjects">Subject</option>
                          <option value="isbn">ISBN</option>
                        </select>
                      </td>
                      <td scope="col">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td>
                        <select name="SortSearchOPAC2" id="SortSearchOPAC2">
                          <option value="title">Title</option>
                          <option value="authors" selected>Author</option>
                          <option value="subjects">Subject</option>
                          <option value="isbn">ISBN</option>
                      </select></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td>
                        <select name="SortSearchOPAC3" id="SortSearchOPAC3">
                          <option value="title">Title</option>
                          <option value="authors">Author</option>
                          <option value="subjects" selected>Subject</option>
                          <option value="isbn">ISBN</option>
                      </select></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td>
                        <select name="SortSearchOPAC4" id="SortSearchOPAC4">
                          <option value="title">Title</option>
                          <option value="authors">Author</option>
                          <option value="subjects">Subject</option>
                          <option value="isbn" selected>ISBN</option>
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
                      <td scope="col"><select name="LimitN" id="select5">
                          <option value="10">10</option>
                          <option value="20" selected>20</option>
                          <option value="30">30</option>
                          <option value="50">50</option>
                        </select>
                          <input name="LimitI" type="hidden" id="LimitI" value="0"></td>
                    </tr>
                    <tr>
                      <td width="10" scope="col">&nbsp;</td>
                      <td scope="col">&nbsp; </td>
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
        </form></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
	<h1>Locate by Access Number</h1>
	<table border="0">
      <tr>
        <td>
          <form class="formNormal" name="form1" method="post" action="book_view.php">
        <input name="ano" type="text" id="ano2">
        <input name="BtnFindByAccNo" type="submit" id="BtnFindByAccNo" value="Find" class="btn">
          </form>
          <script language="JavaScript">
	var frmvalidator1 = new Validator("form1");
	frmvalidator1.addValidation("ano","req","Please enter an access number.");
	frmvalidator1.addValidation("ano","num","Access number must be numeric.");
    </script>        </td>
      </tr>
    </table></td>
  </tr>
</table>
				
                </td>
          </tr>
        </table>    </td>
  </tr>
</table>
<?php include("../inc/bottom.php"); ?>
