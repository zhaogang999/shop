<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="/Public/Admin/js/jquery.js"></script>
</head>

<body>
    <div class="place">
        <span>位置：</span>
        <ul class="placeul">
            <li><a href="#">首页</a></li>
            <li><a href="#">表单</a></li>
        </ul>
    </div>
    <div class="formbody">
        <div class="formtitle"><span>商品相册</span></div>
        <li id="photolist" style="border: 1px solid grey;margin-bottom: 20px;">
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?><label><img src="<?php echo ($vol["pic"]); ?>" width="200" height="200"><a href="javascript:;" class="remove" data="<?php echo ($vol["id"]); ?>">[-]</a>&emsp;&emsp;</label><?php endforeach; endif; else: echo "" ;endif; ?>
        </li>
        <form action="<?php echo U('deal_pics');?>" method="post" enctype="multipart/form-data">
            <ul class="forminfo">
                <input type="hidden" name="goods_id" value="<?php echo ($_GET['id']); ?>">
                <li>
                    <label>商品图片[<a href="javascript:;" class="add">+</a>]</label>
                    <input name="goods_pic[]" type="file" />
                </li>
                <li>
                    <label>&nbsp;</label>
                    <input name="" id="btnSubmit" type="button" class="btn" value="确认保存" />
                </li>
            </ul>
        </form>
    </div>
</body>
<script type="text/javascript">
$(function(){
    $('#btnSubmit').on('click',function(){
        $('form').submit();
    });
    //给+号绑定点击事件
    $('.add').on('click',function(){
        //先定义好需要追加的字符串内容
        var content = "<li><label>商品图片[<a href='javascript:;' class='del'>-</a>]</label><input name='goods_pic[]' type='file' /></li>";
        //给爷爷节点追加内容
        $(this).parent().parent().append(content);
    });

    //给-绑定点击事件
    $('.del').live('click',function(){
        //删除当前节点的最近一个li节点
        $(this).parent().parent().remove();
    });

    //针对删除图片的处理
    $('.remove').on("click",function(){
        //获取id
        var id = $(this).attr('data');
        //重新给this赋值
        var _this = $(this);
        //发送ajax请求
        $.get('/index.php/Admin/Goods/del_pic/id/'+id, function(data) {
            if(data == '1'){
                //删除label
                _this.parent().remove();
            }
        });
    });
});
</script>
</html>