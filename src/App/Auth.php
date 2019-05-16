<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 17:19
 */

namespace Hxc\Curd\App;


use app\app\model\User;
use think\Cache;
use think\Request;
use Hxc\Curd\App\Auth as AuthModel;
use think\Session;

trait Auth
{
    /**
     * 登录方法
     * @param Request $request
     * @return mixed
     * @throws \think\Exception
     */
    public function login(Request $request)
    {
        $params = $request->param();
        $params_status = $this->validate($params,'Auth.login');
        if(true !== $params_status){
            // 验证失败 输出错误信息
            return $this->returnFail($params_status);
        }
        $res = $this->validateLogin($params);
        $data= [];
        if($res){//登录成功
            $data = $res->toArray();
            $token = $this->generateToken($data);
            $this->setAuth($data,$token);
            $data['token'] = $token;
        }
        return $this->returnRes($res,'登录失败:密码不正确',$data);
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $params = $request->param();
        $params_status = $this->validate($params,'Auth.register');
        if(true !== $params_status){
            // 验证失败 输出错误信息
            return $this->returnFail($params_status);
        }
        $status = AuthModel::where([$this->username => $params['username']])->find();
        if($status){
            return $this->returnFail('会员已存在');
        }
        $params[$this->username] = $params['username'];
        $auth = new \app\app\model\Auth;
        $auth->data($params,true);
        $res = $auth->allowField(true)->save();
        if($res){
            //自增id
            $id = $auth->id;
            return $this->returnSuccess('账号创建成功');
        }else{
            return $this->returnFail('账号创建失败');
        }
    }

    /**
     * 找回密码
     * @param Request $request
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function resetPassword(Request $request)
    {
        $params = $request->param();
        $params_status = $this->validate($params,'Auth.resetPassword');
        if(true !== $params_status){
            // 验证失败 输出错误信息
            return $this->returnFail($params_status);
        }
        $id = $this->getAuthId();
        $user = AuthModel::get($id);
        $res = password_verify($params['old_password'],$user->password);
        if($res){
            $save_status = $user->validate('Auth.edit')->allowField(true)->save([
                'password' => $params['password']
            ]);
            if(false === $save_status){
                return $this->returnFail($user->getError());
            }else{
                return $this->returnSuccess();
            }
        }else{
            return $this->returnFail('旧密码验证失败');
        }
    }

    /**
     * 登录验证
     */
    protected function validateLogin($data)
    {
        $username = $data['username'];
        $password = $data['password'];
        $auth = AuthModel::get([$this->username => $username]);
        if($auth){
            return password_verify($password,$auth->password)?$auth:false;
        }else{
            return false;
        }
    }

    /**
     * 获取token
     * @param $params
     * @return string
     */
    protected function generateToken($params)
    {
        $expire = config('token_expire')==0?0:time()+config('token_expire');
        //用户id-用户名-有效期-登录时间
        $token = base64_encode($params['id'] . '-' . $params['name'] . '-'. $expire . '-' . time());
        return $token;
    }

    /**
     * 设置登录
     * @param $data
     * @param $token
     */
    protected function setAuth($data,$token)
    {
        $token_expire = config('token_expire');
        Cache::set($token,$data,$token_expire);
        Session::set('data',$data,'auth');
    }
}