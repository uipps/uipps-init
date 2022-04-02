<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CMS系统初始数据表
        /*Schema::create('aups_t001', function (Blueprint $table) {
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

            $table->text('aups_f001')->comment('内容');
            $table->string('aups_f002', 255)->default('')->comment('图片1');
            $table->string('aups_f003', 255)->default('')->comment('标题1');
            $table->string('aups_f004', 255)->default('')->comment('链接1');
            $table->string('aups_f005', 255)->default('')->comment('图片2');
            $table->string('aups_f006', 255)->default('')->comment('标题2');
            $table->string('aups_f007', 255)->default('')->comment('链接2');
            $table->string('aups_f008', 255)->default('')->comment('图片3');
            $table->string('aups_f009', 255)->default('')->comment('标题3');
            $table->string('aups_f010', 255)->default('')->comment('链接3');
            $table->string('aups_f011', 255)->default('')->comment('说明');

            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->index(['createdate','createtime'], 'idx_cdt');
            $table->engine = 'InnoDB';
            $table->comment = '页面碎片';
        });

        Schema::create('aups_t002', function (Blueprint $table) {
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

            $table->string('aups_f012', 200)->default('')->comment('文档标题');
            $table->string('aups_f013', 200)->default('')->comment('副标题');
            $table->string('aups_f014', 200)->default('')->comment('来源');
            $table->string('aups_f015', 200)->default('')->comment('其他来源');
            $table->string('aups_f016', 200)->default('')->comment('主题词');
            $table->string('aups_f017', 200)->default('')->comment('作者');
            $table->string('aups_f018', 600)->default('')->comment('摘要');
            $table->string('aups_f019', 200)->default('')->comment('备注');
            $table->unsignedSmallInteger('aups_f020')->default('70')->comment('权重');
            $table->string('aups_f032', 200)->default('')->comment('期号');
            $table->string('aups_f041', 200)->default('')->comment('功能代码');
            $table->string('aups_f050', 200)->default('')->comment('机构名称');
            $table->string('aups_f055', 200)->default('')->comment('是否显示心情');
            $table->text('aups_f056')->comment('正文');
            $table->string('s_shu_chengshi', 60)->default('')->comment('所属城市, 可能是地级市，也可能是直辖市。地级市以上包括直辖市, 如可能是孝感，武汉，北京等能作为首页的');
            $table->string('aups_f057', 200)->default('')->comment('所属栏目');
            $table->string('aups_f058', 200)->default('')->comment('所属子栏目');
            $table->string('aups_f059', 200)->default('')->comment('所属专题');
            $table->string('aups_f060', 200)->default('')->comment('所属专题子栏目');
            $table->string('aups_f061', 200)->default('')->comment('所属专题2');
            $table->string('aups_f062', 200)->default('')->comment('所属专题子栏目2');
            $table->string('aups_f063', 200)->default('')->comment('附件');
            $table->string('aups_f064', 200)->default('')->comment('推荐小图');
            $table->string('aups_f065', 200)->default('')->comment('图片');
            $table->string('aups_f066', 200)->default('')->comment('图注');
            $table->string('aups_f067', 3000)->default('')->comment('相关报道');
            $table->string('aups_f068', 200)->default('')->comment('视频链接');
            $table->string('aups_f069', 200)->default('')->comment('是否显示评论');


            $table->enum('status_', ['use','stop','test','del','scrap','open','pause','close'])->default('use')->comment('状态, 使用、停用等');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->index(['createdate','createtime'], 'idx_cdt');
            $table->engine = 'InnoDB';
            $table->comment = '正文页';
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
