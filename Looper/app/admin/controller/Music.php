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
	public function music_upload(){
		if(input("id")){
			$albumnid=input("id");
			$_SESSION["albumnid"]=$albumnid;
		}
		 $count=0;
		if($_FILES){
			$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
			for($i=0;$i<count($_FILES);$i++) {
				$file = $_FILES["file" . $i]["tmp_name"];
				$filename=$_FILES["file" . $i]["name"];
				$size=$_FILES["file" . $i]["size"];
				$fileType = strrchr($filename, '.');
				$path = ROOT_PATH . config('upload_path') . DS . date('Y-m-d') ;
				if (!file_exists($path)){
					mkdir ($path);
				}
				$path = ROOT_PATH . config('upload_path') . DS . date('Y-m-d') . '/' .$filename;
				$move_file = move_uploaded_file($file, $path);
				if ($move_file) {
					$path=str_replace(ROOT_PATH ."/","/",$path);
					$music_url = $path;//写入数据库
					$music = $this->music_header($music_url);
					$artist=$music['Artist'];
					$data1['artist'] =$artist;
					$data1['year'] = $music['Year'];
					$data1['albumtitle'] = $music['AlbumTitle'];
					$albumncover =$this->albumn_cover($music['Title']);
					if($albumncover){
						$data1['music_cover']=$albumncover;
					}
					$filename=$music['Title'];
					$data1['filename'] = $filename;
					$data1['flag_name'] = $music['Genre'];
					$data1['copyright'] = $music['Copyright'];
					$data1['description'] = $music['Description'];
					$data1["fileext"] = $fileType;
					$data1['uptime'] = date("Y-m-d H:i:s", time());
					$data1['filesize'] = $size;
					$data1['path'] = $music_url;
					$data1['albumnid']=$_SESSION["albumnid"];
					$fileid=Db::name('plug_files')->where("artist='$artist' and filename='$filename'")->select();
					if(empty($fileid)){
						$fileid=Db::name('plug_files')->insertGetId($data1);
					}else{
						$count++;
					}
				}
			}
		}
		echo $count;
	}
	public function artistalbumn_list(){
		$artistname=input("artistname");
		$fileid=Db::name('loop_ablumn')->where("artistname='$artistname'")->select();
		echo json_encode($fileid,JSON_UNESCAPED_UNICODE);
	}
	public function alertalbumn_edit(){
		if(input("albumnid")){
			$file=input("albumncover");
			$albumnid=input("albumnid");
			$artistname=input("artistname");
			$albumnname=input("albumnname");
			$albumn["albumncover"]=$file;
			$albumn["artistname"]=$artistname;
			$albumn["albumnname"]=$albumnname;
			$albumn["creationdate"]=date("Y-m-d H:i:s");
			$fileid=Db::name('loop_ablumn')->where("albumnid=$albumnid")->update($albumn);
			if($fileid){
				echo $fileid;
			}
		}
	}
	/*
	 *获取某个专辑中的所有音乐
	 */
	public function music_list(){
		$albumnid=input("albumnid");

		$fileid=Db::name('plug_files')->where("albumnid=$albumnid")->select();
		echo json_encode($fileid,JSON_UNESCAPED_UNICODE);
	}
/*
 * 自动添加专辑
 */
	public function alertalbumn_list(){
		$albumnid=input("albumnid");
		$fileid=Db::name('loop_ablumn')->where("albumnid=$albumnid")->select();
		$news_flag=Db::name('loop_ablumn')->field('albumnstyle')->where("albumnid=$albumnid")->select();
		$news_listarr = explode(",",$news_flag[0]['albumnstyle']);//用于遍历出所有的已选中的风格
		$fileid[1]['news_flag']=$news_listarr;
		$this->assign("news_flag",$news_listarr);
		echo json_encode($fileid,JSON_UNESCAPED_UNICODE);
	}
	public function music_add(){
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
		$count=0;
		if($_FILES){
			for($i=0;$i<count($_FILES);$i++) {
				$file = $_FILES["file" . $i]["tmp_name"];
				$filename=$_FILES["file" . $i]["name"];
				$size=$_FILES["file" . $i]["size"];
				$fileType = strrchr($filename, '.');
				$path = ROOT_PATH . config('upload_path') . DS . date('Y-m-d') ;
				if (!file_exists($path)){
					mkdir ($path);
				}
				$path = ROOT_PATH . config('upload_path') . DS . date('Y-m-d') . '/' .md5($filename.time()).$fileType;
				$move_file = rename($file,$path);
				if ($move_file) {
					$path=str_replace(ROOT_PATH ."/","/",$path);
					$music_url = $path;//写入数据库
					$music = $this->music_header($music_url);
					$artist=$music['Artist'];
					$data1['artist'] =$artist;
					$data1['year'] = $music['Year'];
					$data1['albumtitle'] = $music['AlbumTitle'];
					$albumncover =$this->albumn_cover($music['Title']);
					if($albumncover){
						$data1['music_cover']=$albumncover;
					}
					$filename=$music['Title'];
					$data1['flag_name'] = $music['Genre'];
					$data1['copyright'] = $music['Copyright'];
					$data1['description'] = $music['Description'];
					$data1["fileext"] = $fileType;
					$data1['uptime'] = date("Y-m-d H:i:s", time());
					$data1['filesize'] = $size;
					$data1['path'] = $music_url;
					$result_musiclist=Db::name('plug_files')->where("artist='$artist' and filename='$filename'")->select();
					if(empty($result_musiclist)){
						$fileid=Db::name('plug_files')->insertGetId($data1);
					}else{
						$count++;
					}
					if($fileid) {
						$artistname= $music['Artist'];
						$albumnname = $music['AlbumTitle'];
						$ablumn_list=Db::name('loop_ablumn')->field("albumnid")->where("albumnname='$albumnname'AND artistname='$artistname'")->select();
						if(empty($ablumn_list)){
							$albumncover =$this->albumn_cover($music['Title']);
							if($albumncover){
								$ablumn['albumncover']=$albumncover;
							}
							$ablumn['albumnname']=$music['AlbumTitle'];
							$ablumn['albumnstyle']=$music['Genre'];
							$ablumn['artistname']=$music['Artist'];
							$ablumn['manageid']=$userid;
							$ablumn['creationdate']=date("Y-m-d H:i:s",time());
							$result=Db::name('loop_ablumn')->insertGetId($ablumn);
							if($result){
								$data["albumnid"]=$result;
								Db::name('plug_files')->where("id=$fileid")->update($data);
							}
						}else{
							$albumnid=$ablumn_list[0]["albumnid"];
							$data["albumnid"]=$albumnid;
							$ablumn['creationdate']=date("Y-m-d H:i:s",time());
							Db::name('loop_ablumn')->where("albumnid=$albumnid")->update($ablumn);
							Db::name('plug_files')->where("id=$fileid")->update($data);
						}
					}
				}
			}
		}
	}
	/*
	 * 音乐删除
	 */
	public function music_del(){
		$music_path=$_POST['music_url'];
		$musicdel_result=Db::name('plug_files')->where("path='$music_path'")->delete();
		echo $musicdel_result;
	}
	public function music_list_del(){
		$id=input("albumnid");
		$result=Db::name('plug_files')->where("id=$id")->delete();
		echo $result;
	}
	/*
	 * 专辑列表
	 * */
	public function albumn_list()
	{
        $userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
		$times=input("times");
		if(!empty($times)){
			$oldtimes=date("Y-m-d",strtotime("-1 month - day"));
			$times="creationdate >$oldtimes";
		}
		$ABC=input("letter");
		if(!empty($ABC)){
			$map['artistname']= ['like',"{$ABC}%","and"];
		}
		$diyflag=input('diyflag');
		$con=input('con');
		if(!empty($diyflag)){
			$map['albumnstyle']= ['like',"%{$diyflag}%"];
		}
		$key=input('key');
		if(!empty($key)){
			$btu="artistname LIKE '%{$key}%' or albumnname LIKE '%{$key}%'";
		}
		if(empty($ABC) and empty($diyflag)){
			if($con) {
				$albumn = Db::name('loop_ablumn')
					->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}else{
				if(!empty($key)){
					$albumn=Db::name('loop_ablumn')-> where($btu)->order('creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
				}else{
					$albumn=Db::name('loop_ablumn')->order('creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
				}
			}
		}else{
			if($con) {
				$albumn = Db::name('loop_ablumn')
					->where($map)->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}else{
				if(!empty($key)){
					$albumn=Db::name('loop_ablumn')-> where($btu)->where($map)->order('creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
				}else{
					$albumn=Db::name('loop_ablumn')->where($map)->order('creationdate desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
				}
			}
	}

		$show = $albumn->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
		$this->assign('page',$show);
		$letter=range('A','Z');
		$this->assign('letter',$letter);
		$this->assign('news',$albumn);
        $this->assign('manageid',$userid);
		$diyflag=Db::name('diyflag')->where("pid!=0 and manage_id=-1 and pid=3")->select();
		$this->assign('diyflag',$diyflag);
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
		$diyflag=Db::name('diyflag')->where("pid!=0 and manage_id=-1 and pid=3")->select();
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
		$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
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
			'creationdate'=>date("Y-m-d H:i:s",time()),//专辑创建时间
		);
		//图片字段处理
	//	$diyflag_rusult=Db::name('loop_ablumn')->insert($sl_data);//插入表数据
		$diyflag_rusult=Db::name('loop_ablumn')->insertGetId($sl_data);//插入表数据并返回主键ID
		if($diyflag_rusult){//如果插入成功并根据主键ID将图片插入到yf_plug_files表中
			//获取图片
				if ($info) {
					//写入数据库
					$data['fileext']=$fileType;
					$data['uptime'] = date("Y-m-d H:i:s",time());
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
						$data1['albumtitle']=$music['AlbumTitle'];
						$data1['filename']=$music['Title'];
						$albumncover =$this->albumn_cover($music['Title']);
						if($albumncover){
							$data1['music_cover']=$albumncover;
						}
						$data1['flag_name']=$music['Genre'];
						$data1['copyright']=$music['Copyright'];
						$data1['description']=$music['Description'];
						$fileType=strrchr($info->getFilename(), '.');
						$data1["fileext"]=$fileType;
						$data1['uptime'] = date("Y-m-d H:i:s",time());
						$data1['filesize'] = $info->getSize();
						$data1['path'] = $music_url;
						$data1['albumnid']=$diyflag_rusult;
						Db::name('plug_files')->insert($data1);
					} else {
						$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
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
			$this->error('参数错误',url('admin/Music/albumn_list'));
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
		$diyflag=Db::name('diyflag')->where("pid!=0 and manage_id=-1")->select();//风格
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
			$info = $file->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
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
			'creationdate'=>date("Y-m-d H:i:s",time()),//专辑创建时间
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
					$data['uptime'] = date("Y-m-d H:i:s",time());
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
					$albumncover =$this->albumn_cover($music['Title']);
					if($albumncover){
						$data1['music_cover']=$albumncover;
					}
					$data1['albumtitle']=$music['AlbumTitle'];
					$data1['flag_name']=$music['Genre'];
					$data1['copyright']=$music['Copyright'];
					$data1['description']=$music['Description'];
					$fileType=strrchr($info->getFilename(), '.');
					$data1["fileext"]=$fileType;
					$data1['uptime'] = date("Y-m-d H:i:s",time());
					$data1['filesize'] = $info->getSize();
					$data1['path'] = $music_url;
					$data1['albumnid']=$albumnid;
					Db::name('plug_files')->insert($data1);
				} else {
					$this->error($file->getError(), url('admin/Music/albumn_list'));//否则就是上传错误，显示错误原因
				}
			}
		}

		$this->success('专辑修改成功,返回列表页',url('admin/Music/albumn_list'));
	}
	/*
	 * 专辑添加
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
			$this -> error("请选择删除文章",url('admin/Music/albumn_list',array('p'=>$p)));//判断是否选择了文章ID
		}
		if(is_array($ids)){//判断获取文章ID的形式是否数组
			$where = 'n_id in('.implode(',',$ids).')';
		}else{
			$where = 'n_id='.$ids;
		}
		$news_model=new NewsModel;
		$rst=$news_model->where($where)->setField('news_back',1);//转入回收站
		if($rst!==false){
			$this->success("成功把文章移至回收站！",url('admin/Music/albumn_list',array('p'=>$p)));
		}else{
			$this -> error("删除文章失败！",url('admin/Music/albumn_list',array('p'=>$p)));
		}
	}
}