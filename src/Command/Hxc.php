<?php

namespace Hxc\Curd\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Hxc extends Command
{
    protected function configure()
    {
        $this->setName('hxc')->setDescription(mb_convert_encoding('代码生成器开启', 'GBK'));
    }

    protected function execute(Input $input, Output $output)
    {
        $doc = '这是代码生成器所需文件。';

        file_put_contents(ROOT_PATH . 'hxc.lock', $doc);
        $output->writeln("---------------------------------------");
        $output->writeln("Starting success");
        $output->writeln("---------------------------------------");
        $output->writeln("Url:/generate");
        $output->writeln("Document path:/hxc.lock");
        $output->writeln("---------------------------------------");
        $targetPath = APP_PATH . 'extra/';
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        if (file_exists($targetPath . 'curd.php')) {
            //配置文件已存在
            $file = realpath(__DIR__ . '/../config.php');
            $output->warning("The configuration file (" . realpath($targetPath . 'curd.php') . ") already exists. Please check {$file} to see if there are any updates.");
        } else {
            copy(__DIR__ . '/../config.php', $targetPath . 'curd.php');
            $output->writeln("Copy config file success");
        }
        $output->writeln("---------------------------------------");
    }
}