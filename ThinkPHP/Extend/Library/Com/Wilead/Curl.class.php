<?php

/**
 * Curl
 * Curl操作封装类
 * 
 * @package Wilead
 * @author LamJane <linjue@wilead.com>
 * @copyright 2012
 * @version 1.0.1
 * @access public
 */
class Curl {
	/**
	 * Curl::get()
	 * 发起GET请求
	 * 
	 * @param mixed $url
	 * @param string $user_agent
	 * @param string $http_headers
	 * @param string $user_name
	 * @param string $password
	 * @return mixed
	 */
	static public function get($url, $user_agent = '', $http_headers = '', $user_name = '', $password = '') {
		$ret = self::__execute('GET', $url, '', $user_agent, $http_headers, $user_name, $password);
		if(false === $ret) {
			return false;
		}
		if(is_array($ret)) {
			return false;
		}
		return $ret;
	}

	/**
	 * Curl::post()
	 * 发起POST请求
	 * 
	 * @param mixed $url
	 * @param mixed $fields
	 * @param string $user_agent
	 * @param string $http_headers
	 * @param string $user_name
	 * @param string $password
	 * @return mixed
	 */
	static public function post($url, $fields, $user_agent = '', $http_headers = '', $user_name = '', $password = '') {
		$ret = self::__execute('POST', $url, $fields, $user_agent, $http_headers, $user_name, $password);
		if(false === $ret) {
			return false;
		}
		if(is_array($ret)) {
			return false;
		}
		return $ret;
	}

	/**
	 * Curl::__execute()
	 * 发起curl查询
	 * 
	 * @param mixed $method
	 * @param mixed $url
	 * @param string $fields
	 * @param string $user_agent
	 * @param string $http_headers
	 * @param string $user_name
	 * @param string $password
	 * @return mixed
	 */
	private function __execute($method, $url, $fields = '', $user_agent = '', $http_headers = '', $user_name = '', $password = '') {
		$ch = self::__create();
		if(false === $ch) {
			return false;
		}

		if(is_string($url) && strlen($url)) {
			// 设置要访问的URL
			$ret = curl_setopt($ch, CURLOPT_URL, $url);
		} else {
			return false;
		}

		// 是否显示头部信息
		curl_setopt($ch, CURLOPT_HEADER, false);
		// 是否返回输出的文本流
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if($user_name != '') {
			curl_setopt($ch, CURLOPT_USERPWD, $user_name.':'.$password);
		}

		$method = strtolower($method);
		if('post' == $method) {
			curl_setopt($ch, CURLOPT_POST, true);
			if(is_array($fields)) {
				$sets = array();
				foreach($fields as $k => $v) {
					$sets[] = $k.'='.urlencode($v);
				}
				$fields = import('&', $sets);
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		} elseif('put' == $method) {
			curl_setopt($ch, CURLOPT_PUT, true);
		}

		// CURL超时秒数
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		if(strlen($user_agent)) {
			$user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10';
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

		if(is_array($http_headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		}

		$ret = curl_exec($ch);
		if(curl_errno($ch)) {
			curl_close($ch);
			return array(curl_error($ch), curl_errno($ch));
		} else {
			curl_close($ch);
			if(!is_string($ret) || !strlen($ret)) {
				return false;
			}
			return $ret;
		}
	}

	/**
	 * Curl::create()
	 * 创建curl句柄
	 * 
	 * @return mixed
	 */
	private function __create() {
		$ch = null;
		if(!function_exists('curl_init')) {
			return false;
		}
		$ch = curl_init();
		if(!is_resource($ch)) {
			return false;
		}
		return $ch;
	}
}

?>