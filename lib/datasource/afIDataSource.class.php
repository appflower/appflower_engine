<?php

interface afIDataSource {
    /**
     * Sets the start offset.
     */
    public function setStart($start);

    /**
     * Sets the limit.
     * Null limit means unlimited.
     */
    public function setLimit($limit);

    /**
     * Makes the rows sorted by the given column.
     */
    public function setSort($column, $sortDir='ASC');

    /**
     * Returns the total number of rows in the datasource.
     * That number is not affected by the limit.
     */
    public function getTotalCount();

    /**
     * Returns the selected rows.
     */
    public function getRows();
}
