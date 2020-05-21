<?php


namespace app\components;


use yii\base\Component;

class ApiFlash extends Component
{
    public $accessKey;

    public function getScreenshotUrl($url)
    {
        $params = http_build_query(array(
            "access_key" => $this->accessKey,
            "url" => $url,
        ));

        return "https://api.apiflash.com/v1/urltoimage?" . $params;
    }
}