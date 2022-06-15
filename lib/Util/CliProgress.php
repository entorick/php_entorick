<?php
/**
 * 命令行下显示进度
 */
class Util_CliProgress{
    private $_total = 0;
    private $_gap = 0;
    private $_timeGapBegin = 0;
    private $_remain = '';
    private $_gapTimes = 0;
    private $_perSecond = 0;
    private $_cur = 0;

    public function __construct($total){
        if (is_numeric($total)){
            $this->_total = $total;
            $this->_gap = $total / 10000;
            $this->_timeGapBegin = microtime(true);
        }
    }

    public function show($cur = null){
        if ($cur === null){
            $this->_cur++;
            $cur = $this->_cur;
        }
        if (($cur % $this->_gap) == 0 || $cur == $this->_total){

            $this->_gapTimes++;
            $timeCost = microtime(true) - $this->_timeGapBegin;
            if ($timeCost > 1){
                $this->_timeGapBegin = microtime(true);
                $perSecond = $this->_gap * $this->_gapTimes / $timeCost;
                $this->_perSecond = $perSecond;
                $left = $this->_total - $cur;
                $second = intval($left / $perSecond);
                $min = floor($second / 60);
                $hour = floor($min / 60);
                $second = $second % 60;
                $min = $min % 60;
                $this->_remain = $hour . "h " . $min . "m " . $second . "s";
                $this->_gapTimes = 0;
            }


            if ($cur >= $this->_total){
                printf("progress: [%-100s] %-5s%%,remain:%-15s\n", str_repeat('=', intval($cur / $this->_total * 100)) . ">", round($cur / $this->_total * 100, 2), $this->_remain);
            } else {
                printf("progress: [%-100s] %-5s%%,remain:%-15s\r", str_repeat('=', intval($cur / $this->_total * 100)) . ">", round($cur / $this->_total * 100, 2), $this->_remain);
            }
        }
    }

}
