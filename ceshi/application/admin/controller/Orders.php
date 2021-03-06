<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Orders as Order;
use app\common\model\Good;
use app\common\model\Goodc;
use app\common\model\Goods;

class Orders extends Controller
{
	//判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}//end 判断是否登录-----------------------------------------------------------------------------

	//加载用户列表页
	function order_list(){
		$Order=new Order;

        //封装搜索条件
        $where=array();
        //封装url搜索条件
        $url=array();

        //初始化参数
        $sea_state=0;
        $sea_paystate=0;

        //订单号搜索
        if (!empty(input('code'))) {
            $code=input('code');
            $where['code']=array('=',$code);
            $url['query']['code']=$code;
            $this->assign('seachC',$code);
        }
        //联系电话搜索
        if (!empty(input('phone'))) {
            $phone=input('phone');
            $where['phone']=array('=',$phone);
            $url['query']['phone']=$phone;
            $this->assign('seachP',$phone);
        }
        //订单状态搜索
        if (!empty(input('state'))) {
            $sea_state=input('state');
            $where['state']=array('=',$sea_state);
            $url['query']['state']=$sea_state;
        }
        //订单支付状态搜索
        if (!empty(input('paystate'))) {
            $sea_paystate=input('paystate');
            $where['paystate']=array('=',$sea_paystate);
            $url['query']['paystate']=$sea_paystate;
        }
        //商品时间的搜索
        if (!empty(input('time'))) {
            $time=input('time');
            $tarr=explode(' ~ ',$time);
            $ltime=strtotime($tarr[0]);
            $rtime=strtotime($tarr[1]);

            $where['addtime']=array(
                array('>=',$ltime),
                array('<',$rtime)
            );
            $url['query']['time']=$time;
            $this->assign('sea_time',$time);
        }

		$state=[1=>'待发货',2=>'已发货',3=>'已签收',4=>'已评论'];
		$paystate=[1=>'未支付',2=>'已支付'];

		$data=$Order->seach($where,$url);

		$this->assign('data',$data);
		$this->assign('state',$state);
		$this->assign('paystate',$paystate);
		$this->assign('sea_state',$sea_state);
		$this->assign('sea_paystate',$sea_paystate);
		return $this->fetch();
	}//end 加载用户列表页-----------------------------------------------------------------------------

	//加载订单修改页
	function order_edit(){
		$Order=new Order;
		$Goods=new Goods;
		$Goodc=new Goodc;

		$id=input('id');
		$data=$Order->ordersget($id);
		$sid=$data['sid'];

		$sdata=$Goods->seach(['gid'=>$data['gid']]);
		$cdata=$Goodc->seach(['sid'=>$sid]);

		$this->assign('data',$data);
		$this->assign('id',$id);
		$this->assign('sdata',$sdata);
		$this->assign('cdata',$cdata);

		return $this->fetch();
	}//end 加载用户密码修改页-------------------------------------------------------------------------

	//加载被选择数据
	function order_get_edit(){
		$Order=new Order;
		$Goods=new Goods;
		$Goodc=new Goodc;

		$gid=input('gid');
		$sdata=$Goods->seach(['gid'=>$gid]);
		$resdata=[];
		foreach($sdata as $v){
			$lssdata['name']=$v['name'];
			$lssdata['id']=$v['id'];
			$lssdata['price']=$v['price'];
			$cdata=$Goodc->seach(['sid'=>$v['id']]);
			$lscsdata=[];
			foreach($cdata as $vv){
				$lscdata['name']=$vv['name'];
				$lscdata['id']=$vv['id'];
				$lscsdata[]=$lscdata;
			}
			$lssdata['colorList']=$lscsdata;
			$resdata[]=$lssdata;
		}
		return $resdata;

	}//end 加载被选择数据-------------------------------------------------------------------------

	//执行订单修改操作
	function order_toedit(){
		$Order=new Order;
		$Goods=new Goods;
		$Goodc=new Goodc;

		$data=input('post.');
		$sname=$Goods->goodsget($data['size']);
		$cname=$Goodc->goodcget($data['color']);

		//整理修改数据
		$edit_data['user']=$data['user'];
		$edit_data['phone']=$data['phone'];
		$edit_data['address']=$data['province'].','.$data['city'].','.$data['area'].'@'.$data['detail'];
		$edit_data['content']=$data['content'];
		$edit_data['sid']=$data['size'];
		$edit_data['cid']=$data['color'];
		$edit_data['num']=$data['num'];
		$edit_data['total']=$data['total'];
		$edit_data['category']=$sname['name'].','.$cname['name'];
		$edit_data['state']=$data['state'];


		$where['id']=$data['id'];
		//执行修改语句
		$res=$Order->orders_edit($edit_data,$where);//return 受影响的行数
		if($res!==false){
			//修改成功
			return 1;
		}else{
			//修改失败
			return 0;
		}
	}//end 执行用户密码修改操作------------------------------------------------------------------------

	//执行订单删除操作
	function order_del(){
		$Order=new Order;

		//获取要删除的订单数据
		$id=input('id');
		$data=$Order->ordersget($id);
		//判断该订单是否已完成
		if(($data['state']>2 && $data['paystate']==2) || $data['paystate']==3){
			//删除
			$res=$Order->delwhere(['id'=>$id]);
			if($res>0){
				//成功
				return 1;
			}else{
				//失败
				return 0;
			}
		}else{
			//订单未完成
			return 2;
		}
	}
}