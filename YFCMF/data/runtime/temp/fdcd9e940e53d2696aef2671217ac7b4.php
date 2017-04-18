<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\\step5.html";i:1489327653;s:72:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\head.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\header.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\footer.html";i:1489327653;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>YFCMF安装</title>
<link rel="stylesheet" href="__PUBLIC__/install/css/theme.min.css" />
<link rel="stylesheet" href="__PUBLIC__/install/css/install.css" />
<link rel="stylesheet" href="__PUBLIC__/font-awesome/css/font-awesome.min.css" />

<!--[if !IE]> -->
<script src="__PUBLIC__/others/jquery.min-2.2.1.js"></script>
<!-- <![endif]-->
<!-- 如果为IE,则引入jq1.12.1 -->
<!--[if IE]>
<script src="__PUBLIC__/others/jquery.min-1.12.1.js"></script>
<![endif]-->
</head>
<body>
	<div class="wrap">
		<div class="header">
	<h1 class="logo">YFCMF 安装向导</h1>
	<div class="version"><?php echo \think\Config::get('yfcmf_version'); ?></div>
</div>
		<section class="section">
			<div style="padding: 40px 20px;">
				<div class="text-center">
					<a style="font-size: 18px;">恭喜您，安装完成！</a>
					<br>
					<br>
					<div class="alert alert-danger" style="width: 350px;display: inline-block;">
						为了您站点的安全，安装完成后即可将网站app目录下的“install”文件夹删除!
						另请对/网站目录/app/conf/database.php文件做好备份，以防丢失！
					</div>
					<br>
					<a class="btn btn-success" href="<?php echo url('home/Index/index'); ?>">进入前台</a>
					<a class="btn btn-success" href="<?php echo url('admin/Login/login'); ?>">进入后台</a>
				</div>
			</div>
		</section>
	</div>
	<div class="footer">
	&copy; 2015-<?php echo date('Y'); ?> <a href="http://www.rainfer.cn" target="_blank">YFCMF</a>雨飞工作室出品
</div>
</body>
</html>