<?php
/**
 * 
 * @authors	九炼
 * @wat 传智播客教育集团 PHP学院
 * @date    2016-09-20 11:18:20
 * @url 	http://www.itcast.cn/php
 * @desc	Auth控制器
 *
 * ━━━━━━神兽出没━━━━━━
 * 　　   ┏┓　 ┏┓
 * 　┏━━━━┛┻━━━┛┻━━━┓
 * 　┃              ┃
 * 　┃       ━　    ┃
 * 　┃　  ┳┛ 　┗┳   ┃
 * 　┃              ┃
 * 　┃       ┻　    ┃
 * 　┃              ┃
 * 　┗━━━┓      ┏━━━┛ Code is far away from bugs with the animal protecting.
 *       ┃      ┃     神兽保佑,代码无bug。
 *       ┃      ┃
 *       ┃      ┗━━━┓
 *       ┃      　　┣┓
 *       ┃      　　┏┛
 *       ┗━┓┓┏━━┳┓┏━┛
 *     　  ┃┫┫　┃┫┫
 *     　  ┗┻┛　┗┻┛
 *
 * ━━━━━━感觉萌萌哒━━━━━━
 */

//命名空间
namespace Admin\Controller;
//声明类并且继承父类
class AuthController extends CommonController{

	//showList方法
	public function showList(){
		//获取数据
		$data = M('Auth') -> select();
		//使用getTree方法进行无限级分类
		load('@/tree');
		$data = getTree($data);
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}

	//add方法，展示添加权限的模版
	public function add(){
		//查询父级方法
		$data = M('Auth') -> where('auth_pid = 0') -> select();
		//传递给模版
		$this -> assign('data',$data);
		//展示模版
		$this -> display();
	}

	//addOk方法
	public function addOk(){
		//接收数据
		$post = I('post.');
		//入库
		$rst = M('Auth') -> add($post);
		//判断结果
		if($rst){
			//成功
			$this -> success('添加成功');
		}else{
			//失败
			$this -> error('添加失败');
		}
	}
}
