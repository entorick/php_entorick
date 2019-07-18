<?php

class Util_TopN{
    private $_matchArr = [];

    CONST SORT_ORDER_ASC = 1;
    CONST SORT_ORDER_DESC = 2;

    private $_max = 0; // 最大保留个数
    private $_sortKey = ''; // 多维数组排序key
    private $_sortOrder = self::SORT_ORDER_ASC; // 默认小->大排序

    public function __construct($max = 0, $sortKey = null, $sortOrder = self::SORT_ORDER_ASC)
    {
        $this->_max = $max;
        $this->_sortKey = $sortKey; // 默认null用于一维数组
        $this->_sortOrder = $sortOrder;
    }


    public function add($v){
        $sortKey = null;
        if (is_array($v)){
            if (empty($this->_sortKey)) {
                $sortKey = 0;
            } else {
                $sortKey = $this->_sortKey;
            }

            if (is_array($v[$sortKey])){
                // 超过二维，不能添加
                return;
            }
        }

        // max比较大的话，应该二分查找
        foreach ($this->_matchArr as $k => $item){
            if ($sortKey == null){
                // 一维
                if ($this->_sortOrder == self::SORT_ORDER_ASC){
                    if ($v < $item){
                        $this->_matchArr[$k] = $v;
                        $v = $item; // 当前v改变，后面自动顺位
                    }
                }
                if ($this->_sortOrder == self::SORT_ORDER_DESC){
                    if ($v > $item){
                        $this->_matchArr[$k] = $v;
                        $v = $item; // 当前v改变，后面自动顺位
                    }
                }
            } else {
                // 二维
                if ($this->_sortOrder == self::SORT_ORDER_ASC){
                    if ($v[$sortKey] < $item[$sortKey]){
                        $this->_matchArr[$k] = $v;
                        $v = $item; // 当前v改变，后面自动顺位
                    }
                }
                if ($this->_sortOrder == self::SORT_ORDER_DESC){
                    if ($v[$sortKey] > $item[$sortKey]){
                        $this->_matchArr[$k] = $v;
                        $v = $item; // 当前v改变，后面自动顺位
                    }
                }
            }
        }

        if ($this->_max > 0){
            if (count($this->_matchArr) < $this->_max){
                $this->_matchArr[] = $v; // 这时$v已经变成排序最后一个了
            }
        } else {
            $this->_matchArr[] = $v;
        }
    }

    public function getResult(){
        return $this->_matchArr;
    }

}
