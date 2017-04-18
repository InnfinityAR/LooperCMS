<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:55:"E:\wamp\www\YFCMF/app/admin\view\music\albumn_edit.html";i:1492247815;s:49:"E:\wamp\www\YFCMF/app/admin\view\public\base.html";i:1489327653;s:51:"E:\wamp\www\YFCMF/app/admin\view\public\header.html";i:1491797532;s:53:"E:\wamp\www\YFCMF/app/admin\view\public\left_nav.html";i:1490771066;s:53:"E:\wamp\www\YFCMF/app/admin\view\public\head_nav.html";i:1490763638;s:51:"E:\wamp\www\YFCMF/app/admin\view\public\footer.html";i:1489327653;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>loop后台管理</title>

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="Bookmark" href="__ROOT__/favicon.ico" >
    <link rel="Shortcut Icon" href="__ROOT__/favicon.ico" />
	<!-- bootstrap & fontawesome必须的css -->
	<link rel="stylesheet" href="__PUBLIC__/ace/css/bootstrap.min.css" />
	<link rel="stylesheet" href="__PUBLIC__/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" href="__PUBLIC__/datePicker/bootstrap-datepicker.css" />
	<link rel="stylesheet" href="__PUBLIC__/datetimepicker/bootstrap-datetimepicker.css" />
	<link href="__PUBLIC__/ace/css/multiple-select.css" rel="stylesheet" />

	<!-- 此页插件css -->

	<!-- ace的css -->
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<!-- IE版本小于9的ace的css -->
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace-ie.css" />
	<![endif]-->

	<link rel="stylesheet" href="__PUBLIC__/yfcmf/yfcmf.css" />
	<!-- 此页相关css -->
	
	<!-- ace设置处理的js -->
	<script src="__PUBLIC__/ace/js/ace-extra.js"></script>
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="__PUBLIC__/others/html5shiv.min.js"></script>
	<script src="__PUBLIC__/others/respond.min.js"></script>
	<![endif]-->
    <!-- 引入基本的js -->
    <script type="text/javascript">
        var admin_ueditor_handle = "<?php echo url('admin/Ueditor/upload'); ?>";
        var admin_ueditor_lang ='zh-cn';
    </script>
    <!--[if !IE]> -->
    <script src="__PUBLIC__/others/jquery.min-2.2.1.js"></script>
	<script src="__PUBLIC__/ace/js/multiple-select.js"></script>
    <!-- <![endif]-->
    <!-- 如果为IE,则引入jq1.12.1 -->
    <!--[if IE]>
    <script src="__PUBLIC__/others/jquery.min-1.12.1.js"></script>
	<script src="__PUBLIC__/ace/js/multiple-select.js"></script>
    <![endif]-->

    <!-- 如果为触屏,则引入jquery.mobile -->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='__PUBLIC__/others/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="__PUBLIC__/others/bootstrap.min.js"></script>
	<script src="__PUBLIC__/ace/js/multiple-select.js"></script>
</head>

<body class="no-skin">
<!-- 导航栏开始 -->
<div id="navbar" class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-container" id="navbar-container">
		<!-- 导航左侧按钮手机样式开始 -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button><!-- 导航左侧按钮手机样式结束 -->
		<button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
			<span class="sr-only">Toggle sidebar</span>
			<i class="ace-icon fa fa-dashboard white bigger-125"></i>
		</button>
		<!-- 导航左侧正常样式开始 -->
		<div class="navbar-header pull-left">
			<!-- logo -->
			<a href="<?php echo url('admin/Index/index'); ?>" class="navbar-brand" title="管理后台首页">
				<small>
					<i class="fa fa-leaf"></i>
					YFCMF
				</small>
			</a>
		</div><!-- 导航左侧正常样式结束 -->

		<!-- 导航栏开始 -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="grey">
					<a href="<?php echo url('home/Index/index'); ?>" target="_blank">
						前台首页
					</a>
				</li>

				<li class="purple">
					<a data-info="确定要清理缓存吗？" class="confirm-rst-btn" href="<?php echo url('admin/Sys/clear'); ?>">
						清除缓存
					</a>
				</li>
				
				<?php if(config('lang_switch_on')): ?>
				<li class="grey">
					<?php switch($lang): case "zh-cn": ?><a href="<?php echo url('admin/Index/lang',['lang_s'=>'en']); ?>" class="rst-url-btn">ENGLISH</a><?php break; case "en-us": ?><a href="<?php echo url('admin/Index/lang',['lang_s'=>'cn']); ?>" class="rst-url-btn">简体中文</a><?php break; endswitch; ?>
				</li>
				<?php endif; ?>
				<!-- 用户菜单开始 -->
				<li class="light-blue dropdown-modal">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="<?php echo get_imgurl($admin_avatar,2); ?>" alt="<?php echo session('admin_auth.admin_username'); ?>" />
								<span class="user-info">
									<small>欢迎,</small>
									<?php echo session('admin_auth.admin_username'); ?>
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo url('admin/Admin/profile'); ?>">
								<i class="ace-icon fa fa-user"></i>
								会员中心
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo url('admin/Login/logout'); ?>"  data-info="你确定要退出吗？" class="confirm-btn">
								<i class="ace-icon fa fa-power-off"></i>
								注销
							</a>
						</li>
					</ul>
				</li><!-- 用户菜单结束 -->
			</ul>
		</div><!-- 导航栏结束 -->
	</div><!-- 导航栏容器结束 -->
</div><!-- 导航栏结束 -->

<!-- 整个页面内容开始 -->
<div class="main-container" id="main-container">
	<!-- 菜单栏开始 -->
	<div id="sidebar" class="sidebar responsive sidebar-fixed ace-save-state">
	<script type="text/javascript">
		try{ace.settings.loadState('sidebar')}catch(e){}
	</script>
	<div class="sidebar-shortcuts" id="sidebar-shortcuts">
		<!--左侧顶端按钮-->
		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
			<a class="btn btn-success" href="<?php echo url('admin/News/news_list'); ?>" role="button" title="Loop列表"><i class="ace-icon fa fa-signal"></i></a>
			<a class="btn btn-info" href="<?php echo url('admin/News/news_add'); ?>" role="button" title="添加Loop"><i class="ace-icon fa fa-pencil"></i></a>
			<a class="btn btn-warning" href="<?php echo url('admin/Member/member_list'); ?>" role="button" title="会员列表"><i class="ace-icon fa fa-users"></i></a>
			<a class="btn btn-danger" href="<?php echo url('admin/Sys/sys'); ?>" role="button" title="站点设置"><i class="ace-icon fa fa-cogs"></i></a>
		</div>
		<!--左侧顶端按钮（手机）-->
		<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
			<a class="btn btn-success" href="<?php echo url('admin/News/news_list'); ?>" role="button" title="Loop列表"></a>
			<a class="btn btn-info" href="<?php echo url('admin/News/news_add'); ?>" role="button" title="添加Loop"></a>
			<a class="btn btn-warning" href="<?php echo url('admin/Member/member_list'); ?>" role="button" title="会员列表"></a>
			<a class="btn btn-danger" href="<?php echo url('admin/Sys/sys'); ?>" role="button" title="站点设置"></a>
		</div>
	</div>
	<!-- 菜单列表开始 -->
	<ul class="nav nav-list">
		<!--一级菜单遍历开始-->
		<?php if(is_array($menus) || $menus instanceof \think\Collection || $menus instanceof \think\Paginator): if( count($menus)==0 ) : echo "" ;else: foreach($menus as $key=>$v): if(!empty($v['_child'])): ?>
				<li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>open<?php endif; ?>">
			<a href="javascript:void(0);" class="dropdown-toggle">
				<i class="menu-icon fa <?php echo $v['css']; ?>"></i>
				<span class="menu-text"><?php echo $v['title']; ?></span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu">
				<!--二级菜单遍历开始-->
				<?php if(is_array($v['_child']) || $v['_child'] instanceof \think\Collection || $v['_child'] instanceof \think\Paginator): if( count($v['_child'])==0 ) : echo "" ;else: foreach($v['_child'] as $key=>$vv): if(!empty($vv['_child'])): ?>
						<li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active open<?php endif; ?>">
					<a href="javascript:void(0);" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo $vv['title']; ?>
						<b class="arrow fa fa-angle-down"></b>
					</a>
					<b class="arrow"></b>
					<ul class="submenu">
						<!--三级菜单遍历开始-->
						<?php if(is_array($vv['_child']) || $vv['_child'] instanceof \think\Collection || $vv['_child'] instanceof \think\Paginator): if( count($vv['_child'])==0 ) : echo "" ;else: foreach($vv['_child'] as $key=>$vvv): ?>
							<li class="<?php if((count($menus_curr) >= 3) AND ($menus_curr[2] == $vvv['id'])): ?>active<?php endif; ?>">
							<a href="<?php echo url($vvv['name']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								<?php echo $vvv['title']; ?>
							</a>
							<b class="arrow"></b>
							</li>
						<?php endforeach; endif; else: echo "" ;endif; ?><!--三级菜单遍历结束-->
					</ul>
					</li>
					<?php else: ?>
					<li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active<?php endif; ?>">
					<a href="<?php echo url($vv['name']); ?>">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo $vv['title']; ?>
					</a>
					<b class="arrow"></b>
					</li>
					<?php endif; endforeach; endif; else: echo "" ;endif; ?><!--二级菜单遍历结束-->
			</ul>
			</li>
			<?php else: ?>
			<li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>active<?php endif; ?>">
			<a href="<?php echo url($v['name']); ?>">
				<i class="menu-icon fa fa-caret-right"></i>
				<?php echo $v['title']; ?>
			</a>
			<b class="arrow"></b>
			</li>
			<?php endif; endforeach; endif; else: echo "" ;endif; ?><!--一级菜单遍历结束-->
	</ul><!-- 菜单列表结束 -->

	<!-- 菜单栏缩进开始 -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div><!-- 菜单栏缩进结束 -->
</div>
	<!-- 菜单栏结束 -->

	<!-- 主要内容开始 -->
	<div class="main-content">
		<div class="main-content-inner">
			<!-- 右侧主要内容页顶部标题栏开始 -->
			<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse breadcrumbs-fixed" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
	<div class="nav-wrap-up pos-rel">
		<div class="nav-wrap">
			<ul class="nav nav-list">
				<?php if(($id_curr != '') AND (!empty($menus_child))): if(is_array($menus_child) || $menus_child instanceof \think\Collection || $menus_child instanceof \think\Paginator): if( count($menus_child)==0 ) : echo "" ;else: foreach($menus_child as $key=>$k): ?>
				<li>
					<a href="<?php echo url(''.$k['name'].''); ?>">
					<o class="font12 <?php if($id_curr == $k['id']): ?>rigbg<?php endif; ?>"><?php echo $k['title']; ?></o>
					</a>
					<b class="arrow"></b>
				</li>
				<?php endforeach; endif; else: echo "" ;endif; else: ?>
				<li>
					<a href="<?php echo url('admin/Index/index'); ?>">
						<o class="font12">loop后台系统管理</o>
					</a>
					<b class="arrow"></b>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div><!-- /.nav-list -->
</div>
			<!-- 右侧主要内容页顶部标题栏结束 -->
			<!-- 右侧下主要内容开始 -->
			
	<div class="page-content">
		<!--主题-->
		<div class="page-header">
			<h1>
				您当前操作
				<small>
					<i class="ace-icon fa fa-angle-double-right"></i>
					修改专辑
				</small>
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<form class="form-horizontal ajaxForm2" name="form0" method="post" action="<?php echo url('admin/Music/albumn_runedit'); ?>"  enctype="multipart/form-data">
					<input type="hidden" name="n_id" id="n_id" value="<?php echo $news_list['albumnid']; ?>" />
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 专辑名称：  </label>
						<div class="col-sm-10">
							<input type="text" name="news_title" id="news_title"  value="<?php echo $news_list['albumnname']; ?>"   class="col-xs-10 col-sm-6" required/>
						</div>
					</div>
					<div class="space-4"></div>
					<!--专辑风格未改数据库之前-->
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 专辑属性：  </label>
						<div class="checkbox">
							<?php if(is_array($diyflag) || $diyflag instanceof \think\Collection || $diyflag instanceof \think\Paginator): if( count($diyflag)==0 ) : echo "" ;else: foreach($diyflag as $key=>$diyflag): ?>
								<label id="news_flag_<?php echo $diyflag['diyflag_name']; ?>">
										<input <?php if(is_array($news_flag) || $news_flag instanceof \think\Collection || $news_flag instanceof \think\Paginator): if( count($news_flag)==0 ) : echo "" ;else: foreach($news_flag as $key=>$news_flagv): if($news_flagv == $diyflag['diyflag_name']): ?>checked<?php endif; endforeach; endif; else: echo "" ;endif; ?> class="ace ace-checkbox-2" name="news_flag[]" type="checkbox" id="news_flag_va<?php echo $diyflag['diyflag_name']; ?>" value="<?php echo $diyflag['diyflag_name']; ?>" />
										<span class="lbl"> <?php echo $diyflag['diyflag_name']; ?></span>
								</label>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 封面图片上传： </label>
						<div class="col-sm-10">
							<input type="hidden" name="checkpic" id="checkpic" value="<?php echo $news_list['albumncover']; ?>" />
							<input type="hidden" name="oldcheckpic" id="oldcheckpic" value="<?php echo $news_list['albumncover']; ?>" />
							<a href="javascript:;" class="file" title="点击选择所要上传的图片">
								<input type="file" name="pic_one" id="file0" multiple="multiple"/>
								选择上传文件
							</a>
							<span class="lbl">&nbsp;&nbsp;<img src="<?php echo get_imgurl($news_list['albumncover']); ?>" width="100" height="70" id="img0" ></span>&nbsp;&nbsp;
							<a href="javascript:;" onclick="return backpic('<?php echo get_imgurl($news_list['albumncover']); ?>');" title="还原修改前的图片" class="file">撤销修改</a>
						</div>
					</div>
		<!--多音乐上传-->
		<div class="form-group">
			<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 专辑封面： </label>
			<div class="col-sm-10">
				<span class="btn btn-minier btn-success"  data-toggle="modal" data-target="#myModal">查看已上传的歌单</span>
			</div>
			<div class="space-4"></div>
			<link href="__PUBLIC__/ppy/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
			<script src="__PUBLIC__/ppy/js/fileinput.js" type="text/javascript"></script>
			<script src="__PUBLIC__/ppy/js/fileinput_locale_zh.js" type="text/javascript"></script>
			<div class="col-sm-10 col-sm-offset-2" style="padding-top:5px;">
				<input id="file-5" name="music[]" type="file" class="file"  multiple data-preview-file-type="any" data-upload-url="#" data-preview-file-icon=""><br />
			</div>
		</div>

		<!--多音乐上传-->
		<!---->
		<div class="space-4"></div>
		<div class="clearfix form-actions">
			<div class="col-md-offset-3 col-md-9">
				<button class="btn btn-info" type="submit">
					<i class="ace-icon fa fa-check bigger-110"></i>
					保存
				</button>
				&nbsp; &nbsp; &nbsp;
				<button class="btn" type="reset">
					<i class="ace-icon fa fa-undo bigger-110"></i>
					重置
				</button>
			</div>
		</div>
		<!-- 显示模态框（Modal） -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" style="width:80%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
								aria-hidden="true">×
						</button>
						<h4 class="modal-title" id="myModalLabel">
							操作已上传的音乐
						</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-sm-12">
										<ul>
											<?php if(is_array($pic_list) || $pic_list instanceof \think\Collection || $pic_list instanceof \think\Paginator): $i = 0; $__LIST__ = $pic_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
												<div class="file-preview-frame" data-fileindex="0" id="id<?php echo $i; ?>" style="width: 300px;">
													<audio controls="" style="width: 100%;" >
														<source src="<?php echo get_imgurl($v['path']); ?>" type="audio/mp3">
														<div class="file-preview-other"></div>
													</audio>
													<div class="file-actions">
														<div class="file-footer-buttons">
															<a class="red" href="javascript:;" onclick="return delall(<?php echo $i; ?>,'<?php echo $v['path']; ?>');" title="回收站">
																<i class="ace-icon fa fa-trash-o bigger-130"></i>
															</a>
														</div>
														<div class="clearfix"></div>
													</div>
													<h5><?php echo $v['filename']; ?></h5>
												</div>
											<?php endforeach; endif; else: echo "" ;endif; ?>
										</ul>
									</div>
								</div>
								<div class="space-4"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
				</form>
			</div>
		</div>

			<!-- 右侧下主要内容结束 -->
		</div>
	</div><!-- 主要内容结束 -->
	<!-- 页脚开始 -->
	<div class="footer">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120">
				<span class="blue bolder"><a href="http://www.rainfer.cn" target="_ablank">YFCMF</a></span>
				后台管理系统 &copy; 2015-<?php echo date('Y'); ?>
			</span>
		</div>
	</div>
</div>

	<!-- 页脚结束 -->
	<!-- 返回顶端开始 -->
	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
	<!-- 返回顶端结束 -->
</div><!-- 整个页面内结束 -->

<!-- ace的js,可以通过打包生成,避免引入文件数多 -->
<script src="__PUBLIC__/ace/js/ace.js"></script>
<script src="__PUBLIC__/ace/js/ace.min.js"></script>
<!-- jquery.form、layer、yfcmf的js -->
<script src="__PUBLIC__/others/jquery.form.js"></script>
<script src="__PUBLIC__/others/maxlength.js"></script>
<script src="__PUBLIC__/layer/layer_zh-cn.js"></script>
<script src="__PUBLIC__/datePicker/bootstrap-datepicker.js"></script>
<script src="__PUBLIC__/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="__PUBLIC__/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="__PUBLIC__/yfcmf/yfcmf.js?<?php echo time(); ?>"></script>
<!-- 此页相关插件js -->

	<script>
        if(!$("#news_flag_vaj").attr("checked")){
            $("#pptaddress").hide();
        }
        $("#news_flag_vaj").click(function(){
            $("#pptaddress").toggle(400);
        });
        if(!$("#news_flag_vacp").attr("checked")){
            $("#cpprice").hide();
        }
        $("#news_flag_vacp").click(function(){
            $("#cpprice").toggle(400);
        });
		$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true,
				language:'zh-CN',
			})
	</script>

<!-- 与此页相关的js -->
</body>
</html>
