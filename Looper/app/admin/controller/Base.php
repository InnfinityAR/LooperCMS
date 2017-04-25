<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Common;
use app\admin\model\AuthRule;

class Base extends Common 
{
	public function _initialize()
	{
        parent::_initialize();
 		if(!$this->check_admin_login()) $this->redirect('admin/Login/login');//未登录
 		$auth=new AuthRule;
		$id_curr=$auth->get_url_id();
        if(!$auth->check_auth($id_curr)) $this->error('没有权限',url('admin/Index/index'));
		//获取有权限的菜单tree
		$menus=$auth->get_admin_menus();
		//print_r($menus);exit();
		$this->assign('menus',$menus);
		//当前方法倒推到顶级菜单ids数组
		$menus_curr=$auth->get_admin_parents($id_curr);
		$this->assign('menus_curr',$menus_curr);
		//取当前操作菜单父节点下菜单 当前菜单id(仅显示状态)
        $menus_child=$auth->get_admin_parent_menus($id_curr);
		$this->assign('menus_child',$menus_child);
		$this->assign('id_curr',$id_curr);
		$this->assign('admin_avatar',session('admin_auth.admin_avatar'));
	}
	/*
	 * 获取音乐头文件
	 */
	public function music_header($path){
		header('content-type: text/html; charset= utf-8');
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~ E_STRICT & ~ E_WARNING);
		$AE= new \AudioExif();
		$path= "..".__ROOT__.$path;
		// 1. 检查文件是否完整 (only for wma, mp3始终返回 true)
		$AE->CheckSize($path);
		// 2. 读取信息, 返回值由信息组成的数组, 键名解释参见上
		$fileResult=$AE->GetInfo($path);
		$music=array(
			'Artist'=>$fileResult["Artist"],
			'Title'=>$fileResult['Title'],
			'Year'=>$fileResult['Year'],
			'Genre'=>$fileResult['Genre'],
			'AlbumTitle'=>$fileResult['AlbumTitle'],
			'Copyright'=> $fileResult['Copyright'],
			'Description'=> $fileResult['Description'],
		);
		$AE->SetInfo($path, $music);
		return $music;

	}
	/*
	 *获取专辑封面
	 */
	public function albumn_cover($music_name) {
		$file_contents = file_get_contents("http://musicmini.baidu.com/app/search/searchList.php?qword={$music_name}&ie=utf-8");
		$preg='/<th[^>]*>[\s\S]*?<\/th>/';
		preg_match_all($preg,$file_contents,$arr);//获取tr
		$preg='/id=[\"]\w+[\"]/';
		preg_match_all($preg,$arr[0][2],$int);//获取id="";
		preg_match('/\d+/',$int[0][0],$newNum);//获取纯ID
		$musid= $newNum[0];//获取音乐中的ID；
		$file_contents=file_get_contents("http://music.baidu.com/data/music/links?songIds=$musid");
		$aa=json_decode($file_contents,true);
		$music_pic=$aa["data"]["songList"][0]["songPicBig"];//缩略图
		//$music_pic=strtok($music_pic, "@");echo $music_pic; //原图
//		echo "<img src=".$music_pic.">";
		return $music_pic;
	}
}