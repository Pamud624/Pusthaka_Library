<?php
class Books
{
    function toStringCopy($copy)
    {
        //cid, access_no, reference, barcode, lending_type, acquired_on, notes
        return
                '<strong>cid</strong>: ' . $copy['cid'] . '&nbsp;&nbsp;' . '<strong>Access No</strong>: ' . $copy['access_no'] . '<strong>Reference</strong>: ' .
                $copy['reference'] . '&nbsp;&nbsp;' . '<strong>Lending Type</strong>: ' . $copy['lending_type'] . '&nbsp;&nbsp;' .
                '<strong>Acquired On</strong>: ' . $copy['acquired_on'] . '&nbsp;&nbsp;' . '<strong>Barcode</strong>: ' . $copy['barcode'] . '&nbsp;&nbsp;' .
                '<strong>Notes</strong>: ' . $copy['notes'] . '<br>';
    }

    function toString($book)
    {
        //bid, isbn, class, location, title, authors, edition,
        //publisher, published_year, subjects, lang, series, pages
        $str =
                '<strong>Title</strong>:' . $book['title'] . '&nbsp;&nbsp;' . '<strong>Authors</strong>: ' . $book['authors'] . '<br>' .
                '<strong>Isbn</strong>: ' . $book['isbn'] . '&nbsp;&nbsp;' . '<strong>Class</strong>: ' . $book['class'] . '&nbsp;&nbsp;' .
                '<strong>Location</strong>: ' . $book['location'] . '<br>' .
                '<strong>Publisher</strong>: ' . $book['publisher'] . '&nbsp;&nbsp;' . '<strong>Year</strong>: ' . $book['published_year'] . '&nbsp;&nbsp;' .
                '<strong>Edition</strong>: ' . $book['edition'] . '&nbsp;&nbsp;' . '<strong>Language</strong>: ' . $book['lang'] . '&nbsp;&nbsp;' .
                '<strong>Pages</strong>: ' . $book['pages'] . '<br>' .
                '<strong>Subjects</strong>: ' . $book['subjects'] . '&nbsp;&nbsp;' . '<strong>Series</strong>: ' . $book['series'] . '<br>';

        $rs = $this->getCopies($book);
        $cnt = mysqli_num_rows($rs);
        if ($cnt == 0) {
            $str .= "<div class='contentEm'>";
            $str .= 'There are no copies of this book';
            $str .= "</div>";
        } elseif ($cnt > 0) {
            $str .= "<div class='contentEm'>";
            while ($r = mysqli_fetch_assoc($rs)) {
                $str .= $this->toStringCopy($r);
            }
            $str .= "</div>";
        } else { //ERROR

        }
        return $str;
    }

    function addBook($book)
    {
        //[Validate new record fields]--------------------

        //[Check if the new record is unique]--------------------

        //[Add new record]--------------------
        //bid, isbn, class, location, title, authors, edition,
        //publisher, published_year, subjects, lang, series, pages
        $sql = sprintf("INSERT INTO book (isbn, class, location, title, authors, edition, publisher, published_year, subjects, lang, series, pages) VALUES ('%s','%s', '%s', '%s','%s','%s','%s',%d,'%s','%s','%s',%d)",
                       $book['isbn'], $book['class'], $book['location'], $book['title'], $book['authors'], $book['edition'], $book['publisher'], $book['published_year'],
                       $book['subjects'], $book['lang'], $book['series'], $book['pages']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        $new_id = $a['id'];
        $book['bid'] = $new_id;
        if ($rows_affected == 0) { //TEST
            $msg = 'The system could not add a new book record.<br>' .
                   'Please review the settings you entered and try again.<hr>' .
                   $this->toString($book) . '<hr>' .
                   "<a href='book_add.php'>Try Again</a>";
            $title = 'Failed to Add New Book';
            displayMsg($msg, $title);
        } elseif ($rows_affected == 1) {
            $msg = 'A new book with the following details was added.<hr>' .
                   $this->toString($book) . '<hr>' .
                   "<a href='book_edit.php?ID=" . $new_id . "'>Edit Book/Add Copies</a>" .
                   "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?do=del&ID=" . $new_id . "'>Delete Book</a>";
            $title = 'New Book Added';
            $backlink = 'index.php';

            //[Log Event]---------------------------
            $des = '[BID=' . $new_id . "] ==> [" . $book['title'] . ' by ' . $book['authors'] . "]";
            logEvent('BOOK_ADDED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsg($msg, $title, $backlink);
        }
    } // function addBook()

    function updateBook($book)
    {
        $sql = sprintf("update book set isbn='%s', class='%s', location='%s', title='%s', authors='%s', edition='%s', publisher='%s', published_year=%d, subjects='%s', lang='%s', series='%s', pages=%d WHERE bid=%d",
                       $book['isbn'], $book['class'], $book['location'], $book['title'], $book['authors'], $book['edition'], $book['publisher'], $book['published_year'],
                       $book['subjects'], $book['lang'], $book['series'], $book['pages'], $book['bid']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        $bid = $book['bid'];
        if ($rows_affected == 1) {
            //[Display message] ------------------------------
            $msg = 'The book with the following details was updated<hr>' .
                   $this->toString($book) . '<hr>' .
                   "<a href='book_edit.php?ID=$bid'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_view.php?ID=$bid'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'Book Updated';

            //[Log Event]---------------------------
            $des = '[BID=' . $book['bid'] . "] ==> [" . $book['title'] . ' by ' . $book['authors'] . "]";
            logEvent('BOOK_UPDATED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsg($msg, $title);
        } elseif ($rows_affected == 0) {
            $msg = 'No changes were made to this book, because you hadn\'t changed anything.<hr>' .
                   $this->toString($book) . '<hr>' .
                   "<a href='book_edit.php?ID=$bid'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_view.php?ID=$bid'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'No Changes Made';
            displayMsg($msg, $title);
        }
    } // function updateBook()

    function deleteBook($book)
    {
        $bid = $book['bid'];
        // Check if there are any copies
        $sql = sprintf("SELECT * FROM copy WHERE bid=%d", $bid);
        $rs = executeSqlQuery($sql);
        $rows = mysqli_num_rows($rs);
        if ($rows != 0) {
            $msg = 'The following book was not deleted, because it has associated copies.<hr>' .
                   $this->toString($book) . '<hr>' .
                   "<a href='book_edit.php?ID=$bid'>Edit Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_view.php?ID=$bid'>View Full Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'Book NOT Deleted';
            displayMsg($msg, $title);
        }

        //[Delete record] ------------------------------
        $sql = sprintf("DELETE FROM book WHERE bid=%d", $bid);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        if ($rows_affected == 1) {
            //[Display message] ------------------------------
            $msg = 'The book with the following details was deleted<hr>' .
                   $this->toString($book);
            $title = 'Book Deleted';
            $backlink = 'book_search.php';

            //[Log Event]---------------------------
            $des = '[BID=' . $book['bid'] . "] ==> [" . $book['title'] . ' by ' . $book['authors'] . "]" . '<br>' .
                   addslashes($this->toString($book));
            logEvent('BOOK_DELETED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsg($msg, $title, $backlink);
        } else {
            $msg = 'Could not delete Book<br>';
            trigger_error($msg, E_USER_ERROR);
            exit();
        }
    } // function deleteBook($book)

    function addCopy($copy)
    {
        //[Validate new record fields]--------------------
        if (($copy['acquired_on'] != "") && (strtotime($copy['acquired_on']) == -1)) {
            $msg = "The 'Acquired Date' should be either blank or in the format <strong>yyyy-mm-dd</strong>";
            displayMsgInSamePage($msg, $copy['bid'], 'Error: Acquired Date is Invalid');
        }
        if (!($copy['access_no'] > 0)) {
            $msg = "The access number must be a number.";
            displayMsgInSamePage($msg, $copy['bid'], 'Error: Access Number Invalid');
        }


        //[Check if the new record is unique]--------------------
        $sql = "SELECT * FROM copy WHERE access_no='" . $copy['access_no'] . "'";
        $rs = executeSqlQuery($sql);
        $count = mysqli_num_rows($rs);
        if ($count >= 1) {
            $msg = "The access number " . $copy['access_no'] . " already exists!<br>" .
                   'Please enter a unique access number for each copy';
            displayMsgInSamePage($msg, $copy['bid'], 'ERROR: Duplicate Access Number');
        }


        //[Add new record]--------------------
        $sql = sprintf("INSERT INTO copy (bid, access_no, reference, lending_type, acquired_on, notes, price, currancy) VALUES (%d,'%s',%d,'%s', '%s', '%s', '%s', '%s')",
                       $copy['bid'], $copy['access_no'], $copy['reference'], $copy['lending_type'], $copy['acquired_on'], $copy['notes'], $copy['price'], $copy['currancy']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        $new_id = $a['id'];
        if ($rows_affected == 0) { //TEST
            $msg = 'The system could not add a new copy to the following book.<br>' .
                   'Please review the settings you entered and try again.<hr>' .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_edit.php?ID=" . $copy['bid'] . "'>Try Again</a>";
            $title = 'Failed to Add New Copy';
            displayMsg($msg, $title);
        } elseif ($rows_affected == 1) {
            $msg = 'A new copy was added.<br>' .
                   $this->toStringCopy($copy) .
                   "<a href='book_copy_edit.php?ID=" . $new_id . "'>Edit/Delete this Copy</a>";

            //[Log Event]---------------------------
            $des = '[BID=' . $new_id . " ACC#" . $copy['access_no'] . ' ' . $book['lending_type'] . "]";
            logEvent('COPY_ADDED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsgInSamePage($msg, $copy['bid'], 'New Copy Added');
        }
    } // function addCopy($copy)

    function updateCopy($copy)
    {
        $sql = sprintf("update copy set access_no='%s', reference=%d, lending_type='%s', acquired_on='%s', notes='%s', copy_status='%s', lost_by=%d, lost_action='%s', amount_paid=%d, replaced_book=%d, action_date='%s', price='%s', currancy='%s' WHERE cid=%d",
                       $copy['access_no'], $copy['reference'], $copy['lending_type'], $copy['acquired_on'], $copy['notes'], $copy['copy_status'], $copy['lost_by'], $copy['lost_action'], $copy['amount_paid'], $copy['replaced_book'], $copy['action_date'], $copy['price'], $copy['currancy'], $copy['cid']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        $bid = $copy['bid'];
        $cid = $copy['cid'];
        if ($rows_affected == 1) {
            //[Display message] ------------------------------
            $msg = 'The copy with the following details was updated<hr>' .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'Copy Updated';

            //[Log Event]---------------------------
            $des = '[BID=' . $copy['cid'] . " ACC#" . $copy['access_no'] . ' ' . $book['lending_type'] . "]";
            logEvent('COPY_UPDATED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsg($msg, $title);
        } elseif ($rows_affected == 0) {
            $msg = 'No changes were made to this copy, because you hadn\'t changed anything.<hr>' .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'No Changes Made';
            displayMsg($msg, $title);
        }
    }

    function deleteCopy($copy)
    {
        $sql = sprintf("DELETE FROM copy WHERE cid=%d", $copy['cid']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        if ($rows_affected == 1) {
            //[Display message] ------------------------------
            $msg = 'The copy with the following details was deleted<hr>' .
                   $this->toStringCopy($copy);
            $title = 'Copy Deleted';
            $backlink = 'book_edit.php?ID=' . $copy['bid'];

            //[Log Event]---------------------------
            $des = '[BID=' . $copy['cid'] . " ACC#" . $copy['access_no'] . ' ' . $book['lending_type'] . "]";
            logEvent('COPY_DELETED', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            displayMsg($msg, $title, $backlink);
        } else {
            $msg = 'Could not delete Copy<br>';
            trigger_error($msg, E_USER_ERROR);
            exit();
        }
    }

    function getBookByID($bid)
    {
        $sql = "SELECT * FROM book WHERE bid=" . $bid;
        $rs = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($rs);
        if ($rowcount == 0) {
            trigger_error("There is no book with ID =" . $bid, E_USER_ERROR);
            exit();
        } elseif ($rowcount > 1) {
            trigger_error("DATA ERROR: There is more than one book with ID =" . $bid, E_USER_ERROR);
            exit();
        } elseif ($rowcount == 1) {
            $row = mysqli_fetch_assoc($rs);
            return $row;
        } else {
            trigger_error('UNKNOWN ERROR: while accessing book with ID = ' . $bid, E_USER_ERROR);
            exit();
        }
    } // function book getBookByID()

    function getCopies($book)
    {
        $sql = "SELECT * FROM copy WHERE bid=" . $book['bid'];
        $rs = executeSqlQuery($sql);
        $rowcount = mysqli_num_rows($rs);
        if ($rowcount >= 0) {
            return $rs;
        } else {
            trigger_error('UNKNOWN ERROR: while getting copies of book with ID = ' . $book['bid'], E_USER_ERROR);
            exit();
        }
    }

    function getCopyByID($cid)
    {
        $sql = "SELECT * FROM copy WHERE cid=" . $cid;
        $rs = executeSqlQuery($sql);
        $cnt = mysqli_num_rows($rs);
        if ($cnt == 0) {
            trigger_error("There is no copy with ID =" . $id, E_USER_ERROR);
            exit();
        } elseif ($cnt > 1) {
            trigger_error("DATA ERROR: There is more than one copy with ID =" . $id, E_USER_ERROR);
            exit();
        } elseif ($cnt == 1) {
            $row = mysqli_fetch_assoc($rs);
            return $row;
        } else {
            trigger_error('UNKNOWN ERROR', E_USER_ERROR);
            exit();
        }

    }

    function getAllBooks()
    {
        $sql = "SELECT * FROM member ORDER BY surname, firstnames, title";
        $rs = executeSqlQuery($sql);
        return $rs;
    }

    function changeBarcode($copy, $barcode)
    {
        $sql = sprintf("SELECT * FROM copy WHERE barcode='%s'", $barcode);
        $rs = executeSqlQuery($sql);
        $cnt = mysqli_num_rows($rs);
        if ($cnt > 0) { //Specified barcode already exist
            $msg = "The barcode <strong>$barcode</strong> already exists!<br>Barcode was not changed<hr>" .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'No Changes Made';
            displayMsg($msg, $title);
        }

        $sql = sprintf("update copy set barcode='%s' WHERE cid=%d", $barcode, $copy['cid']);
        $a = executeSqlNonQuery($sql);
        $rows_affected = $a['rows'];
        $bid = $copy['bid'];
        $cid = $copy['cid'];
        if ($rows_affected == 1) {
            //[Log Event]---------------------------
            $des = '[' . $copy['barcode'] . "] ==> [$barcode]";
            logEvent('BOOK_BARCODE', $_SESSION['CurrentUser']['mid'], 0, addslashes($des));

            //[Display message] ------------------------------
            $msg = "Barcode Changed to: $barcode<br>Copy Details before the change are:<hr>" .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'Copy Updated';
            displayMsg($msg, $title);
        } elseif ($rows_affected == 0) {
            $msg = 'Barcode was not changed, because you had specified the old barcode as the new one.<hr>' .
                   $this->toStringCopy($copy) . '<hr>' .
                   "<a href='book_copy_edit.php?ID=$cid'>Edit This Copy Again</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_edit.php?ID=$bid'>Edit the Associated Book</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='book_search.php'>Browse Books</a>";
            $title = 'No Changes Made';
            displayMsg($msg, $title);
        }
    }


} // class


?>