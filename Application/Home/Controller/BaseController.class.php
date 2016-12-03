<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller{
	public $errMsg;
	/**
	 * 检测地址是否正确
	 */
	public function checkUrl(){
		$q_name = trim($_SERVER['QUERY_STRING']);
		if ( !$q_name ) return false;
		$userInfo = M('user')->where(array('username'=>$q_name))->find();
		if ( !$userInfo ) return false;
		$arrStatus = array('2'=>'账号被删除','3'=>'账号已过期', '4'=>'账号异常');
		if ( in_array($userInfo['status'], $arrStatus)) {
			$this->errMsg = $arrStatus[$userInfo['status']]."，如有疑问，请联系管理员！";
			return false;
		}
		$timeStr = strtotime(date('Y-m-d H:i:s',$userInfo['start_time']).' '.$userInfo['limit_time'].' month');
		if ( $userInfo['limit_time'] !=0 && $timeStr<time() ) {
			$this->errMsg = "抽奖无法使用，请联系群主！";
			return false;
		}
		// 存储当前用户的user_id
		session('home_user_id', $userInfo['user_id']);
		return true;
	}
	/**
	 * 获取抽奖页面配置
	 */
	public function getConfig(){
		$user_id = session('home_user_id');
		if ( !$user_id ) return false;
		$map['user_id'] = $user_id;
		return M('config')->where($map)->order('id desc')->find();
	}
	/**
	 * 获取菜单栏数据
	 */
	public function getMenu(){
		return array(
				'1' => array('name'=>'9.9秒杀', 'where' => ' price < 10 '),
				'2' => array('name'=>'19.9秒杀', 'where' => ' price between 10 AND 20 '),
				'3' => array('name'=>'29.9秒杀', 'where' => ' price between 20 AND 30'),
				'4' => array('name'=>'化妆品秒杀', 'where' =>  " g_name like '%化妆%' "),
				'5' => array('name'=>'服饰秒杀', 'where' => " g_name like '%春%'  OR g_name like '%夏%' OR g_name like '%秋%' OR g_name like '%东%' "),
				'6' => array('name'=>'吃货世界', 'where' => " g_name like '%零食%' OR g_name like '%吃%' OR g_name like '%食品%' OR g_name like '%特产%' "),
		);
	}

}