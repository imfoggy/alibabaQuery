# alibabaQuery
##### 此library可以将阿里巴巴店铺中的产品搜索结果中的产品标题全部保存下来。此library可以将阿里巴巴店铺中的产品搜索结果中的产品标题全部保存下来。
##### 用法如下：
- composer require foggy/alibaba-query 到项目中。
- 根目录下新建run.php文件，内容如下：
    ```php
    #!/usr/bin/env php
    <?php
    require 'vendor/autoload.php';
    use Foggy\AlibabaQuery;
    
    function is_cli(){
    	return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
    
    if(!is_cli()){
    	exit('please running it in CLI mode');
    }
    
    AlibabaQuery::init();
    ```
- 命令行下运行 php run.php 即可。
- 进入到阿里巴巴的某个店铺中，搜索产品关键字，如搜索"cutting",页面的url地址会跳转到"https://zbxbl.en.alibaba.com/search/product?SearchText=cutting",
- 复制上面的url地址。
- 粘贴到命令行中please input a url address中去。