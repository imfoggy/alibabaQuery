<?php
namespace Foggy;
use GuzzleHttp\Psr7\Response;
use QL\QueryList;

class AlibabaQuery {

	/**
	 * 执行
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @return [type]            [description]
	 */
	public function run(){
		$this->getInputUrl();
	}

	/**
	 * 获取用户输入的链接地址
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @return [type]            [description]
	 */
	protected function getInputUrl(){
		std:
		fwrite (STDOUT,"\r\n please input a url address :");
		$url = trim(fgets(STDIN));
		$str="/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
		if(!preg_match($str, $url)){  
		    echo " \r\n URL is wrong, please input it again\r\n";
		    goto std;
		}else{
			$this->inputUrl = $url;
			echo "\r\n Fetching..., please wait...\r\n";
			sleep(2);
		}
	}
}