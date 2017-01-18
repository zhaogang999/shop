<?php
namespace Admin\Controller;
class IndexController extends CommonController {

    public function index(){
        $this -> display();
    }

    public function left(){
        //处理菜单
        //从session中获取当前用户的role_id
        $role_id = session('role_id');
        //实例化
        $model = M('Auth');
        //判断身份是否是超级管理员
        if($role_id == 1){
            //具有全部权限
            $top = $model -> where('isnav = 1 and auth_pid = 0') -> select();//顶级菜单
            $cate = $model -> where('isnav = 1 and auth_pid > 0') -> select();//非顶级菜单
        }else{
            //非超级管理员
            $role = M('Role') -> find($role_id);
            $top = $model -> where("isnav = 1 and auth_pid = 0 and auth_id in ({$role['role_auth_ids']})") -> select();//顶级菜单
            $cate = $model -> where("isnav = 1 and auth_pid > 0 and auth_id in ({$role['role_auth_ids']})") -> select();//非顶级菜单
        }
        //传递给模版
        $this -> assign('top',$top);
        $this -> assign('cate',$cate);
        $this -> display();
    }

    public function top(){
        $this -> display();
    }

    public function main(){
        $this -> display();
    }
}