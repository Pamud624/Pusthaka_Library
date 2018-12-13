<table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="navigator">
			<form action="notices.php" method="post" name="FlipPage" id="FlipPage">		
			<input name="LimitN" type="hidden" value="<?php echo $LimitN; ?>">
			<input name="LimitI" type="hidden" value="<?php echo $LimitI; ?>">			
			<input name="TotalRecords" type="hidden" value="<?php echo $TotalRecords; ?>">
					              
			<strong><?php echo $TotalRecords; ?></strong> notices in total. Showing&nbsp;
				<select name="LimitN" id="LimitN">
					<option value="<?php echo $LimitN; ?>" selected><?php echo $LimitN; ?></option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="500">500</option>
					<option value="1000">1000</option>
					<option value="2000">2000</option>
					<option value="5000">5000</option>
				</select>
			&nbsp;results per page.(from <strong><?php echo $LimitI +1; ?></strong>).<br>
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
