<?php
namespace app\home\controller;
use think\Controller;
use app\common\model\Orders as Order;
use app\common\model\Address;
use app\common\model\Shopcar;
use app\common\model\Good;

class Orders extends Controller
{
	//判断是否登录
	public function _initialize(){
		if (empty(session('user'))) {
			$this->redirect("login/login");
		}
	}//end 判断是否登录-------------------------------------------------------------------------------

	//加载添加订单页面
	public function order_add(){
		$Order=new Order;
		$Address=new Address;
		$Shopcar=new Shopcar;
		$Good=new Good;


		//生成订单号
		do {
			$ordernum=time().rand(00000000,99999999);
			$row=$Order->whereget(['code'=>$ordernum]);
		} while ($row);
		//获取用户id
		$uid=session('user')['id'];

		//查询该用户的地址信息
		$address_list=$Address->seach(['uid'=>$uid]);

		//判断是从购物车进来的还是点击立即购买进来的
		if(input('type')==1){

			//查询该用户的购物车信息
			$list=$Shopcar->seach(['uid'=>$uid]);
			//循环查询购物车下的商品信息
			foreach ($list as $value) {
				$g_list[]=$Good->goodget($value['gid']);
			}
			$a=1;
			//加载数据
			$this->assign('list',$list);
			$this->assign('g_list',$g_list);
			$this->assign('a',$a);
		}else{
			// dump(input('get.'));
			$list[]=input('get.');
			$g_list[]=$Good->goodget(input('id'));
			$a=2;
			session('add_good',$list[0]);
			//加载数据
			$this->assign('list',$list);
			$this->assign('g_list',$g_list);
			$this->assign('a',$a);
		}
		// dump($address_list);
		//加载数据
		$this->assign('address_list',$address_list);
		$this->assign('ordernum',$ordernum);
		// $this->assign('now',time());
		//加载模板
		return $this->fetch();
	}//end 加载添加订单页面-------------------------------------------------------------------------------

	//执行添加订单操作
	public function order_toadd(){
		if (empty(input('address'))) {
			return 4;
		}
		if (md5(input('paypass'))!=session('user')['paypass']) {
			return 3;
		}
		//查询用户地址数据
		$address=Db::name('address')->where('id',input('address'))->find();
		if (input('a')==1) {
			//查询该用户的购物车信息
			$list=Db::name('car')->where('uid',session('user')['id'])->select();
			foreach ($list as $v) {
				switch ($v['goodtype']) {
					case '1':
						$find=Db::name('gooddetail')->where('goodid',$v['gid'])->where('color',$v['color'])->find();
						if ($find[$v['size']]<$v['num']) {
							return 2;
						}
					break;
					default:
						// code...
					break;
				}
			}
			//整理添加数据
			$data=[
				'uid'=>session('user')['id'],
				'user'=>$address['name'],
				'phone'=>$address['phone'],
				'address'=>$address['address']." ".$address['detail'],
				'total'=>input('total'),
				'state'=>1,
				'addtime'=>time(),
				'code'=>input('ordernum'),
				'paytype'=>input('pay_type')
			];
			//添加订单
			$id=Db::name('order')->insertGetId($data);
			if ($id>0) {
				//整理详情数据
				foreach ($list as $v) {
					$detaildata[]=[
						'goodid'=>$v['gid'],
						'orderid'=>$id,
						'num'=>$v['num'],
						'color'=>$v['color'],
						'size'=>$v['size'],
						'goodtype'=>$v['goodtype'],
						'state'=>1
					];
					switch ($v['goodtype']) {
						case '1':
							//查询购买颜色尺寸的原库存
							$detailnum=Db::name('gooddetail')->where('goodid',$v['gid'])->find();
							//修改颜色尺寸库存
							Db::name('gooddetail')->where('goodid',$v['gid'])->where('color',$v['color'])->update([$v['size']=>$detailnum[$v['size']]-$v['num']]);
						break;

						default:
							// code...
						break;
					}
					//查询购买商品的原库存
					$znum=Db::name('good')->where('id',$v['gid'])->find();
					//修改商品库存
					Db::name('good')->where('id',$v['gid'])->update(['num'=>$znum['num']-$v['num'],'paynum'=>$v['num']+$znum['paynum']]);
				}
				//添加订单详情
				$e=Db::name('dorder')->insertAll($detaildata);
				if ($e>0) {
					//清空购物车
					$error=Db::name('car')->where('uid',session('user')['id'])->delete();
					if ($error>0) {
						return 1;
					}else {
						return 0;
					}
				}else {
					return 0;
				}
			}else {
				return 0;
			}
		}else {
			$arr=session('add_good');
			//整理添加数据
			$data=[
				'uid'=>session('user')['id'],
				'user'=>$address['name'],
				'phone'=>$address['phone'],
				'address'=>$address['address']." ".$address['detail'],
				'total'=>$arr['price']*$arr['num'],
				'state'=>1,
				'addtime'=>time(),
				'code'=>input('ordernum'),
				'paytype'=>input('pay_type')
			];
			//添加订单
			$id=Db::name('order')->insertGetId($data);
			if ($id>0) {
				//整理详情数据
				$detaildata=[
					'goodid'=>$arr['gid'],
					'orderid'=>$id,
					'num'=>$arr['num'],
					'color'=>$arr['color'],
					'size'=>$arr['size'],
					'goodtype'=>$arr['goodtype'],
					'state'=>1
				];
				switch ($arr['goodtype']) {
					case '1':
						//查询购买颜色尺寸的原库存
						$detailnum=Db::name('gooddetail')->where('goodid',$arr['gid'])->find();
						//修改颜色尺寸库存
						Db::name('gooddetail')->where('goodid',$arr['gid'])->where('color',$arr['color'])->update([$arr['size']=>$detailnum[$arr['size']]-$arr['num']]);
					break;

					default:
						// code...
					break;
				}
				//查询购买商品的原库存
				$znum=Db::name('good')->where('id',$arr['gid'])->find();
				//修改商品库存
				$e=Db::name('good')->where('id',$arr['gid'])->update(['num'=>$znum['num']-$arr['num'],'paynum'=>$arr['num']+$znum['paynum']]);
				//添加订单详情
				Db::name('dorder')->insert($detaildata);
				return $e;
			}else {
				return 0;
			}
		}

	}
}
