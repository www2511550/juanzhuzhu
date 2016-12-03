<?php
namespace Admin\Controller;
use Think\Controller;
class CouponController extends AuthController{
	/**
	 * 优惠卷商品列表
	 */
	public function lists(){
		$couponModel = M('coupon');
		$whereStr = " 1 ";
		// 分页类
		$count = $couponModel->where($whereStr)->count();
		$Page = new \Think\Page($count,15);
		$data['page'] = $Page->show();
		$data['data'] = $couponModel->where($whereStr)->order('end_time')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign($data);
		$this->display();
	}

	
	
}