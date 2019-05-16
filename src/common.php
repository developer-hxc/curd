<?php

\think\Console::addDefaultCommands([
    "Hxc\\Curd\\Command\\Hxc",
]);

\think\Route::rule('generate$','\\Hxc\\Curd\\Controller\\Generate@index');