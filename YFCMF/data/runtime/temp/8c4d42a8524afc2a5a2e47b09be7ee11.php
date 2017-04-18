<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"E:\wamp\www\YFCMF/app/admin\view\music\ajax_albumn_list.html";i:1492247092;}*/ ?>
<?php if(is_array($news) || $news instanceof \think\Collection || $news instanceof \think\Paginator): if( count($news)==0 ) : echo "" ;else: foreach($news as $key=>$v): ?>
	<tr>
		<td class="hidden-xs" align="center">
		<label class="pos-rel">
			<input name='albumnid' id="navid" class="ace"  type='checkbox' value='<?php echo $v['albumnid']; ?>'>
			<span class="lbl"></span>
		</label>
	</td>
		<td class="hidden-xs center"><input name="<?php echo $v['albumnid']; ?>" value="<?php echo (isset($v['albumnid']) && ($v['albumnid'] !== '')?$v['albumnid']:50); ?>" class="list_order news_order"/></td>
		<td><a href="<?php echo url('admin/Music/albumn_edit',array('albumnid'=>$v['albumnid'])); ?>" title="<?php echo $v['albumnname']; ?>"><?php echo subtext($v['albumnname'],25); ?></a>【<?php echo (isset($v['member_list_nickname']) && ($v['member_list_nickname'] !== '')?$v['member_list_nickname']:$v['member_list_username']); ?>】</td>
		<!--专辑风格-->
		<td class="hidden-sm hidden-xs">
			<span style="color:#03C"><?php echo $v['albumnstyle']; ?></span>
			</td>
		<td class="hidden-xs"><?php echo $v['creationdate']; ?></td>
		<td>
			<div class="hidden-sm hidden-xs action-buttons">
				<a class="green" href="<?php echo url('admin/Music/albumn_edit',array('albumnid'=>$v['albumnid'])); ?>" data-toggle="tooltip" title="修改">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
				</a>
				<a class="red confirm-rst-url-btn" data-info="你确定要删除吗？" href="<?php echo url('admin/Music/albumn_del',array('albumnid'=>$v['albumnid'],'p'=>input('p',1))); ?>" title="回收站" data-toggle="tooltip">
					<i class="ace-icon fa fa-trash-o bigger-130"></i>
				</a>
			</div>
			<div class="hidden-md hidden-lg">
				<div class="inline position-relative">
					<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
						<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
					</button>
					<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo url('admin/Music/albumn_edit',array('albumnid'=>$v['albumnid'])); ?>" class="tooltip-success" data-rel="tooltip" title="" data-original-title="修改">
								<span class="green">
									<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('admin/Music/albumn_del',array('albumnid'=>$v['albumnid'],'p'=>input('p',1))); ?>" data-info="你确定要删除到回收站吗？" class="tooltip-error confirm-rst-url-btn" data-rel="tooltip" title="回收站" data-original-title="回收站">
								<span class="red">
									<i class="ace-icon fa fa-trash-o bigger-120"></i>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</td>
	</tr>
<?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
	<td align="left" class="hidden-xs center"><button id="btnsubmit" class="btn btn-white btn-yellow btn-sm hidden-xs">删</button> </td>
	<td colspan="2" align="left"class="hidden-lg hidden-md hidden-sm"><?php echo $page; ?></td>
	<td align="left" class="hidden-xs center"><button  id="btnorder" href="<?php echo url('admin/Music/albumn_order'); ?>" class="btn btn-white btn-yellow btn-sm">排序</button></td>
	<td colspan="7" align="right" class="hidden-xs"><?php echo $page; ?></td>
</tr>
