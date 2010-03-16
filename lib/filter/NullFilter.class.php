<?php

/**
 * A filter that converts empty param values into nulls.
 * It does so only for *_value named parameters to be safer.
 */
class NullFilter extends sfFilter
{
    public function execute ($filterChain)
    {
        if ($this->isFirstCall()) {
            $holder = $this->context->getRequest()->getParameterHolder();
            $params =& $holder->getAll();
            if (isset($params['edit']) && is_array($params['edit'])) {
                foreach ($params['edit'] as &$edit) {
                    self::nullifyEmptyParams($edit);
                }
            }
        }

        $filterChain->execute();
    }

    private static function nullifyEmptyParams(&$params)
    {
        foreach ($params as $key => $value) {
            if ($value === '' && StringUtil::endsWith($key, '_value')) {
                unset($params[$key]);
            }
        }
    }
}
