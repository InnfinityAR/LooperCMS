<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
namespace app\admin\controller;
use think\Db;
/**
 * 文章模型
 * @package app\admin\model
 */
class Flag extends Model
{
	protected $insert = ['news_hits' => 200];
	public function user()
	{
		return $this->belongsTo('MemberList','member_list_id');
	}
	public function menu()
	{
		return $this->belongsTo('Menu','id');
	}
}
