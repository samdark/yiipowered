<?php

Yii::$container->set('yii\grid\GridView', [
    'pager' => [
        'options' => [
            'class' => 'pagination pull-right',
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-hover'
    ],
    'options' => [
        'class' => 'panel panel-default'
    ],
    'layout' => "{items}{pager}",
]);

Yii::$container->set('yii\grid\ActionColumn', [
    'header' => 'Action',
    'headerOptions' => [
        'class' => 'text-center col-md-1',
    ],
    'contentOptions' => [
        'class' => 'text-center text-nowrap',
    ],
    'buttonOptions' => [
        'class' => 'btn btn-default btn-xs',
    ],
    'template' => '{update} {delete}',
]);

/*
Yii::$container->set('yii\bootstrap\ActiveForm', [
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'options' => [
        'autocomplete' => 'off'
    ],
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-8',
            'hint' => 'col-sm-offset-2 col-sm-8'
        ],
    ],
]);
*/