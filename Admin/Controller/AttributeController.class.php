<?php
//声明命名空间
namespace Admin\Controller;
//声明类并且继承父类
class AttributeController extends CommonController{

	//添加商品属性
	public function add(){
		//判断是否是保存操作
		if(IS_POST){
			//保存操作
			$post = I('post.');//可以使用数据对象创建方式进行接收
			//替换atr_vals中的中文逗号
			$post['attr_vals'] = str_replace('，',',',$post['attr_vals']);
			//写入数据表
			$rst = M('Attribute') -> add($post);
			//判断是否成功
			if($rst){
				//成功
				$this -> success('添加成功');
			}else{
				//失败
				$this -> error('添加失败');
			}
		}else{
			//获取商品的类型
			$data = M('Type') -> select();
			//传递给模版
			$this -> assign('data',$data);
			//展示操作
			$this -> display();
		}
	}

	//列表展示方法
	public function showList(){
		//select t1.*,t2.type_name from sp_attribute as t1,sp_type as t2 where t1.type_id = t2.type_id;
		$data=  M() -> field('t1.*,t2.type_name')
					-> table('sp_attribute as t1,sp_type as t2')
					-> where('t1.type_id = t2.type_id') 
					-> select();
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}
}