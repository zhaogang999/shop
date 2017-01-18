<?php
#声明命名空间
namespace Home\Controller;
#引入父类元素
use Think\Controller;
#定义类并且继承父类
class GoodsController extends Controller{

	//showList方法
	public function showList(){
		//实例化
		$model = M('Goods');
		//查询数据
		$data = $model -> select();
		//传递给模版
		$this -> assign('data',$data);
		$this -> display();
	}

	public function detail(){
		//获取id
		$goods_id = I('get.goods_id');
		//获取基本信息
		$model = M('Goods');
		$data = $model -> find($goods_id);
		//查询商品的多选属性
		//select t1.attr_id,t1.attr_name,group_concat(t2.attr_value) as attr_values from sp_goodsattr t2 inner join sp_attribute t1 on t2.attr_id = t1.attr_id where t2.goods_id = 商品id and t1.attr_sel =  '1' group by t1.attr_id;
		$attrs = M('Goodsattr') 
				-> field('t1.attr_id,t1.attr_name,group_concat(t2.attr_value) as attr_values') 
				-> alias('t2') 
				-> join('inner join sp_attribute t1 on t2.attr_id = t1.attr_id') 
				-> where("t2.goods_id = {$data['goods_id']} and t1.attr_sel =  '1'") 
				-> group('t1.attr_id')
				-> select();
		//将attr_values处理成数组
		foreach ($attrs as $key => $value) {
			$attrs[$key]['attr_vals'] = explode(',',$value['attr_values']);
		}
		//dump($attrs);die;
		//单选属性的查询
		//select t1.attr_id,t2.attr_name,t1.attr_value from sp_goodsattr as t1,sp_attribute as t2 where t1.attr_id = t2.attr_id and t2.attr_sel = ‘0’ and t1.goods_id = 13;
		$single = M() -> field('t1.attr_id,t2.attr_name,t1.attr_value') 
					-> table('sp_goodsattr as t1,sp_attribute as t2') 
					-> where("t1.attr_id = t2.attr_id and t2.attr_sel = '0' and t1.goods_id = {$data['goods_id']}") 
					-> select();
		//dump($single);die;
		//传递给模版
		$this -> assign('data',$data);
		$this -> assign('attrs',$attrs);
		$this -> assign('single',$single);
		$this -> display();
	}
}