<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\\index.html";i:1489327653;s:72:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\head.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\header.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\footer.html";i:1489327653;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>YFCMF安装</title>
<link rel="stylesheet" href="__PUBLIC__/install/css/theme.min.css" />
<link rel="stylesheet" href="__PUBLIC__/install/css/install.css" />
<link rel="stylesheet" href="__PUBLIC__/font-awesome/css/font-awesome.min.css" />

</head>
<body>
	<div class="wrap">
		<div class="header">
	<h1 class="logo">YFCMF 安装向导</h1>
	<div class="version"><?php echo \think\Config::get('yfcmf_version'); ?></div>
</div>
		<div class="section">
			<div class="main">
				<pre class="agreement">YFCMF软件使用协议

版权所有 ©2015-<?php echo date("Y"); ?>,YFCMF开源社区
感谢您选择YFCMF内容管理框架, 希望我们的产品能够帮您把网站发展的更快、更好、更强！
YFCMF遵循Apache2开源协议发布，并提供免费使用。
YFCMF建站系统由雨飞工作室(官网http://www.rainfer.cn)发起并开源发布。
Apache Licence是著名的非盈利开源组织Apache采用的协议。
该协议鼓励代码共享和尊重原作者的著作权，允许代码修改，再作为开源或商业软件发布。需要满足的条件： 
1． 需要给代码的用户一份Apache Licence ；
2． 如果你修改了代码，需要在被修改的文件中说明；
3． 在延伸的代码中（修改和有源代码衍生的代码中）需要带有原来代码中的协议，商标，专利声明和其他原来作者规定需要包含的说明；
4． 如果再发布的产品中包含一个Notice文件，则在Notice文件中需要带有本协议内容。你可以在Notice中增加自己的许可，但不可以表现为对Apache Licence构成更改。 
具体的协议参考：http://www.apache.org/licenses/LICENSE-2.0

YFCMF免责声明
  1、使用YFCMF构建的网站的任何信息内容以及导致的任何版权纠纷和法律争议及后果，YFCMF官方不承担任何责任。
  2、您一旦安装使用YFCMF，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。</pre>
			</div>
			<div class="bottom text-center">
				<a href="<?php echo url('install/Index/step2'); ?>" class="btn btn-primary">接受</a>
			</div>
		</div>
	</div>
	<div class="footer">
	&copy; 2015-<?php echo date('Y'); ?> <a href="http://www.rainfer.cn" target="_blank">YFCMF</a>雨飞工作室出品
</div>
</body>
</html>