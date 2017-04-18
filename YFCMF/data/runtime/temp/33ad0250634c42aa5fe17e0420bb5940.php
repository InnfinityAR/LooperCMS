<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\wamp\www\YFCMF/app/admin\view\flag\ajax_flag_list.html";i:1492404637;}*/ ?>
<?php if(is_array($news) || $news instanceof \think\Collection || $news instanceof \think\Paginator): if( count($news)==0 ) : echo "" ;else: foreach($news as $key=>$v): ?>
	<tr>
		<td class="hidden-xs"><?php echo $v['diyflag_id']; ?></td>
		<td class="hidden-xs"><?php echo $v['diyflag_name']; ?></td>
		<td class="hidden-xs"><?php echo $v['creatdata']; ?></td>
		<td>
			<div class="hidden-sm hidden-xs action-buttons">
				<a class="green" href="<?php echo url('admin/Flag/flag_edit',array('diyflag_id'=>$v['diyflag_id'])); ?>" data-toggle="tooltip" title="修改">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
				</a>
				<a class="red confirm-rst-url-btn" data-info="你确定要删除吗？" href="<?php echo url('admin/Flag/flag_del',array('diyflag_id'=>$v['diyflag_id'],'p'=>input('p',1))); ?>" title="回收站" data-toggle="tooltip">
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
							<a href="<?php echo url('admin/Flag/flag_edit',array('diyflag_id'=>$v['diyflag_id'])); ?>" class="tooltip-success" data-rel="tooltip" title="" data-original-title="修改">
								<span class="green">
									<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('admin/Flag/flag_del',array('diyflag_id'=>$v['diyflag_id'],'p'=>input('p',1))); ?>" data-info="你确定要删除到回收站吗？" class="tooltip-error confirm-rst-url-btn" data-rel="tooltip" title="回收站" data-original-title="回收站">
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
	<td colspan="2" align="left"class="hidden-lg hidden-md hidden-sm"><?php echo $page; ?></td>
	<td colspan="7" align="right" class="hidden-xs"><?php echo $page; ?></td>
</tr>
