<?php
/**
 * Inventory related features.
 */

class InventoryService
{
    private $dbc;

    function __construct($dbc)
    {
        $this->dbc = $dbc;
    }



    /**
     * Find all inventory taking instances.
     *
     * @return void
     *
     */
    public function findAll()
    {
        // Exec query to get all from inventory_table

        // Wrap in InventoryTaking objects


    }
}
