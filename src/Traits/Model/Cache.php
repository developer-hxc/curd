<?php

namespace Hxc\Curd\Traits\Model;


use Think\Model;

/**
 * @mixin Model
 */
trait Cache
{
    protected static function init()
    {
        $event_arr = ['afterWrite', 'afterDelete'];

        foreach ($event_arr as $k => $v) {
            self::{$v}(function () {
                \think\Cache::clear($this->name . 'cache_data');
            });
        }
    }
}