<?php
return [
    'success_code' => 1, //成功返回的code值
    'error_code' => 0, //失败返回的code值
    'back_base_controller' => 'Right', //后台控制器基类，为空则使用\think\Controller
    'front_base_controller' => '', //前台无需登录的控制器基类,为空则使用\think\Controller
    'front_sign_controller' => 'SignIn', //前台带登录验证的控制器基类,为空则使用front_base_controller的值
    'index_template' => '',//列表页模板，为空则使用默认
    'add_template' => '',//添加页模板，为空则使用默认
    'edit_template' => '',//修改页模板，为空则使用默认
    /**
     * form表单字段模板，指定使用以下占位符
     * {{name}}{{label}}{{value}}{{attr}}
     */
    'form' => [
        'text' => '{include file="tpl/input" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'number' => '{include file="tpl/integer" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'select' => '{include file="tpl/selectCol" results="data" name="{{name}}" label="{{label}}" value="{{value}}" list="hxc" attr=\'{{attr}}\'/}',
        'uploadImage' => '{include file="tpl/singleImage" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'ueditor' => '{include file="tpl/ueditor" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'date' => '{include file="tpl/date" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'datetime' => '{include file="tpl/datetime" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
        'textarea' => '{include file="tpl/input" results="data" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'{{attr}}\'/}',
    ],
    /**
     * 搜索字段模板，指定使用以下占位符
     * {{name}}{{label}}{{value}}
     */
    'search' => [
        'text' => '{include file=\"tpl/search\" results=\"params\" name=\"{{name}}\" label=\"{{label}}\" attr=\'\'/}',
        'number' => '{include file="tpl/integer" results="params" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'\'/}',
        'select' => '{include file="tpl/select" results="params" name="{{name}}" label="{{label}}" value="{{value}}" list="hxc" attr=\'\'/}',
        'date' => '{include file="tpl/dateRange" results="params" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'\'/}',
        'datetime' => '{include file="tpl/datetimeRange" results="params" name="{{name}}" label="{{label}}" value="{{value}}" attr=\'\'/}',
        'textarea' => '{include file=\"tpl/search\" results=\"params\" name=\"{{name}}\" label=\"{{label}}\" value="{{value}}" attr=\'\'/}',
    ]
];