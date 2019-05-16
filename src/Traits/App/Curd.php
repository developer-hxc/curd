<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-14
 * Time: 9:04
 */

namespace Hxc\Curd\Traits\App;


use think\Cache;
use think\Request;

trait Curd
{
    /**
     * 每页显示的数量
     * @var int
     */
    protected $limit = 10;

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        /**
         * 遵循RESTful API
         * get 查
         * post 增
         * put 改
         * delete 删
         */
        switch ($request->method()){
            case 'GET':
                return $this->get($request);
                break;
            case 'POST':
                return $this->post($request);
                break;
            case 'PUT':
                return $this->put($request);
                break;
            case 'DELETE':
                return $this->delete($request);
                break;
        }
    }

    /**
     * 查
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    protected function get($request)
    {
        if($request->isGet()){
            $sql = model($this->model)->with($this->with);
            if($this->cache){
                $sql = $sql->cache(true,0,'controller');
            }
            $res = $sql->paginate($this->limit);
            return $this->returnRes($res->toArray()['data'],'数据不存在',$res);
        }
    }

    /**
     * 增
     * @param Request $request
     * @return \think\response\Json
     */
    protected function post(Request $request)
    {
        if($request->isPost()){
            $params = $request->param();
            $params_status = $this->validate($params,"{$this->model}.store");
            if(true !== $params_status){
                // 验证失败 输出错误信息
                return $this->returnFail($params_status);
            }
            $res = model($this->model)->allowField(true)->save($params);
            if($this->cache){
                Cache::tag('controller')->clear();
            }
            return $this->returnRes($res,'创建失败');
        }
    }

    /**
     * 改
     * @param Request $request
     * @return \think\response\Json
     */
    protected function put(Request $request)
    {
        if($request->isPut()){
            $params = $request->param();
            $params_status = $this->validate($params,"{$this->model}.update");
            if(true !== $params_status){
                // 验证失败 输出错误信息
                return $this->returnFail($params_status);
            }
            $res = model($this->model)->allowField(true)->save($params,['id' => $params['id']]);
            if($this->cache){
                Cache::tag('controller')->clear();
            }
            return $this->returnRes($res,'编辑失败');
        }
    }

    /**
     * 删
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    protected function delete(Request $request)
    {
        if($request->isDelete()){
            $params = $request->param();
            $params_status = $this->validate($params,"{$this->model}.delete");
            if(true !== $params_status){
                // 验证失败 输出错误信息
                return $this->returnFail($params_status);
            }
            $data = model($this->model)->get($params['id']);
            $res = $data->delete();
            if($this->cache){
                Cache::tag('controller')->clear();
            }
            return $this->returnRes($res,'删除失败');
        }
    }
}