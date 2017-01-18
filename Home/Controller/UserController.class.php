<?php
#声明命名空间
namespace Home\Controller;
#引入父类元素
use Think\Controller;
#声明并且继承父类
class UserController extends Controller{

	//构造方法
	public function _initialize(){
		//判断当前用户是否登录，如果登录则跳转到首页
		$action = strtolower(ACTION_NAME);
		if(session('uid')){
			if($action == 'register' || $action == 'login'){
				//跳转到首页
				$this -> redirect('Index/index');exit;
			}
		}
	}

	//用户注册
	public function register(){
		//根据请求类型处理请求
		if(IS_POST){
			//表单的提交
			$post = I('post.');
			//验证两次密码是否一致
			if($post['user_pwd'] != $post['user_pwd1']){
				//两次输入的密码不一样
				$this -> error('两次输入的密码不一致...');
			}
			$model = M('User');
			//查询用户名是否重复
			$data =  $model -> where(array('user_name' => $post['user_name'])) -> find();
			if($data){
				//提示用户名重复
				$this -> error('用户名已经存在，请更换。。。');exit;
			}
			//补全字段
			$post['add_time'] = time();
			$post['user_pwd'] = getPwd($post['user_pwd']);
			//写入到数据表中
			$rst = $model -> add($post);
			//判断结果
			if($rst){
				//注册成功
				$this -> success('注册成功',U('login'),3);
			}else{
				//注册失败
				$this -> error('注册失败');
			}
		}else{
			$this -> display();
		}
	}

	//用户登录
	public function login(){
		//根据请求类型分别处理请求
		if(IS_POST){
			//接收数据
			$post = I('post.');
			//处理密码
			$post['user_pwd'] = getPwd($post['user_pwd']);
			//查询用户名和密码是否存在
			$info = M('User') -> where($post) -> find();
			//判断是否成功
			if($info){
				//信息持久化
				session('uid',$info['user_id']);//记录用户的id
				session('uname',$info['user_name']);//记录用户名
				//用户名和密码是正确的
				if($_GET['tc'] && $_GET['ta']){
					//购物车来源，跳转到回调地址
					$this -> success('登录成功',U("{$_GET['tc']}/{$_GET['ta']}"),3);
				}else{
					//非购物车来源，跳转到首页
					$this -> success('登录成功',U('Index/index'),3);
				}
			}else{
				//用户名或密码错误
				$this -> error('用户名或密码错误');
			}
		}else{
			$this -> display();
		}
	}

	//退出方法
	public function logout(){
		//session清空操作
		session(null);
		$this -> success('退出成功',U('Index/index'),3);
	}

	//QQ登录的回调地址
	public function callback(){
		//复制过来的时候要记得修改路径
		require_once("./shop/Tools/qq/API/qqConnectAPI.php");
		$qc = new \QC();
		//获取access token
		$access_token =  $qc->qq_callback();
		//获取openid
		$openid = $qc->get_openid();
		//重新实例化QC，传递$access_token，$openid
		$qc = new \QC($access_token,$openid);
		//获取用户基本信息
		$rst = $qc -> get_user_info();
		//查询当前openid是否在数据表中存在
		$exist = M('User') -> where("openid = '$openid'") -> find();
		//判断是否存在
		if($exist){
			//这个用户是老用户
			session('uid',$exist['user_id']);
			session('uname',$exist['user_name']);
		}else{
			//新用户
			//记录字段	openid、nickname（user_name）、gender（user_sex）
			$data['openid'] = $openid;
			$data['user_name'] = $rst['nickname'];
			$data['user_sex'] = $rst['gender'];
			//写入数据表
			$uid = M('User') -> add($data);
			//持久化处理
			session('uid',$uid);
			session('uname',$rst['nickname']);
		}
		echo "<script>opener.location='/';window.close();</script>";
	}

}