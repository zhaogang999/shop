<?php
//声明命名空间
namespace Admin\Controller;
//声明类并且继承父类
class RoleController extends CommonController{

	//showList方法，展示用户组/角色信息
	public function showList(){
		//实例化模型
		$model = M('Role');
		//查询
		$data = $model -> select();
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}

	//setAuth方法展示权限设置页面
	public function setAuth(){
		//接收id
		$id = I('get.role_id');
		//实例化模型
		$model = M('Role');
		$info = $model -> find($id);//当前修改的用户组信息
		//查询全部的权限信息
		$top = M('Auth') -> where('auth_pid = 0') -> select();//顶级
		$cate = M('Auth') -> where('auth_pid > 0') -> select();//非顶级
		//传递给模版
		//dump($info);die;
		$this -> assign('info',$info);
		$this -> assign('top',$top);
		$this -> assign('cate',$cate);
		//展示模版
		$this -> display();
	}

	//saveAuth方法，接受数据保存数据
	public function saveAuth(){
		//接收数据
		$post = I('post.');
		//实例化自定义模型
		$model = D('Role');
		//写入
		$rst = $model -> saveAuth($post);
		//判断返回值
		if($rst){
			//设置成功
			$this -> success('设置成功',U('showList'),3);
		}else{
			//设置失败
			$this -> error('设置失败');
		}
	}
}