<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(2, new lime_output_color());

$colMap = EventInfoPeer::getTableMap()->getColumn('publisher_id');
$t->is(afMetaDb::getRelatedMethodName($colMap), 'getPublisher');

$colMap = EventInfoPeer::getTableMap()->getColumn('confidentiality_id');
$t->is(afMetaDb::getRelatedMethodName($colMap), 'getEventImpactRelatedByConfidentialityId');

