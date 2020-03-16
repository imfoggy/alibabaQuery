<?php
namespace Foggy;
use GuzzleHttp\Psr7\Response;
use QL\QueryList;

class AlibabaQuery {

	/**
	 * 引导
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @return [type]            [description]
	 */
	public static function init(){
		self::run();
	}


	/**
	 * 执行
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @return [type]            [description]
	 */
	protected function run(){
		$urls = $this->getInputUrl()->urlFormat();
		$data = [];
		QueryList::multiGet($urls)->success(function(QueryList $ql,Response $response, $index) use($urls, &$data){
			$url = $urls[$index+1];
			$listHtml = $ql->get($url);
			$title = $listHtml->find('.component-product-list .product-item .product-info a')->attrs('title')->all();
			$data[$index+1] = $title;
		})->send();
		print "数据采集成功，正在格式化处理...\r\n";
		//打印结果
		$this->putFiles($data);
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

		return $this;
	}

	/**
	 * 对Url进行处理
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @param  [type]            $url [description]
	 * @return [type]                 [description]
	 */
	protected function urlFormat(){
		$html = QueryList::get($this->inputUrl);
		$count = $html->find('.next-pagination-list a:eq(-1)')->text();
		if(empty($count)){
			print '搜索失败，请手动检索此关键字下是否有商品';
			exit;
		}
		$urlParams = parse_url($this->inputUrl);
		$domain = $urlParams['scheme'].'://'.$urlParams['host'];
		//获取到结果一共有多少页
		$urls = [];
		$url = $html->find('.next-pagination-list a:eq(-1)')->href;
		$url = $domain.$url;
		$param = parse_url($url);
		$ex = explode('/', $param['path']);
		array_pop($ex);
		$newParam = implode('/', $ex);
		$param['path'] = $newParam;
		for($i = 1; $i<= $count; $i++){
			$urls[$i] = $param['scheme'].'://'.$param['host'].$param['path'].'/'.$i.'.html';
		}
		print "------------------------------\r\n";
		print '搜索成功，本次搜索一共有'.count($urls).'页'."\r\n";
		print "------------------------------\r\n";

		return $urls;
	}

	/**
	 * 写入文件
	 * @Author Foggy
	 * @Date   2020-03-16
	 * @WeChat [vita_hacker]
	 * @Email  [x_foggy@163.com]
	 * @param  [type]            $content [description]
	 * @return [type]                     [description]
	 */
	protected function putFiles($data){
		$string = '';
		ksort($data);
		foreach ($data as $key => $value) {
			$string .= '--------第'.$key.'页--------'."\r\n\r\n";
			print '--------第'.$key.'页--------'."\r\n";;
			foreach ($value as $k => $v) {
				$string .= ($k+1).':'.$v."\r\n\r\n";
				print ($k+1).':'.$v."\r\n";
			}
			print "\r\n";
		}
		$time = date('Y-m-d-H-i-s', time());
		$file = './data/'.$time.'.txt';
		if(!is_dir('./data')){
			mkdir('./data/', 0755, true);
		}
		file_put_contents($file, $string);
		print "------------------------------\r\n";
		echo "数据已保存，文件名是".$file.",请 进入data目录查看";
	}
}