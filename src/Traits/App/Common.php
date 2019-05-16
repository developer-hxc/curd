<?php
/**
 * Created by PhpStorm.
 * Auth: Administrator
 * Date: 2019-05-11
 * Time: 16:56
 */

namespace Hxc\Curd\Traits\App;


use think\Cache;
use think\Request;
use think\Session;

trait Common
{
    /**
     * 通用返回，程序内部判断应该返回的状态
     * @param $flag
     * @param $failMessage
     * @param array $res
     * @return \think\response\Json
     */
    public function returnRes($flag,$failMessage,$res = [])
    {
        if($flag || is_array($flag)){
            return $this->returnSuccess($res);
        }else{
            return $this->returnFail($failMessage);
        }
    }

    /**
     * @param array $res
     * @return \think\response\Json
     */
    public function returnSuccess($res = [])
    {
        $data = [
            'code' => 1,
            'status' => 'success',
        ];
        if($res){
            $data['data'] = $res;
        }
        return json_encode($data);
    }

    /**
     * @param $failMessage
     * @return \think\response\Json
     */
    public function returnFail($failMessage)
    {
        $data = [
            'code' => 0,
            'status' => 'fail',
            'msg' => $failMessage
        ];
        return json_encode($data);
    }

    /**
     * @return \think\response\Json
     */
    public function notLogin()
    {
        $data = [
            'code' => -1,
            'status' => 'fail',
            'msg' => '没有登录'
        ];
        return json_encode($data);
    }

    /**
     * 获取当前登录用户的id
     * @return mixed
     */
    public function getAuthId()
    {
        return $this->getAuth()['id'];
    }

    /**
     * 获取当前登录用的数据
     * @return mixed
     */
    public function getAuth()
    {
        $token = Request::instance()->param('token');
        if($token){//token登录
            $res = Cache::get($token);
        }else{//web登录
            $res = Session::get('data','auth');
        }
        return $res;
    }
}