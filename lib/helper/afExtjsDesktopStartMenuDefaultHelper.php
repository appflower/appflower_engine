<?php

if (file_exists(afExtjsBuilderParser::getHelperPath(sfProjectConfiguration::getActive()->getApplication()))) {
    afExtjsDesktopStartMenuBuilder::create(sfProjectConfiguration::getActive()->getApplication())->process()->getMenuInstance()->end();
} else {
    afExtjsDesktopStartMenuBuilder::create('appFlowerPlugin', 'plugin')->process()->getMenuInstance()->end();
}
