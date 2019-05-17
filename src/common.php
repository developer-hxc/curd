<?php

use think\Console;
use think\response\Json;
use think\Route;

Console::addDefaultCommands([
    "Hxc\\Curd\\Command\\Hxc",
]);

Route::rule([
    'generate/showTables' => '\\Hxc\\Curd\\Controller\\Generate@showTables',
    'generate/getModelData' => '\\Hxc\\Curd\\Controller\\Generate@getModelData',
    'generate/getTableFieldData' => '\\Hxc\\Curd\\Controller\\Generate@getTableFieldData',
    'generate/generate' => '\\Hxc\\Curd\\Controller\\Generate@generate',
    'generate/generateRelation' => '\\Hxc\\Curd\\Controller\\Generate@generateRelation',
    'generate' => '\\Hxc\\Curd\\Controller\\Generate@index',
]);