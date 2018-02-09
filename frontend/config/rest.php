<?php
/*
 * - `'PUT,PATCH users/<id>' => 'user/update'`: update a user
 * - `'DELETE users/<id>' => 'user/delete'`: delete a user
 * - `'GET,HEAD users/<id>' => 'user/view'`: return the details/overview/options of a user
 * - `'POST users' => 'user/create'`: create a new user
 * - `'GET,HEAD users' => 'user/index'`: return a list/overview/options of users
 * - `'users/<id>' => 'user/options'`: process all unhandled verbs of a user
 * - `'users' => 'user/options'`: process all unhandled verbs of user collection
'PUT,PATCH {id}' => 'update',
'DELETE {id}' => 'delete',
'GET,HEAD {id}' => 'view',
'POST' => 'create',
'GET,HEAD' => 'index',
'{id}' => 'options',
'' => 'options',
 *  * You may configure [[only]] and/or [[except]] to disable some of the above rules.
 * You may configure [[patterns]] to completely redefine your own list of rules.
 * You may configure [[controller]] with multiple controller IDs to generate rules for all these controllers.
 * For example, the following code will disable the `delete` rule and generate rules for both `user` and `post` controllers:
 *
 * ```php
 * [
 *     'class' => 'yii\rest\UrlRule',
 *     'controller' => ['user', 'post'],
 *     'except' => ['delete'],
 * ]
 * ```
 */
return [
    'enablePrettyUrl' => true,
    'enableStrictParsing'=>false,
    'showScriptName' => false,
    'rules' => [
         [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['api'=>'category'],
            'patterns'=>[],
            'extraPatterns' => [
                'GET category' => 'list',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['api'=>'posts'],
            'extraPatterns' => [
                'GET index' => 'index',
                'GET categoy/list' => 'categoy-list',
                'GET keyword/list' => 'keyword-list',
                'GET info' => 'info',
                'GET recommend' => 'recommend',
                'GET search' => 'search',

                'GET categoy/list/<cid>/<page>/<pageSize>' => 'categoy-list',
                'GET keyword/list/<cid>/<page>/<pageSize>' => 'keyword-list',
                'GET info/<id>/<page>' => 'info',
                'GET search/<key>' => 'search',
            ]
        ],

        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['api'=>'keyword'],
            'extraPatterns' => [
                'GET keyword' => 'list',
            ]
        ],
    ],

];
