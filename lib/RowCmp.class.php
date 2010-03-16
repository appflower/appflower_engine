<?php
class RowCmp
{
    private
        $sort,
        $dir;

    public function __construct($sort, $dir='ASC')
    {
        $this->sort = $sort;
        $this->dir = $dir;
    }

    public function cmp($rowA, $rowB)
    {
        $a = isset($rowA[$this->sort]) ? $rowA[$this->sort] : '';
        $b = isset($rowB[$this->sort]) ? $rowB[$this->sort] : '';
        if ($a == $b) {
            return 0;
        }
        $result = $a < $b ? -1 : 1;
        if ($this->dir === 'DESC') {
            return -$result;
        }
        return $result;
    }
}
