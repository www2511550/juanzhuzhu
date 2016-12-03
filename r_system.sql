-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-09-18 17:18:33
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `r_system`
--

-- --------------------------------------------------------

--
-- 表的结构 `sys_config`
--

CREATE TABLE IF NOT EXISTS `sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0' COMMENT '账号id',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `status` tinyint(4) DEFAULT '1' COMMENT '1-正常，2-异常',
  `reward` varchar(200) DEFAULT NULL COMMENT '中奖概率',
  `qun_into` text NOT NULL COMMENT '群入口地址',
  `qun_photo` varchar(255) NOT NULL COMMENT '群图片地址',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='中奖页面相关信息设置' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `sys_config`
--

INSERT INTO `sys_config` (`id`, `user_id`, `title`, `status`, `reward`, `qun_into`, `qun_photo`, `addtime`) VALUES
(1, 2, '', 1, '1-1-2-5-150', '&lt;a target=&quot;_blank&quot; href=&quot;http://shang.qq.com/wpa/qunwpa?idkey=77ce309137172ae3a5eba2cf3a336f1961d944b7560901942261fa8c8d9d7aad&quot;&gt;&lt;img border=&quot;0&quot; src=&quot;http://pub.idqqimg.com/wpa/images/group.png&quot; alt=&quot;赣南医学新生交流购物&quot; title=&quot;赣南医学新生交流购物&quot;&gt;&lt;/a&gt;', '', 1474207778);

-- --------------------------------------------------------

--
-- 表的结构 `sys_tborder`
--

CREATE TABLE IF NOT EXISTS `sys_tborder` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `order_num` varchar(16) DEFAULT NULL COMMENT '淘宝订单号',
  `money` decimal(12,2) DEFAULT NULL COMMENT '预估收入',
  `now_price` decimal(12,2) DEFAULT NULL COMMENT '实际付款金额',
  `status` tinyint(4) DEFAULT '1' COMMENT '1-正常，2-失效订单,3-已领奖',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `shop` varchar(20) DEFAULT NULL COMMENT '店铺',
  `g_time` datetime DEFAULT NULL COMMENT '下单时间',
  `reward` tinyint(4) DEFAULT '0' COMMENT '中奖等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='淘宝客订单' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `sys_tborder`
--

INSERT INTO `sys_tborder` (`id`, `user_id`, `order_num`, `money`, `now_price`, `status`, `title`, `shop`, `g_time`, `reward`) VALUES
(1, 2, '2345527829168544', '2.03', '9.90', 3, '绿润活性炭竹炭包新房装修除甲醛除味活性碳木炭家用装修吸甲醛', '绿润旗舰店', '2016-09-14 09:21:49', 5),
(2, 2, '2344338809118544', '3.99', '7.90', 1, '锐巢活性炭包新房椰壳除甲醛装修家用竹碳吸甲醛木炭除味 活性炭', '锐巢旗舰店', '2016-09-14 09:18:34', 0),
(3, 2, '2344332608368544', '0.55', '9.90', 1, '粘毛器水洗衣物除尘滚筒 非可撕式刷毛器粘尘纸 衣服除尘器毛刷', '利临居家日用旗舰店', '2016-09-14 09:17:06', 5),
(4, 2, '2345499821608544', '0.59', '10.80', 1, '小萌主家用防滑成人无痕塑料衣撑干湿两用多功能加粗防风晾挂衣架', '小萌主旗舰店', '2016-09-14 09:14:07', 0),
(5, 2, '2282482308890970', '3.57', '9.90', 1, '韩婵保湿补水面膜卡通动物玻尿酸水光针提亮肤色非美白护肤正品', '韩婵居家日用旗舰店', '2016-09-13 22:02:05', 0),
(6, 2, '2279301307820387', '4.80', '9.90', 1, '指甲油可剥无味 水性指甲油健康环保可撕拉 孕妇儿童可用 包邮', 'newpeptin化妆品旗舰店', '2016-09-12 22:00:27', 0),
(7, 2, '2331082818953231', '0.39', '19.00', 1, '维斯威超轻太阳眼镜女tr90时尚墨镜女款复古大框太阳镜偏光驾驶镜', '维斯威旗舰店', '2016-09-11 00:36:16', 0),
(8, 2, '2269882490932189', '0.00', '0.00', 2, '油头发蜡复古发油发泥定型啫喱膏持久保湿大背头造型碎发整理膏', '陌恋时尚旗舰店', '2016-09-10 10:53:08', 0),
(9, 2, '2327070028760322', '0.00', '0.00', 2, '茶花塑料衣架加厚衣撑子家用晒衣架干湿两用大号衣服架加长被子架', '茶花广州专卖店', '2016-09-09 22:35:59', 0),
(10, 2, '2265127893582184', '9.12', '29.90', 1, '唐纺上品天然 乳胶枕头 颈椎枕 保健枕 成人护颈按摩记忆枕睡眠', '唐纺上品旗舰店', '2016-09-09 08:30:10', 0),
(11, 2, '2188437366593047', '4.04', '8.00', 1, '眼睛框镜架女潮大脸防蓝光复古圆形简约文艺全框近视眼镜框女款', '维斯威旗舰店', '2016-09-08 23:34:49', 0);

-- --------------------------------------------------------

--
-- 表的结构 `sys_user`
--

CREATE TABLE IF NOT EXISTS `sys_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL COMMENT '用户名',
  `pwd` char(32) NOT NULL COMMENT '密码',
  `user_role` tinyint(4) DEFAULT '2' COMMENT '1-管理员，2-普通用户',
  `token` int(11) NOT NULL COMMENT '令牌',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-正常，2-删除，3-过期，4-账号异常',
  `limit_time` int(11) DEFAULT '0' COMMENT '过期时间',
  `addtime` int(11) NOT NULL COMMENT '注册时间',
  `lastLoginTime` datetime NOT NULL COMMENT '最后登录时间',
  `ip` varchar(50) NOT NULL COMMENT 'ip',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `sys_user`
--

INSERT INTO `sys_user` (`user_id`, `username`, `pwd`, `user_role`, `token`, `status`, `limit_time`, `addtime`, `lastLoginTime`, `ip`) VALUES
(1, 'chengcong', '6c341d3ebe69b38047d147c84518a1ff', 1, 1474208014, 1, 0, 1474208014, '0000-00-00 00:00:00', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
