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
		$html = QueryList::get($this->inputUrl);
		$urlParams = parse_url($this->inputUrl);
		$domain = $urlParams['scheme'].'://'.$urlParams['host'];
		//获取到结果一共有多少页
		$urls = [];
		$count = $html->find('.next-pagination-list a:eq(-1)')->text();
		if(empty($count)){
			die('搜索失败，请手动检索此关键字下是否有商品');
		}
		$url = $html->find('.next-pagination-list a:eq(-1)')->href;
		$url = $domain.$url;
		$param = parse_url($url);
		$ex = explode('/', $param['path']);
		array_pop($ex);
		$newParam = implode('/', $ex);
		//var_dump($newParam);exit;
		$param['path'] = $newParam;
		for($i = 1; $i<= $count; $i++){
			$urls[$i] = $param['scheme'].'://'.$param['host'].$param['path'].'/'.$i.'.html';
		}
		print "<br>------------------------------<br>";
		print '搜索成功，本次搜索一共有'.count($urls).'页<br>';
		print "------------------------------<br>";

		$data = [];

		QueryList::multiGet($urls)->success(function(QueryList $ql,Response $response, $index) use($urls, &$data){
			$url = $urls[$index+1];
			$listHtml = $ql->get($url);
			$title = $listHtml->find('.component-product-list .product-item .product-info a')->attrs('title')->all();
			//$link = $listHtml->find('.component-product-list .product-item .product-info a')->attrs('href');
			$data[$index+1] = $title;
		})->send();

		print "数据采集成功，正在格式化处理...<br><br>";

		//打印结果
		$string = '';
		ksort($data);
		foreach ($data as $key => $value) {
			$string .= '--------第'.$key.'页--------'."\r\n\r\n";
			print '--------第'.$key.'页--------<br>';;
			foreach ($value as $k => $v) {
				$string .= ($k+1).':'.$v."\r\n\r\n";
				print ($k+1).':'.$v.'<br>';
			}
			print "<br>";
		}
		$time = date('Y-m-d-H-i-s', time());
		$file = './data/'.$time.'.txt';
		if(!is_dir('./data')){
			mkdir('./data/', 0755, true);
		}
		file_put_contents($file, $string);
		print "------------------------------<br>";
		echo "数据已保存，文件名是".$file.",请 <a href='download.php?file=".$time."'><点击这里></a> 进行下载。或者你可以 <a href='index.php'>返回上一页</a>";
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