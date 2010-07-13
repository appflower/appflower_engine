<?php

class afOptionPassingStaticSource extends afStaticSource {
    protected function getResponse() {
        $options = array(
            'start'=>$this->start,
            'limit'=>$this->limit,
            'sort'=>$this->sortColumn,
            'dir'=>$this->sortDir);
        $params = $this->params;
        $params[] = $options;
        return afCall::funcArray($this->callback, $params);
    }
}

