<?php

if (file_exists(afExtjsBuilderParser::getHelperPath(sfProjectConfiguration::getActive()->getApplication()))) {
    afExtjsDesktopStartMenuBuilder::create(sfProjectConfiguration::getActive()->getApplication())->process()->getBuildedInstance()->end();
} else {
    afExtjsDesktopStartMenuBuilder::create('appFlowerPlugin', 'plugin')->process()->getBuildedInstance()->end();
}
