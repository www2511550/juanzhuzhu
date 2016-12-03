<?php 
/**
 * 用户管理类
 */
namespace Common\Logic;
class UserLogic{
	private $errorMsg;
	/**
	 * 用户操作1-添加 2-更新
	 */
	function saveUser($data, $type = 1){
		if ( !$data ) return false;
		if ( $type == 1 ) {
			// 只允许一个管理员
			$userModel = M('user');
			$record = $userModel->where(array('user_role'=>1,'status'=>1))->select();
			if ( count($record)>2 ) return false;
			$data['pwd'] = md5(md5(C('ADMIN_KEY').$data['pwd']));
			$data['token'] = time();
			$data['addtime'] = time();
			return $userModel->add($data);
		}else if ( $type == 2 ) {
			$user_id = $data['user_id'];
			if ( !$user_id ) return false;
			unset($data['user_id']);
			if ( !$data ) return false;
			return M('user')->where(array('user_id'=>$user_id))->save($data);
		}
		return false;
	}
	/**
	 * 是否过期
	 */
	function isOvertime(){
		$userInfo = session('userInfo');
		$user = M('user')->where(array('user_id'=>$userInfo['user_id']))->find();
		if ( $user['limit_time'] == 0 ) return true;
		$timeStr = strtotime(date('Y-m-d H:i:s',$user['start_time']).' '.$user['limit_time'].' months');
		if ( !$user || $timeStr<time() ) return false;
		return true;
	}


}

