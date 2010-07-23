<?php

class afWizard {
    private static $SESSION_NS = 'af/wizard';

    /**
     * Defines a path of [initialPath, updateAction, updateAction, ...].
     * All the given update actions have to be submitted
     * in the following steps.
     */
    public static function startPath($path) {
        if (count($path) < 2) {
            throw new Exception('The wizard path has to have a initial page and a final page.');
        }

        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $holder->removeNamespace(self::$SESSION_NS);
        $holder->set('path', $path, self::$SESSION_NS);
        $holder->set('steps', array($path[0]), self::$SESSION_NS);
    }

    public static function redefinePath($path) {
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $holder->set('path', $path, self::$SESSION_NS);
    }

    /**
     * Remembers the taken step.
     * It renders an JSON error if the form is out of the wizard sequence.
     */
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
                'steps:', $steps, 'path:', $path);
            self::renderError('The wizard is already finished!');
        }

        $steps = self::getStepsTo($steps, $updateAction);
        for($i = 0, $len = count($steps); $i < $len; $i++) {
            if ($steps[$i] !== $path[$i]) {
                Console::debug('wrong steps:', $steps, 'path:', $path);
                self::renderError('It is not possible to skip a wizard step.');
            }
        }

        $holder->set('steps', $steps, self::$SESSION_NS);
    }

    /**
     * Returns steps to the given action.
     * A shorter sequence of steps is returned
     * if the user went back.
     */
    private static function getStepsTo($steps, $updateAction) {
        $index = 0;
        foreach($steps as $step) {
            if ($step === $updateAction) {
                return array_slice($steps, 0, $index + 1);
            }
            $index++;
        }

        $steps[] = $updateAction;
        return $steps;
    }

    private static function renderError($msg) {
        $result = array('success'=>false, 'message'=>$msg);
        echo json_encode($result);
        exit;
    }
}
