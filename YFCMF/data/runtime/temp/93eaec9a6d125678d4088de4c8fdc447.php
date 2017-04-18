<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:67:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\\step2.html";i:1489327653;s:72:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\head.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\header.html";i:1489327653;s:74:"E:\wamp\www\rainfer-YFCMF-master\YFCMF/app/install\view\public\footer.html";i:1489327653;}*/ ?>
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
		<section class="section">
			<div class="step">
				<ul class="unstyled">
					<li class="current"><em>1</em>检测环境</li>
					<li><em>2</em>创建数据</li>
					<li><em>3</em>完成安装</li>
				</ul>
			</div>
			<div class="server">
				<table width="100%">
					<tr>
						<td class="td1">环境检测</td>
						<td class="td1" width="25%">推荐配置</td>
						<td class="td1" width="25%">当前状态</td>
						<td class="td1" width="25%">最低要求</td>
					</tr>
					<tr>
						<td>操作系统</td>
						<td>类UNIX</td>
						<td><i class="fa fa-check correct"></i> <?php echo $os; ?></td>
						<td>不限制</td>
					</tr>
					<tr>
						<td>PHP版本</td>
						<td>>5.4.x</td>
						<td><i class="fa fa-check correct"></i> <?php echo $phpversion; ?></td>
						<td>5.4.0</td>
					</tr>
					<tr>
						<td>
							PDO 
							<a href="https://www.baidu.com/s?wd=开启PDO,PDO_MYSQL扩展" target="_blank">
								<i class="fa fa-question-circle question"></i>
							</a>
						</td>
						<td>开启</td>
						<td>
							<?php echo $pdo; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
						<td>
							PDO_MySQL
							<a href="https://www.baidu.com/s?wd=开启PDO,PDO_MYSQL扩展" target="_blank">
								<i class="fa fa-question-circle question"></i>
							</a>
						</td>
						<td>开启</td>
						<td>
							<?php echo $pdo_mysql; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
					<td>
						PHP的curl扩展
					</td>
					<td>开启</td>
					<td>
						<?php echo $curl; ?>
					</td>
					<td>开启</td>
					</tr>
					<tr>
						<td>
							PHP的mbstring扩展
						</td>
						<td>开启</td>
						<td>
							<?php echo $mbstring; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
						<td>file_get_contents</td>
						<td>开启</td>
						<td>
							<?php echo $file_get_contents; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
						<td>session</td>
						<td>开启</td>
						<td>
							<?php echo $session; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
						<td>
							PHP的allow_url_fopen
						</td>
						<td>开启</td>
						<td>
							<?php echo $allow_url_fopen; ?>
						</td>
						<td>开启</td>
					</tr>
					<tr>
						<td>附件上传</td>
						<td>>2M</td>
						<td>
							<?php echo $upload_size; ?>
						</td>
						<td>不限制</td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td class="td1">目录、文件权限检查</td>
						<td class="td1" width="25%">写入</td>
						<td class="td1" width="25%">读取</td>
					</tr>
					<?php if(is_array($checklist) || $checklist instanceof \think\Collection || $checklist instanceof \think\Paginator): if( count($checklist)==0 ) : echo "" ;else: foreach($checklist as $path=>$vo): ?>
						<tr>
							<td>
								./<?php echo $path; ?>
							</td>
							<td>
								<?php if($vo['w']): ?>
									<i class="fa fa-check correct"></i> 可写 
								<?php else: ?>
									<i class="fa fa-remove error"></i> 不可写 
								<?php endif; ?>
							</td>
							<td>
								<?php if($vo['r']): ?>
									<i class="fa fa-check correct"></i> 可读
								<?php else: ?>
									<i class="fa fa-remove error"></i> 不可读
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</table>
			</div>
			<div class="bottom text-center">
				<a href="<?php echo url('install/Index/step2'); ?>" class="btn btn-primary">重新检测</a>
				<a href="<?php echo url('install/Index/step3'); ?>" class="btn btn-primary">下一步</a>
			</div>
		</section>
	</div>
	<div class="footer">
	&copy; 2015-<?php echo date('Y'); ?> <a href="http://www.rainfer.cn" target="_blank">YFCMF</a>雨飞工作室出品
</div>
</body>
</html>