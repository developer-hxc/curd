<?php

namespace Hxc\Curd\Traits\Admin;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\HttpResponseException;
use think\Request;
use think\Validate;

/**
 * Trait curd
 * @package app\admin\library\hxc
 * @property $countField
 * @property $modelName
 * @property $searchField
 * @property $pageLimit
 * @property $orderField
 * @property bool $cache
 * @property array $indexField
 * @property array $addField
 * @property array $editField
 * @property array $add_rule
 * @property array $edit_rule
 * @method mixed assign($name, $value = '')
 * @method mixed fetch($template = '', $vars = [], $replace = [], $config = [])
 * @mixin Common
 */
trait Curd
{

    /**
     * 列表页
     * @param Request $request
     * @throws DbException
     */
    public function index(Request $request)
    {
        $special = [];
        $only_arr = [];
        $where = [];
        foreach ($this->searchField as $k => $v) {
            if (is_array($v)) {
                $key = key($v);
                $val = $v[$key];
                $only_arr[] = $key;
                $special[$key] = $val;
            } else {
                $only_arr[] = $v;
            }
        }
        $relationSearch = '';
        $whereData = $this->search($request->only($only_arr), $special, $relationSearch);
        foreach ($whereData as $k => $v) {
            if ($k != 'pageSize' && $k != 'RelationSearch') {
                switch ($v['type']) {
                    case 'select':
                        $where[$v['field'] ?: $k] = $v['val'];
                        break;
                    case 'time_start':
                        $where[$v['field'] ?: $k][] = ['>= time', $v['val'] . ' 00:00:00'];
                        break;
                    case 'time_end':
                        $where[$v['field'] ?: $k][] = ['<= time', $v['val'] . ' 23:59:59'];
                        break;
                    default:
                        $where[$v['field'] ?: $k] = ['like', "%{$v['val']}%"];
                        break;
                }
            }
        }
        $pageSize = $request->param('pageSize') ?: $this->pageLimit;

        if (!empty($relationSearch)) {
            $model = model($this->modelName)->$relationSearch()->hasWhere([], null);
        } else {
            $model = model($this->modelName);
        }
        if ($this->cache) {
            $model->cache(true, 0, $this->modelName . '_cache_data');
        }
        $model->field($this->indexField)->where($where);

        $list = $this->indexQuery($model)->order($this->orderField)->paginate($pageSize)->each(function ($item, $key) {
            return $this->pageEach($item, $key);
        });
        $this->returnSuccess($list);
    }

    /**
     * 条件查询
     * @param Request $request
     * @param $params
     * @param $special
     * @param $relationSearch
     * @return array
     */
    public function search($params, $special, &$relationSearch)
    {
        $whereData = [];
        foreach ($params as $k => $v) {
            if ($v !== '') {
                $data = isset($special[$k]) ? $special[$k] : $k;
                $type = '';
                if (is_array($data)) {
                    $field = $data[0];
                    $type = $data[1];
                    if ($type == 'relation' && strpos($field, '.') !== false) {
                        $name = explode('.', $field, 2);
                        $name[0] = strtolower($name[0]);
                        $relationSearch = $name[0];
                    }
                } else {
                    $field = $k;
                }
                $whereData[$k] = [
                    'val' => $v,
                    'field' => $field,
                    'type' => $type
                ];
            }
        }
        return $whereData;
    }

    /**
     * 新增数据页
     * @param Request $request
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $params = $request->only($this->addField);
            $add_data = $this->addData($params);
            $validate = new Validate($this->add_rule);
            $result = $validate->check($add_data);
            if (!$result) {//验证不通过
                $this->returnFail($validate->getError());
            }
            //验证通过
            Db::startTrans();
            try {
                $model = model($this->modelName);
                $model->allowField(true)->save($add_data);
                $this->addEnd($model->id, $add_data);
            } catch (HttpResponseException $e) {
                Db::rollback();
                throw $e;
            } catch (\Exception $e) {
                Db::rollback();
                $this->returnFail($e->getMessage());
            }
            Db::commit();
            $this->returnSuccess(['id' => $model->id]);
        }
        $this->returnSuccess($this->addAssign([]));
    }

    /**
     * 编辑数据页
     * @param Request $request
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function edit(Request $request)
    {
        $id = $request->param('id');
        if (!$id) {
            $this->returnFail('参数有误，缺少id');
        }
        if ($request->isPost()) {
            $params = $request->only($this->editField);
            $edit_data = $this->editData($params);
            $validate = new Validate($this->edit_rule);
            $result = $validate->check($edit_data);
            if (!$result) {//验证不通过
                $this->returnFail($validate->getError());
            }
            //验证通过
            Db::startTrans();
            try {
                model($this->modelName)->allowField(true)->save($edit_data, ['id' => $id]);
                $this->editEnd($id, $edit_data);
            } catch (HttpResponseException $e) {
                Db::rollback();
                throw $e;
            } catch (\Exception $e) {
                Db::rollback();
                $this->returnFail($e->getMessage());
            }
            Db::commit();
            $this->returnSuccess();
        }
        $data = model($this->modelName)->find($id);
        $this->returnSuccess($this->editAssign([
            'id' => $id,
            'data' => $data
        ]));
    }

    /**
     * 删除
     * @param Request $request
     * @throws Exception
     */
    public function delete(Request $request)
    {
        $id = $request->param('id');
        $data = model($this->modelName)->get($id);
        if (empty($data)) {
            $this->returnFail();
        }
        Db::startTrans();
        try {
            $data->delete();
            $this->deleteEnd($id);
        } catch (HttpResponseException $e) {
            Db::rollback();
            throw $e;
        } catch (\Exception $e) {
            Db::rollback();
            $this->returnFail($e->getMessage());
        }
        Db::commit();
        $this->returnSuccess();
    }
}
