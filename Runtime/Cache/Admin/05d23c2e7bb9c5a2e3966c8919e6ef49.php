<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="/Public/Admin/js/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/plugins/ue/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/plugins/ue/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/plugins/ue/lang/zh-cn/zh-cn.js"></script>

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
        <div class="formtitle"><span>基本信息</span></div>
        <form action="<?php echo U('editOk');?>" method="post" enctype="multipart/form-data">
            <ul class="forminfo">
                <input type="hidden" name="goods_id" value="<?php echo ($data["goods_id"]); ?>">
                <li>
                    <label>商品名称</label>
                    <input name="goods_name" placeholder="请输入商品名称" value="<?php echo ($data["goods_name"]); ?>" type="text" class="dfinput" /><i>名称不能超过30个字符</i></li>
                <li>
                    <label>商品价格</label>
                    <input name="goods_price" placeholder="请输入商品价格" value="<?php echo ($data["goods_price"]); ?>" type="text" class="dfinput" /><i></i></li>
                <li>
                    <label>商品数量</label>
                    <input name="goods_number" placeholder="请输入商品数量" value="<?php echo ($data["goods_number"]); ?>" type="text" class="dfinput" />
                </li>
                <li>
                    <label>商品重量</label>
                    <input name="goods_weight" placeholder="请输入商品重量" value="<?php echo ($data["goods_weight"]); ?>" type="text" class="dfinput" />
                </li>
                <li>
                    <label>商品图片</label>
                    <input name="goods_big_logo" type="file" /> <img src="<?php echo ($data["goods_small_logo"]); ?>"/>
                </li>
                <!--
                <li><label>是否审核</label><cite><input name="" type="radio" value="" checked="checked" />是&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="radio" value="" />否</cite></li>
                -->
                <li>
                    <label>商品描述<textarea id="ue" name="goods_introduce" placeholder="请输入商品描述" cols="" rows="" style="width: 560px;height: 300px;"><?php echo ($data["goods_introduce"]); ?></textarea></label>
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
    //容器实例化
    var ue = UE.getEditor('ue',{toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion'
        ]]});

    //jQuery代码
    $(function(){
        //给btn绑定点击事件
        $('#btnSubmit').on('click',function(){
            //提交表单
            $('form').submit();
        });
    });
</script>
</html>