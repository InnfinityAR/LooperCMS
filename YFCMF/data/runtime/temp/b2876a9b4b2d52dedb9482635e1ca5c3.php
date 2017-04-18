<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\\step4.html";i:1489327653;s:72:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\head.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\header.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\footer.html";i:1489327653;}*/ ?>
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
			<div class="step">
				<ul class="unstyled">
					<li class="on"><em>1</em>检测环境</li>
					<li class="on"><em>2</em>创建数据</li>
					<li class="current"><em>3</em>完成安装</li>
				</ul>
			</div>
			<div class="install" id="log">
				<ul id="loginner" class="unstyled"></ul>
			</div>
			<div class="bottom text-center">
				<a href="javascript:;"><i class="fa fa-refresh fa-spin"></i>&nbsp;正在安装...</a>
			</div>
		</section>
		<script type="text/javascript">
			function showmsg(content,status){
				var icon='<i class="fa fa-check correct"></i> ';
				if(status=="error"){
					icon ='<i class="fa fa-remove error"></i> ';
				}
				$('#loginner').append("<li>"+icon+content+"</li>");
				$("#log").scrollTop(1000000000);
			}
		</script>
	</div>
	<div class="footer">
	&copy; 2015-<?php echo date('Y'); ?> <a href="http://www.rainfer.cn" target="_blank">YFCMF</a>雨飞工作室出品
</div>
</body>
</html>