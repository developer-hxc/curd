<?php
return [
    'success_code' => 1, //成功返回的code值
    'error_code' => 0, //失败返回的code值
    'back_base_controller' => '', //后台控制器基类，为空则使用\think\Controller
    'front_base_controller' => '', //前台无需登录的控制器基类,为空则使用front_base_controller的值
    'front_sign_controller' => '', //前台带登录验证的控制器基类,为空则使用front_base_controller的值
    /**
     * form表单字段模板
     * 采用php占位符规则
     * 顺序：name（必填）->label（必填）->value（添加页面留空）->attr（附加属性）
     */
    'form' => [
        'text' => '{include file="tpl/input" results="data" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'number' => '{include file="tpl/integer" results="data" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'select' => '{include file="tpl/selectCol" results="data" name="%s" label="%s" value="%s" list="hxc" attr=\'%s\'/}',
        'uploadImage' => '{include file="tpl/singleImage" results="data" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'ueditor' => '{include file="tpl/ueditor" results="data" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'date' => '{include file="tpl/date" results="params" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'datetime' => '{include file="tpl/datetime" results="params" name="%s" label="%s" value="%s" attr=\'%s\'/}',
        'textarea' => '{include file="tpl/input" results="data" name="%s" label="%s" value="%s" attr=\'%s\'/}',
    ],
    /**
     * 搜索字段模板
     * 采用php占位符规则
     * 顺序：name（必填）->label（必填）->value（添加页面留空）->attr（附加属性）
     */
    'search' => [
        'search' => '{include file=\"tpl/search\" results=\"params\" name=\"%s\" label=\"%s\" attr=\'\'/}',
    ]
];