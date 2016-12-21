<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{
	public function __construct(){
		parent::__construct();
		if ( isset($_GET['login']) ) {
			redirect(U('index/login'));
		}
		if( is_mobile() ){
			redirect(U('Wap/index'));
		}
	}

    /**
     * 优惠券
     */
    public function index(){
        // 参数
        $mid = I('mid', 1);
        $kw = I('kw');
        $sid = I('sid');  // 0-低到高，1-高到低
        $sort = I('sort'); // 排序条件
        // 目录
        $menuData = $this->getMenu(); 
        $couponLogic = D('Home/Coupon','Logic');
                
$couponLogic->autoCountCouponNum();

        if ( $kw ) {  // 关键字搜索
            $data = $couponLogic->getSearchData(array('kw'=>$kw,'sid'=>$sid,'sort'=>$sort));
        }else{
            $data = $couponLogic->getMenuData(array('mid'=>$mid,'menuData'=>$menuData,'sid'=>$sid,'sort'=>$sort));
        }
        // 获取浏览纪录
        $history = $this->getHistoryData();
        $this->assign(array('data'=>$data['data'],'page'=>$data['page'],'menu'=>$menuData,'history'=>$history));
        $this->display();
    }
    /**
     * 商品详情页面
     */
    public function detail(){
        $id = I('id');
        $quan = I('quan');
        if ( !$id ) $this->error('商品不存在！',U('index/index'));
        $couponModel = M('coupon');
        $good_info = $couponModel->where(array('id'=>$id))->find();
        if ( !$good_info ) $this->error('商品不存在！',U('index/index'));
        $to_url = $quan ? $good_info['coupon_url'] : $good_info['money_url'];
        // 浏览数
        $updateData = array('look_num'=>++$good_info['look_num'],'id'=>$id);
        $quan && $updateData['look_quan_num'] = $good_info['look_quan_num'] + 1;
        $couponModel->save($updateData);
        // 浏览纪录
        if ( $history = session('history') ) {
            foreach ($history as $k => $val){
                if ( $id == $val['id'] ) {
                    unset($history[$k]);
                }
            }
            array_unshift($history,$good_info);
            session('history',$history);
        }else{
            session('history',array($good_info));
        }
        header("Location:$to_url");
    }
    /**
     * 登陆
     */
    public function login(){
    	if ( !IS_POST) {
    		$this->assign('action','login');
    		$this->display();
    	}else{
    		$username = trim(I('username'));
    		$pwd = trim(I('pwd'));
    		!$username && $this->error('用户名不能为空！');
    		!$pwd && $this->error('密码不能为空！');
    		// 查找当前登陆人信息
    		$info = M('user')->where(array('username'=>$username))->find();
    		!$info && $this->error('用户名不存在！');
    		$info['pwd'] !== md5(md5( C('PWD_KEY').$pwd )) && $this->error('密码错误！');
    		session('user_id', $info['user_id']);
    		session('username', $info['username']);
    		redirect(U('index/index'));
    	}

    }
    /**
     * 注册
     */
    public function register(){
    	if ( !IS_POST) {
    		$this->assign('action','register');
    		$this->display('login');
    	}else{
    		$username = trim(I('username'));
    		$pwd = trim(I('pwd'));
    		!$username && $this->error('用户名不能为空！');
    		!$pwd && $this->error('密码不能为空！');
    		// 查找当前登陆人信息
    		$info = M('user')->where(array('username'=>$username))->find();
    		$info && $this->error('用户名存在，请登录！');
    		$pwdStr = md5(md5( C('PWD_KEY').$pwd ));
    		$insertData = array('username'=>$username,'pwd'=>$pwdStr,'lastLoginTime'=>date('Y-m-d H:i:s'),'addtime'=>time(),'ip'=>get_client_ip() );
    		$status = M('user')->add($insertData);
    		session('user_id', $status);
    		session('username', $username);
    		$status ? $this->success('注册成功！',U('index/index')) : $this->error('注册失败，稍后再试！');
    	}
    }
    /**
     * 重置密码
     */
    public function reset(){
    	if ( !IS_POST) {
    		$this->assign('action','reset');
    		$this->display('login');
    	}else{
    		$username = trim(I('username'));
    		$pwd = trim(I('pwd'));
    		!$username && $this->error('用户名不能为空！');
    		!$pwd && $this->error('密码不能为空！');
    		// 查找当前登陆人信息
    		$info = M('user')->where(array('username'=>$username))->find();
    		!$info && $this->error('用户名不存在！');
    		$info['pwd'] !== md5(md5( C('PWD_KEY').$pwd )) && $this->error('密码错误！');
    		unset($info['pwd']);
    		session('user_id', $info['user_id']);
    		session('username', $info['username']);
    		redirect(U('index/index'));
    	}
    }
    /**
     * 退出
     */
    public function out(){
    	session('user_id', null);
    	session('username', null);
    	$this->success('退出成功！', U('index/index'));
    }
    /**
     * 抽奖
     */
    public function reward(){
        if ( !$this->checkUrl() ) {
            header("Content-Type:text/html;charset=utf8"); 
            if ( $this->errMsg) {
                echo $this->errMsg;die;
            }
            exit('地址不存在，请联系群主！');
        }
        $this->assign('config', $this->getConfig());
        $this->display();
    }
    /**
     * 中奖号
     */
    public function toAward(){
        $res = array('status'=>0,'msg'=>'');
        $order_num = trim(I('post.order_num'));
        if ( !$order_num ) {
            $res['msg'] = "订单号不能为空！";
            $this->ajaxReturn($res);
        }
        if ( strlen($order_num) != 16 ) {
            $res['msg'] = "淘宝订单号长度为16位！";
            $this->ajaxReturn($res);
        }
        $tborderModel = M('tborder');
        $map['order_num'] = $order_num;
        $map['user_id'] = session('home_user_id');
        $record = $tborderModel->where($map)->find();
        if ( !$record || $record['status'] == 2 ) {
            $res['msg'] = "订单号不存在,请联系群主核对！";
            $this->ajaxReturn($res);
        }
        if ( $record['reward'] != 0 || $record['status'] == 3) {
            $res['msg'] = "该订单号已经抽奖，若未领取，可联系群主领取！";
            $this->ajaxReturn($res);
        }
        // 中奖号
        $rewardLogic = D('Common/reward', 'Logic');
        $prize_arr = $rewardLogic->getRate();
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id 
        // 中奖号，佣金较少的给最低
        if ( $record['money']<=1.88 ) {
            $rid = 5;
        }elseif ( $record['money'] <= 8.88 && $rid != 5) {
            $rid = 4;
        }
        $res['yes_num'] = $rid;  
        $res['yes_name'] = "恭喜你获得".$prize_arr[$rid-1]['prize']; //中奖项 
        unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项 
        shuffle($prize_arr); //打乱数组顺序 
        // for($i=0;$i<count($prize_arr);$i++){ 
        //     $pr[] = $prize_arr[$i]['prize'];
        // } 
        // $res['no'] = $pr; 
        // 更新中奖号
        $status = $tborderModel->where(array('id'=>$record['id']))->save(array('reward'=>$rid));
        if ( $status === false ) {
            $res['msg'] = "系统出错，请重新抽奖！";
            $this->ajaxReturn($res);
        }
        $res['status'] = 1;
        $res['msg'] = "Success!";
        $this->ajaxReturn($res);
    }
    // 中奖概率计算
    private function get_rand($proArr){
        $result = "";
        // 概率数组的总概率精度 
        $proSum = array_sum($proArr);
        // 概率数组循环 
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ( $randNum <= $proCur ){
                $result = $key; 
                break;
            }else{
                $proSum -= $proCur; 
            }
        }
        unset($proArr); 
        return $result; 

    }
    /**
     * 获取浏览数据
     */
    public function getHistoryData(){
        $data = session('history');
        $limit = 8;
        $history = array();
        if ( count($data > $limit) ) {
            sort($data);
            foreach ($data as $k => $val){
                if ( $k < $limit) $history[] = $val;
            }
        }
        return $history;
    }
    public function weibo(){
        $this->display();
    }
    /**
     * 定时发送新浪微博消息
     */
    public function sendwb(){
    	// 模型
        $couponModel = M('coupon');
        $couponLogic = D('Home/Coupon','Logic');
        $map['end_time'] = array('gt', time());
        $map['is_send'] = 0 ;
        $map['money'] = array('gt',5);
        // $whereStr = " ( (g_name LIKE '%女%' OR g_name LIKE '%化妆%' ) AND g_name NOT LIKE '%老年%' AND g_name NOT LIKE '%童%' ) ";
        // $record = $couponModel->where($map)->where($whereStr)->order('rand()')->find();
        $record = $couponModel->where($map)->order('rand()')->find();
        // 检测是否有优惠卷
        $num = 1;
        $couponInfo = $couponLogic->checkCoupon($record['id']);
        while ( !$couponInfo['result']['amount'] || $couponInfo['result']['item']['discountPrice'] >299 ){
        	if ( $num > 50 ) break;
        	$record = $couponModel->where($map)->order('rand()')->find();
        	$couponInfo = $couponLogic->checkCoupon($record['id']);
        	$num++;
        }
		// 循环超过50次，需要添加商品
        if ( $num > 50 ) {
        	$result['status'] = 0 ;
        	$result['msg'] = '请添加符合条件商品！' ;
        	$this->ajaxReturn($result);
        }
        // 拼接商品文字
        $content = $record['g_name'] ."
       	价：{$couponInfo['result']['item']['discountPrice']}元;
        卷：{$couponInfo['result']['amount']}元" . $record['coupon_url'];
        
        // 获取图片,2.00TZqY1GQqKObD68a55db5c6ut1o6E - 卷猪网（已上线）
        $img_path = '1.jpg';
        $imgData = $this->http($record['img_url']);
        file_put_contents($img_path, $imgData);
        $params = array(
                'source' => 68377399,
                'pic'=>'@'.$img_path,
                'access_token' => '2.00TZqY1GQqKObD68a55db5c6ut1o6E',
                'status' => $content,
        );
        $url = 'https://api.weibo.com/2/statuses/upload.json';
        $header = array('Content-type:multipart/form-data');
        // 发送请求
        $result = $this->http($url, $params, 'POST', $header, true);
        $data = json_decode($result, true);
        $result = array('g_name'=>$record['g_name'],'update'=>date('Y-m-d H:i:s'));
        if ( $data['user'] ){
            $couponModel->where(array('id'=>$record['id']))->save(array('is_send'=>1));
            $result['status'] = 1 ;
            $this->ajaxReturn($result);
        }else{
            $result['status'] = 0 ;
            $this->ajaxReturn($result);
        }
    }
    /**
     * 发送HTTP请求方法
     * @param  string $url    请求URL
     * @param  array  $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @return array  $data   响应数据
     */
    function http($url, $params, $method = 'GET', $header = array(), $multi = false){
        $opts = array(
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new \Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);p($error);
        curl_close($ch);
        if($error) throw new \Exception('请求发生错误：' . $error);
        return  $data;
    }
    /**
     * 通过百度API市集获取新闻
     */
    public function getNews(){
        $ch = curl_init();
        // get参数
        $getParams = array(
            'channelId' => '5572a109b3cdc86cf39001db',
            'channelName'=> urlencode('国内最新'),
//          'title' => urlencode('上市'),
            'needContent' => 0,  // 是否需要返回正文
            'needHtml' => 0, // 是否需要返回正文的html格式
            'page' => rand(1,20),
        );
        $url = 'http://apis.baidu.com/showapi_open_bus/channel_news/search_news';
        // 添加apikey到header
        $header = array(
                'apikey: 7e38be24baa8332ba0e438609b6b5b71',
        );
        $res = $this->http($url, $getParams,'GET',$header);
        $result= json_decode($res,true);
        // 存储数据
        $data = array();
        $newsModel = M('news');
        foreach ( $result['showapi_res_body']['pagebean']['contentlist'] as $val){
            if ( !$val['havePic'] ) continue;
            $data['title'] = $val['title'];
            $data['description'] = $val['desc'];
            $data['img_url'] = $val['allList'][1]['url'];
            $data['article_url'] = $val['link'];
            $data['source'] = $val['source'];
            $data['publish_time'] = strtotime( $val['pubDate'] );
            $map['title'] = $val['title'];
            $map['publish_time'] = strtotime( $val['publish_time'] );
            $record = $newsModel->where($map)->find();
            if ( $record ) continue;
            $newsModel->add($data);
        }
        return true;
    }
    /**
     * 发送新闻
     */
    public function sendnews(){
        $this->sendPhoto();die;
        $this->getNews(); // 获取新闻
        $newsModel = M('news');
        $map['is_send'] = 0;
        $map['publish_time'] = array('egt',strtotime(date('Y-m-d')));
        $map['img_url'] = array('neq', '');
        $record = $newsModel->where($map)->order('rand()')->find();
        // 拼接新闻文字
        $content = "#新闻早餐#".mb_substr(trim($record['description']), 0,100,'utf-8').$record['article_url']."来源：".$record['source'];
        // 获取图片，app2 － 2.00xv8JIG0OLeuK66aa79d12fs2QWEC ，app3 － 2.00xv8JIGF5Hx9B43c1f207710cp6E8
        $img_path = 'news.jpg';exec('aa');
        $imgData = $this->http($record['img_url']);
        @file_put_contents($img_path, $imgData);
        $params = array(
                'source' => 653729307,
                'pic'=>'@'.$img_path,
                'access_token' => '2.00xv8JIGF5Hx9B43c1f207710cp6E8',
                'status' => $content,
        );
        $url = 'https://api.weibo.com/2/statuses/upload.json';
        $header = array('Content-type:multipart/form-data');
        // 发送请求
        $result = $this->http($url, $params, 'POST', $header, true);
        $data = json_decode($result, true);
        $result = array('title'=>$record['title'],'update'=>date('Y-m-d H:i:s'));
        if ( $data['user'] ){
            $newsModel->where(array('id'=>$record['id']))->save(array('is_send'=>1));
            $this->getNews(); // 获取新闻
            $result['status'] = 1 ;
            $this->ajaxReturn($result);
        }else{
            $result['status'] = 0 ;
            $this->ajaxReturn($result);
        }
    }
    /**
     * 获取美女图片
     * http://www.tianapi.com/
     */
    public function getPhoto(){
        $ch = curl_init();
        // get参数
        $getParams = array(
            'key' => 'e992a3a1b7f415b8376137de4b9700e7',  // API密钥
            'num' => '50',  // 指定返回数量，最大50
            'rand' => '', // 参数值为1则随机获取
            'word' => '写真', // 检索关键词,如‘上海’
            'page' => rand(1,10), // 翻页，每页输出数量跟随num参数
        );
        $url = 'http://api.tianapi.com/meinv/';
        $res = $this->http($url, $getParams,'GET');
        $result= json_decode($res,true);
        // 存储数据
        $data = array();
        $photosModel = M('photos');
        $adsimg = "https://img.alicdn.com/imgextra/i2/220615456/TB2FZntpFXXXXbtXXXXXXXXXXXX_!!220615456.jpg";
        foreach ( $result['newslist'] as $val){
            if ( $adsimg == $val['picUrl']) continue;
            $data['title'] = $val['title'];
            $data['description'] = $val['description'];
            $data['img_url'] = $val['picUrl'];
            $data['detail_url'] = $val['url'];
            $data['ctime'] = strtotime($val['ctime']);
            $data['is_send'] = 0;
            $map['img_url'] = $val['picUrl'];
            $record = $photosModel->where($map)->find();
            if ( $record ) continue;
            $photosModel->add($data);
        }
        return true;
    }
    /**
     * 发送图片
     */
    public function sendPhoto(){
        $this->getPhoto();
        $newsModel = M('photos');
        $map['is_send'] = 0;
        $map['img_url'] = array('neq', '');
        $record = $newsModel->where($map)->order('rand()')->find();
        // 拼接新闻文字
        $content = $record['title'].'
                详情： '.$record['detail_url'];
        // 获取图片，app2 － 2.00xv8JIG0OLeuK66aa79d12fs2QWEC ，app3 － 2.00xv8JIGF5Hx9B43c1f207710cp6E8
        $img_path = 'news.jpg';
        $imgData = $this->http($record['img_url']);
        @file_put_contents($img_path, $imgData);        
        $params = array(
                'source' => 653729307,
                'pic'=>'@'.$img_path,
                'access_token' => '2.00xv8JIG0FryOidc6ac444bcUCQpmC',
                'status' => $content,
        );
        $url = 'https://api.weibo.com/2/statuses/upload.json';
        $header = array('Content-type:multipart/form-data');
        // 发送请求
        $result = $this->http($url, $params, 'POST', $header, true);
        $data = json_decode($result, true);
        $result = array('title'=>$record['title'],'update'=>date('Y-m-d H:i:s'));
        if ( $data['user'] ){
            $newsModel->where(array('id'=>$record['id']))->save(array('is_send'=>1));
            $result['status'] = 1 ;
            $this->ajaxReturn($result);
        }else{
            $result['status'] = 0 ;
            $this->ajaxReturn($result);
        }
    }
    
    public function append(){
    	$page = I('page',1);
    	$mid = I('mid',1);
    	$kw = trim(I('kw'));
    	$couponLogic = D('Home/Coupon','Logic');
    	// 目录
    	$str = '';
    	$menuData = $this->getMenu();
    	if ( $kw ) {
    		$data = $couponLogic->getSearchData(array('kw'=>$kw,'p'=>$page,'is_mobile'=>1));
    	}else{
    		$data = $couponLogic->getMenuData(array('mid'=>$mid,'menuData'=>$menuData,'rows'=>10,'p'=>$page));
    	}
    	if ( $data ) {
    		$data['data'] = $couponLogic->formatData($data['data']);
    		foreach ( $data['data'] as $vo ) {
    			$str .= '<li class="one_goods">
				            <div class="pic">
				                <a href="'.U('index/detail',array('id'=>$vo['id'])).'" target="_blank">
				                    <img class="lazy" width="253" height="253" src="'.$vo['img_url'].'" alt="" />
				                </a>
				                <b class="is_new"></b>
				            </div>
				            <h3><a href="'.U('index/detail',array('id'=>$vo['id'])).'">'.$vo['g_name'].'</a></h3>
				            <div class="text">
				                <h5>限时秒杀价：<span><b>￥</b>'.$vo['price'].'</span></h5>
				                <a href="'.U('index/detail',array('id'=>$vo['id'],'quan'=>1)).'" target="_blank"><span>点我领券：'.$vo['coupon_money'].'</span></a>
				                <!-- <b class="fav"><img style="width:18px;height:16px;" src="__PUBLIC__/images/fav.png"></b> -->
				            </div>
				            <div class="info">
				                <span>'.$vo['shop_name'].'</span><span class="sale_num">已售: <b>'.$vo['sale_num'].'</b> 件</span>
				            </div>
				        </li>';
    		}
    	}
    	$this->ajaxReturn($str);
    }
}