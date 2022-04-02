<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableFieldTmplDesign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO 考虑解析table_field.sql文件的方法，这样就不用写两处sql了，避免不一致和重复修改的问题
        // 表定义表
        Schema::create('table_def', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('p_id')->default(0)->comment('所属项目ID');
            $table->string('name_eng', 200)->comment('英文名称');
            $table->string('name_cn', 200)->comment('中文名称');
            $table->enum('tbl_type', ['00', '01', '02'])->default('00')->comment('表类型');
            $table->string('field_def_table', 32)->default('field_def')->comment('字段定义表, 暂未支持，默认是field_def');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->date('createdate')->comment('创建日期');
            $table->time('createtime')->default('00:00:00')->comment('创建时间');
            $table->string('mender', 100)->default('')->comment('修改者ID');
            $table->date('menddate')->comment('修改日期');
            $table->time('mendtime')->default('00:00:00')->comment('修改时间');
            $table->unsignedTinyInteger('list_order')->default(100)->comment('显示顺序');
            $table->string('description', 255)->default('')->comment('描述');
            $table->string('doc_list_order', 2000)->default('')->comment('文档显示顺序');
            $table->enum('source', ['db','grab','none'])->default('none')->comment('来源');
            $table->string('arithmetic', 10000)->default('')->comment('表定义表的算法, 通常是CodeResult类型');
            $table->string('waiwang_url', 255)->default('')->comment('外网URL, 没有提供则继承自project的');
            $table->string('bendi_uri', 255)->default('')->comment('本地URI, 没有提供则继承自project的');
            $table->enum('js_verify_add_edit', ['TRUE','FALSE'])->default('FALSE')->comment('是否js验证, 某张表中添加、修改记录的时候');
            $table->text('js_code_add_edit')->comment('js验证代码, 某张表中添加、修改记录的时候');

            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->unique(['p_id', 'name_eng'], 'pid_name_eng');
            $table->index(['createdate','createtime'], 'idx_cdt');
            $table->engine = 'InnoDB';
            $table->comment = '表定义表';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "table_def` comment '表定义表'");


        // 字段定义表
        Schema::create('field_def', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('t_id')->default(0)->comment('表ID');
            $table->string('name_eng', 100)->default('aups_f001')->comment('英文名称');
            $table->string('name_cn', 200)->comment('中文名称');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->date('createdate')->comment('创建日期');
            $table->time('createtime')->default('00:00:00')->comment('创建时间');
            $table->string('mender', 100)->default('')->comment('修改者ID');
            $table->date('menddate')->comment('修改日期');
            $table->time('mendtime')->default('00:00:00')->comment('修改时间');
            $table->enum('edit_flag', ['0','1','2','3'])->default('0')->comment('编辑标记');
            $table->enum('is_null', ['YES','NO'])->default('YES')->comment('是否为空. YES:为空；NO:非空');
            $table->enum('key', ['','PRI','MUL','UNI','fulltext'])->default('')->comment('键类型. PRI：主键；‘MUL’索引；UNI唯一，fulltext：全文搜索');
            $table->enum('extra', ['','AUTO_INCREMENT','on update CURRENT_TIMESTAMP','DEFAULT_GENERATED','DEFAULT_GENERATED on update CURRENT_TIMESTAMP'])->default('')->comment('额外属性');
            $table->enum('type', ['VARCHAR','TINYINT','TEXT','DATE','SMALLINT','MEDIUMINT','INT','BIGINT','FLOAT','DOUBLE','DECIMAL','DATETIME','TIMESTAMP','TIME','YEAR','CHAR','TINYBLOB','TINYTEXT','BLOB','MEDIUMBLOB','MEDIUMTEXT','LONGBLOB','LONGTEXT','ENUM','SET','BIT','BOOL','BINARY','VARBINARY'])->default('VARCHAR')->comment('字段数据类型');
            $table->enum('f_type', ['Form::CodeResult','Form::TextField','Form::Date','Form::DateTime','Form::Password','Form::TextArea','Form::HTMLEditor','Form::Select','Form::DB_Select','Form::DB_RadioGroup','Form::ImageFile','Form::File','Application::SQLResult','Application::PostInPage','Application::CrossPublish','Application::CodeResult'])->default('Form::TextField')->comment('字段的算法类型');
            $table->string('length', 600)->default('255')->comment('长度');
            $table->enum('attribute', ['','BINARY','UNSIGNED','UNSIGNED ZEROFILL','ON UPDATE CURRENT_TIMESTAMP'])->default('')->comment('无符号等属性');
            $table->string('unit', 20)->default('')->comment('单位');
            $table->text('default')->nullable()->comment('默认值');
            $table->string('arithmetic', 10000)->default('')->comment('字段算法');
            $table->enum('exec_mode', ['0','1','2','3'])->default('0')->comment('执行模式');
            $table->unsignedSmallInteger('list_order')->default('1000')->comment('显示顺序');
            $table->enum('source', ['db','grab','none'])->default('none')->comment('来源');
            $table->string('description', 255)->nullable()->default('')->comment('描述');
            $table->enum('if_display_edit', ['T','F'])->default('T')->comment('是否显示在添加或编辑界面');
            //$table->enum('if_display_api', ['T','F'])->default('T')->comment('是否在接口中返回');

            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->unique(['t_id', 'name_eng'], 'tid_name_eng');
            $table->index(['t_id', 'list_order'], 'idx_tid');
            $table->engine = 'InnoDB';
            $table->comment = '字段定义表';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "field_def` comment '字段定义表'");

        // 模板设计表
        Schema::create('tmpl_design', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');

            $table->unsignedInteger('tbl_id')->default(0)->comment('所属表ID');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->date('createdate')->comment('创建日期');
            $table->time('createtime')->default('00:00:00')->comment('创建时间');
            $table->string('mender', 100)->default('')->comment('修改者ID');
            $table->date('menddate')->comment('修改日期');
            $table->time('mendtime')->default('00:00:00')->comment('修改时间');
            $table->enum('if_publish', ['TRUE','FALSE'])->default('TRUE')->comment('是否发布');
            $table->enum('content_type', ['Text','HTML','XML','WML','JSON'])->default('HTML')->comment('内容类型, html、json、xml');
            $table->string('default_field', 60)->default('url_1')->comment('发布地址存放字段, 发布成功以后得到的地址存放到哪个字段中');
            $table->string('default_url', 255)->default('')->comment('默认URL');
            $table->text('default_html')->comment('默认静态模板代码');
            $table->string('tmpl_expr', 600)->default('')->comment('执行条件, 只有满足此表达式条件才执行发布, 必须是PHP表达式(如:${是否发往首页) == "yes" && ${栏目名称} == "国内")');
            $table->string('description', 255)->nullable()->default('')->comment('描述');

            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->index(['createdate','createtime'], 'idx_cdt');
            $table->engine = 'InnoDB';
            $table->comment = '模板设计表';
        });
        DB::statement("ALTER TABLE `" . env('DB_PREFIX', '') . "tmpl_design` comment '模板设计表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
