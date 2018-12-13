<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="search">
			  There are <strong><?php echo $TotalRecords; ?></strong> search results. (Showing <strong><?php echo $LimitN; ?></strong> results per page (from <strong><?php echo $LimitI +1; ?></strong>)) <br>
					 Search criteria (<?php echo $SearchCriteria; ?>)&nbsp;|
		    Sorted on: (<span class="text2"><?php echo $_REQUEST['SortSearchOPAC1']; ?></span>, <span class="text2"><?php echo $_REQUEST['SortSearchOPAC2']; ?></span>, <span class="text2"><?php echo $_REQUEST['SortSearchOPAC3']; ?></span>, <span class="text2"><?php echo $_REQUEST['SortSearchOPAC4']; ?>)</span><br>			</td>
          </tr>
          <tr>
            <td class="navigator">
			<form action="book_browse.php" method="post" name="FlipPage" id="FlipPage">
			<input name="SearchTitle" type="hidden" value="<?php echo $SearchTitle; ?>">
			<input name="SearchAuthors" type="hidden" value="<?php echo $SearchAuthors; ?>">
			<input name="SearchSubjects" type="hidden" value="<?php echo $SearchSubjects; ?>">
			<input name="SearchISBN" type="hidden" value="<?php echo $SearchISBN; ?>">
			
			<input name="SortSearchOPAC1" type="hidden" value="<?php echo $SortSearchOPAC1; ?>">
			<input name="SortSearchOPAC2" type="hidden" value="<?php echo $SortSearchOPAC2; ?>">
			<input name="SortSearchOPAC3" type="hidden" value="<?php echo $SortSearchOPAC3; ?>">
			<input name="SortSearchOPAC4" type="hidden" value="<?php echo $SortSearchOPAC4; ?>">
			
			<input name="LimitN" type="hidden" value="<?php echo $LimitN; ?>">
			<input name="LimitI" type="hidden" value="<?php echo $LimitI; ?>">
			
			<input name="TotalRecords" type="hidden" value="<?php echo $TotalRecords; ?>">			
              <?php if($CurrentPage != 1) {?>
			  <input name="BtnFirst" type="submit" id="BtnFirst" value="First Page (1)">			  
              <input name="BtnPrevious" type="submit" id="BtnPrevious" value="Previous Page (<?php echo $CurrentPage - 1;?>)">
			  <?php } ?>
              (Page <?php echo $CurrentPage; ?> of <?php echo $TotalPages; ?>)
			  <?php if($CurrentPage != $TotalPages) {?>
			  <input name="BtnNext" type="submit" id="BtnNext" value="Next Page (<?php echo $CurrentPage + 1;?>)">
              <input name="BtnLast" type="submit" id="BtnLast" value="Last Page (<?php echo $TotalPages;?>)">
			  <?php } ?>
              </form></td>
          </tr>
          <tr>
            <td>	
              </td>
          </tr>
        </table>