<?php


namespace app\modules\api1\controllers;


use yii\helpers\Markdown;
use yii\web\Controller;

class DocsController extends Controller
{
    public function actionIndex()
    {
        $docs = file_get_contents(\Yii::getAlias('@app/modules/api1/docs.md'));
        
        return $this->render('index', [
            'content' => Markdown::process($docs, 'gfm'),
        ]);
    }
}