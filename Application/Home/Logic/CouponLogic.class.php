<?php 
namespace Home\Logic;
/**
 * 优惠券逻辑处理层 
 *
 */
class CouponLogic
{
	/**
	 * 优惠券开始和结束时间条件
	 */
	function getTimeCondition(){
		return " AND end_time>=".strtotime(date('Y-m-d'));
	}
	
	/**
     * 获取分类数据
     */
    public function getMenuData($params){
    	$data = array();
        extract($params);
        // 优惠卷模型
        $couponModel = M('coupon');
        $whereStr = " 1 ".$this->getTimeCondition();
        $rows = $rows ? $rows : 24 ;
        // 排序
        $orderStr = $this->getOrderStr($params);
        $menuData[$mid]['where'] && $whereStr .= " AND ".$menuData[$mid]['where'];
        // 分页类
        $count = $couponModel->where($whereStr)->count();
        $Page = new \Think\Page($count,$rows);
        // 缓存key
        $dataKey = $whereStr.$orderStr.$_GET['p'];
        if ( $tmpData = S($dataKey) ) {
        	return json_decode($tmpData, true);
        }
        $data['page'] = $Page->show();
        $data['data'] = $couponModel->where($whereStr)->order($orderStr)->limit($Page->firstRow.','.$Page->listRows)->select();
    	S($dataKey, json_encode($data), 60);
        return $data;
    }
    /**
     * 搜索结果
     */
    public function getSearchData($params){
    	$data = array();
    	extract($params);
        // 优惠卷模型
        $couponModel = M('coupon');
        $whereStr = " 1 ".$this->getTimeCondition();
        $rows = $rows ? $rows : 24 ;
        $arrKw = explode(' ', $kw);
        // 排序
        $orderStr = $this->getOrderStr($params);
        $kwData = array();
        if ( $kw ) {
            foreach ($arrKw as $val) {
                $val && $whereStr .= " AND g_name like '%".$val."%'";
            }
        }
        // 分页类
        $count = $couponModel->where($whereStr)->count();
        $Page = new \Think\Page($count,$rows);
        $data['page'] = $Page->show();
        $kwData = $couponModel->where($whereStr)->order($orderStr)->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['data'] = $this->getKwData($kwData,$arrKw);
        return $data;
    }
    /**
     * 获取排序字符串
     * @param unknown $params
     * @return string
     */
    public function getOrderStr($params){
    	$sort = $params['sort'];
    	$sid = $params['sid'];
    	$orderStr = 'rand()';
    	// 排序
    	$desc = intval($sid) ? ' desc ' : ' asc ';
    	$arrOrder = array('price'=>' price ', 'sale' => ' sale_num ');
    	$arrOrder[$sort] && $orderStr = $arrOrder[$sort].$desc;
    	return $orderStr;
    }

    /**
     * 关键字标红
     */
    public function getKwData($params,$arrKw){
        $data = array();
        if ( !$params ) return $data;
        if ( !$arrKw ) return $params;
        foreach ($params as $key => $value) {
            $data[$key] = $value;
            $kw_g_name = $value['g_name'];
            foreach ($arrKw as $kw) {
                if ( !$kw ) continue;
                $tmp_kw_g_name = preg_replace("/$kw/", "<font style='color:red'>$kw</font>", $kw_g_name);
                $kw_g_name = $tmp_kw_g_name;
            }
            $data[$key]['g_name'] = $kw_g_name;
        }
        return $data;
    }
	/**
	 * 检查优惠卷是否用完
	 */
    public function checkCoupon($id = 0){
    	$coupon_url = M('coupon')->where(array('id'=>$id))->getField('coupon_url');
    	if ( !$coupon_url ) return array();
    	preg_match('/e\=(.*)&pid/', $coupon_url, $e);
    	$ch = curl_init();
    	// get参数
    	$getParams = array(
    			'e' => $e[1],
    	);
    	$url = 'https://uland.taobao.com/cp/coupon';
    	$res = http($url, $getParams);
    	return json_decode($res,true);
    }



}





 ?>