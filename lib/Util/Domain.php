<?php
/**
 * 提供和域名相关的通用函数处理
 *
 */
class Util_Domain
{
    /**
     * 归一化URL 正则表达式
     * 归一化的信息：
     * 1.去除http[s] 协议头
     * 2.将域名部分转换为小写
     * 3.如果是域名类型的url，去除url最后斜杠
     */
    const NORMALURL_PREG = '#^(https?://)?([^/]+)(/.*)?$#i';
	/**
	 * 通过url获取主域(开放域名中使用的主域)
	 * 迁移自sf-lib/lib/str.lib.php的url2domain_new函数
	 *
	 * @param string $strUrl， url地址
	 * @return string，开放域名使用的主域
	 */
	public static function getOpenDomain($strUrl) {
		//域名后缀格式配置
		//顶级域名配置
		$arrSuffixTop =  array( 'aero', 'asia', 'biz', 'cat', 'com', 'coop', 'edu', 'gov', 'info', 'int', 'jobs', 'mil', 'mobi', 'museum', 'name', 'net', 'org', 'pro', 'tel', 'travel');

		//国家域名后缀配置
		$arrSuffixCountry = array(
				'ac', 'ad', 'ae', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao', 'aq', 'ar', 'as', 'at', 'au', 'aw', 'ax', 'az', 
				'ba', 'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'bj', 'bm', 'bn', 'bo', 'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 
				'ca', 'cc', 'cd', 'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'cr', 'cu', 'cv', 'cx', 'cy', 'cz', 
				'de', 'dj', 'dk', 'dm', 'do', 'dz', 
				'ec', 'ee', 'eg', 'er', 'es', 'et', 'eu', 
				'fi', 'fj', 'fk', 'fm', 'fo', 'fr', 
				'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm', 'gn', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu', 'gw', 'gy', 
				'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 
				'id', 'ie', 'il', 'im', 'in', 'io', 'iq', 'ir', 'is', 'it', 
				'je', 'jm', 'jo', 'jp', 
				'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kp', 'kr', 'kw', 'ky', 'kz', 
				'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 
				'ma', 'mc', 'md', 'me', 'mg', 'mh', 'mk', 'ml', 'mm', 'mn', 'mo', 'mp', 'mq', 'mr', 'ms', 'mt', 'mu', 'mv', 'mw', 'mx', 'my', 'mz',
				'na', 'nc', 'ne', 'nf', 'ng', 'ni', 'nl', 'no', 'np', 'nr', 'nu', 'nz', 
				'om', 
				'pa', 'pe', 'pf', 'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'ps', 'pt', 'pw', 'py', 
				'qa', 
				're', 'ro', 'rs', 'ru', 'rw', 
				'sa', 'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr', 'st', 'su', 'sv', 'sy', 'sz', 
				'tc', 'td', 'tf', 'tg', 'th', 'tj', 'tk', 'tl', 'tm', 'tn', 'to', 'tp', 'tr', 'tt', 'tv', 'tw', 'tz', 
				'ua', 'ug', 'uk', 'us', 'uy', 'uz', 
				'va', 'vc', 've', 'vg', 'vi', 'vn', 'vu', 
				'wf', 'ws',
				'ye', 'yt', 'yu', 
				'za', 'zm', 'zw'
			);

		//二级域名信息
		$arrSecSuffixCountry = array(
			'cn' => array(
				'ac','ah', 'bj', 'cq', 'fj', 'gd', 'gs', 'gz', 'gx', 'ha', 'hb', 'he', 'hi', 'hl', 'hn', 
				'jl', 'js', 'jx', 'ln', 'nm', 'nx', 'qh', 'sc', 'sd', 'sh', 'sn', 'sx', 'tj', 'tw', 'xj', 
				'xz', 'yn', 'zj'
			),
			'tw' => array('mil', 'idv', 'game', 'ebiz', 'club'),
			'hk' => array('idv'),
			'jp' => array('ac', 'ad', 'co', 'ed', 'go', 'gr', 'lg', 'ne', 'or'),
			'kr' => array(
				'co', 'ne', 'or', 're', 'pe', 'go', 'mil', 'ac', 'hs', 'ms', 'es', 'sc', 'kg', 
				'seoul', 'busan', 'daegu', 'incheon', 'gwangju', 'daejeon', 'ulsan', 'gyeonggi', 
				'gangwon', 'chungbuk', 'chungnam', 'jeonbuk', 'jeonnam', 'gyeongbuk', 'gyeongnam', 'jeju'
			),
			'uk' => array('ac' ,'co' ,'gov' ,'ltd' ,'me' ,'mod' ,'net' ,'nhs' ,'nic' ,'org' ,'parliament' ,'plc' ,'police' ,'sch'),
			'nz' => array('ac' ,'co' ,'geek' ,'gen' ,'maori' ,'net' ,'org' ,'school' ,'cri' ,'govt' ,'iwi' ,'parliament' ,'mil'),
			'il' => array('ac' ,'co' ,'org' ,'net' ,'k12' ,'gov' ,'muni' ,'idf'),
		);

		//通用二级域名后缀
		$arrCommSecSuffix = array('com', 'net', 'org', 'gov', 'edu', 'co');

		//获取url域名部分
		$strUrl = strtolower($strUrl);
		$strUrl = trim($strUrl);
		$strDomain = '';
		preg_match("/^(https?:\/\/)?([^\/:?]+)/i", $strUrl, $arrMatches);
		$strDomain = $arrMatches[2];

		if (!empty($strDomain)){
			//按"."切分域名
			$arrTmp = explode('.', $strDomain);
			$intCnt = count($arrTmp);

			//异常处理:
			//如果按"."号切割后数组长度小于2，则url错误，没有域名
			if ($intCnt < 2){
				$strDomain = "";
			}

			$strLastSuffix = $arrTmp[$intCnt - 1];

			if (in_array($strLastSuffix, $arrSuffixTop)){
				//以国际通用域名结尾，将倒数第二节部分开始的做为主域名
				$strDomain = $arrTmp[$intCnt - 2];
				$strDomain .= '.';
				$strDomain .= $strLastSuffix;
			} elseif (in_array($strLastSuffix, $arrSuffixCountry)){
				//以国家域名结尾
				//判断是否在二级域名列表中
				$arrTmpSuffix = $arrSecSuffixCountry[$strLastSuffix];
				if ((!empty($arrTmpSuffix) && in_array($arrTmp[$intCnt - 2], $arrTmpSuffix) )
				|| in_array($arrTmp[$intCnt - 2], $arrCommSecSuffix)){
					//向前取三位作为域名，如果只存在两部分，则取倒数二位
					if ($intCnt >= 3){
						$strDomain = $arrTmp[$intCnt - 3];
						$strDomain .= '.';
					} else {
						$strDomain = '';
					}

					$strDomain .= $arrTmp[$intCnt - 2];
					$strDomain .= '.';
					$strDomain .= $strLastSuffix;
				} else {
					$strDomain = $arrTmp[$intCnt - 2];
					$strDomain .= '.';
					$strDomain .= $strLastSuffix;
				}
			} else {
				//对于新规则获取的主域名为空的情况，取域名部分按照"."分割的后两位作为主域名，如果不足两位，则取整个域名部分作为主域名
				if ($intCnt >= 2){
					$strDomain = $arrTmp[$intCnt - 2];
					$strDomain .= '.';
				}
				$strDomain .= $arrTmp[$intCnt - 1];
			}
		}
		return trim($strDomain);
	}

    /**
     * 获取url的域名
     * @param string $strUrl
     * @return string 域名
     */
    public static function getDomain($strUrl)
    {
        $strUrl = strtolower($strUrl);
        $strUrl = trim($strUrl);
        preg_match("/^(https?:\/\/)?([^\/:?]+)/i", $strUrl, $arrMatches);
        $strDomain = strval($arrMatches[2]);
        return $strDomain;
    }
	
	/**
	 * 获取url的域名
	 * @param string $strUrl
	 * @return string 域名,不包含前面的www.
	 */
	public static function getDomainWithout3W($strUrl){
		$url = self::getDomain($strUrl);
		if (strrpos($url,'www.') === 0) {
			return substr_replace($url, "", 0, 4);
		}
		return $url;
	}

    /**
     * 归一化需要推送的URL内容
     * @param string $strUrl 待归一化的URL
     * @return string 归一化的后的URL
     */
    public static function normalUrl($strUrl) {
        $url = preg_replace_callback(self::NORMALURL_PREG, array(
            'Util_Domain',
            'normalUrlCallBack',
        ), trim($strUrl));

        return $url;
    }

    /**
     * 归一化需要推送的URL内容 正则匹配回调
     * @param array $matches 匹配的内容
     * $matches[0]是完全匹配
     * $matches[1]是匹配到的协议头部分
     * $matches[2]是匹配到的域名部分
     * $matches[3]是匹配到的目录部分
     * @return string 归一化后的URL
     */
    private static function normalUrlCallBack($matches) {
        //如果目录部分只包含斜杠，则去除目录部分
        if ('/' === $matches[3]) {
            $matches[3] = '';
        }

        //去除协议头，将域名部分转换为小写
        return strtolower($matches[2]) . $matches[3];
    }
}
