<?php
//声明命名空间
namespace Admin\Model;
//引入父类元素
use Think\Model;
//声明类并且继承父类
class RoleModel extends Model{

	//自定义方法实现用户组的更新
	public function saveAuth($data){
		//处理role_auth_ids字段
		$post['role_auth_ids'] = implode(',',$data['auth_ids']);
		//处理主键
		$post['role_id'] = $data['role_id'];
		//处理role_auth_ac字段（需要查询auth表）
		$model = M('Auth');
		$auth = $model -> where("auth_pid > 0 and auth_id in ({$post['role_auth_ids']})") -> select();
		//遍历$auth进行ac处理
		$ac = '';
		foreach ($auth as $key => $value) {
			$ac .= $value['auth_c'] . '-' . $value['auth_a'] . ',';
		}
		//去掉最后多余的逗号
		$post['role_auth_ac'] = rtrim($ac,',');
		//写入数据表
		return $this -> save($post);
	}
}