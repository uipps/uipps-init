<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UippsProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 主机
        Schema::create('host_reg', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('host_label', 50)->unique()->comment('主机卷标');
            $table->string('host_name', 200)->unique()->comment('主机名称');
            $table->string('host_os', 200)->comment('主机的操作系统');
            $table->string('host_ip', 15)->comment('主机的ip'); //
            $table->string('host_domain', 100)->comment('主机域名');
            $table->string('host_port', 100)->comment('主机端口');
            $table->string('host_root', 200)->comment('主机root');
            $table->string('description', 1000)->default('')->comment('描述');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->string('mender', 100)->default('')->comment('修改者');
            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');

            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->comment('更新时间'); // Laravel 5.1.25 以后，可以使用 useCurrent()
            $table->engine = 'InnoDB';
            //$table->comment = '主机';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "host_reg` CHANGE `updated_at` `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间'");
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "host_reg` comment '主机'");

        // 项目
        Schema::create('project', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('name_cn', 128)->unique()->comment('中文名称');
            $table->enum('type', ['SYSTEM','CMS','PHP_PROJECT','NORMAL','PUB','RES','GRAB'])->default('PHP_PROJECT')->comment('项目类型');
            $table->unsignedInteger('parent_id')->default(0)->comment('所属父级ID');
            $table->unsignedInteger('table_field_belong_project_id')->default(0)->comment('字段定义表所属项目ID, 0:表示所属项目, 外来的项目可能不是0');
            $table->string('table_def_table', 32)->nullable()->default('')->comment('表定义表的数据表名,含前缀，以下类似');
            $table->string('field_def_table', 32)->nullable()->default('')->comment('字段定义表的数据表名, 与字段定义表中的field_def_table不冲突');
            $table->string('tmpl_design_table', 32)->nullable()->default('')->comment('模板设计表的数据表名');
            $table->string('creator', 100)->nullable()->comment('创建者');
            $table->string('mender', 100)->nullable()->comment('修改者');
            // 主库
            $table->string('db_host', 50)->default('127.0.0.1')->comment('数据库主机');
            $table->string('db_name', 50)->comment('数据库名称, 英文名称');
            $table->unsignedInteger('db_port')->default(3306)->comment('数据库端口');
            $table->string('db_user', 20)->default('root')->comment('数据库用户名');
            $table->string('db_pwd', 20)->default('')->comment('数据库密码');
            $table->unsignedInteger('db_timeout')->default(0)->comment('数据库超时时间');
            $table->string('db_sock', 100)->nullable()->default('')->comment('数据库socket位置');
            $table->string('db_prefix', 20)->nullable()->default(env('DB_PREFIX', ''))->comment('数据库表前缀');
            $table->string('other_setting', 255)->nullable()->default('')->comment('其他设置,预留字段:如charset,collation,timezone等');
            $table->enum('if_use_slave', ['T', 'F'])->default('F')->comment('是否使用从库');
            // 从库
            $table->string('slave_db_host', 50)->nullable()->comment('从库主机名');  // TODO 当前为了测试有null的情况，记得删除这些nullable()
            $table->string('slave_db_name', 50)->nullable()->comment('从库数据库名');
            $table->unsignedInteger('slave_db_port')->nullable()->comment('从库端口');
            $table->string('slave_db_user', 20)->nullable()->comment('从库用户名');
            $table->string('slave_db_pwd', 20)->nullable()->comment('从库密码');
            $table->unsignedInteger('slave_db_timeout')->nullable()->comment('从库超时时间');
            $table->string('slave_db_sock', 100)->nullable()->comment('从库socket位置');
            // 其他
            $table->enum('if_daemon_pub', ['T','F'])->default('F')->comment('是否后台发布');
            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('search_order')->index()->default(0)->comment('搜索顺序');
            $table->unsignedInteger('list_order')->default(50)->comment('显示顺序');
            $table->enum('if_hide', ['T','F'])->default('F')->comment('是否隐藏');
            $table->string('description', 255)->nullable()->default('')->comment('描述');
            $table->unsignedInteger('host_id')->nullable()->default(0)->comment('主机id');
            $table->unsignedInteger('res_pub_map')->nullable()->default(0)->comment('发布地图');
            $table->string('website_name_cn', 200)->default('建站管理系统')->comment('网站中文名称, 该项目的');
            $table->string('waiwang_url', 255)->default('http://www.uipps.com')->comment('外网URL');
            $table->string('bendi_uri', 255)->default('/data0/htdocs/www')->comment('本地URI, D:/www/htdocs/www.uipps.com');

            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->engine = 'InnoDB';
            //$table->comment = '全部项目';

        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "project` comment '全部项目'"); // 表注释

        // 用户
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('g_id')->default(0)->comment('所属组ID,角色');
            $table->unsignedInteger('parent_id')->default(0)->comment('所属父级ID');
            $table->string('username', 100)->unique()->comment('用户名');
            $table->string('pwd', 100)->comment('密码');
            $table->string('nickname', 20)->default('')->comment('昵称');
            $table->string('mobile', 11)->default('')->comment('手机号码');
            $table->string('telephone', 20)->default('')->comment('电话');
            $table->string('email', 100)->default('')->comment('email');
            $table->string('google_authenticator', 16)->default('')->comment('google动态口令认证');
            $table->enum('locked', ['T','F'])->default('F')->comment('是否锁定');
            $table->enum('is_admin', ['T','F'])->default('F')->comment('是否管理员');
            $table->enum('if_super', ['1','0'])->default('0')->comment('是否超级用户, 1：是；0：不是');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->string('mender', 100)->default('')->comment('修改者');
            $table->dateTime('expired')->comment('过期时间');
            $table->string('description', 255)->default('')->comment('描述');
            $table->string('badPwdStr', 100)->default('')->comment('错误密码');
            $table->string('lastPwdChange', 100)->default('')->comment('最后一次修改的密码');
            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');

            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->engine = 'InnoDB';
            $table->comment = '用户表';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "user` comment '用户表'"); // 表注释

        // 登录日志
        Schema::create('loginlog', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('username', 100)->comment('用户名');
            $table->string('nickname', 100)->comment('昵称');
            $table->string('clientip', 15)->default('')->comment('客户端IP');
            $table->string('serverip', 15)->default('')->comment('服务器IP');
            $table->dateTime('logindate')->comment('登录时间');
            $table->string('description', 200)->default('')->comment('描述');
            $table->enum('succ_or_not', ['y','n'])->default('n')->comment('登录成功如否');
            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');

            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->engine = 'InnoDB';
            $table->comment = '登录日志';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "loginlog` comment '登录日志'");

        // 计划任务
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('name', 255)->default('')->comment('名称');
            $table->string('host', 255)->default('')->comment('主机');
            $table->enum('fashion', ['0','1','2'])->default('0')->comment('样式');
            $table->string('month', 255)->default('*')->comment('执行月份');
            $table->string('day', 255)->default('*')->comment('执行天');
            $table->string('week', 255)->default('*')->comment('执行周');
            $table->string('hour', 255)->default('*')->comment('执行小时');
            $table->string('minute', 255)->default('*')->comment('执行分钟');
            $table->enum('mode', ['0','1','2'])->default('0')->comment('模式');
            $table->string('shell_command', 600)->default('')->comment('shell命令');
            $table->unsignedInteger('suoshuxiangmu_id')->default(0)->comment('所属项目id');
            $table->unsignedInteger('suoshubiao_id')->default(0)->comment('所属表id');
            $table->enum('condition', ['0','1'])->default('0')->comment('条件');
            $table->string('doc_list', 255)->default('')->comment('文档列表');
            $table->enum('jit', ['yes','no'])->default('yes')->comment('jit');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->date('createdate')->comment('创建日期');
            $table->time('createtime')->default('00:00:00')->comment('创建时间');
            $table->string('mender', 100)->default('')->comment('修改者');
            $table->date('menddate')->comment('修改日期');
            $table->time('mendtime')->default('00:00:00')->comment('修改时间');
            $table->string('belong_user', 100)->default('finance')->comment('所属用户');
            $table->string('forbidden_date', 200)->default('')->comment('禁止执行的日期');
            $table->tinyInteger('forbidden_timezone')->default('-12')->comment('禁止执行的时区');
            $table->tinyInteger('server_timezone')->default('8')->comment('服务器时区');
            $table->string('description', 255)->default('')->comment('描述');
            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');

            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->engine = 'InnoDB';
            $table->comment = '计划任务';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "schedule` comment '计划任务'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('users');
        // Schema::dropIfExists('project');
        Schema::table('loginlog', function (Blueprint $table) {
            //
        });


    }
}
