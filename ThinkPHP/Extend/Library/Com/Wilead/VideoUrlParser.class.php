<?php

/**
 * VideoUrlParser
 * 视频信息分析类
 * 根据视频URL抓取视频信息的工具，支持优酷、土豆、酷六、56、乐视、搜狐、腾讯、新浪
 * 
 * @package Wilead
 * @author LamJane<linjue@wilead.com>
 * @copyright 2012
 * @version 1.0.1
 * @access public
 */
class VideoUrlParser {
	const USER_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko)
        Chrome/8.0.552.224 Safari/534.10";
	const CHECK_URL_VALID = "/(youku\.com|tudou\.com|ku6\.com|56\.com|letv\.com|video\.sina\.com\.cn|(my\.)?tv\.sohu\.com|v\.qq\.com)/";

	/**
	 * VideoUrlParser::parse()
	 * 提取视频信息
	 * 
	 * @param string $url 要提取的视频地址
	 * @param bool $create_object 是否输出object内容
	 * @return array
	 */
	static public function parse($url = '', $create_object = true) {
		$lower_url = strtolower($url);
		preg_match(self::CHECK_URL_VALID, $lower_url, $matches);
		if(!$matches)
			return false;

		switch($matches[1]) {
			case 'youku.com':
				$data = self::__parseYouku($url);
				break;
			case 'tudou.com':
				$data = self::__parseTudou($url);
				break;
			case 'ku6.com':
				$data = self::__parseKu6($url);
				break;
			case '56.com':
				$data = self::__parse56($url);
				break;
			case 'letv.com':
				$data = self::__parseLetv($url);
				break;
			case 'video.sina.com.cn':
				$data = self::__parseSina($url);
				break;
			case 'my.tv.sohu.com':
			case 'tv.sohu.com':
			case 'sohu.com':
				$data = self::__parseSohu($url);
				break;
			case 'v.qq.com':
				$data = self::__parseQq($url);
				break;
			default:
				$data = false;
		}

		if($data && $createObject)
			$data['object'] = "<embed src=\"{$data['swf']}\" quality=\"high\" width=\"480\" height=\"400\" align=\"middle\" allowNetworking=\"all\" allowScriptAccess=\"always\" type=\"application/x-shockwave-flash\"></embed>";
		return $data;
	}

	/**
	 * VideoUrlParser::__parseQq()
	 * 腾讯视频
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseQq($url) {
		if(preg_match("/\/play\//", $url)) {
			$html = self::__fget($url);
			preg_match("/url=[^\"]+/", $html, $matches);
			if(!$matches)
				;
			return false;
			$url = $matches[0];
		}
		preg_match("/vid=([^\_]+)/", $url, $matches);
		$vid = $matches[1];
		$html = self::__fget($url);
		// query
		preg_match("/flashvars\s=\s\"([^;]+)/s", $html, $matches);
		$query = $matches[1];
		if(!$vid) {
			preg_match("/vid\s?=\s?vid\s?\|\|\s?\"(\w+)\";/i", $html, $matches);
			$vid = $matches[1];
		}
		$query = str_replace('"+vid+"', $vid, $query);
		parse_str($query, $output);
		$data['img'] = "http://vpic.video.qq.com/{$$output['cid']}/{$vid}_1.jpg";
		$data['url'] = $url;
		$data['title'] = $output['title'];
		$data['swf'] = "http://imgcache.qq.com/tencentvideo_v1/player/TencentPlayer.swf?".$query;
		return $data;
	}

	/**
	 * VideoUrlParser::__parseYouku()
	 * 优酷视频
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseYouku($url) {
		preg_match("#id\_(\w+)#", $url, $matches);

		if(empty($matches)) {
			preg_match("#v_playlist\/#", $url, $mat);
			if(!$mat)
				return false;

			$html = self::__fget($url);

			preg_match("#videoId2\s*=\s*\'(\w+)\'#", $html, $matches);
			if(!$matches)
				return false;
		}

		$link = "http://v.youku.com/player/getPlayList/VideoIDS/{$matches[1]}/timezone/+08/version/5/source/out?password=&ran=2513&n=3";

		$retval = self::__cget($link);
		if($retval) {
			$json = json_decode($retval, true);

			$data['img'] = $json['data'][0]['logo'];
			$data['title'] = $json['data'][0]['title'];
			$data['url'] = $url;
			$data['swf'] = "http://player.youku.com/player.php/sid/{$matches[1]}/v.swf";

			return $data;
		} else {
			return false;
		}
	}

	/**
	 * VideoUrlParser::__parseTudou()
	 * 土豆网
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseTudou($url) {
		preg_match("#view/([-\w]+)/#", $url, $matches);

		if(empty($matches)) {
			if(strpos($url, "/playlist/") == false)
				return false;

			if(strpos($url, 'iid=') !== false) {
				$quarr = explode("iid=", $lowerurl);
				if(empty($quarr[1]))
					return false;
			} elseif(preg_match("#p\/l(\d+).#", $lowerurl, $quarr)) {
				if(empty($quarr[1]))
					return false;
			}

			$html = self::__fget($url);
			$html = iconv("GB2312", "UTF-8", $html);

			preg_match("/lid_code\s=\slcode\s=\s[\'\"]([^\'\"]+)/s", $html, $matches);
			$icode = $matches[1];

			preg_match("/iid\s=\s.*?\|\|\s(\d+)/sx", $html, $matches);
			$iid = $matches[1];

			preg_match("/listData\s=\s(\[\{.*\}\])/sx", $html, $matches);

			$find = array("/\n/", '/\s/', "/:[^\d\"]\w+[^\,]*,/i", "/(\{|,)(\w+):/");
			$replace = array("", "", ':"",', '\\1"\\2":');
			$str = preg_replace($find, $replace, $matches[1]);
			//var_dump($str);
			$json = json_decode($str);
			//var_dump($json);exit;
			if(is_array($json) || is_object($json) && !empty($json)) {
				foreach($json as $val) {
					if($val->iid == $iid) {
						break;
					}
				}
			}

			$data['img'] = $val->pic;
			$data['title'] = $val->title;
			$data['url'] = $url;
			$data['swf'] = "http://www.tudou.com/l/{$icode}/&iid={$iid}/v.swf";

			return $data;
		}

		$host = "www.tudou.com";
		$path = "/v/{$matches[1]}/v.swf";

		$ret = self::__fsget($path, $host);

		if(preg_match("#\nLocation: (.*)\n#", $ret, $mat)) {
			parse_str(parse_url(urldecode($mat[1]), PHP_URL_QUERY));

			$data['img'] = $snap_pic;
			$data['title'] = $title;
			$data['url'] = $url;
			$data['swf'] = "http://www.tudou.com/v/{$matches[1]}/v.swf";

			return $data;
		}
		return false;
	}

	/**
	 * VideoUrlParser::__parseKu6()
	 * 酷6
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseKu6($url) {
		if(preg_match("/show\_/", $url)) {
			preg_match("#/([-\w]+)\.html#", $url, $matches);
			$url = "http://v.ku6.com/fetchVideo4Player/{$matches[1]}.html";
			$html = self::__fget($url);

			if($html) {
				$json = json_decode($html, true);
				if(!$json)
					return false;

				$data['img'] = $json['data']['picpath'];
				$data['title'] = $json['data']['t'];
				$data['url'] = $url;
				$data['swf'] = "http://player.ku6.com/refer/{$matches[1]}/v.swf";

				return $data;
			} else {
				return false;
			}
		} elseif(preg_match("/show\//", $url, $matches)) {
			$html = self::__fget($url);
			preg_match("/ObjectInfo\s?=\s?([^\n]*)};/si", $html, $matches);
			$str = $matches[1];
			// img
			preg_match("/cover\s?:\s?\"([^\"]+)\"/", $str, $matches);
			$data['img'] = $matches[1];
			// title
			preg_match("/title\"?\s?:\s?\"([^\"]+)\"/", $str, $matches);
			$jsstr = "{\"title\":\"{$matches[1]}\"}";
			$json = json_decode($jsstr, true);
			$data['title'] = $json['title'];
			// url
			$data['url'] = $url;
			// query
			preg_match("/\"(vid=[^\"]+)\"\sname=\"flashVars\"/s", $html, $matches);
			$query = str_replace("&amp;", '&', $matches[1]);
			preg_match("/\/\/player\.ku6cdn\.com[^\"\']+/", $html, $matches);
			$data['swf'] = 'http:'.$matches[0].'?'.$query;

			return $data;
		}
	}

	/**
	 * VideoUrlParser::__parse56()
	 * 56视频
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parse56($url) {
		preg_match("#/v_(\w+)\.html#", $url, $matches);

		if(empty($matches))
			return false;

		$link = "http://vxml.56.com/json/{$matches[1]}/?src=out";
		$retval = self::__cget($link);

		if($retval) {
			$json = json_decode($retval, true);

			$data['img'] = $json['info']['img'];
			$data['title'] = $json['info']['Subject'];
			$data['url'] = $url;
			$data['swf'] = "http://player.56.com/v_{$matches[1]}.swf";

			return $data;
		} else {
			return false;
		}
	}

	/**
	 * VideoUrlParser::__parseLetv()
	 * 乐视
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseLetv($url) {
		$html = self::__fget($url);
		preg_match("#http://v.t.sina.com.cn/([^'\"]*)#", $html, $matches);
		parse_str(parse_url(urldecode($matches[0]), PHP_URL_QUERY));
		preg_match("#vplay/(\d+)#", $url, $matches);
		$data['img'] = $pic;
		$data['title'] = $title;
		$data['url'] = $url;
		$data['swf'] = "http://www.letv.com/player/x{$matches[1]}.swf";

		return $data;
	}

	/**
	 * VideoUrlParser::__parseSohu()
	 * 搜狐TV
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseSohu($url) {
		$html = self::__fget($url);
		$html = iconv("GB2312", "UTF-8", $html);
		preg_match_all("/og:(?:title|image|videosrc)\"\scontent=\"([^\"]+)\"/s", $html, $matches);
		$data['img'] = $matches[1][1];
		$data['title'] = $matches[1][0];
		$data['url'] = $url;
		$data['swf'] = $matches[1][2];
		return $data;
	}

	/**
	 * VideoUrlParser::__parseSina()
	 * 新浪
	 * 
	 * @param mixed $url
	 * @return
	 */
	private function __parseSina($url) {
		preg_match("/(\d+)(?:\-|\_)(\d+)/", $url, $matches);
		$url = "http://video.sina.com.cn/v/b/{$matches[1]}-{$matches[2]}.html";
		$html = self::__fget($url);
		preg_match("/video\s?:\s?([^<]+)}/", $html, $matches);
		$find = array("/\n/", "/\s*/", "/\'/", "/\{([^:,]+):/", "/,([^:]+):/", "/:[^\d\"]\w+[^\,]*,/i");
		$replace = array('', '', '"', '{"\\1":', ',"\\1":', ':"",');
		$str = preg_replace($find, $replace, $matches[1]);
		$arr = json_decode($str, true);

		$data['img'] = $arr['pic'];
		$data['title'] = $arr['title'];
		$data['url'] = $url;
		$data['swf'] = $arr['swfOutsideUrl'];

		return $data;
	}

	/**
	 * VideoUrlParser::__fget()
	 * 通过file_get_content获取内容
	 * 
	 * @param string $url
	 * @return
	 */
	private function __fget($url = '') {
		if(!$url)
			return false;
		$html = file_get_contents($url);
		// 判断是否gzip压缩
		if($dehtml = self::__gzdecode($html))
			return $dehtml;
		else
			return $html;
	}

	/**
	 * VideoUrlParser::__fsget()
	 * 通过fsock获取内容
	 * 
	 * @param string $path
	 * @param string $host
	 * @param string $user_agent
	 * @return
	 */
	private function __fsget($path = '/', $host = '', $user_agent = '') {
		if(!$path || !$host)
			return false;
		$user_agent = $user_agent?$user_agent:self::USER_AGENT;

		$out = <<< HEADER
GET $path HTTP/1.1
Host: $host
User-Agent: $user_agent
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-cn,zh;q=0.5
Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n\r\n
HEADER;
		$fp = @fsockopen($host, 80, $errno, $errstr, 10);
		if(!$fp)
			return false;
		if(!fputs($fp, $out))
			return false;
		while(!feof($fp)) {
			$html .= fgets($fp, 1024);
		}
		fclose($fp);
		// 判断是否gzip压缩
		if($dehtml = self::__gzdecode($html))
			return $dehtml;
		else
			return $html;
	}

	/**
	 * VideoUrlParser::__cget()
	 * 通过curl获取内容
	 * 
	 * @param string $url
	 * @param string $user_agent
	 * @return
	 */
	private function __cget($url = '', $user_agent = '') {
		if(!$url)
			return;

		$user_agent = $user_agent?$user_agent:self::USER_AGENT;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if(strlen($user_agent))
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

		ob_start();
		curl_exec($ch);
		$html = ob_get_contents();
		ob_end_clean();

		if(curl_errno($ch)) {
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		if(!is_string($html) || !strlen($html)) {
			return false;
		}
		return $html;
		// 判断是否gzip压缩
		if($dehtml = self::__gzdecode($html))
			return $dehtml;
		else
			return $html;
	}

	/**
	 * VideoUrlParser::__gzdecode()
	 * gzip解压
	 * 
	 * @param mixed $data
	 * @return
	 */
	private function __gzdecode($data) {
		$len = strlen($data);
		if($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
			return null; // Not GZIP format (See RFC 1952)
		}
		$method = ord(substr($data, 2, 1)); // Compression method
		$flags = ord(substr($data, 3, 1)); // Flags
		if($flags & 31 != $flags) {
			// Reserved bits are set -- NOT ALLOWED by RFC 1952
			return null;
		}
		// NOTE: $mtime may be negative (PHP integer limitations)
		$mtime = unpack("V", substr($data, 4, 4));
		$mtime = $mtime[1];
		$xfl = substr($data, 8, 1);
		$os = substr($data, 8, 1);
		$headerlen = 10;
		$extralen = 0;
		$extra = "";
		if($flags & 4) {
			// 2-byte length prefixed EXTRA data in header
			if($len - $headerlen - 2 < 8) {
				return false; // Invalid format
			}
			$extralen = unpack("v", substr($data, 8, 2));
			$extralen = $extralen[1];
			if($len - $headerlen - 2 - $extralen < 8) {
				return false; // Invalid format
			}
			$extra = substr($data, 10, $extralen);
			$headerlen += 2 + $extralen;
		}

		$filenamelen = 0;
		$filename = "";
		if($flags & 8) {
			// C-style string file NAME data in header
			if($len - $headerlen - 1 < 8) {
				return false; // Invalid format
			}
			$filenamelen = strpos(substr($data, 8 + $extralen), chr(0));
			if($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
				return false; // Invalid format
			}
			$filename = substr($data, $headerlen, $filenamelen);
			$headerlen += $filenamelen + 1;
		}

		$commentlen = 0;
		$comment = "";
		if($flags & 16) {
			// C-style string COMMENT data in header
			if($len - $headerlen - 1 < 8) {
				return false; // Invalid format
			}
			$commentlen = strpos(substr($data, 8 + $extralen + $filenamelen), chr(0));
			if($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
				return false; // Invalid header format
			}
			$comment = substr($data, $headerlen, $commentlen);
			$headerlen += $commentlen + 1;
		}

		$headercrc = "";
		if($flags & 1) {
			// 2-bytes (lowest order) of CRC32 on header present
			if($len - $headerlen - 2 < 8) {
				return false; // Invalid format
			}
			$calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
			$headercrc = unpack("v", substr($data, $headerlen, 2));
			$headercrc = $headercrc[1];
			if($headercrc != $calccrc) {
				return false; // Bad header CRC
			}
			$headerlen += 2;
		}

		// GZIP FOOTER - These be negative due to PHP's limitations
		$datacrc = unpack("V", substr($data, -8, 4));
		$datacrc = $datacrc[1];
		$isize = unpack("V", substr($data, -4));
		$isize = $isize[1];

		// Perform the decompression:
		$bodylen = $len - $headerlen - 8;
		if($bodylen < 1) {
			// This should never happen - IMPLEMENTATION BUG!
			return null;
		}
		$body = substr($data, $headerlen, $bodylen);
		$data = "";
		if($bodylen > 0) {
			switch($method) {
				case 8:
					// Currently the only supported compression method:
					$data = gzinflate($body);
					break;
				default:
					// Unknown compression method
					return false;
			}
		} else {
			//...
		}

		if($isize != strlen($data) || crc32($data) != $datacrc) {
			// Bad format!  Length or CRC doesn't match!
			return false;
		}
		return $data;
	}
}
