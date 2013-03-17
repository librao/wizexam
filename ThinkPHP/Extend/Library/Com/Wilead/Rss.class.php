<?php

/**
 * RSS
 * RSS生成类
 * 
 * @package Wilead
 * @author LamJane <linjue@wilead.com>
 * @copyright 2012
 * @version 1.0.1
 * @access public
 */
class RSS {
	/**
	 * +----------------------------------------------------------
	 * RSS频道名
	 * +----------------------------------------------------------
	 */
	protected $channel_title = '';
	/**
	 * +----------------------------------------------------------
	 * RSS频道链接
	 * +----------------------------------------------------------
	 */
	protected $channel_link = '';
	/**
	 * +----------------------------------------------------------
	 * RSS频道描述
	 * +----------------------------------------------------------
	 */
	protected $channel_description = '';
	/**
	 * +----------------------------------------------------------
	 * RSS频道使用的小图标的URL
	 * +----------------------------------------------------------
	 */
	protected $channel_imgurl = '';
	/**
	 * +----------------------------------------------------------
	 * RSS频道所使用的语言
	 * +----------------------------------------------------------
	 */
	protected $language = 'zh_CN';
	/**
	 * +----------------------------------------------------------
	 * RSS文档创建日期，默认为今天
	 * +----------------------------------------------------------
	 */
	protected $pubDate = '';
	protected $lastBuildDate = '';

	protected $generator = 'RSS Generator';

	/**
	 * +----------------------------------------------------------
	 * RSS单条信息的数组
	 * +----------------------------------------------------------
	 */
	protected $items = array();

	/**
	 * RSS::__construct()
     * 构造函数
	 * 
	 * @param mixed $title RSS频道名
	 * @param mixed $link RSS频道链接
	 * @param mixed $description RSS频道描述
	 * @param string $imgurl RSS频道图标
	 * @return void
	 */
	public function __construct($title, $link, $description, $imgurl = '') {
		$this->channel_title = $title;
		$this->channel_link = $link;
		$this->channel_description = $description;
		$this->channel_imgurl = $imgurl;
		$this->pubDate = Date('Y-m-d H:i:s', time());
		$this->lastBuildDate = Date('Y-m-d H:i:s', time());
	}


	/**
	 * RSS::config()
     * 设置私有变量
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function config($key, $value) {
		$this->{$key} = $value;
	}

	/**
	 * RSS::AddItem()
     * 添加RSS项
	 * 
	 * @param mixed $title 标题
	 * @param mixed $link 链接
	 * @param mixed $description 摘要
	 * @param mixed $pubDate 发布日期
	 * @return void
	 */
	function AddItem($title, $link, $description, $pubDate) {
		$this->items[] = array('title' => $title, 'link' => $link, 'description' => $description, 'pubDate' => $pubDate);
	}

	/**
	 * RSS::fetch()
     * 输出RSS的XML字符串
	 * 
	 * @return
	 */
	public function fetch() {
		$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
		$rss = "<rss version=\"2.0\">\r\n";
		$rss .= "<channel>\r\n";
		$rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
		$rss .= "<description><![CDATA[{$this->channel_description}]]></description>\r\n";
		$rss .= "<link>{$this->channel_link}</link>\r\n";
		$rss .= "<language>{$this->language}</language>\r\n";

		if(!empty($this->pubDate))
			$rss .= "<pubDate>{$this->pubDate}</pubDate>\r\n";
		if(!empty($this->lastBuildDate))
			$rss .= "<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>\r\n";
		if(!empty($this->generator))
			$rss .= "<generator>{$this->generator}</generator>\r\n";

		$rss .= "<ttl>5</ttl>\r\n";

		if(!empty($this->channel_imgurl)) {
			$rss .= "<image>\r\n";
			$rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
			$rss .= "<link>{$this->channel_link}</link>\r\n";
			$rss .= "<url>{$this->channel_imgurl}</url>\r\n";
			$rss .= "</image>\r\n";
		}

		for($i = 0; $i < count($this->items); $i++) {
			$rss .= "<item>\r\n";
			$rss .= "<title><![CDATA[{$this->items[$i]['title']}]]></title>\r\n";
			$rss .= "<link>{$this->items[$i]['link']}</link>\r\n";
			$rss .= "<description><![CDATA[{$this->items[$i]['description']}]]></description>\r\n";
			$rss .= "<pubDate>{$this->items[$i]['pubDate']}</pubDate>\r\n";
			$rss .= "</item>\r\n";
		}

		$rss .= "</channel>\r\n</rss>";
		return $rss;
	}


	/**
	 * RSS::Display()
     * 输出RSS内容到浏览器
	 * 
	 * @return void
	 */
	public function display() {
		header("Content-Type: text/xml; charset=utf-8");
		echo $this->fetch();
		exit;
	}
}

?>