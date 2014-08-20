<?php
/**
 * Util_Array 
 * 提供数组常用的接口封装
 * @copyright Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 * @author 雷果国<leiguoguo@baidu.com> 
 */
class Util_Array {
	protected static $sortKey; // 排序的内部key
	protected static $reverse; // 排序是否逆序
	/**
	 * parseCommaArray 
	 * 场景: 逗号分隔字符串在处理字符串时, 通常会需要做检查
	 * @param mixed $arrOrCommaStr 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function parseCommaArray($arrOrCommaStr) {
		/* 非数组类型当做字符串做分割处理 */
		if ( !is_array($arrOrCommaStr) ) {
			$arrOrCommaStr	= strval($arrOrCommaStr);
			$arrOrCommaStr	= explode(',', $arrOrCommaStr);
		}
		return $arrOrCommaStr;
	}
	/**
	 * changeKey 
	 * 场景: 从DB中取得用户数据集, 需要将该数据集的Key修改为用户UID
	 * @param mixed $array 输入数据集
	 * @param mixed $key 要做为新key的数据目前的key
	 * @param mixed $multi 适应从DB中取得的用户数据集中有重复的情况
	 * @param string $valueKey 如果指定, 则返回的数组中, 值只是原数组中该Key对应的数据
	 * @static
	 * @access public
	 * @return array
	 */
	public static function changeKey($array, $key, $multi = false, $valueKey = null) {
		$infos	= array();
		if (is_array($array)){
			foreach ( $array as $ele ) {
				if ( $multi ) {
					$infos[$ele[$key]][]	= is_null($valueKey) ? $ele : $ele[$valueKey];
				} else {
					$infos[$ele[$key]]		= is_null($valueKey) ? $ele : $ele[$valueKey];
				}
			}
		}
		return $infos;
	}
	/**
	 * pickup 
	 * 场景: 从DB中取得用户数据集, 需要提取该数据集中所有的用户ID
	 * @param mixed $array 输入数据集
	 * @param mixed $key 要提取数据目前的key
	 * @param mixed $holdKey 是否需要保留索引
	 * @param mixed $unique 是否需要做唯一处理
	 * @static
	 * @access public
	 * @return array
	 */
	public static function pickup($array, $key, $holdKey = true, $unique = false) {
		$infos	= array();
		$i		= 0;
		if (is_array($array)){
			foreach ( $array as $k => $ele ) {
				$k			= $holdKey ? $k : $i ++;
				$infos[$k]	= $ele[$key];
			}
		}
		if ( $unique ) {
			$infos	= array_unique($infos);
		}
		return $infos;
	}

	/**
	 * isAvailable 
	 * 检查目标是否全部是可用值
	 * @param mixed $target 检查的目标, 支持的格式: 单个元素, 一维数组, 逗号表达式字符串
	 * @param mixed $availables 可用值, 支持的格式: 单个元素, 一维数组, 逗号表达式字符串
	 * @static
	 * @access public
	 * @return bool 全部$target都是可用值返回true, 否则返回false
	 */
	public static function isAvailable($target, $availables) {
		$targets    = self::parseCommaArray($target);
		$availables = self::parseCommaArray($availables);
		foreach ($targets as $target){
			if (!in_array($target, $availables)){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * pickupMulti
	 * 场景 ： 提取数组中所指定的健值对
	 * @param mixed $array 
	 * @param mixed $select 要保留的键值 array('id','name',...)
	 * @param string $key 要做为新key的数据目前的key 如果等于null表示不需要转化key
	 * @param bool $multi 适应从DB中取得的用户数据集中有重复的情况
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function pickupMulti($array, $select, $key = null, $multi = false ) {
		$infos	= array();
		if (is_array($array)){
			$select = (array) $select;
			$selectNew 	= array_flip($select);

			foreach ( $array as $k=>$value ) {
				$newValue 	= array_intersect_key($value, $selectNew);
				$k 			= (is_null($key)) ? $k : $value[$key];
				if ($multi){
					$infos[$k][] 	= $newValue;
				} else{
					$infos[$k] 	= $newValue;
				}
			}
		}
		return $infos;
	}
	
	/**
	 * pickupValues
	 * 获取三维数组中指定key的值的集合
	 * @param array $array 输入数组 
	 * @param string $key 指定的键值
	 * @param bool $unique 是否进行array_unique操作
	 * 
	 * 例: array(
	 * 	array(
	 * 		array('id' => 1, 'name' => 'a'),
	 * 		array('id' => 2, 'name' => 'b'),
	 * 		...
	 * 		)
	 * 	array(
	 * 		array('id' => 1, 'name' => 'a'),
	 * 		array('id' => 3, 'name' => 'c'),
	 * 		...
	 * 		)
	 * 	...
	 * )
	 * pickupValues($array, 'id' ,true)
	 * @return array(1,2,3)
	 */
	public static function pickupValues($array, $key, $unique = true){
		$result = array();
		if (is_array($array)){
			foreach ($array as $item){
				foreach ($item as $info){
					$result[] = $info[$key];
				}
			}
			
			if ($unique){
				$result = array_unique($result);
			}
		}
		return $result;
	}

	/**
	 * cmpNum 
	 * 数值比较
	 * @param mixed $a 参与比较的第一个数据
	 * @param mixed $b 参与比较的第二个数据
	 * @static
	 * @access public
	 * @return 0: 相等. >0: $a > $b. <0: $a < $b;
	 */
	public static function cmpNum($a, $b) {
		$reala = self::$reverse ? $b : $a;
		$realb = self::$reverse ? $a : $b;
		return $reala[self::$sortKey] - $realb[self::$sortKey];
	}

	/**
	 * sortWithNumEle 
	 * 用数值类型的元素排序
	 * @param array $array 要排序的数组
	 * @param mixed $sortKey 排序的元素Key
	 * @param bool $reverse 是否逆序
	 * @static
	 * @access public
	 * @return 排序后的数组
	 */
	public static function sortWithNumEle($array, $sortKey, $reverse = false, $reserveKey = true) {
		self::$sortKey = $sortKey;
		self::$reverse = $reverse;
		if ($reserveKey){
			uasort($array, array(__CLASS__, 'cmpNum'));
		} else {
			usort($array, array(__CLASS__, 'cmpNum'));
		}
		self::$sortKey = null;
		self::$reverse = false;
		return $array;
	}

	/**
	 * cmpStrLen 
	 * 用作数组排序的字符串长度比较
	 * @param string $a 参与比较的第一个数组
	 * @param string $b 参与比较的第二个数组
	 * @static
	 * @access public
	 * @return 0: 相等. >0: $a > $b. <0: $a < $b;
	 */
	public static function cmpStrLen($a, $b){
		return strlen($a) - strlen($b);
	}
	/**
	 * sortWithString
	 * 用字符串类型的元素排序
	 * @param array $array 要排序的数组
	 * @param mixed $sortKey 排序的元素Key
	 * @param bool $reverse 是否逆序
	 * @static
	 * @access public
	 * @return 排序后的数组
	 */
	public static function sortWithString($array, $sortKey, $reverse = false){
		self::$sortKey = $sortKey;
		self::$reverse = $reverse;
		uasort($array, array(__CLASS__, 'cmpString'));
		self::$sortKey = null;
		self::$reverse = false;
		return $array;
	}
	/**
	 * cmpString 
	 * 用作数组排序的字符串长度比较
	 * @param string $a 参与比较的第一个数组
	 * @param string $b 参与比较的第二个数组
	 * @static
	 * @access public
	 * @return 0: 相等. >0: $a > $b. <0: $a < $b;
	 */
	public static function cmpString($a, $b) {
		$reala = self::$reverse ? $b : $a;
		$realb = self::$reverse ? $a : $b;
		return strcmp($reala[self::$sortKey],$realb[self::$sortKey]);		
	}

	/**
	 * cmpWithDateEle
	 * 用作数组排序的时间值比较
	 * @param string $a 
	 * @param string $b 
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function cmpWithDateEle($a, $b) {
		$reala = self::$reverse ? $b : $a;
		$realb = self::$reverse ? $a : $b;
		return strtotime($reala[self::$sortKey]) - strtotime($realb[self::$sortKey]);
	}

	/**
	 * sortWithDateEle
	 * 用日启类型元素比较排序
	 * @param array $array 
	 * @param string $sortKey 
	 * @param bool $reverse 
	 * @param bool $reserveKey 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function sortWithDateEle($array, $sortKey, $reverse = false, $reserveKey = true) {
		self::$sortKey = $sortKey;
		self::$reverse = $reverse;
		if ($reserveKey){
			uasort($array, array(__CLASS__, 'cmpWithDateEle'));
		} else {
			usort($array, array(__CLASS__, 'cmpWithDateEle'));
		}
		self::$sortKey = null;
		self::$reverse = false;
		return $array;
	}
}