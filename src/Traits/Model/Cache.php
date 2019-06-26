<?php

namespace Hxc\Curd\Traits\Model;


use Think\Model;

/**
 * @mixin Model
 */
trait Cache
{
    protected function initialize()
    {
        parent::initialize();
        $event_arr = ['afterWrite', 'afterDelete'];
        foreach ($event_arr as $k => $v) {
            self::{$v}(function () {
                \think\Cache::clear($this->name . 'cache_data');
            });
        }
    }
}