<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model;
use think\Db;
class Flag extends Base
{
	/*
	 * 对专辑选择的音乐进行插入操作
	 */

	/**
	 * 文章列表
	 */
	public function flag_list()
	{
		$result = Db::name("diyflag")->field("*")->where("pid=0")->select();
		$this->assign("theme", $result);
		$where=input("diyflag");
		$con=input('con');
		if($where){
			if($con) {
				$news = Db::name("diyflag")->field("*")->where("pid=$where")->where("pid!=0")->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);

			}else{
				$news = Db::name("diyflag")->where("pid=$where")->where("pid!=0")->field("*")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}
		}else{
			if($con) {
				$news = Db::name("diyflag")->field("*")->where("pid!=0")->order("$con desc")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);

			}else{
				$news = Db::name("diyflag")->where("pid!=0")->field("*")->paginate(config('paginate.list_rows'), false, ['query' => get_query()]);
			}
		}
		$show = $news->render();
		$show = preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)", "<a href='javascript:ajax_page($1);'>$2</a>", $show);
		$this->assign('page', $show);
		$this->assign('news', $news);
		if (request()->isAjax()) {
			return $this->fetch('ajax_flag_list');
		} else {
			return $this->fetch();
		}
	}

	/**
	 * 添加显示
	 */
	public function flag_add()
	{//查找专辑中的所有音乐
		$result = Db::name("diyflag")->field("*")->where("pid=0")->select();
		$this->assign("theme", $result);
		return $this->fetch();
	}

	/**
	 * 添加操作
	 *
	 */
	public function flag_runadd()
	{
		if (!request()->isAjax()) {
			$this->error('提交方式不正确', url('admin/Flag/flag_list'));
		}
		$fileone = request()->file('pic_one');
		$info = $fileone->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
		if ($info) {
			$img_urlone = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
		}
		$filetwo = request()->file('pic_two');
		$info = $filetwo->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
		if ($info) {
			$img_urltwo = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
		}
		$title = input("news_title");
			$data['pid'] = input("theme");
			$data['diyflag_name'] = $title;
			if($img_urltwo){
				$data['diyflag_img'] = $img_urltwo;
			}
			if($img_urlone){
				$data['diyflag_newimg'] = $img_urlone;
			}
			$data['manage_id'] = 0;
			$data['creatdata'] =date("Y-m-d H:i:s", time());
			$data['userid'] = 0;
		$flag_result = Db::name("diyflag")->insertGetId($data);
		if ($flag_result) {
			$this->success('风格添加成功,继续发布', url('admin/Flag/flag_add'));
		} else {
			$this->success('风格添加成功,返回列表页', url('admin/Flag/flag_list'));
		}
	}

	/**
	 * 编辑显示
	 */
	public function flag_edit()
	{
		$result = Db::name("diyflag")->field("*")->where("pid=0")->select();
		$this->assign("theme", $result);
		$diyflag_id = input('diyflag_id');
		$diyflag_list = Db::name('diyflag')->field("*")
			->where("diyflag_id=$diyflag_id")->select();
		if (empty($diyflag_id)) {
			$this->error('参数错误', url('admin/Flag/flag_list'));
		}
		$this->assign("diyflag_list", $diyflag_list[0]);
		return $this->fetch();
	}

	/**
	 * 编辑操作
	 */
	public function flag_runedit()
	{
		if (!request()->isAjax()) {
			$this->error('提交方式不正确', url('admin/Flag/flag_list'));
		}
		$diyflag_id = input('diyflag_id');
		$fileone = request()->file('pic_one');
		if ($fileone) {
			$info = $fileone->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
			if ($info) {
				$img_urlone = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
			}
			$data['diyflag_newimg'] = $img_urlone;
		}
		$filetwo = request()->file('pic_two');
		if ($filetwo) {
			$info = $filetwo->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
			if ($info) {
				$img_urltwo = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
			}
			$data["diyflag_img"] = $img_urltwo;
		}
		$title = input("news_title");
		$data['diyflag_name'] = $title;
		$data['pid'] = input("theme");
		$flag_result = Db::name("diyflag")->where("diyflag_id=$diyflag_id")->update($data);
		if ($flag_result) {
			$this->success('风格修改成功,返回列表页', url('admin/Flag/flag_list'));
		} else {
			$this->success('风格添加失败,返回列表页', url('admin/Flag/flag_list'));
		}
	}

	public function flag_del()
	{
		$rst = Db::name("diyflag")->where(array('diyflag_id' => input('diyflag_id')))->delete();//删除
		if ($rst !== false) {
			$this->success('风格删除成功', url('admin/Flag/flag_list'));
		} else {
			$this->error("删除风格失败！", url('admin/Flag/flag_list'));
		}
	}
	/**
	 * 文章排序
	 */
	/**
	 * 删除至回收站(单个)
	 */
}