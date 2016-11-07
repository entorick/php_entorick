<?php
class Util_Log{
    public static function log($filePath, $content){
        file_put_contents($filePath, "[" . date('Y-m-d H:i:s') . "] " . $content, FILE_APPEND);
    }
}
