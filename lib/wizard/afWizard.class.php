<?php

class afWizard {
    private static $SESSION_NS = 'af/wizard';

    public static function startPath($path) {
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $holder->removeNamespace(self::$SESSION_NS);
        $holder->set('path', $path, self::$SESSION_NS);
    }

    public static function redefinePath($path) {
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $holder->set('path', $path, self::$SESSION_NS);
    }

    public static function takeStep() {
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $path = $holder->get('path', null, self::$SESSION_NS);
        if ($path === null) {
            // To support not-yet-migrated wizards.
            return;
        }

        $module = sfContext::getInstance()->getModuleName();
        $action = sfContext::getInstance()->getActionName();
        $updateAction = "$module/$action";

        $steps = $holder->get('steps', array(), self::$SESSION_NS);
        if (count($steps) >= count($path)) {
            Console::debug('extra updateAction:', $updateAction,
                'steps:', $steps, 'path:' $path);
            self::renderError('The wizard is already finished!');
        }

        $steps = self::getStepsTo($steps, $updateAction);
        $expected = $path[count($steps) + 1];
        if ($updateAction !== $expected) {
            Console::debug('wrong updateAction:', $updateAction,
                'steps:', $steps, 'path:' $path);
            self::renderError('It is not possible to skip a wizard step.');
        }

        $steps[] = $updateAction;
        $holder->set('steps', $steps, self::$SESSION_NS);
    }

    private static function getStepsTo($steps, $updateAction) {
        $index = 0;
        foreach($steps as $step) {
            if ($step === $updateAction) {
                return array_slice($steps, 0, $index);
            }
            $index++;
        }

        return $steps;
    }

    private static function renderError($msg) {
        $result = array('success'=>false, 'message'=>$msg);
        echo json_encode($result);
        exit;
    }
}
