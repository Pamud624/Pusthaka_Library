<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Pusthaka: <?php echo $PageTitle; ?></title>
        <meta name="description" content="Pusthaka Integrated Library System">
        <meta name="author" content="Nalaka Jayasena">

        <!--[if lt IE 9]>
        <script src="lib/html5shiv/html5shiv.js"></script>
        <![endif]-->
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--[if IE 6]>
        <link href="lib/bootstrap-ie6/bootstrap-ie6.css" rel="stylesheet">
        <![endif]-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">

                    <a class="brand pull-left span2" href="index.php">Pusthaka</a>

                    <ul class="nav">
                        <li><a href="book_search.php">Catalog</a></li>
                        <li><a href="_newarrivals.php">New Arrivals</a></li>

                        <?php if (isset($_SESSION['CurrentUser']) && (($_SESSION['CurrentUser']['login_type'] == "ADMIN") || ($_SESSION['CurrentUser']['login_type'] == "LIBSTAFF"))) { ?>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Books
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">                      
                                    <li><a href="book_search.php">Browse Books</a></li>
                                    <li><a href="book_add.php">Add Book</a></li>                                    
                                </ul>
                            </li> 


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Members
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">                                    
                                    <li><a href="member_search.php">Browse Members</a></li>
                                    <li><a href="member_add.php">Add Member</a></li>
                                </ul>
                            </li> 

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Manage
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">  
                                    <li><a href="ir1.php">Issue & Return</a></li>
                                    <li><a href="reservations.php">Reservations</a></li>
                                    <li class="divider"></li>                                    
                                    <li><a href="circulation.php">Circulation</a></li>
                                    <li><a href="eventlog.php">Event Log</a></li>
                                </ul>
                            </li>

                            <li><a href="ir1-inventory.php">Inventory</a></li>
							
							<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Holidays
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">  
                                    <li><a href="_holidays.php">All Holidays</a></li>
                                    <li><a href="_add_holiday.php">Add a Holiday</a></li>                                   
                                   
                                </ul>
                            </li>
							<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Reports
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">  
                                    <li><a href="book_status_report.php">Book Status Report</a></li>
                                    <!--<li><a href="_add_holiday.php">Add a Holiday</a></li>  -->                                 
                                   
                                </ul>
                            </li>

                        <?php } ?>                                                
                    </ul>

                    <ul class="nav pull-right">                                                                        
                   <?php if (!isset($_SESSION['CurrentUser'])) { ?>
                        <form class="navbar-form pull-right" action="_login.php" method="post" name="login" id="login">
                            <input class="span1" name="Username" type="text" id="Username3" placeholder="user"/>&nbsp;&nbsp;
                            <input class="span2" name="Password" type="password" id="Password4" placeholder="password"/>&nbsp;&nbsp;
                            <input class="btn" name="btnLogin" type="submit" id="btnLogin5" value="Login" />&nbsp;&nbsp
                        </form>                
                    <?php } else { ?>
                        <li class="disabled">
                            <a class="active" href="#">Hello, <?php echo $_SESSION['CurrentUser']['username']; ?></a>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                My Pusthaka
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">                                    
                                <li><a href="my_loans.php">My Loans</a></li>
                                <li><a href="my_reservations.php">My Reservations</a></li>
                                <li><a href="my_history.php">My History</a></li>                                
                                <li><a href="my_info.php">My Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="_login.php">Logout</a></li>
                            </ul>
                        </li>                    

                    <?php } ?>  
                    </ul>
                    
                </div>
            </div>
        </div>       