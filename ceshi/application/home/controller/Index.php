<?php
namespace app\home\controller;
use think\Controller;

class Index extends Controller
{
	function index (){
		return $this->fetch();
	}
}
