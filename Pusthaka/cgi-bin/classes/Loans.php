<?php
class Loans{
    function getCurrentLoansByMember($member){
        if(is_null($member)){
            trigger_error('getCurrentLoansByMember: The member is not valid', E_USER_ERROR);
            exit();
        }
        $sql = sprintf("select l.lid, l.member mid, l.copy cid, l.date_loaned, l.date_due, " .
            "l.loaned_by, c.access_no, b.bid, b.title, b.authors  " .
            "FROM ( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) " .
            "WHERE (l.returned=0 AND l.member=%d)", $member['mid']);
        $rs = executeSqlQuery($sql);
        return $rs;
    }

    function getPastLoansByMember($member){
        if(is_null($member)){
            trigger_error('getPastLoansByMember: The member is not valid', E_USER_ERROR);
            exit();
        }

        $sql = sprintf("select l.lid, l.member mid, l.copy cid, l.date_loaned, l.date_due, l.date_returned, " .
            "l.loaned_by, c.access_no, b.bid, b.title, b.authors  " .
            "FROM ( (loan l LEFT JOIN copy c ON  l.copy = c.cid) LEFT JOIN book b ON c.bid=b.bid) " .
            "WHERE (l.returned=1 AND l.member=%d)", $member['mid']);
        $rs = executeSqlQuery($sql);
        return $rs;
    }
}
?>