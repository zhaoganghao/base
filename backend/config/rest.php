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
            'patterns' => [],
            'controller' => ['login'=>'login'],
            'extraPatterns' => [
                'POST ' => 'login',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['category'=>'category'],
            'patterns'=>[],
            'extraPatterns' => [
                'GET list' => 'list',
                'POST createOrUpdate' => 'create-or-update',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['posts'=>'posts'],
            'extraPatterns' => [
                'GET list' => 'list',
                'GET images' => 'images',
                'POST createOrUpdate' => 'create-or-update',
                'POST updateStatus' => 'update-status',
                'POST updateCategory' => 'update-category',
                'POST updateRank' => 'update-rank',
                'POST addKeyword' => 'add-keyword',
                'POST delKeyword' => 'del-keyword',
                'POST addImage' => 'add-image',
                'POST delImage' => 'del-image',
                'POST addIcon' => 'add-icon',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['keyword'=>'keyword'],
            'extraPatterns' => [
                'GET list' => 'list',
                'POST createOrUpdate' => 'create-or-update',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['image'=>'image'],
            'extraPatterns' => [
                'GET list' => 'list',
                'POST bindPosts' => 'bind-posts',
                'POST upload' => 'upload',
                'POST updateStatus' => 'update-status',
                'POST updateCategory' => 'update-category',
                'POST addKeyword' => 'add-keyword',
                'POST delKeyword' => 'del-keyword',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['report'=>'report'],
            'extraPatterns' => [
                'GET realTime' => 'real-time',
                'GET date' => 'date',
                'GET hour' => 'hour',
                'GET promote/date' => 'promote-date',
                'GET api/date' => 'api-date',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['promote'=>'promote'],
            'extraPatterns' => [
                'GET list' => 'list',
                'POST createOrUpdate' => 'create-or-update',
            ]
        ],
    ],

];
