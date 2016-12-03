<?php 
/**
 * 用户管理类
 */
namespace Common\Logic;
class RewardLogic{
	/**
	 * 获取中奖概率
	 */
	function getRate()
	{
		$user_id = session('home_user_id');
		$configModel = M('config');
    	$record = $configModel->where(array('user_id'=>$user_id))->find();
    	include_once APP_PATH.'Common/Conf/reward.php';  // 默认中奖率
    	if ( $record ) {
    		$rate = explode('-', $record['reward']);
    		foreach ($prize_arr as $key => $val) {
    			$prize_arr[$key]['v'] = $rate[$val['id']-1];
    		}
    	}
    	return $prize_arr;
	}
}

