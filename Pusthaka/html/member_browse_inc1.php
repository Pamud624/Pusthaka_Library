<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="search">
			  There are <strong><?php echo $TotalRecords; ?></strong> search results.<br>
					 Search criteria (<?php echo $SearchCriteria; ?>)&nbsp;|
					 Sorted on: (<span class="text2"><?php echo $_REQUEST['Sort1']; ?></span>, <span class="text2"><?php echo $_REQUEST['Sort2']; ?></span>, <span class="text2"><?php echo $_REQUEST['Sort3']; ?></span>, <span class="text2"><?php echo $_REQUEST['Sort4']; ?>)&nbsp;&nbsp;&nbsp; <a href="member_search.php">Search Again</a></span><br>
			</td>
          </tr>
          <tr>
            <td class="navigator">
			<form action="member_browse.php" method="post" name="FlipPage" id="FlipPage">
			<input name="mid" type="hidden" value="<?php echo $mid; ?>">
			<input name="mem_no" type="hidden" value="<?php echo $mem_no; ?>">
			<input name="type" type="hidden" value="<?php echo $type; ?>">
			<input name="surname" type="hidden" value="<?php echo $surname; ?>">
			<input name="firstnames" type="hidden" value="<?php echo $firstnames; ?>">
			<input name="title" type="hidden" value="<?php echo $title; ?>">
			<input name="address" type="hidden" value="<?php echo $address; ?>">
			<input name="nic" type="hidden" value="<?php echo $nic; ?>">
			<input name="reg_no" type="hidden" value="<?php echo $reg_no; ?>">
			<input name="phone" type="hidden" value="<?php echo $phone; ?>">
			<input name="email" type="hidden" value="<?php echo $email; ?>">
			<input name="index_no" type="hidden" value="<?php echo $index_no; ?>">

			
			<input name="Sort1" type="hidden" value="<?php echo $Sort1; ?>">
			<input name="Sort2" type="hidden" value="<?php echo $Sort2; ?>">
			<input name="Sort3" type="hidden" value="<?php echo $Sort3; ?>">
			<input name="Sort4" type="hidden" value="<?php echo $Sort4; ?>">
			
			<input name="LimitN" type="hidden" value="<?php echo $LimitN; ?>">
			<input name="LimitI" type="hidden" value="<?php echo $LimitI; ?>">
			
			<input name="TotalRecords" type="hidden" value="<?php echo $TotalRecords; ?>">
					              
			Showing <strong><?php echo $LimitN; ?></strong> results per page.(from <strong><?php echo $LimitI +1; ?></strong>).<br>
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
