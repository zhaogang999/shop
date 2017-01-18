<?php
#声明命名空间
namespace Admin\Controller;
#定义类并且继承父类
class GoodsController extends CommonController{

	//商品列表
	public function showList(){
		//实例化模型
		$model = M('Goods');
		//查询
		$data = $model -> select();	//二维数组
		//传递给模版
		$this -> assign('data',$data);
		$this -> display();
	}

	//添加商品
	public function add(){
		//查询商品的类型
		$data = M('Type') -> select();
		//传递给模版
		$this -> assign('data',$data);
		$this -> display();
	}

	//获取属性方法
	public function getAttr(){
		//判断是否是ajax请求
		if(IS_AJAX){
			//获取id
			$id = I('get.type_id');
			//查询属性
			$data = M('Attribute') -> where("type_id = $id") -> select();
			//转化json格式
			//$this -> ajaxReturn($data);
			echo json_encode($data);
		}
	}

	//保存数据
	public function addOk(){
		//接收数据
		//$post = $_POST;
		//通过I方法接收数据
		$post = I('post.');
		//dump($post);die;
		//追加两个时间
		$post['add_time'] = $post['upd_time'] = time();
		//针对富文本编辑器进行特殊的处理
		$post['goods_introduce'] = filterXSS($_POST['goods_introduce']);
		//处理图片上传操作
		if($_FILES['goods_big_logo']['size'] > 0){
			//配置数组
			$cfg = array('rootPath' => WORKING_PATH . UPLOAD_ROOT_PATH);
			//实例化上传类
			$upload = new \Think\Upload($cfg);
			//开始上传
			$info = $upload -> uploadOne($_FILES['goods_big_logo']);
			//如果成功则返回数组，如果失败则返回false
			if($info){
				//处理数据表中的goods_big_logo字段
				$post['goods_big_logo'] = UPLOAD_ROOT_PATH . $info['savepath'] . $info['savename'];
				//针对缩略图的处理
				$image = new \Think\Image();//实例化图像处理类
				//第一步：打开图片
				$image -> open(WORKING_PATH . $post['goods_big_logo']);
				//第二步：制作缩略图
				$image -> thumb(150,150);
				//第三步：保存图片
				$image -> save(WORKING_PATH . UPLOAD_ROOT_PATH . $info['savepath'] . 'thumb_' . $info['savename']);
				//补上goods_small_logo字段
				$post['goods_small_logo'] = UPLOAD_ROOT_PATH . $info['savepath'] . 'thumb_' . $info['savename'];
			}
		}
		//实例化模型
		$model = M('Goods');
		//添加操作
		$rst = $model -> add($post);
		//判断添加结果
		if($rst){
			//针对商品属性的处理
			foreach ($post['goods_attrs'] as $key => $value) {
				//遍历元素
				foreach ($value as $val) {
					//val 属性值
					//$rst 商品id
					//$key 属性id
					$attr['goods_id'] = $rst;
					$attr['attr_id'] = $key;
					$attr['attr_value'] = $val;
					M('Goodsattr') -> add($attr);
				}
			}
			//添加成功
			$this -> success('添加成功',U('showList'),3);
		}else{
			//添加失败
			$this -> error('添加失败',U('add'),3);
		}
	}

	//商品相册显示
	public function photos(){
		//获取已有的相册数据
		$model = M('Goodspics');
		//查询
		$id = I('get.id');
		$data = $model -> where('goods_id = ' . $id) -> select();
		//传递变量
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}

	//处理多文件的上传
	public function deal_pics(){
		//判断是否有文件需要处理（至少有一个文件成功上传）
		$flag = false;
		foreach ($_FILES['goods_pic']['error'] as $key => $value) {
			//如果当前的错误=0，则表示文件上传成功
			if($value == 0){
				$flag = true;
				break;
			}
		}
		//判断
		if($flag){
			//接收id
			$id = I('post.goods_id');
			//配置
			$cfg = array('rootPath' => WORKING_PATH . UPLOAD_ROOT_PATH);
			//实例化上传类
			$upload = new \Think\Upload($cfg);
			//多文件上传
			$info = $upload -> upload($_FILES);
			//使用addAll方法进行批量添加
			$model = M('Goodspics');
			//添加
			foreach ($info as $k => $v) {
				$data[$k]['goods_id'] = $id;
				$data[$k]['pic'] = UPLOAD_ROOT_PATH . $v['savepath'] . $v['savename'];
			}
			$rst = $model -> addAll($data);
			//判断是否添加成功
			if($rst){
				//成功，如果跳转地址是上一页，则可以不写后面的参数
				$this -> success('添加成功');
			}else{
				$this -> error('添加失败');
			}
		}
	}

	//删除图片
	public function del_pic(){
		//是否是ajax请求
		if(IS_AJAX){
			//接收id
			$id = I('get.id');
			//先查询图片的信息
			$model = M('Goodspics');
			$info = $model -> find($id);
			//删除记录
			$rst = $model -> delete($id);
			//当记录被删除成功之后再去删除文件
			if($rst){
				//删除文件
				unlink(WORKING_PATH . $info['pic']);
				echo '1';
			}
		}
	}

	//edit方法，展示数据和模版
	public function edit(){
		//接收id
		$id = I('get.id');
		//实例化模型
		$model = M('Goods');
		//查询
		$data = $model -> find($id);
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}

	//editOk方法，保存数据
	public function editOk(){
		//接收数据
		$post = I('post.');
		//指定修改时间
		$post['upd_time'] = time();
		//针对富文本编辑进行特殊的防XSS攻击的处理
		$post['goods_introduce'] = filterXSS($_POST['goods_introduce']);
		//上传处理
		if($_FILES['goods_big_logo']['size'] > 0){
			//配置
			$cfg = array('rootPath' => WORKING_PATH . UPLOAD_ROOT_PATH);
			//实例化上传类
			$upload = new \Think\Upload($cfg);
			//上传操作
			$info = $upload -> uploadOne($_FILES['goods_big_logo']);
			//判断上传结果
			if($info){
				//goods_big_logo字段
				$post['goods_big_logo'] = UPLOAD_ROOT_PATH . $info['savepath'] . $info['savename'];
				//处理缩略图
				$image = new \Think\Image();
				//1、打开图片
				$image -> open(WORKING_PATH . $post['goods_big_logo']);
				//2、制作
				$image -> thumb(150,150);//等比缩放
				//3、保存图片
				$image -> save(WORKING_PATH . UPLOAD_ROOT_PATH . $info['savepath'] . 'thumb_' . $info['savename']);
				//goods_small_logo字段
				$post['goods_small_logo'] = UPLOAD_ROOT_PATH . $info['savepath'] . 'thumb_' . $info['savename'];
			}
		}
		//写入表
		$model = M('Goods');
		$rst = $model -> save($post);
		//判断
		if($rst){
			//修改成功
			$this -> success('修改成功',U('showList'),3);
		}else{
			//失败
			$this -> error('修改失败',U('edit',array('id' => $post['id'])),3);
		}
	}

	public function test(){
		echo getPwd('123456');
	}

}