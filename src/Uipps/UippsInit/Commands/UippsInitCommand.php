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

class UippsInitCommand extends Command
{
    const DOT_REPLACE_TO_STR = '---______---'; // 配置中不能有.点符号, 否则会被当成数组的层级，因此需要将配置中的.替换成特殊符号便于还原

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
                            {--c|connection= : The name of the connection}
                            {--d|database= : The name of the MySQL database}
                            ';

    protected $description = 'init uipps platform';

    protected $_connection = '';    // 数据库连接的名称，连接别名
    protected $_database = '';      // 指定的数据库名称

    // Execute the console command.
    public function handle()
    {

        $this->info(date('Y-m-d H:i:s') . ' uipps init begin: ');

        $this->doInit();

        $this->info(date('Y-m-d H:i:s') . ' uipps init end!');
        return ;
    }

    protected function doInit() {
        $this->_connection = $this->getConnection();
        $this->_database = $this->getSchema();

        // 复制迁移文件 到database/目录下

        // 执行 php artisan migrate:fresh && php artisan db:seed --class=project  等命令

        // 支持dsn形式的连接形式；
        echo ' $connection: ' ; print_r($this->_connection); echo "\r\n";
        echo ' $database: ' ; print_r($this->_database); echo "\r\n";

        return ;
    }
}
