<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends AuthController {

    public function index(){
        $data = M('user')->where(array('status'=>1))->select();
        $this->assign('data', $data);
        $this->display();
    }
    /**
     * 添加用户
     */
    public function add(){
        if ( !IS_POST ) {
            $userCate = array(1=>'管理员',2=>'普通用户');
            $times = $this->getLimitTime();
            $this->assign(array('roles'=>$userCate,'times'=>$times));
            $this->display();            
        }else{
            $this->userCondition();
            $userLogic = D('Common/User', 'Logic');
            $userLogic->saveUser($params) ? $this->success('添加成功！') : $this->error('添加失败！');
        }
    }
    /**
     * 登录其他人账号
     */
    public function loginInto(){
        $user_id = I('uid');
        $isadmin = I('isadmin');
        if ( !$user_id || !$isadmin ) $this->error('非法登入！');
        $record = M('user')->where(array('user_id'=>$user_id,'status'=>1))->find();
        if ( !$record ) $this->error('此账号不存在！');
        session('userInfo', null);
        unset($record['pwd']);
        session('userInfo', $record);
        $this->success('登入成功！');
    }
    /**
     * 修改
     */
    public function edit(){
        if ( !IS_POST ) {
            $user_id = I('uid');
            (!$user_id || !I('isadmin')) && $this->error('参数异常！');
            $times = $this->getLimitTime();
            $userInfo = M('user')->where(array('user_id'=>$user_id,'status'=>1))->find();
            !$userInfo && $this->error('用户存在！');
            $this->assign(array('times'=>$times, 'user'=>$userInfo));
            $this->display();
        }else{
            $params = I('post.');
            !$params['username'] && $this->error('用户名不能为空！');
            !$params['start_time'] && $this->error('开始时间不能为空！');
            $params['start_time'] = strtotime($params['start_time']);
            $params['limit_time'] != 0 && !$params['limit_time'] && $this->error('截止日期不能空！');
            $userLogic = D('Common/User', 'Logic');
            $userLogic->saveUser($params,2) !== false ? $this->success('修改成功！',U('user/index')) : $this->error('修改失败！');
        }
    }
    /**
     * 删除
     */
    public function del(){
        $user_id = I('uid');
        (!$user_id || !I('isadmin')) && $this->error('参数异常！');
        $params['user_id'] = $user_id;
        $params['status'] = 2;
        $userLogic = D('Common/User', 'Logic');
        $userLogic->saveUser($params,2) !== false ? $this->success('删除成功！',U('user/index')) : $this->error('删除失败！');
    }
    /**
     * 公共条件判断
     */
    public function userCondition(){
        $params = I('post.');
        !$params['username'] && $this->error('用户名不能为空！');
        !$params['pwd'] && $this->error('密码不能为空！');
        !$params['start_time'] && $this->error('开始时间不能为空！');
        $params['start_time'] = strtotime($params['start_time']);
        $params['limit_time'] != 0 && !$params['limit_time'] && $this->error('截止日期不能空！');
    }
    /**
     * 获取有效期限
     */
    public function getLimitTime(){
        $times = array();
        for ($i=1; $i <= 24; $i++) { 
            $times[$i] = $i."个月"; 
        }
        return $times;
    }
}