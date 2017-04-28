<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Diyflag;
use think\Db;
use app\admin\model\News as NewsModel;

class News extends Base
{	/*
	*音乐排序
	*/
	public function music_order(){
		if(input("id")){
			$id=input("id");
		}else{
			$id=input("id1");
		}
		if(input('order_id')){
			$order_id=input('order_id');
			$data["order_id"]=$order_id+1;
		}else{
			$order_id=input('order_id1');
			$data["order_id"]=$order_id-1;
		}
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
		$order_result=Db::name("loop_music")->where("id=$id")->update($data);
		if($order_result){
			$news_flag=Db::name('loop_music')->alias("a")->field("a.id,a.order_id,a.fileid,b.filename,b.artist")
				->join(config('database.prefix').'plug_files b','a.fileid =b.id')
				->where("loopid=0 and userid=$userid")->order("order_id desc")->select();
			echo json_encode($news_flag,JSON_UNESCAPED_UNICODE);
		}
	}
	/*
	 * 音乐列表删除
	*/
	public function music_listdel(){
		$id=input("id");
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
		Db::name("loop_music")->where("id=$id")->delete();
		$news_flag=Db::name('loop_music')->alias("a")->field("a.id,a.order_id,a.fileid,b.filename,b.artist")
			->join(config('database.prefix').'plug_files b','a.fileid =b.id')
			->where("loopid=0 and userid=$userid")->order("order_id desc")->select();
		echo json_encode($news_flag,JSON_UNESCAPED_UNICODE);
	}
	/*
	 * 对专辑选择的音乐进行插入操作
	 */
	public function music_add(){
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
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
					$music_url = $path;
					//写入数据库
					$music = $this->music_header($music_url);
					$data1['artist'] = $music['Artist'];
					$data1['year'] = $music['Year'];
					$data1['albumtitle'] = $music['AlbumTitle'];
					$data1['filename'] = $music['Title'];
					$albumncover =$this->albumn_cover($music['Title']);
					if($albumncover){
						$data1['music_cover']=$albumncover;
					}
					$data1['flag_name'] = $music['Genre'];
					$data1['copyright'] = $music['Copyright'];
					$data1['description'] = $music['Description'];
					$data1["fileext"] = $fileType;
					$data1['uptime'] = date("Y-m-d H:i:s", time());
					$data1['filesize'] = $size;
					$data1['path'] = $music_url;
					$fileid=Db::name('plug_files')->insertGetId($data1);
					if($fileid) {
						$music_add = array(
							'fileid' =>intval($fileid),
							'loopid' =>0,
							'creationdate' => date("Y-m-d H:i:s", time()),
							'userid' => $userid,
							'order_id'=>$i,
						);
						$musicid=Db::name('loop_music')->insertGetId($music_add);
					}
				}
			}
		}
		if(input('fileid')) {
			$fileid = input('fileid');
			if (is_int($fileid)) {
				$music_add = array(
					'fileid' =>intval($fileid),
					'loopid' =>0,
					'creationdate' => date("Y-m-d H:i:s", time()),
					'userid' => $userid,
					'order_id'=>1,
				);
				Db::name('loop_music')->insertGetId($music_add);
			} else {
				$music_add['loopid'] =0;
				$fileid_list = explode(",", "$fileid");
				$i=0;
				foreach ($fileid_list as $fileid) {
					if(!empty($fileid)){
						$i+=1;
						$music_add['fileid'] =intval($fileid);
						$music_add['userid'] = $userid;
						$music_add['order_id']=$i;
						$music_add['creationdate'] = date("Y-m-d H:i:s", time());
						$select_music = Db::name('loop_music')->where("fileid=$fileid and loopid=0 and userid=$userid")->select();
					}
					if (!$select_music) {
						Db::name('loop_music')->insertGetId($music_add);
					}
				}
			}
		}
			Db::name('loop_music')->where("fileid=0")->delete();
			$news_flag=Db::name('loop_music')->alias("a")->field("a.id,a.order_id,a.fileid,b.albumtitle,b.music_cover,b.filename,b.artist")
				->join(config('database.prefix').'plug_files b','a.fileid =b.id')
				->where("loopid=0 and userid=$userid")->order("order_id desc")->select();
			echo json_encode($news_flag,JSON_UNESCAPED_UNICODE);
	}
	public function music_edit(){
		$loopid=$_SESSION["edit_id"];
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
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
					$music_url = $path;
					//写入数据库
					$music = $this->music_header($music_url);
					$data1['artist'] = $music['Artist'];
					$data1['year'] = $music['Year'];
					$data1['albumtitle'] = $music['AlbumTitle'];
					$data1['filename'] = $music['Title'];
					$data1['flag_name'] = $music['Genre'];
					$albumncover =$this->albumn_cover($music['Title']);
					if($albumncover){
						$data1['music_cover']=$albumncover;
					}
					$data1['copyright'] = $music['Copyright'];
					$data1['description'] = $music['Description'];
					$data1["fileext"] = $fileType;
					$data1['uptime'] = date("Y-m-d H:i:s", time());
					$data1['filesize'] = $size;
					$data1['path'] = $music_url;
					$fileid=Db::name('plug_files')->insertGetId($data1);
					if($fileid) {
						$music_add = array(
							'fileid' =>intval($fileid),
							'loopid' =>0,
							'creationdate' => date("Y-m-d H:i:s", time()),
							'userid' => $userid,
							'order_id'=>$i,
						);
						$musicid=Db::name('loop_music')->insertGetId($music_add);
					}
				}
			}
		}
		if(input('fileid')) {
			$fileid = input('fileid');
			if (is_int($fileid)) {
				$music_add = array(
					'fileid' =>intval($fileid),
					'loopid' =>0,
					'creationdate' => date("Y-m-d H:i:s", time()),
					'userid' => $userid,
					'order_id'=>1,
				);
				Db::name('loop_music')->insertGetId($music_add);
			} else {
				$music_add['loopid'] =0;
				$fileid_list = explode(",", "$fileid");
				$i=0;
				foreach ($fileid_list as $fileid) {
					$i+=1;
					$music_add['fileid'] =intval($fileid);
					$music_add['userid'] = $userid;
					$music_add['order_id']=$i;
					$music_add['creationdate'] = date("Y-m-d H:i:s", time());
					$select_music = Db::name('loop_music')->where("fileid=$fileid and loopid=0 and userid=$userid")->select();
					if (!$select_music) {
						Db::name('loop_music')->insertGetId($music_add);
					}
				}
			}
		}
		Db::name('loop_music')->where("fileid=0")->delete();
		$news_flag=Db::name('loop_music')->alias("a")->field("a.id,a.order_id,a.fileid,b.albumtitle,b.music_cover,b.filename,b.artist")
			->join(config('database.prefix').'plug_files b','a.fileid =b.id')
			->where("loopid=0 and userid=$userid")->order("order_id desc")->select();
		echo json_encode($news_flag,JSON_UNESCAPED_UNICODE);
	}
	/*
	 * 管理员新建风格
	 * ajax
	 */
	public function news_flag(){
		$manage_id=$_SESSION["think"]["admin_auth"]["aid"];
		$diyflag_name=input('creat_flag');
		$news_flag=Db::name('diyflag')->where("diyflag_name='$diyflag_name' AND manage_id =$manage_id")->select();
		//查询diyflag中此风格是否存在
		if($news_flag){
			echo json_encode("此风格已存在",JSON_UNESCAPED_UNICODE);
		}else{
			if($manage_id==1){
				$diyflag_order=0;
			}else{
				$diyflag_order=1;
			}
			$sl_data=array(
				'diyflag_name'=>$diyflag_name,
				'manage_id'=>$manage_id,
				'diyflag_order'=>$diyflag_order,
				'creatdata'=>date("Y-m-d H:i:s",time()),
				'pid'=>-1,
			);
			$diyflag_rusult=Db::name('diyflag')->insert($sl_data);
			//Diyflag::create($sl_data);
			if($diyflag_rusult){
				$diyflag_list=Db::name('diyflag')->where("pid!=0")->select();
				echo json_encode($diyflag_list,JSON_UNESCAPED_UNICODE);
			}
		}

	}
    /**
     * Loop列表
     */
	public function news_list()
	{
		$keytype=input('keytype','news_title');
		$key=input('key');
		$opentype_check=input('opentype_check','');
		$diyflag=input('diyflag','');
		//查询：时间格式过滤 获取格式 2015-11-12 - 2015-11-18
		$sldate=input('reservation','');
		$arr = explode(" - ",$sldate);
        if(count($arr)==2){
            $arrdateone=$arr[0];
            $arrdatetwo=$arr[1];
            $map['news_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
		}
		//map架构查询条件数组
//		$map['news_back']= 0;
		if(!empty($key)){
			$map['news_title']= array('like',"%".$key."%");
		}
		$where=$diyflag?"FIND_IN_SET('$diyflag',news_flag)":'';
		$news_model=new NewsModel;
		$con=input('con');
		if(!empty($key)){
			if($con) {
				$news = $news_model->alias("a")->field('a.*,b.*')
					->join(config('database.prefix') . 'member_list b', 'a.news_auto =b.member_list_id')
					->where($map)->where($where)->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}else{
				$news = $news_model->alias("a")->field('a.*,b.*')
					->join(config('database.prefix') . 'member_list b', 'a.news_auto =b.member_list_id')
					->where($map)->where($where)->order('news_time desc')->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);

			}
		}else{
			if($con) {
				$news = $news_model->alias("a")->field('a.*,b.*')
					->join(config('database.prefix') . 'member_list b', 'a.news_auto =b.member_list_id')
					->where($where)->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}else{
				$news = $news_model->alias("a")->field('a.*,b.*')
					->join(config('database.prefix') . 'member_list b', 'a.news_auto =b.member_list_id')
					->where($where)->order('news_time desc')->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);

			}
		}
			$show = $news->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
		$this->assign('page',$show);
		//Loop属性数据
		$diyflag_list=Db::name('diyflag')->select();
		///print_r($diyflag_list);
		$this->assign('diyflag',$diyflag_list);
		//栏目数据
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$this->assign('opentype_check',$opentype_check);
		$this->assign('keytype',$keytype);
		$this->assign('keyy',$key);
		$this->assign('sldate',$sldate);
		$this->assign('diyflag_check',$diyflag);
		$this->assign('news',$news);
		if(request()->isAjax()){
			return $this->fetch('ajax_news_list');
		}else{
			return $this->fetch();
		}
	}
    /**
     * 添加显示
     */
	public function news_add()
	{//查找专辑中的所有音乐
		$manageid=$_SESSION["think"]["admin_auth"]["aid"];
		$albumn_list=Db::name('loop_ablumn')->field("albumnname,albumnid")->select();
		foreach($albumn_list as $v) {
			$albumnid=$v["albumnid"];
			$albumnname=$v["albumnname"];
			$music_list["$albumnname"] = Db::name('loop_ablumn')->alias("a")->field('a.albumnid,a.albumnname,b.filename,b.id')
				->join(config('database.prefix') . 'plug_files b', 'a.albumnid =b.albumnid')->where("a.albumnid=$albumnid")->select();

		}
		$this->assign('music_list',$music_list);
		$news_columnid=input('news_columnid',0,'intval');
	    $menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$manage_id=$_SESSION["think"]["admin_auth"]["aid"];
		$diyflag=Db::name('diyflag')->where("diyflag_order = 0 or manage_id = $manage_id")->select();
		$source=Db::name('source')->select();
		$this->assign('news_columnid',$news_columnid);
        $this->assign('source',$source);
		$this->assign('diyflag',$diyflag);

		return $this->fetch();
	}
    /**
     * 添加操作
	 *
     */
	public function news_runadd()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/News/news_list'));
		}
		//获取图片上传后路径
        $manageid=$_SESSION["think"]["admin_auth"]["aid"];
		$pic_oldlist=input('pic_oldlist');//老多图字符串
//		获取Loop属性
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach($news_flag as $v){
				$flag[]=$v;
			}
		}

//		$flagdata=implode(',',$flag);
        $sl_data=array(
            'name'=>input('news_title'),
            'description'=>htmlspecialchars_decode(input('news_content')),
           'tags'=>$flag,
            'manageId'=>$manageid,
        );
		$fileone = request()->file('pic_one');
		$filetwo = request()->file('pic_two');
		$info1 = $fileone->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
		if ($info1) {
			$img_urlone = config('upload_path') . '/' . date('Y-m-d') . '/' . $info1->getFilename();
    		$sl_data['photo1'] =$this->base64_img($img_urlone);

		}
		$info2 = $filetwo->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
		if ($info2) {
			$img_urltwo = config('upload_path') . '/' . date('Y-m-d') . '/' . $info2->getFilename();
			$sl_data['photo2'] =$this->base64_img($img_urltwo);
		}

		//图片字段处理
		//附加字段
//		$loop_result=Db::name('news')->insertGetId($sl_data);
        $url = "http://api2.innfinityar.com/web/createLoop";
        $ch =curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//要访问的地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//执行结果是否被返回，0是返回，1是不返回
        curl_setopt($ch, CURLOPT_POST, 1);// 发送一个常规的POST请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sl_data));
        $output = curl_exec($ch);//执行并获取数据
        curl_close($ch);
		if($output){
            $this->success('Loop添加成功,继续发布',url('admin/News/news_list'));
        }else{
            $this->success('Loop添加失败,返回列表页',url('admin/News/news_list'));
        }
	}
    /**
     * 编辑显示
     */
	public function news_edit(){
		$n_id = input('n_id');
		$_SESSION["edit_id"]=$n_id;
		$music_list1=Db::name('loop_music')->alias("a")->field('b.path,b.filename')
			->join(config('database.prefix').'plug_files b','a.fileid = b.id')
			->where("a.loopid=$n_id")->select();
		if(!empty($music_list)) {
			$this->assign('pic_list', $music_list1);
			$this->assign('music_list1',$music_list1[0]);
		}else{
			$music_list["path"]="";
			$this->assign('music_list1',$music_list1);
		}
		if (empty($n_id)){
			$this->error('参数错误',url('admin/News/news_list'));
		}
		$manageid=$_SESSION["think"]["admin_auth"]["aid"];
		$albumn_list=Db::name('loop_ablumn')->field("albumnname,albumnid")->select();
		foreach($albumn_list as $v) {
			$albumnid=$v["albumnid"];
			$albumnname=$v["albumnname"];
			$music_list["$albumnname"] = Db::name('loop_ablumn')->alias("a")->field('a.albumnid,a.albumnname,b.filename,b.id')
				->join(config('database.prefix') . 'plug_files b', 'a.albumnid =b.albumnid')->where("a.albumnid=$albumnid")->select();
		}
		$this->assign('music_list',$music_list);
		$news_list=NewsModel::get($n_id);
		$news_extra=json_decode($news_list['news_extra'],true);
		$news_extra['showdate']=($news_extra['showdate']=='')?$news_list['news_time']:$news_extra['showdate'];
		//多图字符串转换成数组
		$text = $news_list['news_pic_allurl'];
		$pic_list = array_filter(explode(",", $text));
		$this->assign('pic_list',$pic_list);
		//栏目数据
		$menu_text=menu_text($this->lang);
		$this->assign('menu',$menu_text);
		$diyflag=Db::name('diyflag')->where("pid!=0")->select();//风格
		$source=Db::name('source')->select();//来源
		$news_flag=Db::name('news')->field('news_flag')->where("n_id=$n_id")->select();
		$news_listarr = explode(",", $news_flag[0]['news_flag']);//用于遍历出所有的已选中的风格
		$this->assign('source',$source);
		$this->assign('news_extra',$news_extra);
		$this->assign('diyflag',$diyflag);
		$this->assign('news_flag',$news_listarr);
		$this->assign('news_list',$news_list);
		return $this->fetch();
	}
    /**
     * 编辑操作
     */
	public function news_runedit()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/News/news_list'));
		}
//		获取文章属性
		$userid=$_SESSION["think"]["admin_auth"]["aid"];//专辑管理员ID
		$news_flag=input('post.news_flag/a');
		$flag=array();
		if(!empty($news_flag)){
			foreach ($news_flag as $v){
				$flag[]=$v;
			}
		}
		$flagdata=implode(',',$flag);
		$n_id=input("n_id");
		$sl_data=array(
			'news_title'=>input('news_title'),
			'news_flag'=>$flagdata,
			'news_source'=>input('news_source',''),
			'news_content'=>htmlspecialchars_decode(input('news_content')),
		);
		//图片字段处理
		$fileone = request()->file('pic_one');
		if ($fileone) {
			$info3 = $fileone->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
			if ($info3) {
				$img_urlone = config('upload_path') . '/' . date('Y-m-d') . '/' . $info3->getFilename();
			}
			$sl_data['news_img'] = $img_urlone;
		}
		$filetwo = request()->file('pic_two');
		if ($filetwo) {
			$info2 = $filetwo->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
			if ($info2) {
				$img_urltwo = config('upload_path') . '/' . date('Y-m-d') . '/' . $info2->getFilename();
			}
			$sl_data["news_img2"] = $img_urltwo;
		}
		$rst=Db::name("news")->where("n_id=$n_id")->update($sl_data);
		$filesid=Db::name('loop_music')->field("id")->where("userid=$userid and loopid=0")->select();
		if($filesid){
			$musicdata["loopid"]=$n_id;
			foreach($filesid as $v){
				$id=$v["id"];
				Db::name('loop_music')->where("id=$id")->update($musicdata);
			}
			Db::name('loop_music')->where("fileid=0")->delete();
		}
		if($rst){
			$this->success('loop修改成功,返回列表页',url('admin/News/news_list'));
		}else{
			$this->error('loop修改失败',url('admin/News/news_list'));
		}
	}
    /**
     * Loop排序
     */
	public function news_order()
	{
		if (!request()->isAjax()){
			$this->error('提交方式不正确',url('admin/News/news_list'));
		}else{
			$list=[];
			foreach (input('post.') as $n_id => $news_order){
				$list[]=['n_id'=>$n_id,'listorder'=>$news_order];
			}
			$news_model=new NewsModel;
			$news_model->saveAll($list);
			$this->success('排序更新成功',url('admin/News/news_list'));
		}
	}
    /**
     * 删除至回收站(单个)
     */
	public function news_del()
	{
		$p=input('n_id');
		$rst=Db::name("news")->where("n_id=$p")->delete();
		if($rst){
			$this->success('Loop删除成功',url('admin/News/news_list',array('p' => $p)));
		}else{
			$this -> error("删除Loop失败！",url('admin/News/news_list',array('p'=>$p)));
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
			$this -> error("请选择删除Loop",url('admin/News/news_list',array('p'=>$p)));//判断是否选择了LoopID
		}
		if(is_array($ids)){//判断获取LoopID的形式是否数组
			$where = 'n_id in('.implode(',',$ids).')';
		}else{
			$where = 'n_id='.$ids;
		}
		$news_model=new NewsModel;
		$rst=$news_model->where($where)->setField('news_back',1);//转入回收站
		if($rst!==false){
			$this->success("删除成功！",url('admin/News/news_list',array('p'=>$p)));
		}else{
			$this -> error("删除Loop失败！",url('admin/News/news_list',array('p'=>$p)));
		}
	}
    /**
     * Loop审核/取消审核
     */
	public function news_state()
	{
		$id=input('x');
		$news_model=new NewsModel;
		$status=$news_model->where(array('n_id'=>$id))->value('news_open');
		if($status==1){
			$statedata = array('news_open'=>0);
			$news_model->where(array('n_id'=>$id))->setField($statedata);
			$this->success('正常');
		}else{
			$statedata = array('news_open'=>1);
			$news_model->where(array('n_id'=>$id))->setField($statedata);
			$this->success('禁用');
		}
	}
	/*
	 * 是否显示在首页
	 */
	public function news_home()
	{
		$id=input('x');
		$news_model=new NewsModel;
		$status=$news_model->where(array('n_id'=>$id))->value('loop_home');
		if($status==1){
			$statedata = array('loop_home'=>0);
			$news_model->where(array('n_id'=>$id))->setField($statedata);
			$this->success('推荐');
		}else{
			$statedata = array('loop_home'=>1);
			$news_model->where(array('n_id'=>$id))->setField($statedata);
			$this->success('正常');
		}
	}
    /**
     * Loop修改栏目
     */
    public function news_columnid()
    {
        $news_columnid=input('news_columnid');
        $n_id=input('n_id');
        $news_model=new NewsModel;
        $data=$news_model->find($n_id);
        if($data){
            $rst=$news_model->where('n_id',$n_id)->update(['news_columnid'=>$news_columnid]);
            if($rst!==false){
                $this->success('更新栏目成功');
            }else{
                $this->error('更新栏目失败');
            }
        }else{
            $this->error('Loop不存在');
        }
    }
    /**
     * 回收站列表
     */
	public function news_back()
	{
		$keytype=input('keytype','news_title');
		$key=input('key');
		$news_l=input('news_l');
		$opentype_check=input('opentype_check','');
		$diyflag=input('diyflag','');
		//查询：时间格式过滤 格式 2015-11-12 - 2015-11-18
		$sldate=input('reservation','');
		$arr = explode(" - ",$sldate);
		if(count($arr)==2){
			$arrdateone=strtotime($arr[0]);
			$arrdatetwo=strtotime($arr[1].' 23:55:55');
			$map['news_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
		}
		//map架构查询条件数组
		$map['news_back']= 1;
		if(!empty($key)){
			$map[$keytype]= array('like',"%".$key."%");
		}
		if ($opentype_check!=''){
			$map['news_open']= array('eq',$opentype_check);
		}
		if (!empty($news_l)){
			$map['news_l']= array('eq',$news_l);
		}
        if(!config('lang_switch_on')){
            $map['news_l']=  $this->lang;
        }
		$where=$diyflag?"FIND_IN_SET('$diyflag',news_flag)":'';
		$news_model=new NewsModel;
		$news=$news_model->alias("a")->field('a.*,b.*,c.menu_name')
				->join(config('database.prefix').'member_list b','a.news_auto =b.member_list_id')
				->join(config('database.prefix').'menu c','a.news_columnid =c.id')->where($map)
				->where($where)->order('news_time desc')->paginate(config('paginate.list_rows'),false,['query'=>get_query()]);
		$show = $news->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
		$this->assign('page',$show);
		//Loop属性数据
		$diyflag_list=Db::name('diyflag')->select();
		$this->assign('diyflag',$diyflag_list);
		$this->assign('opentype_check',$opentype_check);
		$this->assign('keytype',$keytype);
		$this->assign('keyy',$key);
		$this->assign('news_l',$news_l);
		$this->assign('sldate',$sldate);
		$this->assign('diyflag_check',$diyflag);
		$this->assign('news',$news);
		if(request()->isAjax()){
			return $this->fetch('ajax_news_back');
		}else{
			return $this->fetch();
		}
	}
    /**
     * 还原Loop
     */
	public function news_back_open()
	{
		$p=input('p');
		$news_model=new NewsModel;
		$rst=$news_model->where('n_id',input('n_id'))->setField('news_back',0);//转入正常
		if($rst!==false){
			$this->success('Loop还原成功',url('admin/News/news_back',array('p' => $p)));
		}else{
			$this -> error("Loop还原失败！",url('admin/News/news_back',array('p' => $p)));
		}
	}
    /**
     * 彻底删除(单个)
     */
	public function news_back_del()
	{
		$n_id=input('n_id');
		$p = input('p');
		$news_model=new NewsModel;
		if (empty($n_id)){
			$this->error('参数错误',url('admin/News/news_back',array('p' => $p)));
		}else{
			$rst=$news_model->where('n_id',input('n_id'))->delete();
			if($rst!==false){
				$this->success('Loop彻底删除成功',url('admin/News/news_back',array('p' => $p)));
			}else{
				$this -> error("Loop彻底删除失败！",url('admin/News/news_back',array('p' => $p)));
			}
		}
	}
    /**
     * 彻底删除(全选)
     */
	public function news_back_alldel()
	{
		$p = input('p');
		$ids = input('n_id/a');
		if(empty($ids)){
			$this -> error("请选择删除Loop",url('admin/News/news_back',array('p'=>$p)));//判断是否选择了LoopID
		}
		if(is_array($ids)){//判断获取LoopID的形式是否数组
			$where = 'n_id in('.implode(',',$ids).')';
		}else{
			$where = 'n_id='.$ids;
		}
		$news_model=new NewsModel;
		$rst=$news_model->where($where)->delete();
		if($rst!==false){
			$this->success("成功把Loop删除，不可还原！",url('admin/News/news_back',array('p'=>$p)));
		}else{
			$this -> error("Loop彻底删除失败！",url('admin/News/news_back',array('p' => $p)));
		}
	}
}