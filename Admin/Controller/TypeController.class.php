<?php
//声明命名空间
namespace Admin\Controller;
//声明类并且继承父类
class TypeController extends CommonController{

	//添加商品类型
	public function add(){
		//判断是否是保存操作
		if(IS_POST){
			//保存操作
			$post = I('post.');//或者可以使用create方法接收数据
			//写入数据
			$rst = M('Type') -> add($post);
			//判断返回值
			if($rst){
				//添加成功
				$this -> success('添加成功',U('showList'),3);
			}else{
				//添加失败
				$this -> error('添加失败');
			}
		}else{
			//展示操作
			$this -> display();
		}
	}

	//列表展示方法
	public function showList(){
		//获取数据
		$data = M('Type') -> select();
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}
}