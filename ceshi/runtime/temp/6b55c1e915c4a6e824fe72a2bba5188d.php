<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"W:\phpStudy\PHPTutorial\WWW\ceshi/application/admin\view\good\good_list.html";i:1564035359;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="/ceshi/public/static/admin/css/font.css">
        <link rel="stylesheet" href="/ceshi/public/static/admin/css/xadmin.css">
        <link rel="icon" href="data:image/ico;base64,aWNv">
        <script src="/ceshi/public/static/admin/lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="/ceshi/public/static/admin/js/xadmin.js"></script>
    </head>
    
    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <form class="layui-form layui-col-space5">
                                <div class="layui-inline"> <!-- 注意：这一层元素并不是必须的 -->
                                    <input type="text" class="layui-input" id="aa" placeholder="请选择日期范围">
                                </div>

                                <script>
                                    layui.use('laydate', function(){
                                        var laydate = layui.laydate;

                                        //执行一个laydate实例
                                        laydate.render({
                                            elem: '#aa', //指定元素
                                            type: 'datetime',
                                            range: true
                                        });
                                    });
                                </script>
                                <div class="layui-input-inline layui-show-xs-block">
                                    <select name="type" lay-filter="aa">
                                        <option value="0" >所有分类</option >
                                        <?php foreach($type as $v): ?>
                                        <option xsla="<?php echo $nbsp = str_repeat('&nbsp;',($v['path']-1)*4); if($v['path']==3): ?><?php echo $str=''; else: ?><?php echo $str='disabled'; endif; ?>" value="<?php echo $v['id']; ?>" <?php echo $str; ?>><?php echo $nbsp; ?><?php echo $v['name']; ?></option >
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="layui-input-inline layui-show-xs-block">
                                    <input type="text" name="username" placeholder="请输入商品名  " autocomplete="off" class="layui-input"></div>
                                <div class="layui-input-inline layui-show-xs-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                        <i class="layui-icon">&#xe615;</i></button>
                                </div>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn" onclick="xadmin.open('添加商品','<?php echo url('admin/good/good_add'); ?>',800,600)">
                                <i class="layui-icon"></i>添加</button></div>
                        <div class="layui-card-body">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>添加时间</th>
                                        <th>商品名</th>
                                        <th>商品图</th>
                                        <th>商品详情图</th>
                                        <th>商品详情</th>
                                        <th>总库存</th>
                                        <th>销量</th>
                                        <th>状态</th>
                                        <th>商品描述</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($good as $v): ?>
                                    <tr>
                                        <td><?php echo $v['addtime']; ?></td>
                                        <td><?php echo $v['name']; ?></td>
                                        <td><img style="cursor: pointer;" onclick="xadmin.open('商品图片','<?php echo url('admin/good/goodpic_list'); ?>?id=<?php echo $v['id']; ?>')" src="/ceshi/public/static/images/good/<?php echo $v['goodpic'][0]; ?>" alt="" ></td>
                                        <td><img style="cursor: pointer;" onclick="xadmin.open('商品详情图片','<?php echo url('admin/good/detailpic_list'); ?>?id=<?php echo $v['id']; ?>')" src="/ceshi/public/static/images/detailgood/<?php echo $v['detailpic'][0]; ?>" alt="" ></td>
                                        <td><a href="javascript:;" onclick="xadmin.open('商品详情','<?php echo url('admin/good/good_details'); ?>?id=<?php echo $v['id']; ?>',800,600)" >查看</a ></td>
                                        <td><?php echo $v['num']; ?></td>
                                        <td><?php echo $v['paynum']; ?></td>
                                        <td>
                                            <input lay-filter="aaa" type="checkbox" name="switch"  lay-text="在售|下架"  <?php if($v['state']==1): ?>checked<?php endif; ?> lay-skin="switch" value="<?php echo $v['id']; ?>">
                                        </td>
                                        <td><?php echo $v['content']; ?></td>
                                        <td class="td-manage">
                                            <a title="删除" onclick="member_del(this,'<?php echo $v['id']; ?>')" href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i>
                                            </a>
                                            <a title="编辑"  onclick="xadmin.open('编辑','<?php echo url('admin/good/good_edit'); ?>?id=<?php echo $v['id']; ?>',600,500)" href="javascript:;">
                                                <i class="layui-icon">&#xe642;</i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body">
                            <div class="page"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        layui.use(['laydate', 'form'],

        function() {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });
        layui.use(['form'], function(){
            form=layui.form;
            form.on('select(aa)',function(data){
                // console.log(data.elem); //得到select原始DOM对象
                // console.log(data.value); //得到被选中的值
                // console.log(data.othis); //得到美化后的DOM对象

                var typeid=data.value;

                // $.ajax({
                //     url:"<?php echo url('admin/good/good_list'); ?>",
                //     type:"POST",
                //     data:{typeid:typeid},
                //     //cache: false,
                //     // processData: false,
                //     // contentType: false,
                //     success:function(data){
                //         console.log(data);
                //         if(data==1){
                //             layer.msg('修改成功!',{icon:1,time:1000});
                //         }else{
                //             layer.msg('修改失败!',{icon:2,time:1000},function(){
                //                 location.reload();
                //             });
                //         }
                //     }
                // });
            });

            /*商品-在售/下架*/
            form.on('switch(aaa)',function(data){
                var id=data.value;
                // alert(id);
                // return false;
                if(data.elem.checked){
                    var state=1;
                }else{
                    var state=2;
                }
                // console.log(id);
                $.ajax({
                    url:"<?php echo url('admin/good/good_state_edit'); ?>",
                    type:"POST",
                    data:{state:state,id:id},
                    //cache: false,
                    // processData: false,
                    // contentType: false,
                    success:function(data){
                        // console.log(data);
                        if(data==1){
                            layer.msg('修改成功!',{icon:1,time:1000});
                        }else{
                            layer.msg('修改失败!',{icon:2,time:1000},function(){
                                location.reload();
                            });
                        }
                    }
                });
            });
        });


        /*用户-删除*/
        function member_del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                $.ajax({
                    url:"<?php echo url('admin/good/good_del'); ?>",
                    type:"POST",
                    data:{id:id},
                    //cache: false,
                    // processData: false,
                    // contentType: false,
                    success:function(data){
                        // console.log(data);
                        if(data==1){
                            layer.msg('删除成功!',{icon:1,time:1000});
                            //发异步删除数据
                            $(obj).parents("tr").remove();
                            layer.msg('已删除!', {
                                icon: 1,
                                time: 1000
                            });
                        }else{
                            layer.msg('删除失败!',{icon:2,time:1000},function(){
                                location.reload();
                            });
                        }
                    }
                });

            });
        }
    </script>

</html>