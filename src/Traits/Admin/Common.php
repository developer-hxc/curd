<?php

namespace Hxc\Curd\Traits\Admin;


use Hxc\Curd\Traits\JsonReturn;

trait Common
{
    use JsonReturn;

    /**
     * 列表查询sql捕获
     * @param \think\db\Query $sql
     * @return \think\db\Query
     */
    public function indexQuery(\think\db\Query $sql)
    {
        return $sql;
    }

    /**
     * 分页数据捕获，用于追加数据
     * @param $item
     * @param $key
     * @return mixed
     */
    public function pageEach($item, $key)
    {
        return $item;
    }

    /**
     * 输出到列表视图的数据捕获
     * @param $data
     * @return mixed
     */
    public function indexAssign($data)
    {
        $data['lists'] = [
            'hxc' => []
        ];
        return $data;
    }

    /**
     * 输出到新增视图的数据捕获
     * @param $data
     * @return mixed
     */
    public function addAssign($data)
    {
        $data['lists'] = [
            'hxc' => []
        ];
        return $data;
    }

    /**
     * 新增数据插入数据库前数据捕获（注意：在数据验证之前）
     * @param $data
     * @return mixed
     */
    public function addData($data)
    {
        return $data;
    }

    /**
     * 输出到编辑视图的数据捕获
     * @param $data
     * @return mixed
     */
    public function editAssign($data)
    {
        return $data;
    }

    /**
     * 编辑数据插入数据库前数据捕获（注意：在数据验证之前）
     * @param $data
     * @return mixed
     */
    public function editData($data)
    {
        return $data;
    }

    /**
     * 成功添加数据后的数据捕获
     * 通过$this->returnFail($message);将错误信息返回到前端，并且回滚数据
     * @param int $id 添加后的id
     * @param mixed $data 接受的参数，包含追加的
     * @return mixed|void
     */
    public function addEnd($id, $data)
    {

    }

    /**
     * 成功编辑数据后的数据捕获
     * 通过$this->returnFail($message);将错误信息返回到前端，并且回滚数据
     * @param int $id 编辑数据的id
     * @param mixed $data 接受的参数，包含追加的
     * @return void
     */
    public function editEnd($id, $data)
    {

    }

    /**
     * 成功删除数据后的数据捕获
     * 通过$this->returnFail($message);将错误信息返回到前端，并且回滚数据
     * @param int $id 要删除数据的id
     * @return mixed|void
     */
    public function deleteEnd($id)
    {

    }
}