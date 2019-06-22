<?php

namespace Hxc\Curd\Traits\App;

use think\exception\DbException;
use think\Request;
use think\response\Json;

/**
 * Trait Curd
 * @package Hxc\Curd\Traits\App
 * @property string $model
 * @property string $with
 * @property string $cache
 * @method array|string|true validate($data, $validate, $message = [], $batch = false, $callback = null)
 */
trait Curd
{
    use Common;
    /**
     * 每页显示的数量
     * @var int
     */
    protected $limit = null;

    /**
     * @param Request $request
     * @return Json|void
     * @throws DbException
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
        switch ($request->method()) {
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
     * @return Json|void
     * @throws DbException
     */
    protected function get($request)
    {
        if ($request->isGet()) {
            $sql = model($this->model)->with($this->with);
            if ($this->cache) {
                $sql = $sql->cache(true, 0, 'controller');
            }
            $res = $sql->paginate($this->limit);
            $this->returnRes($res->toArray()['data'], '数据不存在', $res);
        }
    }

    /**
     * 增
     * @param Request $request
     * @return Json|void
     */
    protected function post(Request $request)
    {
        if ($request->isPost()) {
            $params = $request->param();
            $params_status = $this->validate($params, "{$this->model}.store");
            if (true !== $params_status) {
                // 验证失败 输出错误信息
                $this->returnFail($params_status);
            }
            $res = model($this->model)->allowField(true)->save($params);
            $this->returnRes($res, '创建失败');
        }
    }

    /**
     * 改
     * @param Request $request
     * @return Json|void
     */
    protected function put(Request $request)
    {
        if ($request->isPut()) {
            $params = $request->param();
            $params_status = $this->validate($params, "{$this->model}.update");
            if (true !== $params_status) {
                // 验证失败 输出错误信息
                $this->returnFail($params_status);
            }
            $res = model($this->model)->allowField(true)->save($params, ['id' => $params['id']]);
            $this->returnRes($res, '编辑失败');
        }
    }

    /**
     * 删
     * @param Request $request
     * @return Json|void
     * @throws DbException
     */
    protected function delete(Request $request)
    {
        if ($request->isDelete()) {
            $params = $request->param();
            $params_status = $this->validate($params, "{$this->model}.delete");
            if (true !== $params_status) {
                // 验证失败 输出错误信息
                $this->returnFail($params_status);
            }
            $data = model($this->model)->get($params['id']);
            $res = $data->delete();
            $this->returnRes($res, '删除失败');
        }
    }
}