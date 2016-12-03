<?php
namespace Admin\Controller;
use Think\Controller;
class SetController extends AuthController {
	/**
	 * 抽奖页面设置
	 */
    public function index(){
        $configModel = M('config');
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
    	if ( !IS_POST ) {
            $record = $configModel->where(array('user_id'=>$user_id))->find();
            $this->assign('config', $record);
    		$this->display();
    	}else{
    		$params= I('post.');
    		!$userInfo && $this->error('请先登录！',U('Auth/login'),3);
    		$record = $configModel->where(array('user_id'=>$user_id))->find();
    		// 组装数据
            $photo = $this->dealPhoto($_FILES['files']['tmp_name']);
            $params['qun_photo'] = $photo ? $photo : $record['qun_photo'];
    		$params['user_id'] = $user_id;
    		$params['addtime'] = time();
            // var_dump($params);die;
    		$status = !$record ? $configModel->add($params) : $configModel->where(array('id'=>$record['id']))->save($params);
    		$status === false ? $this->error('设置失败，稍后再试！') : $this->success('设置成功！');	
    	}
    }
    /**
     * 中奖率设置
     */
    public function rate(){
    	$userInfo = session('userInfo');
    	$configModel = M('config');
    	$record = $configModel->where(array('user_id'=>$userInfo['user_id']))->find();
    	if ( !IS_POST ) {
    		$rate = $record['reward'] ? explode('-', $record['reward']) : '';
    		include_once APP_PATH.'Common/Conf/reward.php'; 
            $r_names = array('特等奖', '一等奖', '二等奖', '三等奖', '四等奖');
    		$this->assign(array('arrReward'=>$prize_arr, 'rate'=>$rate, 'names'=>$r_names));
    		$this->display();
    	}else{
    		$params = I('reward');
    		!$params['4'] && $this->error('每个概率都为必填项！');
    		$reward = implode('-', $params);
    		// 组装数据
    		$data['user_id'] = $userInfo['user_id'];
    		$data['reward'] = $reward;
            $data['addtime'] = time();
    		$status = !$record ? $configModel->add($data) : M('config')->where(array('id'=>$record['id']))->save(array('reward'=>$reward,'addtime'=>time()));
    		$status === false ? $this->error('设置失败！') : $this->success('设置成功！');
    	}
    }

    function dealPhoto($params){
        // var_dump($params);die;
        if ( !$params ) return '';
        // 图片路径
        $_img_path = "Upload/cover/".date('Y-m-d');
        $img_path = $_img_path."/src_".time().".jpg";
        !is_dir($_img_path) && mkdir($_img_path,true,0777);
        $image = new \Think\Image();
        @$image->open($params);
        $status = move_uploaded_file($params, $img_path);
        return $status ? $img_path : false;
    }
}