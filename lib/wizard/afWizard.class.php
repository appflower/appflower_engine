<?php

class afValidationException extends Exception {
}

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
     * Checks that the current update action is expected.
     * It renders an JSON error if the form is out of the wizard sequence.
     */
    public static function checkStepOrRenderError() {
        try {
            self::getValidSteps();
        } catch (afValidationException $e) {
            self::renderError($e->getMessage());
        }
    }

    /**
     * Remembers the taken step.
     * It should be called after successful validation.
     */
    public static function takeStep() {
        $steps = self::getValidSteps();
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $holder->set('steps', $steps, self::$SESSION_NS);
    }

    private static function getValidSteps() {
        $holder = sfContext::getInstance()->getUser()->getAttributeHolder();
        $path = $holder->get('path', null, self::$SESSION_NS);
        if ($path === null) {
            // To support not-yet-migrated wizards.
            return array();
        }

        $module = sfContext::getInstance()->getModuleName();
        $action = sfContext::getInstance()->getActionName();
        $updateAction = "$module/$action";

        $steps = $holder->get('steps', array(), self::$SESSION_NS);
        if (count($steps) >= count($path)) {
            Console::debug('extra updateAction:', $updateAction,
                'steps:', $steps, 'path:', $path);
            throw new afValidationException('The wizard is already finished!');
        }

        $steps = self::getStepsTo($steps, $updateAction);
        for($i = 0, $len = count($steps); $i < $len; $i++) {
            if ($steps[$i] !== $path[$i]) {
                Console::debug('wrong steps:', $steps, 'path:', $path);
                throw new afValidationException('It is not possible to skip a wizard step.');
            }
        }

        return $steps;
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

