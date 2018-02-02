<?php

namespace app\components\object;

interface Linkable
{
    /**
     * @param array $params
     * 
     * @return array url to this object. Should be something to be passed to [[\yii\helpers\Url::to()]].
     */
    public function getUrl($params = []);

    /**
     * @return string title to display for a link to this object.
     */
    public function getLinkTitle();
    
}
