<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="zh-CN">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1">
    <meta name="renderer" content="webkit">
    <title>卷猪猪-优惠券折扣直播第一站！每天更新千款，纯人工筛选验货，限时限量特卖，全场1折包邮！-卷猪猪</title>
    <meta name="Keywords" content="淘宝优惠券，天猫优惠券，优惠券折扣,9块9包邮,限时特卖,优品折扣,卷猪猪"/>
    <meta name="Description" content="优惠券折扣直播第一站！每天更新千款，纯人工筛选验货，限时限量特卖，全场1折包邮！"/>
    <link rel="stylesheet" href="/juanzhuzhu/Public/css/coupon.css">
    <script src="/juanzhuzhu/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/juanzhuzhu/Public/js/jquery.lazyload.min.js"></script>
    <!--<script src="js/product.js"></script>-->
    <script type="text/javascript">
        $(function() {
            $("img.lazy").lazyload();
        });
        $("img.lazy").lazyload({
            threshold : 0
        });
		
    </script>

</head>
<body>
<div class="header_top">
    <div class="container">
        <p>卷猪猪-优惠券折扣直播第一站！每天更新千款优惠券商品，纯人工筛选验货，限时限量特卖，全场1折包邮！</p>
    </div>
</div>
<div class="header">
    <div class="header_inner">
        <div class="logo"><img src="/juanzhuzhu/Public/images/juanzhuzhu.jpg" height="70px" alt=""></div>
        <div class="search">
        <form>
            <input type="text" class="text" name="kw" <?php if(I('kw')): ?>value="<?php echo I('kw');?>" <?php else: ?>placeholder="外套 女 宽松"<?php endif; ?> >
            <input type="submit" class="searchBtn" value="搜索">
        </form>
        </div>
    </div>
    <div class="nav">

        <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key == 1): ?><a <?php if($key == I('mid',1) && !I('kw')): ?>class="active"<?php endif; ?> href="/juanzhuzhu"><?php echo ($vo["name"]); ?></a>
          <?php else: ?>  
            <a <?php if($key == I('mid',1)): ?>class="active"<?php endif; ?> href="/juanzhuzhu/?mid=<?php echo ($key); ?>"><?php echo ($vo["name"]); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			<a href="http://mp.weixin.qq.com/s?__biz=MzA5MTgyMTU1OA==&mid=501545048&idx=1&sn=38292f6d7fa6821f42ec6bbeb6852374" target="_blank">关于我们</a>
            <a href="<?php echo U('index/reward');?>?chengcong" target="_blank">下单抽奖</a>
    </div>
</div>

<!-- 排序 -->
<div class="sort_out">
    <p class="sort">
        <a href="?mid=<?php echo I('mid',1);?>&sort=all&sid=<?php echo 0 == I('sid',0) ? 1 : 0; ?>" <?php if(I('sort','all') == 'all'): ?>class="cur_sort"<?php endif; ?> >综合排序</a>
        <a href="?mid=<?php echo I('mid',1);?>&sort=sale&sid=<?php echo 0 == I('sid',0) ? 1 : 0; ?>" <?php if(I('sort') == 'sale'): ?>class="cur_sort"<?php endif; ?>>销量排序</a>
        <a href="?mid=<?php echo I('mid',1);?>&sort=price&sid=<?php echo 0 == I('sid',0) ? 1 : 0; ?>" <?php if(I('sort') == 'price'): ?>class="cur_sort"<?php endif; ?>>价格排序</a>
    </p>    
</div>
<!-- 排序结束 -->

<div class="main">
    <ul>

    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="pic">
                <a href="<?php echo U('index/detail',array('id'=>$vo['id']));?>" target="_blank">
                    <img class="lazy" width="253" height="253" data-original="<?php echo ($vo['img_url']); ?>" alt="" />
                </a>
                <b class="is_new"></b>
            </div>
            <h3><a href="<?php echo U('index/detail',array('id'=>$vo['id']));?>"><?php echo ($vo['g_name']); ?></a></h3>
            <div class="text">
                <h5>限时秒杀价：<span><b>￥</b><?php echo ($vo['price']); ?></span></h5>
                <a href="<?php echo U('index/detail',array('id'=>$vo['id'],'quan'=>1));?>" target="_blank"><span>点我领券：<?php echo ($vo['coupon_money']); ?></span></a>
                <!-- <b class="fav"><img style="width:18px;height:16px;" src="/juanzhuzhu/Public/images/fav.png"></b> -->
            </div>
            <div class="info">
                <span><?php echo ($vo['shop_name']); ?></span><span class="sale_num">已售: <b><?php echo ($vo['sale_num']); ?></b> 件</span>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
        
    </ul>
    
    <div class="page"><?php echo ($page); ?></div>
</div>

<!-- 猜你喜欢 -->
<?php if($history): ?><div class="main">
	<h3 style="line-height:80px;color:#00aa88">猜你喜欢</h3>
    <ul>

    <?php if(is_array($history)): $i = 0; $__LIST__ = $history;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="pic">
                <a href="<?php echo U('index/detail',array('id'=>$vo['id']));?>" target="_blank">
                    <img class="lazy" width="253" height="253" data-original="<?php echo ($vo['img_url']); ?>" alt="" />
                </a>
                <b class="is_hot"></b>
            </div>
            <h3><a href="<?php echo U('index/detail',array('id'=>$vo['id']));?>"><?php echo ($vo['g_name']); ?></a></h3>
            <div class="text">
                <h5>限时秒杀价：<span><b>￥</b><?php echo ($vo['price']); ?></span></h5>
                <a href="<?php echo U('index/detail',array('id'=>$vo['id'],'quan'=>1));?>" target="_blank"><span>点我领券：<?php echo ($vo['coupon_money']); ?></span></a>
                <!-- <b class="fav"><img style="width:18px;height:16px;" src="/juanzhuzhu/Public/images/fav.png"></b> -->
            </div>
            <div class="info">
                <span><?php echo ($vo['shop_name']); ?></span><span class="sale_num">已售: <b><?php echo ($vo['sale_num']); ?></b> 件</span>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
        
    </ul>
</div><?php endif; ?>


<div class="footer">
    <p>版权所有 © ilufan.com All Rights Reserved，闽ICP备15026085号-1</p>
</div>


<!-- 到顶部 -->
<a href="javascript:scroll(0,0);" style="position:fixed;right:3%;bottom:5%;">
<img style="width:40px;height:40px;" src="/juanzhuzhu/Public/images/top.png" />
</a>

<!-- 分享按钮  -->
<!-- JiaThis Button BEGIN -->
<div class="jiathis_share_slide jiathis_share_24x24" id="jiathis_share_slide" style="left:0;top:30%">
<div class="jiathis_share_slide_top" id="jiathis_share_title"></div>
<div class="jiathis_share_slide_inner">
<div class="jiathis_style_24x24">
<a class="jiathis_button_tsina"></a>
<a class="jiathis_button_cqq"></a>
<a class="jiathis_button_weixin"></a>
<a class="jiathis_button_qzone"></a>
<a class="jiathis_button_tqq"></a>
<a class="jiathis_button_renren"></a>
<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
<script type="text/javascript">
var jiathis_config = {data_track_clickback:'true'
	,slide:{
		divid:'jiathis_main',
		pos:'left'
	}
};
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=2117308" charset="utf-8"></script>	
<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_slide.js" charset="utf-8"></script>
</div></div></div>
<!-- JiaThis Button END -->
<!-- UJian Button BEGIN -->
	<script type="text/javascript" src="http://v1.ujian.cc/code/ujian.js?type=slideuid=2117308"></script>
<!-- UJian Button END -->

</body>
</html>