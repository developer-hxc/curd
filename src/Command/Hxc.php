<?php

namespace Hxc\Curd\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Hxc extends Command
{
    protected function configure()
    {
        $this->setName('hxc')->setDescription($this->convertString('代码生成器开启'));
    }

    private function convertString($str)
    {
        if (strpos(PHP_OS, 'WIN') !== false) {
            return mb_convert_encoding($str, 'GBK');
        }
        return $str;
    }

    protected function execute(Input $input, Output $output)
    {
        //询问是否需要其他功能
        if (function_exists('system')) {
            $needPay = $output->confirm($input, $this->convertString("项目中是否需要支付功能？"), false);
            if ($needPay) {
                system('composer require hxc/qt-pay');
                $output->writeln('---------------------------------------');
                $output->writeln($this->convertString('支付功能已引入，详细用法请查看文档'));
            }
            $needSms = $output->confirm($input, $this->convertString("项目中是否需要短信功能？"), false);
            if ($needSms) {
                system('composer require hxc/qt-sms');
                $output->writeln('---------------------------------------');
                $output->writeln($this->convertString('短信功能已引入，详细用法请查看文档'));
            }
        } else {
            $output->writeln('---------------------------------------');
            $output->writeln($this->convertString('向导所需system函数已被禁用，请根据项目需要自行执行以下代码：'));
            $output->writeln($this->convertString('支付功能：composer require hxc/qt-pay'));
            $output->writeln($this->convertString('短信功能：composer require hxc/qt-sms'));
        }
        $doc = '这是代码生成器所需文件。';

        file_put_contents(ROOT_PATH . 'hxc.lock', $doc);
        $output->writeln('---------------------------------------');
        $output->writeln($this->convertString('代码生成工具地址：/generate'));
        $targetPath = APP_PATH . 'extra/';
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        if (file_exists($targetPath . 'curd.php')) {
            //配置文件已存在
            $file = realpath(__DIR__ . '/../config.php');
            $output->writeln('---------------------------------------');
            $output->warning($this->convertString('配置文件（' . realpath($targetPath . 'curd.php') . '）已存在。请查看' . $file . '确认是否更新。'));
        } else {
            copy(__DIR__ . '/../config.php', $targetPath . 'curd.php');
        }
    }
}