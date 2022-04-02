<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommonDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 星球表
        /*Schema::create('region_planets', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('creator', 100)->default('')->comment('创建者');
            $table->date('createdate')->comment('创建日期');
            $table->time('createtime')->default('00:00:00')->comment('创建时间');
            $table->string('mender', 100)->default('')->comment('修改者ID');
            $table->date('menddate')->comment('修改日期');
            $table->time('mendtime')->default('00:00:00')->comment('修改时间');
            $table->date('expireddate')->comment('过期日期');
            $table->enum('audited', ['0','1'])->default('0')->comment('是否审核, 0:无需审核,能直接显示;1:需审核,不能直接发布,需要审核通过才能发布');
            $table->integer('flag')->default(0)->comment('标示, 预留');
            $table->string('arithmetic', 10000)->default('')->comment('文档算法, 包括发布文档列表算法, [publish_docs]1:28:1,1:28:2,,,,');
            $table->string('unicomment_id', 30)->default('')->comment('评论唯一ID, 1-2-36963:项目id-表id-评论id');
            $table->enum('published_1', ['0','1'])->default('0')->comment('是否发布, 0:不发布;1:发布,通常都是发布的');
            $table->string('url_1', 255)->default('')->comment('文档发布成html的外网url,通常是省略了域名的相对地址');

            $table->string('name_cn', 100)->default('')->comment('星球名称');
            $table->string('code_eng', 255)->default('')->comment('星球编号');

            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->index(['createdate','createtime'], 'idx_cdt');
            $table->engine = 'InnoDB';
            $table->comment = '星球表';
        });*/


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
