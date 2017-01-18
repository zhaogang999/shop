<?php
//命名空间
namespace Home\Controller;
//引入父类元素
use Think\Controller;
//引入cart类元素
use Tools\Cart;
//声明类并且继承父类
class CartController extends Controller{

	//添加到购物车
	public function add(){
		//接收数据
		$goods_id = I('post.goods_id');
		//实例化cart类
		$cart = new Cart();
		//'goods_id'=>'10',
		//'goods_name'=>'诺基亚',
		//'goods_price'=>'1750',
		//'goods_buy_number'=>'1',
		//'goods_total_price'=>1750);
		$rst = M('Goods') -> find($goods_id);
		//拼装数据
		$data['goods_id'] = $goods_id;
		$data['goods_name'] = $rst['goods_name'];
		$data['goods_price'] = $rst['goods_price'];
		$data['goods_buy_number'] = 1;
		$data['goods_total_price'] = $rst['goods_price'] * $data['goods_buy_number'];
		//交给cart类去处理添加
		$cart -> add($data);
		//获取购物车中商品的数量和总价
		echo json_encode($cart -> getNumberPrice());
		//dump($cart);
	}

	//购物车查看页面
	public function flow1(){
		//要求读取购物车中的数据
		$cart = new Cart();
		$data = $cart -> getCartInfo();
		//获取商品id(1,2,3,4)
		$ids = implode(',',array_keys($data));
		//查询商品的缩略图
		$info = M('Goods') -> field('goods_id,goods_small_logo') -> select($ids);
		//合并$data和$info
		foreach ($data as $key => $value) {
			foreach ($info as $k => $v) {
				//判断
				if($value['goods_id'] == $v['goods_id']){
					$data[$key]['goods_small_logo'] = $v['goods_small_logo'];
				}
			}
		}
		//dump($data);die;
		//获取商品的总价
		$price = $cart -> getNumberPrice();
		//传递变量
		$this -> assign('data',$data);
		$this -> assign('price',$price['price']);
		//展示页面
		$this -> display();
	}

	//修改方法
	public function change_number(){
		//接收数据
		$post = I('post.');
		//实例化cart类
		$cart = new Cart();
		//调用cart类中修改购物车的方法，返回的当前商品的小计价格
		//$pd_number,$pd_id   product
		$price = $cart -> changeNumber($post['amount'],$post['goods_id']);
		//重新获取总价
		$total = $cart -> getNumberPrice();
		$totalprice = $total['price'];
		//输出
		echo json_encode(array('price' => $price,'totalprice' => $totalprice));
	}

	//del方法，实现商品的删除
	public function del(){
		//接收id
		$id = I('get.goods_id');
		//实例化cart类
		$cart = new Cart();
		//调用cart类中的del方法实现购物车中商品的删除
		$cart -> del($id);
		//删除之后总价会发生变化，所以需要重新获取购物车中商品的总价
		$total = $cart -> getNumberPrice();
		echo $total['price'];
	}

	//flow2展示模版
	public function flow2(){
		//判断是否是post提交
		if(IS_POST){
			//接收数据
			$post = I('post.');
			//处理数据表的数据
			//订单表
			//处理数据user_id  order_number  order_price  add_time   upd_time
			$cart = new Cart();//实例化购物车类
			$total = $cart -> getNumberPrice();//获取商品的数量和总价
			$post['user_id'] = session('uid');
			$post['order_number'] = 'PHP49' . date('YmdHis') . mt_rand(100000,999999);
			$post['order_price'] = $total['price'];
			$post['add_time'] = $post['upd_time'] = time();
			//接收保存的订单id
			//$oid = M('Order') -> add($post);

			//订单-商品表
			//处理数据order_id  goods_id	goods_price goods_number  goods_total_price
			//先从购物车中读取商品信息
			$info = $cart -> getCartInfo();
			foreach ($info as $key => $value) {
				//补充字段
				$data['order_id'] = $oid;
				$data['goods_id'] = $value['goods_id'];
				$data['goods_price'] = $value['goods_price'];
				$data['goods_number'] = $value['goods_buy_number'];
				$data['goods_total_price'] = $value['goods_total_price'];
				//M('OrderGoods') -> add($data);
			}

			//清空购物车
			//$cart -> delall();

			//订单支付
			//echo '订单创建成功...';
			//在这里需要发送post请求给alipayapi.php，并且还需要跳转到该页面
			echo "<form style='display:none;' name='alipayment' action='/shop/Tools/alipay/alipayapi.php' method='post'>
<input size='30' name='WIDout_trade_no' value='{$post['order_number']}' />
<input size='30' name='WIDsubject' value='PHP49电子商城中订单'/>
<input size='30' name='WIDprice' value='{$post['order_price']}'/>
<input size='30' name='WIDbody' />
<input size='30' name='WIDshow_url' />
<input size='30' name='WIDreceive_name' />
<input size='30' name='WIDreceive_address' />
<input size='30' name='WIDreceive_zip' />
<input size='30' name='WIDreceive_phone' />
<input size='30' name='WIDreceive_mobile' />
</form>
<script>
function load_submit(){
	document.alipayment.submit();
}
load_submit();
</script>";
		}else{
			//判断用户是否登录，如果登录则正常显示，如果没有登录则让其登录
			if(!session('?uid')){
				//用户没有登录
				$this -> error('请先登录',U('User/login',array('tc'=>'Cart','ta'=>'flow2')),3);exit;
			}else{
				//展示数据
				//要求读取购物车中的数据
				$cart = new Cart();
				$data = $cart -> getCartInfo();
				//获取商品id(1,2,3,4)
				$ids = implode(',',array_keys($data));
				//查询商品的缩略图
				$info = M('Goods') -> field('goods_id,goods_small_logo') -> select($ids);
				//合并$data和$info
				foreach ($data as $key => $value) {
					foreach ($info as $k => $v) {
						//判断
						if($value['goods_id'] == $v['goods_id']){
							$data[$key]['goods_small_logo'] = $v['goods_small_logo'];
						}
					}
				}
				//dump($data);die;
				//获取商品的总价
				$price = $cart -> getNumberPrice();
				//传递变量
				$this -> assign('data',$data);
				$this -> assign('price',$price);
				//展示模版
				$this -> display();
			}
		}
	}

	//flow3
	public function flow3(){
		//展示模版
		$this -> display();
	}
}
