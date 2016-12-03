<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AuthController{
    public function __construct()
    {   
        parent::__construct();
        if (!$this->isLogin()) {
            $this->display('Auth/login');die;
        }   
    }
    public function index()
    {
        $this->display();
    }
    /**
     * 导入淘宝客订单
     */
    public function importTbOrder(){
        if (!IS_POST) {
            $this->display();die;
        }
        $fileName = $_FILES['upFile']['tmp_name'];
        if (!$fileName) {
            $this->error('请选择要上传的文件！');
        }
        $excelData = $this->_getExcelData($fileName);
        if ( count($excelData)<1 ){
            $this->error('上传文件为空！');
        }
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        if ( !D('Common/user','Logic')->isOvertime() ) $this->error('账号已过期！');
        $data = array();
        $isAdd = $succ = $error = 0;
        $tbOrderModel = M('tborder');
        foreach ($excelData as $key => $value) {
            $record = $tbOrderModel->where(array('order_num'=>$value[24],'status'=>array('neq',0)))->find();
            if ( $record ) {
                ++$isAdd;
                continue;
            }
            // 组装数据
            $data['order_num'] = $value[24];
            $data['money'] = $value[13];
            $data['now_price'] = $value[12];
            $data['status'] = !$value[12] ? 2 : 1;
            $data['title'] = $value[2];
            $data['shop'] = $value[5];
            $data['g_time'] = $value[0];
            $data['user_id'] = $user_id;
            $status = $tbOrderModel->add($data);
            !$status ? ++$error : ++$succ;
        }
        $msg = "成功".$succ."条，失败".$error."条，已经添加过".$isAdd."条！";
        $this->success($msg);
    }
    /**
     * 导入带优惠卷的淘宝商品
     */
    public function importCoupon(){
        if (!IS_POST) {
            $this->display();die;
        }
        $fileName = $_FILES['upFile']['tmp_name'];
        if (!$fileName) {
            $this->error('请选择要上传的文件！');
        }
        $excelData = $this->_getExcelData($fileName);
        if ( count($excelData)<1 ){
            $this->error('上传文件为空');
        }
        $data = array();
        $isAdd = $succ = $error = 0;
        $couponModel = M('coupon'); 
        foreach ($excelData as $key => $value) {
            $record = $couponModel->where(array('gid'=>$value[0]))->find();
            // 组装数据
            $data['gid'] = $value[0];
            $data['g_name'] = $value[1];
            $data['img_url'] = $value[2];
            $data['detail_url'] = $value[3];
            $data['shop_name'] = $value[10];
            $data['price'] = $value[6];
            $data['sale_num'] = $value[7];
            $data['money_rate'] = $value[8];
            $data['money'] = $value[9];
            $data['buyer'] = $value[12];
            $data['money_url'] = $value[5];
            $data['coupon_total'] = $value[12];
            $data['coupon_num'] = $value[16];
            $data['coupon_money'] = $value[17];
            $data['start_time'] = strtotime( $value[18]);
            $data['end_time'] = strtotime( $value[19]);
            $data['coupon_url'] = $value[21];
            if ( $record ) {
            	++$isAdd;
            	$status = $couponModel->where(array('gid'=>$value[0]))->save($data);
            }else{
            	$status = $couponModel->add($data);
            }
            false === $status ? ++$error : ++$succ;
        }
        $msg = "成功".$succ."条，失败".$error."条，已经添加过".$isAdd."条！";
        $this->success($msg);
    }
    /**
     * 淘宝中奖订单
     */
    public function tbOrder(){
        $order_num = trim(I('o'));
        // 淘宝订单模型
        $tbOrderModel = M('tborder');
        // 奖项
        include_once APP_PATH.'Common/Conf/reward.php'; 
        $map['status'] = array('neq', 0);
        if ($order_num) {  // 用于搜索
            if ( !intval($order_num) ) { // 文字搜索
                $map['title'] = array('like','%'.$order_num.'%');
            }else{  // 单号搜索
                $map['order_num'] = array('like','%'.$order_num.'%');
            }
            $data = $tbOrderModel->where($map)->select();
        }else{
            $userInfo = session('userInfo');
            $map['user_id'] = $userInfo['user_id'];
            // 分页获取数据
            $count = $tbOrderModel->where($map)->count(); 
            $pageSize = 12;
            $Page = new \Think\Page($count,$pageSize);      
            $data = $tbOrderModel->where($map)->order('g_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('page',$Page->show());
        }
        $this->assign('reward',$prize_arr);
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 表示是否已兑奖
     */
    public function setReward(){
        $res = array('status'=>0,'msg'=>'');
        $id = intval(I('post.id'));
        $type = I('type');
        if ( !$id ) {
            $res['msg'] = "参数异常！";
            $this->ajaxReturn($res);
        }
        $tbOrderModel = M('tborder');
        $record = $tbOrderModel->where(array('id'=>$id,'status'=>array('neq',0)))->find(); 
        if ( !$record ) {
            $res['mg'] = "不存在此订单";
            $this->ajaxReturn($res);
        }
        $val = $type == 0 ? 3 : 1;
        $tbOrderModel->where(array('id'=>$id))->save(array('status'=>$val));
        $res['status'] = 1;
        $res['msg'] = "Success";
        $this->ajaxReturn($res);
    }
    /**
     * 手动录入淘宝订单
     */
     public function addOneOrder(){
        if (!IS_POST) {
            $this->display();die;
        }
        $order_num = trim(I('post.order_num'));
        if ( strlen($order_num)!=16 ) {
            $this->error('订单号长度为16位！');
        }
        $tbOrderModel = M('tborder');
        $record = $tbOrderModel->where(array('order_num'=>$order_num,'status'=>array('neq',0)))->find();
        if ( $record ) {
            $this->error('订单已存在！');
        }
        // 组装数据
        $data['user_id'] = $_SESSION['userInfo']['user_id'];
        $data['order_num'] = $order_num;
        $data['money'] = intval(I('post.money'));
        $data['now_price'] = 0;
        $data['status'] = 1;
        $data['title'] = '手动录入的订单';
        $data['shop'] = '手动录入的订单';
        $data['g_time'] = date('Y-m-d H:i:s');
        $status = $tbOrderModel->add($data);
        if ( !$status || !$data['user_id'] ) {
            $this->error('添加失败！');
        }
        $this->success('添加成功，可以参与抽奖！');
    }
    /**
     * 处理订单
     */
    public function dealOrder(){
        $type = I('type');
        $order_id = I('oid');
        $res = array('status'=>0, 'msg'=>'参数异常错误！');
        if ( !in_array($type, array(0,1)) || !$order_id) {
            $res['msg'] = "参数异常！";
            $this->ajaxReturn($res);
        }
        if ( $type == 0 ) { // 删除订单
            $status = M('tborder')->where(array('id'=>$order_id))->save(array('status'=>0));
            $res['status'] = $status === false ? 0 : 1;
            $res['msg'] = $status === false ? '删除失败！' : '删除成功！';
            $this->ajaxReturn($res);
        }
        $this->ajaxReturn($res);
    }

     /**
     * 載入Excel工具类，excel的导入
     * @param string $fileName 上传的文件名
     * @return array $return 返回表格中的数据
     * 2015-08-13
     */
    private function _getExcelData ($fileName) {
        //包含excel工具类
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Reader.Excel5");
        import("Org.Util.PHPExcel.IOFactory",'','.php');

        //实例化PHPExcel对象
        $objReader = \PHPExcel_IOFactory::createReader ( 'Excel5' );
        $objReader->setReadDataOnly ( true );
        $objPHPExcel = $objReader->load ($fileName);

        $objWorksheet = $objPHPExcel->getSheet (0);
        //取得excel的总行数
        $highestRow = $objWorksheet->getHighestRow ();
        //取得excel的总列数
        $highestColumn = $objWorksheet->getHighestColumn ();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString ( $highestColumn );

        $return = array ();
        for($row = 2; $row <= $highestRow; $row++) {
            for($col = 0; $col < $highestColumnIndex; $col++) {
                $return[$row-2][] = $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue ();
            }
        }
        return $return;
    }

}