<?php
class Members{
    function add($member){
        //[Validate new record fields]--------------------

        //[Check if the new record is unique]--------------------

        //[Add new record]--------------------
		  $sql = sprintf("INSERT INTO member (type, surname, firstnames, title, address, nic, reg_no, sex, " .
            "phone, email, index_no, login_type, category, expired) " .
            "VALUES ('%s','%s','%s','%s','%s','%s','%s', '%s', " .
            "'%s','%s','%s','%s','%s',0)",
			$member['type'], $member['surname'], $member['firstnames'], $member['title'], $member['address'], $member['nic'], $member['reg_no'], $member['sex'],
            $member['phone'], $member['email'], $member['index_no'], $member['login_type'], $member['category']);
		  $a = executeSqlNonQuery($sql);
          $rows_affected = $a['rows'];
          $new_id = $a['id'];
		  if ($rows_affected == 0) { //TEST
			$msg = 'The system could not add a new member record.<br>' . 
				'Please review the settings you entered and try again.<hr>' .
					$this->toString($member) . '<hr>' .
					"<a href='member_add.php'>Try Again</a>";
			$title = 'Failed to Add New Member';
			displayMsg($msg, $title);
		  } elseif ($rows_affected == 1){
              //Set member username to the mid
			$sql2 = sprintf("UPDATE member SET username='%s' WHERE mid=%d", $new_id,$new_id);
			executeSqlNonQuery($sql2);						  
			  
			  $msg = 'A new member with the following details was added.<hr>' .
                $this->toString($member) . '<hr>' .
                "<a href='member_edit.php?ID=" . $new_id . "'>Edit Member</a>";
              $title = 'New Member Added';
              $backlink = 'index.php';
              
			//[Log Event]---------------------------		
			$des = '[MID=' . $new_id . "] ==> [" . $member['title'] . ' ' . $member['firstnames'] . ' ' . $member['surname'] . "]";
			logEvent('MEMBER_ADDED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));			  
			  
			  displayMsg($msg, $title, $backlink);
		  } 
    }


    function update($member){
        //[Validate input] ------------------------------

        //[Fill a member data object] ------------------------------
		  $sql = sprintf("update member set mem_no='%s', type='%s', surname='%s', firstnames='%s', " .
		  	"title='%s', address='%s', nic='%s', reg_no='%s', sex='%s', phone='%s', email='%s', " .
			"index_no='%s', category='%s', login_type='%s', expired=%d WHERE mid=%d",
			$member['mem_no'], $member['type'], $member['surname'], $member['firstnames'],
			$member['title'], $member['address'], $member['nic'], $member['reg_no'], $member['sex'], $member['phone'], $member['email'],
			$member['index_no'],  $member['category'], $member['login_type'], $member['expired'], $member['mid']);
		  $a = executeSqlNonQuery($sql);
          $rows_affected = $a['rows'];
		  $midT = $member['mid'];
		  if ($rows_affected == 1) {
            //[Display message] ------------------------------            
            $msg = 'The member with the following details was updated<hr>' .
                $this->toString($member) . '<hr>' .
                "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
			$title = 'Member Updated';				
            
			//[Log Event]---------------------------		
			$des = '[MID=' . $member['mid'] . "] ==> [" . $member['title'] . ' ' . $member['firstnames'] . ' ' . $member['surname'] . "]";
			logEvent('MEMBER_UPDATED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));						
			
			displayMsg($msg,$title);
		  } elseif ($rows_affected == 0) {
			$msg = 'No changes were made to this member, because you hadn\'t changed anything.<hr>' .
			$this->toString($member) . '<hr>' .
			"<a href='member_edit.php?ID=" . $midT . "'>Edit this member again</a>";
			$title = 'No Changes Made';
			displayMsg($msg, $title);
          }
    }

	// Depreciated: set expired=1 in member table (members are never deleted from the database)
    function delete($member){
        //[Validate input] ------------------------------
        if(is_null($member)){
            trigger_error('Delete Member: The specified member is not valid', E_USER_ERROR);
            exit();
        }
        $this->getByID($member['mid']); // This function will fail if $member is invalid

        //[Enforce referential integrity] ------------------------------
        // Check if there are any loan records for this member
        $sql = sprintf("SELECT * FROM loan WHERE member=%d",$member['mid']);
        $rs = executeSqlQuery($sql);
        $rows = mysqli_num_rows($rs);
        if($rows != 0){
        	$msg = 'This member has associated loans. Therefore, the member can not be deleted!<br>' .
                    'Please set the status of member to EXPIRED';
        	$title = 'Can\'t Delete Member';
			displayMsg($msg, $title);
        }

	//TODO: check for reservations, etc.

        //[Delete record] ------------------------------
        $sql = sprintf("DELETE FROM member WHERE mid=%d",$member['mid']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        if($rows_affected == 1){
            //[Display message] ------------------------------
            $msg = 'The member with the following details was deleted<hr>' .
                $this->toString($member);
            $title = 'Member Deleted';
            $backlink = 'member_browse.php';
            displayMsg($msg,$title,$backlink);
        }else{
            $msg = 'Could not delete member<br>';
        	trigger_error($msg, E_USER_ERROR);
        	exit();
        }
		
		
		
    }

    function getByID($id){
        //[Validate input] ------------------------------
        if(! (is_numeric($id) && ($id>0)) ){
            trigger_error('Member Number was not specified', E_USER_ERROR);
            exit();
        }

        //[Retrieve data] ------------------------------
        $sql = "SELECT * FROM member WHERE mid=" . $id;
        $recordset = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($recordset);
        if ($rowcount == 0) {
        	trigger_error("There is no member with Member Number: $id", E_USER_ERROR);
        	exit();
        } else if ( ($rowcount > 1) || ($rowcount<0) ) {
        	trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
        	exit();
        }
        $row = mysqli_fetch_assoc($recordset);

        //[Return] ------------------------------
        if($row){
            return $row;
        } else {
        	trigger_error("DATA INTEGRITY ERROR while accessing member with Member Number: $id", E_USER_ERROR);
        	exit();
        }
    }

    /*
    function member getByOldMemNo(old_mem_no){
    }
    function member getByBarcode(barcode){
    }
    function member[] findByName(surname, firstnames, title){
    }
    function member[] findByType(type){
    }
    function member[] findByLoginType(login_type){
    }
    function member[] findByCategory(Category){
    }
    function member[] getAllExpiredMembers()
    member[] getAllMembersWithIncompleteDetail()
    member[] getAllMembersWithInvalidEmails()
    member[] getAllMembersWithNoPassword()
    */
    function changeUsername($member, $username){
        //[Enforce referential integrity] ------------------------------
        // Check if the username is already defined
        $sql = sprintf("SELECT * FROM member WHERE username='%s'",$username);
        $rs = executeSqlQuery($sql);
        $rows = mysqli_num_rows($rs);
        if($rows != 0){
        	$msg = "There already is a user with the username: $username.<br>" .
                    'Please try with a different username';
			$title = 'Username Already Exists';
			$backlink = 'member_edit.php?ID=' . $member['mid'];
			displayMsg($msg, $title, $backlink);
        }

        $sql = sprintf("update member set username='%s' WHERE mid=%d", $username, $member['mid']);
        $a = executeSqlNonQuery($sql);
		$rows_affected = $a['rows'];
        if ($rows_affected == 1) {
                //[Display message] ------------------------------
            $midT = $member['mid'];
            $msg = 'The username for the member with the following details was changed<hr>' .
                $this->toString($member) . '<hr>' .
                "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
            $title = 'Member Username Changed';
            displayMsg($msg,$title);
        }else {
          trigger_error("Could not change username to new value", E_USER_ERROR);
          exit();
        }
    }

    function changePassword($member, $pwd){
		  if (strlen($pwd) < 1 ) {
             //[Display message] ------------------------------
            $midT = $member['mid'];
            $msg = 'Please enter a valid password for the member<hr>' .
                $this->toString($member) . '<hr>' .
                "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
            $title = 'Password not Changed';
            displayMsg($msg,$title);
		  }

		  $sql = sprintf("update member set password='%s' WHERE mid=%d", md5($pwd), $member['mid']);
		  $a = executeSqlNonQuery($sql);
		  $rows_affected = $a['rows'];
		  if ($rows_affected == 1) {
                //[Display message] ------------------------------
            $midT = $member['mid'];
            $msg = 'The password for the member with the following details was changed<hr>' .
                $this->toString($member) . '<hr>' .
                "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
            $title = 'Member Password Changed';
            displayMsg($msg,$title);
		  } else{
            trigger_error("Could not change password to new value", E_USER_ERROR);
            exit();
          }
    }

    
function changeBarcode($member, $barcode){
	$sql = sprintf("SELECT * FROM member WHERE barcode='%s'",	$barcode);
	$rs = executeSqlQuery($sql);
	$cnt = mysqli_num_rows($rs);
	if($cnt>0){ //Specified barcode already exist
        $midT = $member['mid'];
        $msg = "The barcode <strong>$barcode</strong> already exists!<br>Barcode was not changed<hr>" .
            $this->toString($member) . '<hr>' .
            "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
        $title = 'No Changes Made';
        displayMsg($msg,$title);            
	}

	$sql = sprintf("update member set barcode='%s' WHERE mid=%d",	$barcode, $member['mid']);
	$a = executeSqlNonQuery($sql);
	$rows_affected = $a['rows'];
    $midT = $member['mid'];
	if ($rows_affected == 1) {
		//[Log Event]---------------------------
        $des = '[' . $member['barcode'] . "] ==> [$barcode]";
        logEvent('MEMBER_BARCODE', $_SESSION['CurrentUser']['mid'], $member['mid'], addslashes($des));
        
        //[Display message] ------------------------------
        $msg = "Barcode Changed to: <strong>$barcode</strong><br>Member Details before the change are:<hr>" .
            $this->toString($member) . '<hr>' .
            "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
		$title = 'Member Barcode Changed';
		displayMsg($msg,$title);
	} elseif ($rows_affected == 0) {
		$msg = 'Barcode was not changed, because you had specified the old barcode as the new one.<hr>' .
            $this->toString($member) . '<hr>' .
            "<a href='member_edit.php?ID=$midT'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_view.php?ID=$midT'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='member_browse.php'>Browse Members</a>";
		$title = 'No Changes Made';
		displayMsg($msg, $title);
	}
}
    
    
    function toString($member){
        return
		    $member['title'] . " " . $member['firstnames'] . " " . $member['surname'] . "&nbsp;<strong></a>&nbsp;(Member No:" . $member['mid'] . '</strong> | Old No:' . $member['mem_no'] .  ")<br>" .
		    "<strong>Group:&nbsp;</strong>" .$member['type'] . "&nbsp;|&nbsp;<strong>Lending Category:&nbsp;</strong>" .$member['category'] . "&nbsp;|&nbsp;<strong>Login Category:&nbsp;</strong>" . $member['login_type'] . '&nbsp;|&nbsp;'.
		    "<strong>Barcode:&nbsp;</strong>" .$member['barcode'] . "&nbsp;|&nbsp;<strong>Reg#:&nbsp;</strong>" .$member['reg_no'] . "&nbsp;|&nbsp;<strong>Index#:&nbsp;</strong>" .$member['index_no'] . "&nbsp;|&nbsp;<strong>NIC#:&nbsp;</strong>" .$member['nic'] . "&nbsp;|&nbsp;<strong>Sex:&nbsp;</strong>" .$member['sex'] . "&nbsp;|&nbsp;<strong>Username:&nbsp;</strong>" .$member['username'] . '<br>' .
		    "<strong>Email:&nbsp;</strong>" . $member['email'] . "&nbsp;|&nbsp;<strong>Phone:&nbsp;</strong>" . $member['phone'] . '<br>' .
		    "<strong>Address:&nbsp;</strong>" . $member['address'];
    }
}
?>