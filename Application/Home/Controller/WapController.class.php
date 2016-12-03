<?php
namespace Home\Controller;

use Think\Controller;

class WapController extends BaseController{
	public function index(){
		// 参数
        $mid = I('mid', 1);
        $kw = I('kw');
        $sid = I('sid');  // 0-低到高，1-高到低
        $sort = I('sort'); // 排序条件
        // 目录
        $menuData = $this->getMenu(); 
        $couponLogic = D('Home/Coupon','Logic');
        if ( $kw ) {  // 关键字搜索
            $data = $couponLogic->getSearchData(array('kw'=>$kw,'sid'=>$sid,'sort'=>$sort));
        }else{
            $data = $couponLogic->getMenuData(array('mid'=>$mid,'menuData'=>$menuData,'sid'=>$sid,'sort'=>$sort));
        }
        // 获取浏览纪录
//         $history = $this->getHistoryData();
        p($data);
        $this->assign(array('data'=>$data['data'],'page'=>$data['page'],'menu'=>$menuData,'history'=>$history));
        $this->display();
	}

}