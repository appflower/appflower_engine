<?php

/**
 * A filter that converts empty param values into nulls.
 * It does so only for *_value named parameters to be safer.
 */
class NullFilter extends sfFilter
{
    public function execute ($filterChain)
    {
        $request = $this->context->getRequest();
        if($this->isFirstCall() && $request->getMethod() == sfRequest::POST) {
            $holder = $request->getParameterHolder();
            $params =& $holder->getAll();
            if (isset($params['edit']) && is_array($params['edit'])) {
                foreach ($params['edit'] as &$edit) {
                    if (is_array($edit)) {
                        self::nullifyEmptyParams($edit);
                    }
                }
                self::nullifyEmptyParams($params['edit']);
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
