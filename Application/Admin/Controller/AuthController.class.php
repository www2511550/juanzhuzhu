<?php
namespace Admin\Controller;
use Think\Controller;
class AuthController extends Controller {
	public function __construct(){
		parent::__construct();
        $userInfo = session('userInfo');
        $isadmin = $userInfo['username'] == 'chengcong';
        $this->assign('isadmin', $isadmin);
	}
	/**
	 * 判断是否登录
	 * @return
	 */
	public function isLogin(){
		return !session('userInfo') ? false : true;
	}

	/**
	 * 登录
	 * @return [type] [description]
	 */
	public function login(){
        if (!IS_POST) {
        	$this->display();die;
        }
        $username = trim(I('username'));
        $pwd = trim(I('pwd'));
        if ( !$username || !$pwd ) $this->error('账号或密码不能为空');
        $pwd = md5(md5(C('ADMIN_KEY').$pwd));
        $map['username'] = $username;
        $map['pwd'] = $pwd;
        $res = M('user')->where($map)->find();
        if (!$res) {
        	$this->error('密码或账号错误！');
        }
        unset($res['pwd']);
        session('userInfo', $res);
        $this->success('登录成功！',__ROOT__.'/?m=admin');
    }
    /**
     * 退出登录
     */
    public function out(){
        session('userInfo',null);
        $this->success('退出成功！',U('Auth/login'));
    }
}