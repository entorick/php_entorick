<?php

/**
 * 最长公共子串算法计算相似度
 */
class String_LCS
{
    public static function StringSimilar($str1, $str2)
    {
        if ($str1 == '' || $str2 == '') {
            return 0;
        }
        $same = self::LCS_cn($str1, $str2);
        $ret = $same * 2 / (mb_strlen($str1) + mb_strlen($str2));
        $ret = round($ret, 2) * 100;
        return $ret;
    }

    /**
     * 连续多次比对缓存模式
     * @var array
     */
    static $cache = [];
    /**
     * 拆分字符串
     * @param $string
     * @param string $encoding
     * @return array
     */
    private static function mbStringToArray($string, $encoding = 'UTF-8')
    {
        $originalString = $string;
        if (isset(self::$cache[$originalString])) {
            return self::$cache[$originalString];
        }
        $arrayResult = array();

        while ($iLen = mb_strlen($string, $encoding)) {
            array_push($arrayResult, mb_substr($string, 0, 1, $encoding));
            $string = mb_substr($string, 1, $iLen, $encoding);
        }

        self::$cache[$originalString] = $arrayResult;
        return $arrayResult;
    }

    /**
     * 多次连续比对缓存优化
     * @var array
     */
    static $matrixCache = [];
    private static function getEmptyMatrix($len)
    {
        if (isset(self::$matrixCache[$len])) {
            $dp = self::$matrixCache[$len];
            for ($i = 0; $i <= $len; $i++) {
                $dp[$i][0] = 0;
                $dp[0][$i] = 0;
            }
        } else {
            $dp = array();
            for ($i = 0; $i <= $len; $i++) {
                $dp[$i] = array();
                $dp[$i][0] = 0;
                $dp[0][$i] = 0;
            }
        }
        self::$matrixCache[$len] = $dp;
        return $dp;
    }

    private static function LCS_cn($str1, $str2, $encoding = 'UTF-8')
    {
        $mb_len1 = mb_strlen($str1, $encoding);
        $mb_len2 = mb_strlen($str2, $encoding);

        $mb_str1 = self::mbStringToArray($str1, $encoding);
        $mb_str2 = self::mbStringToArray($str2, $encoding);

        $len = $mb_len1 > $mb_len2 ? $mb_len1 : $mb_len2;

        $dp = self::getEmptyMatrix($len);

        for ($i = 1; $i <= $mb_len1; $i++) {
            for ($j = 1; $j <= $mb_len2; $j++) {
                if ($mb_str1[$i - 1] == $mb_str2[$j - 1]) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
                } else {
                    $dp[$i][$j] = $dp[$i - 1][$j] > $dp[$i][$j - 1] ? $dp[$i - 1][$j] : $dp[$i][$j - 1];
                }
            }
        }

        return $dp[$mb_len1][$mb_len2];
    }
}
