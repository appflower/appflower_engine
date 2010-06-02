<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(17, new lime_output_color());

$results = array();
for($i = 0; $i < 28; $i++) {
    $results[] = array('id'=>$i, 'name'=>sprintf('row%03d', $i));
}

function getFakeResults($id) {
    global $t, $results;
    $t->is($id, 1);
    return $results;
}

$source = new afStaticSource('getFakeResults', array(1));

$totalCount = count($results);
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), $totalCount);

$source->setStart(2);
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), $totalCount - 2);

$source->setLimit(3);
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), 3);

$source->setStart(0);
$source->setLimit(0);
$t->is(count($source->getRows()), 0);
$source->setLimit(null);
$t->is(count($source->getRows()), $totalCount);

$source->setSort('name', 'DESC');
$rows = $source->getRows();
$t->is(count($rows), 28);
$t->is($rows[0]['name'], 'row027');
$t->is($rows[27]['name'], 'row000');

$source->setSort('name', 'ASC');
$rows = $source->getRows();
$t->is($rows[0]['name'], 'row000');

$results = array('totalCount'=>100, 'rows'=>$results);
$source = new afStaticSource('getFakeResults', array(1));
$t->is($source->getTotalCount(), 100);
$t->is(count($source->getRows()), count($results['rows']));

$source->setStart(10);
$t->is(count($source->getRows()), count($results['rows']));
