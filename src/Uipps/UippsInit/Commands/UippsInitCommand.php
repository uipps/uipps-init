<?php
/*
 自动生成model、controller等，支持指定目录

 如：
 php artisan uipps:init -c "mysql://root:101010@127.0.0.1:3511/laravel_dev" -d laravel_dev

 -- 指定目录 -p
 php artisan uipps:init -c "mysql://root:101010@127.0.0.1:3511/laravel_dev" -p Uipps/

 php artisan uipps:init -p Uipps/ --cast=1 --event=1 --observer=1 --scope=1

 */
namespace Uipps\UippsInit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UippsInitCommand extends Command
{
    /*
    The name and signature of the console command.
 不要跟如下冲突：
 -h, --help                     Display help for the given command. When no command is given display help for the list command
 -q, --quiet                    Do not output any message
 -V, --version                  Display this application version
     --ansi|--no-ansi           Force (or disable --no-ansi) ANSI output
 -n, --no-interaction           Do not ask any interactive question
     --env[=ENV]                The environment the command should run under
 -v|vv|vvv, --verbose           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

     */
    protected $signature = 'uipps:init
                            ';

    protected $description = 'init uipps platform';

    // Execute the console command.
    public function handle() {
        $this->info(date('Y-m-d H:i:s') . ' uipps init begin: ');

        $this->doInit();

        $this->info(date('Y-m-d H:i:s') . ' uipps init end!');
        return ;
    }

    protected function doInit() {
        //$this->_connection = $this->getConnection();
        //$this->_database = $this->getSchema();

        // 复制迁移文件 到database/目录下
        self::copyFile2Migrations();
        self::copyFile2Seeders();

        // TODO 检查当前数据库有哪些数据表存在，无数据表提示一下；
        // TODO 检查各个存在的数据表是否有数据，输出有数据的数据表名称及记录条数

        // 执行 php artisan migrate:fresh && php artisan db:seed --class=ProjectSeeder  等命令
        // 清空并重新创建数据表
        $cmd = 'migrate:fresh';
        self::artisanCall($cmd);

        // 填充数据，有几个文件则执行几次
        $source_path = str_replace('\\', '/', database_path('seeders'));
        $d = dir($source_path);
        if ($d) {
            while (false !== ($_file = $d->read())) {
                if ('.' == $_file || '..' == $_file || is_dir($source_path. '/' .$_file))
                    continue;
                $cmd = 'db:seed --class=' . str_replace('.php', '', $_file);
                self::artisanCall($cmd);
            }
            $d->close();
        }

        return ;
    }

    protected function artisanCall($cmd) {
        //$cmd = 'list';
        $exitCode = Artisan::call($cmd);
        $output = Artisan::output();
        echo '    CMD: php artisan '.$cmd.' ; $exitCode: ' . var_export($exitCode, true) . ' $output: ' . PHP_EOL . var_export($output, true) . "\r\n";
        return ;
    }

    protected function copyFile2Migrations() {
        $l_migrate_path = rtrim(str_replace(['\\', '//'], '/', database_path('migrations')), ' /'); //echo $l_migrate_path . PHP_EOL;
        $l_source = str_replace('\\', '/', dirname(__DIR__)) . '/database/migrations'; //echo $l_source . PHP_EOL;
        // 将目录下的文件copy过去
        self::recurse_copy($l_source, $l_migrate_path); // 如果目标文件已存在，将会被覆盖。
        return ;
    }

    protected function copyFile2Seeders() {
        $l_seeder_path = rtrim(str_replace(['\\', '//'], '/', database_path('seeders')), ' /'); // echo $l_seeder_path . PHP_EOL;
        $l_source = str_replace('\\', '/', dirname(__DIR__)) . '/database/seeders'; // echo $l_source . PHP_EOL;
        // 将目录下的文件copy过去
        self::recurse_copy($l_source, $l_seeder_path); // 如果目标文件已存在，将会被覆盖。
        return ;
    }

    // 递归copy, 如果目标文件已存在，将会被覆盖。
    public function recurse_copy($source_path, $target_path) {
        $d = dir($source_path);
        if (!$d)
            return ;
        @mkdir($target_path, 0775);
        while (false !== ($_file = $d->read())) {
            if ('.' == $_file || '..' == $_file)
                continue;
            if (is_dir($source_path. '/' .$_file)) {
                self::recurse_copy($source_path. '/' .$_file, $target_path. '/' .$_file);
            } else {
                copy($source_path. '/' .$_file, $target_path. '/' .$_file);
            }
        }
        $d->close();
    }

}
