<?php
namespace Home\Controller;

use Think\Controller;

class WapController extends BaseController{
	public $couponLogic;
	public function __construct(){
		parent::__construct();
		$this->couponLogic = D('Home/Coupon','Logic');
	}
	// 手机端首页
	public function index(){
		// 参数
        $mid = I('mid', 1);
        $kw = I('kw');
        $sid = I('sid');  // 0-低到高，1-高到低
        $sort = I('sort'); // 排序条件
        // 目录
        $menuData = $this->getMenu(); 
        if ( $kw ) {  // 关键字搜索
            $data = $this->couponLogic->getSearchData(array('kw'=>$kw,'sid'=>$sid,'sort'=>$sort,'rows'=>66));
        }else{
            $data = $this->couponLogic->getMenuData(array('mid'=>$mid,'menuData'=>$menuData,'sid'=>$sid,'sort'=>$sort,'rows'=>10));
        }
        // 格式化数据
        $data['data'] = $this->couponLogic->formatData($data['data']);
        // 获取浏览纪录
//         $history = $this->getHistoryData();
//         p($data);
        $this->assign(array('data'=>$data['data'],'page'=>$data['page'],'menu'=>$menuData,'history'=>$history));
        $this->display();
	}
	
	/**
	 * 异步追加
	 */
	function append(){
		$page = I('page',1);
		$mid = I('mid',1);
		// 目录
		$str = '';
		$menuData = $this->getMenu();
		$data = $this->couponLogic->getMenuData(array('mid'=>$mid,'menuData'=>$menuData,'rows'=>10,'p'=>$page));
		if ( $data ) {
			$data['data'] = $this->couponLogic->formatData($data['data']);
			foreach ( $data['data'] as $vo ) {
				$str .= '<div class="one_out">
				<b class="is_new"></b>
				<div class="one_img" style="overflow:hidden">
				<a href="">
					<img src="'.$vo['img_url'].'" alt="'.$vo['g_name'].'" style="height:244px;">
					</a>
				</div>
				<p class="title">
					<span>[包邮]</span><a href="">'.$vo['g_name'].'</a>
				</p>
				<div class="price">
					<p class="new_price">
						<b>￥</b><span>'.$vo['price'].'</span>
					</p>
					<p class="old_price">
						<span>￥3.5</span>
					</p>
					<a href="" class="buy">'.($vo['coupon_money_num'] ? $vo['coupon_money_num'].'元卷' : '去看看').'</a>
				</div>
			</div>		';
			}
		}
		$this->ajaxReturn($str);
	}

}