<?php


namespace hxc\Curd\Traits;

use think\Config;
use think\response\Json;

trait JsonReturn
{
    /**
     * 通用返回，程序内部判断应该返回的状态
     * @param $flag
     * @param $failMessage
     * @param array $res
     * @param null|integer $code
     * @return Json
     */
    public function returnRes($flag, $failMessage, $res = [], $code = null)
    {
        if ($flag || is_array($flag)) {
            return $this->returnSuccess($res, $code);
        } else {
            return $this->returnFail($failMessage, $code);
        }
    }

    /**
     * @param array $res
     * @param null|integer $code
     * @return Json
     */
    public function returnSuccess($res = [], $code = null)
    {
        if (is_null($code)) {
            $code = Config::get('curd.success_code');
        }
        $data = [
            'code' => $code,
            'status' => 'success',
        ];
        if ($res) {
            $data['data'] = $res;
        }
        return json($data);
    }

    /**
     * @param string $failMessage
     * @param null|integer $code
     * @return Json
     */
    public function returnFail($failMessage = '操作失败', $code = null)
    {
        if (is_null($code)) {
            $code = Config::get('curd.error_code');
        }
        $data = [
            'code' => $code,
            'status' => 'fail',
            'msg' => $failMessage
        ];
        return json($data);
    }
}