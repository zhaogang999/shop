<?php
//声明命名空间
namespace Admin\Controller;
//引入父类元素
use Think\Controller;
//声明类并且继承父类
class CommonController extends Controller{

	//构造方法
	public function __construct(){
		//构造父类
		parent::__construct();
		//第一角度：判断用户是否登录
		$uid = session('mg_id');//获取用户的id
		if(!$uid){
			//没有登录，让其回到登录页面
			$url = U('Public/login');
			$script = "<script>top.location.href='$url';</script>";
			echo $script;exit;
		}
		//第二角度
		//排除用户超级管理员
		if(session('role_id') > 1){
			//权限判断
			$auth = M('Role') -> find(session('role_id'));
			//当前用户拥有的权限（需要连上后台首页的权限，并且转化小写）
			$ac = strtolower($auth['role_auth_ac'] . ',Index-index,Index-left,Index-top,Index-main');
			//获取当前用户访问的控制器-方法
			$curr = strtolower(CONTROLLER_NAME . '-' . ACTION_NAME);
			//判断当前权限组成的字符串在不在权限字符串中，可以使用php内置函数strpos
			if(strpos($ac,$curr) === false){
				//没有权限
				$this -> error('您没有权限',U('Index/main'),3);
			}
		}
	}
}