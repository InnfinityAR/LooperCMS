<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use think\Db;
class Music extends Base
{

	/*
	 * 音乐删除
	 */
	public function music_del(){
		$music_path=$_POST['music_url'];
		$musicdel_result=Db::name('plug_files')->where("path='$music_path'")->delete();
		echo $musicdel_result;
	}
	/*
	 * 专辑列表
	 * */
	public function albumn_list()
	{
		$keytype=input('albumnname','albumnname');
		$key=input('key');
		$diyflag=input('diyflag','');
		//查询：时间格式过滤 获取格式 2015-11-12 - 2015-11-18
		$sldate=input('reservation','');
		$arr = explode(" - ",$sldate);
		if(count($arr)==2){
			$arrdateone=strtotime($arr[0]);
			$arrdatetwo=strtotime($arr[1].' 23:55:55');
			$map['creationdate'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
		}
		//map架构查询条件数组
		if(!empty($key)){
			if($keytype=='albumnname'){
				$map[$keytype]= array('like',"%".$key."%");
			}else{
				$map[$keytype]= $key;
			}
		}
		$where=$diyflag?"FIND_IN_SET('$diyflag',news_flag)":'';
		//$news_model=new NewsModel;
		//$news=$news_model
		$albumn=Db::name('loop_ablumn')->alias("a")->field('a.*,b.*')
			->join(config('database.prefix').'member_list b','a.manageid =b.member_list_id')
		->where($where)->order('creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
		$show = $albumn->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
		$this->assign('page',$show);
		//文章属性数据
		$diyflag_list=Db::name('diyflag')->select();
		///print_r($diyflag_list);
		$this->assign('diyflag',$diyflag_list);
		//栏目数据
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$this->assign('keytype',$keytype);
		$this->assign('keyy',$key);
		$this->assign('sldate',$sldate);
		$this->assign('diyflag_check',$diyflag);
		$this->assign('news',$albumn);
		if(request()->isAjax()){
			return $this->fetch('ajax_albumn_list');
		}else{
			return $this->fetch();
		}
	}
	/*
	 * 专辑添加
	 */
	public function albumn_add()
	{
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$diyflag=Db::name('diyflag')->select();
		$source=Db::name('source')->select();
		$this->assign('source',$source);
		$this->assign('diyflag',$diyflag);
		return $this->fetch();
	}
		public function albumn_runadd()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/Music/albumn_list'));
		}
		//获取专辑封面
		$file = request()->file('pic_one');
		$fileType=substr(strrchr($_FILES['pic_one']['type'], '/'),1);
		$fileOldName=$_FILES['pic_one']['name'];
		$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'),"");
		if ($info) {
			$img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
		}
		//获取专辑风格
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach ($news_flag as $v){
				$flag[]=$v;
			}
		}
		$flagdata=implode(',',$flag);
		$sl_data=array(
			'manageid'=>$_SESSION["think"]["admin_auth"]["aid"],//专辑管理员ID
			'albumnname'=>input('news_title'),//专辑名称
			'albumnstyle'=>$flagdata,//专辑风格
			'albumncover'=>$img_url,//专辑封面
			'artistname'=>input('artistname'),
			'creationdate'=>date("y-m-d h:i:s",time()),//专辑创建时间
		);
		//图片字段处理
	//	$diyflag_rusult=Db::name('loop_ablumn')->insert($sl_data);//插入表数据
		$diyflag_rusult=Db::name('loop_ablumn')->insertGetId($sl_data);//插入表数据并返回主键ID
		if($diyflag_rusult){//如果插入成功并根据主键ID将图片插入到yf_plug_files表中
			//获取图片
				if ($info) {
					//写入数据库
					$data['fileext']=$fileType;
					$data['uptime'] = time();
					$data['filesize'] = $info->getSize();
					$data['path'] = $img_url;
					$data['filename']=$fileOldName;
					$data['albumnid']=$diyflag_rusult;
					Db::name('plug_files')->insert($data);
				} else {
					$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
				}
			$files= request()->file('music');
			//上传处理
			if ($files) {
				foreach ($files as $file) {
					$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
					if ($info) {
						$music_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
						//写入数据库
						$music=$this->music_header($music_url);
						$data1['artist']=$music['Artist'];
						$data1['year']=$music['Year'];
						$data1['albumTitle']=$music['AlbumTitle'];
						$data1['filename']=$music['Title'];
						$data1['flag_name']=$music['Genre'];
						$data1['copyright']=$music['Copyright'];
						$data1['description']=$music['Description'];
						$fileType=strrchr($info->getFilename(), '.');
						$data1["fileext"]=$fileType;
						$data1['uptime'] = date("y-m-d h:i:s",time());
						$data1['filesize'] = $info->getSize();
						$data1['path'] = $music_url;
						$data1['albumnid']=$diyflag_rusult;
						Db::name('plug_files')->insert($data1);
					} else {
						$this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
					}
				}
			}
		}
		$continue=input('continue',0,'intval');
		if($continue){
			$this->success('专辑添加成功,继续发布',url('admin/Music/albumn_add',['news_columnid'=>input('news_columnid')]));
		}else{
			$this->success('专辑添加成功,返回列表页',url('admin/Music/albumn_list'));
		}
	}
	public function albumn_edit()
	{
		$albumnid = input('albumnid');//获取专辑ID
		if (empty($albumnid)){
			$this->error('参数错误',url('admin/News/news_list'));
		}

		//多图字符串转换成数组
		$music_list=Db::name('loop_ablumn')->alias("a")->field('b.path,b.filename')
			->join(config('database.prefix').'plug_files b','a.albumnid = b.albumnid')
			->where("a.albumnid=$albumnid")->select();
		if(!empty($music_list)) {
			$this->assign('pic_list', $music_list);
			$this->assign('music_list',$music_list[0]);
		}else{
			$music_list["path"]="";
			$this->assign('music_list',$music_list);
		}
		if (empty($albumnid)){
			$this->error('参数错误',url('admin/Music/albumn_list'));
		}
		$ablumn_list=Db::name('loop_ablumn')->field("*")->where("albumnid=$albumnid")->select();
		$diyflag=Db::name('diyflag')->select();//风格
		$news_flag=Db::name('loop_ablumn')->field('albumnstyle')->where("albumnid=$albumnid")->select();
		$news_listarr = explode(",", $news_flag[0]['albumnstyle']);//用于遍历出所有的已选中的风格
		$this->assign('diyflag',$diyflag);
		$this->assign('news_flag',$news_listarr);
		$this->assign('news_list',$ablumn_list[0]);



		return $this->fetch();
	}
	/**
	 * 编辑操作
	 */
	public function albumn_runedit()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/Music/albumn_list'));
		}
		//获取专辑封面
		$file = request()->file('pic_one');
		if(!empty($file)){
			$fileType=$_FILES['pic_one']['type'];
			$fileOldName=$_FILES['pic_one']['name'];
			$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'),"");
			if ($info) {
				$img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();

			}
		}else{
			$img_url=input("oldcheckpic");
		}
		//获取专辑风格
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach ($news_flag as $v){
				$flag[]=$v;
			}
		}
		$flagdata=implode(',',$flag);
		$albumnid=input("n_id");
		$sl_data=array(
			'manageid'=>$_SESSION["think"]["admin_auth"]["aid"],//专辑管理员ID
			'albumnname'=>input('news_title'),//专辑名称
			'albumnstyle'=>$flagdata,//专辑风格
			//'albumncover'=>$img_url,//专辑封面
			'artistname'=>input('artistname'),
			'creationdate'=>date("y-m-d h:i:s",time()),//专辑创建时间
		);
		//图片字段处理
		//	$diyflag_rusult=Db::name('loop_ablumn')->insert($sl_data);//插入表数据
		$diyflag_rusult=Db::name('loop_ablumn')->where("albumnid=$albumnid")->update($sl_data);//插入表数据并返回主键ID
		if(!empty($file)) {
			if ($diyflag_rusult) {//如果插入成功并根据主键ID将图片插入到yf_plug_files表中
				//获取图片
				$picID = Db::name('plug_files')->field("id")->where("albumnid=$albumnid and fileext='jpeg'")->select();
				//jpeg
				$picID = $picID['id'];
				if ($info) {
					//写入数据库
					$data['fileext'] = $fileType;
					$data['uptime'] = time();
					$data['filesize'] = $info->getSize();
					$data['path'] = $img_url;
					$data['filename'] = $fileOldName;
					$data['albumnid'] = $albumnid;
					Db::name('plug_files')->where("id=$picID")->update($data);
				} else {
					$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
				}
			}
		}
		$files= request()->file('music');
		//上传处理
		if ($files) {
			foreach ($files as $file) {
				$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
				if ($info) {
					$music_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
					//写入数据库
					$music=$this->music_header($music_url);
					$data1['artist']=$music['Artist'];
					$data1['year']=$music['Year'];
					$data1['filename']=$music['Title'];
					$data1['albumtitle']=$music['AlbumTitle'];
					$data1['flag_name']=$music['Genre'];
					$data1['copyright']=$music['Copyright'];
					$data1['description']=$music['Description'];
					$fileType=strrchr($info->getFilename(), '.');
					$data1["fileext"]=$fileType;
					$data1['uptime'] = date("y-m-d h:i:s",time());
					$data1['filesize'] = $info->getSize();
					$data1['path'] = $music_url;
					$data1['albumnid']=$albumnid;
					Db::name('plug_files')->insert($data1);
				} else {
					$this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
				}
			}
		}

		$this->success('专辑修改成功,返回列表页',url('admin/Music/albumn_list'));
	}
   /*
     * 文章排序
     */
	public function music_list()
	{
		$keytype=input('albumnname','albumnname');
		$key=input('key');
		$albumn=Db::name('loop_music')->alias("mu")->field('mu.id,mu.creationdate,ab.albumnname,fl.filename,fl.artist,fl.year,ne.news_flag')
			->join(config('database.prefix').'plug_files fl','mu.fileid =fl.id')
			->join(config('database.prefix').'news ne','ne.n_id=mu.loopid')
			->join(config('database.prefix').'loop_ablumn ab','ab.albumnid=fl.albumnid')
			->order('mu.creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
		$show = $albumn->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
		$this->assign('page',$show);
		//文章属性数据
		$diyflag_list=Db::name('diyflag')->select();
		///print_r($diyflag_list);
		$this->assign('diyflag',$diyflag_list);
		//栏目数据
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$this->assign('keytype',$keytype);
		$this->assign('keyy',$key);
		$this->assign('news',$albumn);
		if(request()->isAjax()){
			return $this->fetch('ajax_music_list');
		}else{
			return $this->fetch();
		}
	}
	/*
	 * 专辑添加
	 */
	public function music_add()
	{
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$diyflag=Db::name('diyflag')->select();
		$source=Db::name('source')->select();
		$this->assign('source',$source);
		$this->assign('diyflag',$diyflag);
		return $this->fetch();
	}
	public function music_runadd()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/Music/albumn_list'));
		}
		//获取专辑封面
		$file = request()->file('pic_one');
		$fileType=substr(strrchr($_FILES['pic_one']['type'], '/'),1);
		$fileOldName=$_FILES['pic_one']['name'];
		$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'),"");
		if ($info) {
			$img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
		}
		//获取专辑风格
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach ($news_flag as $v){
				$flag[]=$v;
			}
		}
		$flagdata=implode(',',$flag);
		$sl_data=array(
			'manageid'=>$_SESSION["think"]["admin_auth"]["aid"],//专辑管理员ID
			'albumnname'=>input('news_title'),//专辑名称
			'albumnstyle'=>$flagdata,//专辑风格
			'albumncover'=>$img_url,//专辑封面
			'artistname'=>input('artistname'),
			'creationdate'=>date("y-m-d h:i:s",time()),//专辑创建时间
		);
		//图片字段处理
		//	$diyflag_rusult=Db::name('loop_ablumn')->insert($sl_data);//插入表数据
		$diyflag_rusult=Db::name('loop_ablumn')->insertGetId($sl_data);//插入表数据并返回主键ID
		if($diyflag_rusult){//如果插入成功并根据主键ID将图片插入到yf_plug_files表中
			//获取图片
			if ($info) {
				//写入数据库
				$data['fileext']=$fileType;
				$data['uptime'] = time();
				$data['filesize'] = $info->getSize();
				$data['path'] = $img_url;
				$data['filename']=$fileOldName;
				$data['albumnid']=$diyflag_rusult;
				Db::name('plug_files')->insert($data);
			} else {
				$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
			}
			$files= request()->file('music');
			//上传处理
			if ($files) {
				foreach ($files as $file) {
					$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'),"");
					if ($info) {
						$music_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
						//写入数据库
						$music=$this->music_header($music_url);
						$data1['artist']=$music['Artist'];
						$data1['year']=$music['Year'];
						$data1['filename']=$music['Title'];
						$data1['albumtitle']=$music['AlbumTitle'];
						$fileType=strrchr($info->getFilename(), '.');
						$data1["fileext"]=$fileType;
						$data1['uptime'] = date("y-m-d h:i:s",time());
						$data1['filesize'] = $info->getSize();
						$data1['path'] = $music_url;
						$data1['albumnid']=$diyflag_rusult;
						Db::name('plug_files')->insert($data1);
					} else {
						$this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
					}
				}
			}
		}
		$continue=input('continue',0,'intval');
		if($continue){
			$this->success('专辑添加成功,继续发布',url('admin/Music/albumn_add',['news_columnid'=>input('news_columnid')]));
		}else{
			$this->success('专辑添加成功,返回列表页',url('admin/Music/albumn_list'));
		}
	}
	public function music_edit()
	{
		$id = input('id');//获取专辑ID
		if (empty($id)){
			$this->error('参数错误',url('admin/Music/music_list'));
		}

		//多图字符串转换成数组
		$music_list=Db::name('loop_music')->alias("a")->field('b.path,b.filename,b.albumnid')
			->join(config('database.prefix').'plug_files b','a.fileid = b.id')
			->where("a.id=$id")->select();
		if(!empty($music_list[0]["albumnid"])){
			$albumnid=$music_list[0]["albumnid"];
			$music_cover=Db::name('plug_files')->field('path')->where("albumnid=$albumnid and fileext='jpeg'")->select();
			if($music_cover){
				$this->assign('music_cover',$music_cover[0]);
			}else{
				$music_cover[0]["path"]=1;
				$this->assign('music_cover',$music_cover[0]);
			}
		}
		if(!empty($music_list)) {
			$this->assign('pic_list', $music_list);
			$this->assign('music_list',$music_list[0]);
		}else{
			$music_list["path"]="";
			$this->assign('music_list',$music_list);
		}
		if (empty($id)){
			$this->error('参数错误',url('admin/Music/music_list'));
		}
		$ablumn_list=Db::name('loop_music')->field("*")->where("id=$id")->select();
		$diyflag=Db::name('diyflag')->select();//风格
		$news_flag=Db::name('loop_music')->alias("a")->field('b.news_flag')
			->join(config('database.prefix').'news b','a.loopid = b.n_id')
			->where("a.id=$id")->select();
		$news_listarr = explode(",", $news_flag[0]['news_flag']);//用于遍历出所有的已选中的风格
		$this->assign('diyflag',$diyflag);
		$this->assign('news_flag',$news_listarr);
		$this->assign('news_list',$ablumn_list[0]);
		return $this->fetch();
	}
	/**
	 * 编辑操作
	 */
	public function music_runedit()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/Music/music_list'));
		}
		//获取专辑封面
		$file = request()->file('pic_one');
		if(!empty($file)){
			$fileType=$_FILES['pic_one']['type'];
			$fileOldName=$_FILES['pic_one']['name'];
			$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'),"");
			if ($info) {
				$img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();

			}
		}else{
			$img_url=input("oldcheckpic");
		}
		//获取专辑风格
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach ($news_flag as $v){
				$flag[]=$v;
			}
		}
		$flagdata=implode(',',$flag);
		$albumnid=input("n_id");
		$sl_data=array(
			'manageid'=>$_SESSION["think"]["admin_auth"]["aid"],//专辑管理员ID
			'albumnname'=>input('news_title'),//专辑名称
			'albumnstyle'=>$flagdata,//专辑风格
			//'albumncover'=>$img_url,//专辑封面
			'artistname'=>input('artistname'),
			'creationdate'=>date("y-m-d h:i:s",time()),//专辑创建时间
		);
		//图片字段处理
		//	$diyflag_rusult=Db::name('loop_ablumn')->insert($sl_data);//插入表数据
		$diyflag_rusult=Db::name('loop_ablumn')->where("albumnid=$albumnid")->update($sl_data);//插入表数据并返回主键ID
		if(!empty($file)) {
			if ($diyflag_rusult) {//如果插入成功并根据主键ID将图片插入到yf_plug_files表中
				//获取图片
				$picID = Db::name('plug_files')->field("id")->where("albumnid=$albumnid and fileext='jpeg'")->select();
				//jpeg
				$picID = $picID['id'];
				if ($info) {
					//写入数据库
					$data['fileext'] = $fileType;
					$data['uptime'] = time();
					$data['filesize'] = $info->getSize();
					$data['path'] = $img_url;
					$data['filename'] = $fileOldName;
					$data['albumnid'] = $albumnid;
					Db::name('plug_files')->where("id=$picID")->update($data);
				} else {
					$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
				}
			}
		}
		$files= request()->file('music');
		//上传处理
		if ($files) {
			foreach ($files as $file) {
				$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
				if ($info) {
					$music_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
					//写入数据库
					$music=$this->music_header($music_url);
					$data1['artist']=$music['Artist'];
					$data1['year']=$music['Year'];
					$data1['filename']=$music['Title'];
					$data1['flag_name']=$music['Genre'];
					$data1['copyright']=$music['Copyright'];
					$data1['description']=$music['Description'];
					$fileType=strrchr($info->getFilename(), '.');
					$data1["fileext"]=$fileType;
					$data1['uptime'] = date("y-m-d h:i:s",time());
					$data1['filesize'] = $info->getSize();
					$data1['path'] = $music_url;
					$data1['albumnid']=$albumnid;
					Db::name('plug_files')->insert($data1);
				} else {
					$this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
				}
			}
		}

		$this->success('专辑修改成功,返回列表页',url('admin/Music/albumn_list'));
	}
	/**
	 * 删除至回收站(单个)
	 */
	public function albumn_del()
	{
		$rst=Db::name("loop_ablumn")->where(array('albumnid'=>input('albumnid')))->delete();//删除
		if($rst!==false){
			$this->success('删除成功',url('admin/Music/albumn_list'));
		}else{
			$this -> error("删除文章失败！",url('admin/Music/albumn_list'));
		}
	}
	/**
	 * 删除至回收站(全选)
	 */
	public function news_alldel()
	{
		$p = input('p');
		$ids = input('n_id/a');
		if(empty($ids)){
			$this -> error("请选择删除文章",url('admin/News/news_list',array('p'=>$p)));//判断是否选择了文章ID
		}
		if(is_array($ids)){//判断获取文章ID的形式是否数组
			$where = 'n_id in('.implode(',',$ids).')';
		}else{
			$where = 'n_id='.$ids;
		}
		$news_model=new NewsModel;
		$rst=$news_model->where($where)->setField('news_back',1);//转入回收站
		if($rst!==false){
			$this->success("成功把文章移至回收站！",url('admin/News/news_list',array('p'=>$p)));
		}else{
			$this -> error("删除文章失败！",url('admin/News/news_list',array('p'=>$p)));
		}
	}
}