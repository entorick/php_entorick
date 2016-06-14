<?php
/**
 * 命令行下显示进度
 */
class Util_CliProgress{
    private $_total = 0;
    private $_gap = 0;

    public function __construct($total){
        if (is_numeric($total)){
            $this->_total = $total;
            $this->_gap = $total / 10000;
        }
    }

    public function show($cur){
        if (($cur % $this->_gap) == 0 || $cur == $this->_total){
            printf("progress: [%-100s] %s%%\r", str_repeat('=', intval($cur / $this->_total * 100)) . ">", round($cur / $this->_total * 100, 2));
        }
    }

}
