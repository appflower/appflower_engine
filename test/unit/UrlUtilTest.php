<?php
include(dirname(__FILE__).'/../../bootstrap/dbunit.php');
$t = new lime_test(7, new lime_output_color());

$t->is(UrlUtil::addParam('http://hello', 'token', '1234'),
    'http://hello?token=1234');
$t->is(UrlUtil::addParam('http://hello?good=1', 'token', '1234'),
    'http://hello?good=1&token=1234');
$t->is(UrlUtil::addParam('http://hello?good=1&time=now', 'token', '1234'),
    'http://hello?good=1&time=now&token=1234');

$t->is(UrlUtil::addParams('http://hello',
    array('token'=>'1234', 'escape'=>'with&and=value')),
    'http://hello?token=1234&escape=with%26and%3Dvalue');

$t->is(UrlUtil::getPathPart('http://example.com/server/listServer?id=1'),
    '/server/listServer');
$t->is(UrlUtil::getPathPart('http://example.com'), '');
$t->is(UrlUtil::getPathPart('https://example.com/hello#world'), '/hello');
