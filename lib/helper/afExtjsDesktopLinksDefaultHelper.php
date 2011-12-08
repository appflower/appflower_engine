<?php

if (file_exists(afExtjsBuilderParser::getHelperPath(sfProjectConfiguration::getActive()->getApplication()))) {
    afExtjsDesktopLinksBuilder::create(sfProjectConfiguration::getActive()->getApplication())->process()->getBuildedInstance()->end();
} else {
    afExtjsDesktopLinksBuilder::create('appFlowerPlugin', 'plugin')->process()->getBuildedInstance()->end();
}
