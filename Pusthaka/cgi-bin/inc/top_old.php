<table width="100%" border="0" align="center">
  <tr>
    <td>
    <table border="0">
        <tr>
          <td></td>          
          <td align="left" valign="bottom">&nbsp;</td>
          <td align="left" valign="bottom">
              <div style="height: 61px; font-size: 24px; color: #330000">

              <a href="index.php">
                        <img src="images/pusthaka1_logo.jpg" alt="Pusthaka Logo" style="border-style: none" />
              </a>
              </div>
          </td>
          <td align="left" valign="bottom">
          <table width="100%"  border="0">
            <tr>
              <td align="center" class="marginLogin"><?php if (!isset($_SESSION['CurrentUser'])){ ?>
                <form action="_login.php" method="post" name="login" class="marginLogin" id="login">
                	Username: <input name="Username" type="text" class="marginLoginText" id="Username3" size="10" />&nbsp;&nbsp;
                    Password: <input name="Password" type="password" class="marginLoginText" id="Password4" size="10" />&nbsp;&nbsp;
                    <input name="btnLogin" type="submit" class="marginLoginButton" id="btnLogin5" value="Login" />&nbsp;&nbsp                                      
                </form>
                New user? Please <a href="register.php">register</a>.
                <?php } else { 
		echo "Welcome " . $_SESSION['CurrentUser']['title'] . " " . $_SESSION['CurrentUser']['firstnames'] . " " . $_SESSION['CurrentUser']['surname'] . " (" . $_SESSION['CurrentUser']['mid'] . ")<br>";
		echo "<a href='_login.php'>logout</a>";
	 } ?></td>
            </tr>
          </table>
          
          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td class="menu">
      <table width="100%"  border="0">
        <tr>
          <td width="200"><span class="menuPageTitle"><?php echo $PageTitle; ?></span></td>
          <td>
              <a href="index.php" class="menuLink">Back to main screen</a>
          </td>
        </tr>
    </table></td>
  </tr>
</table>
