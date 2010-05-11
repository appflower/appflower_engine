<?php

interface afIDataSource {
    /**
     * Sets the start offset.
     */
    public function setStart($start);

    /**
     * Sets the limit.
     * Zero limit means unlimited.
     */
    public function setLimit($limit);

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
