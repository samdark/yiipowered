<?php


namespace app\components;


use yii\base\Component;

class ScreenshotLayerApi extends Component
{
    public $accessKey;
    public $secret;

    public function getScreenshotUrl($url)
    {
        $args = [];
        $params['url'] = urlencode($url);
        $params += $args;

        $parts = [];
        foreach ($params as $key => $value) {
            $parts[] = "$key=$value";
        }

        $query = implode("&", $parts);

        $secretKey = md5($url . $this->secret);

        return "https://api.screenshotlayer.com/api/capture?access_key={$this->accessKey}&secret_key=$secretKey&$query";
    }
}
