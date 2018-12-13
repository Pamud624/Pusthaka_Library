<?php
/**
 * Inventory view.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$allow = "ADMIN;LIBSTAFF";
$PageTitle = "Inventory";
include('../inc/init.php');
include('../classes/InventoryService.php');

//Save screen state
if (isset($_REQUEST['inventory_instance'])) {
    $_SESSION['page_state']['inventory']['instance'] = $_REQUEST['inventory_instance'];
}

$service = new InventoryService();

?>
<?php include("../inc/top.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <!-- MARGIN WAS HERE -->
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="contents">
                        Content here





                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php include("../inc/bottom.php"); ?>
