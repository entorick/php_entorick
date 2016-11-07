<?php


$str1 = "Ln_上海派维网络科技有限公司-Ls_3102282030395";
$str2 = "Ln_上海派维网络科技有限公司";


$pattern = "/^Ln_(\w*)(-Ls_\d*)?$/";
preg_match($pattern, $str1, $match);

var_export($match);
