<?php
#声明命名空间
namespace Admin\Controller;
#引入父类元素
use Think\Controller;
#声明类并且继承父类
class PublicController extends Controller{

	//展示登录页面
	public function login(){
		$this -> display();
	}

	//checkLogin方法，判断用户登录操作
	public function checkLogin(){
		//接收数据
		$post = I('post.');
		//加密密码
		$post['mg_pwd'] = getPwd($post['mg_pwd']);
		//实例化模型
		$model = M('Manager');
		$data = $model -> where($post) -> find();
		//判断是否存在用户
		if($data){
			//存在用户
			session('mg_id',$data['mg_id']);	//用户id信息
			session('mg_name',$data['mg_name']);	//用户用户名信息
			session('role_id',$data['role_id']);	//用户组信息
			session('mg_time',$data['mg_time']);	//上次登录时间
			//修改成功登录的时间
			$model -> save(array('mg_id' => session('mg_id'),'mg_time' => time()));
			//跳转到后台首页
			$this -> success('登录成功...',U('Index/index'),3);
		}else{
			//用户不存在、密码错误
			$this -> error('用户名或密码错误...');
		}
	}

	//退出方法
	public function logout(){
		//清空session
		session(null);
		//判断是否清除成功
		if(!session('?mg_id')){
			//提示成功
			$this -> success('退出成功',U('login'),3);
		}
	}

}