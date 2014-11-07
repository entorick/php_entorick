<?php
/*
 * 矩阵类
 * @create 2014-11-4
 * @author entorick11@qq.com
 */
class Math_Matrix{

    private $matrix = array(); // 矩阵的数据
    public $rowCount = 0;      // 行数
    public $columnCount = 0;   // 列数
    
    /**
     * 构造函数
     * @param array $arr // 二维数组
     * @return 
     */
    public function __construct($arr){
        $res = self::checkMatrix($arr);
        if ($res === false){
            return false;
        }
        $this->rowCount = $res['rows'];
        $this->columnCount = $res['columns'];
        $this->matrix = $arr;
    }
    
    /**
     * 获取矩阵的元素
     * 
     * @param int $rowOrder    // 行序号
     * @param int $columnOrder // 列序号
     * 
     * @return float $value    // 元素值
     */
    public function getElement($rowOrder, $columnOrder){
        if (!empty($this->matrix)){
            return $this->matrix[$rowOrder][$columnOrder];
        }
        return null;
    }
    
    /**
     * 获得矩阵的数组
     * @return array;
     */
    public function getMatrixToArray(){
        return $this->matrix;
    }
    
    /**
     * 设置矩阵元素值
     * 
     * @param int $rowOrder    // 行序号
     * @param int $columnOrder // 列序号
     * @param float $val       // 元素值
     * 
     * @return bool true | false // 成功 | 失败
     */
    public function setElement($rowOrder, $columnOrder, $val){
        if (empty($this->matrix)){
            return false;
        }
        if ($rowOrder + 1 > $this->rowCount || $columnOrder + 1 > $this->columnCount){
            return false;
        }
        $this->matrix[$rowOrder][$columnOrder] = $val;
        return true;
    }
    
    /**
     * 获取子矩阵
     * 
     * a1 b1 c1
     * a2 b2 c2
     * a3 b3 c3
     * 
     * A11 = b2 c2
     *       b3 c3
     *       
     * A12 = a2 c2
     *       a3 c3
     * 
     * @param int $rowOrder // 行序号
     * @param int $columnOrder // 列序号
     * 
     * @return Math_Matrix // 结果矩阵
     */
    public function getSubMatrix($rowOrder, $columnOrder){
        if ($rowOrder + 1 > $this->rowCount || $columnOrder + 1 > $this->columnCount){
            return false;
        }
        $matrix = $this->matrix;
        $result = array();
        for ($i = 0; $i < $this->rowCount; $i++){
            $tmpRowOrder = ($i > $rowOrder) ? $i - 1 : $i;
            for ($j = 0; $j < $this->columnCount; $j++){
                $tmpColumnOrder = ($j > $columnOrder) ? $j - 1 : $j;
                if ($i != $rowOrder && $j != $columnOrder){
                    $result[$tmpRowOrder][$tmpColumnOrder] = $matrix[$i][$j];
                }
            }
        }
        return new self($result);
    }
    
    /**
     * 矩阵乘法
     * 一个m行n列的矩阵与一个n行p列的矩阵可以相乘，得到的结果是一个m行p列的矩阵。
     * 其中的第i行第j列位置上的数为第一个矩阵第i行上的n个数与第二个矩阵第j列上的n个数对应相乘后所得的n个乘积之和。
     *
     * @param Math_Matrix $leftMatrix 左乘矩阵
     * @param Math_Matrix $rightMatrix 右乘矩阵
     *
     * @return  Math_Matrix 结果矩阵 | bool false 非矩阵
     */
    public static function multiplication($leftMatrix, $rightMatrix){
        if (!is_a($leftMatrix, __CLASS__) || !is_a($rightMatrix, __CLASS__)){
            return false;
        }
    
        if ($leftMatrix->columnCount != $rightMatrix->rowCount){
            return false; // 左乘矩阵的列数 != 右乘矩阵的行数，不能相乘
        }
    
        $result = array();
        /**
         * (m*n) * (n*p)
        */
        for ($m = 0; $m < $leftMatrix->rowCount; $m++){
            for ($p = 0; $p < $rightMatrix->columnCount; $p++){
                $tmp = 0;
                for ($n = 0; $n < $leftMatrix->columnCount; $n++){
                    $tmp += $leftMatrix->getElement($m, $n) * $rightMatrix->getElement($n, $p);
                }
                $result[$m][$p] = $tmp;
            }
        }
        return empty($result) ? false : new self($result);
    }
    
    /**
     * 逆矩阵
     * 
     * A*A(-1) = E
     *
     * @return Math_Matrix // 逆矩阵
     */
    public function inverse() {
        if (empty($this->matrix)){
            return null;
        }
        $ratio = 1 / $this->determinant();
        
        $tmp = new Math_Matrix($this->matrix); // 复制当前矩阵
        for ($i = 0; $i < $tmp->rowCount; $i++){
            for ($j = 0; $j < $tmp->columnCount; $j++){
                $val = pow(-1, $i + $j) * $this->getSubMatrixDeterminant($i, $j);
                $tmp->setElement($j, $i, $ratio * $val);
            }
        }
        return $tmp;
    }
    
    /**
     * 
     * 子矩阵的行列式
     * a1 b1 c1
     * a2 b2 c2
     * a3 b3 c3
     * 
     * (1,1) = |b2 c2|
     *         |b3 c3|
     *       
     * (1,2) = |a2 c2|
     *         |a3 c3|
     * 
     * @param int $rowOrder    // 行序号
     * @param int $columnOrder // 列序号
     * @return boolean|number  // 失败false，成功为子矩阵行列式结果
     */
    public function getSubMatrixDeterminant($rowOrder, $columnOrder){
        $subMatrix = $this->getSubMatrix($rowOrder, $columnOrder);
        if (!is_a($subMatrix, __CLASS__)){
            return false;
        }
        $result = $subMatrix->determinant();
        return $result;
    }
    
    /**
     * 求矩阵的行列式
     * a1 b1 c1
     * a2 b2 c2  =  a1b2c3+b1c2a3+c1a2b3-a3b2c1-b3c2a1-c3a2b1
     * a3 b3 c3
     * 
     * @return int 行列式结果
     */
    public function determinant(){
        if ($this->rowCount != $this->columnCount){
            return false; // 非正方形矩阵不能计算行列式
        }
        if ($this->rowCount == 1){
            return $this->getElement(0, 0);
        }
        if ($this->rowCount == 2){
            return $this->minMatrixDeterminant();
        }
        $result = false;
        for ($i = 0; $i < $this->columnCount; $i++){
            $tmp = $this->getElement(0, $i) * $this->getSubMatrixDeterminant(0, $i) * pow(-1, $i);
            $result = ($result === false) ? $tmp : $result + $tmp;
        }
        return $result;
    }
    
    /**
     * 最小的2*2矩阵行列式
     * @return float 结果
     */
    private function minMatrixDeterminant(){
        if ($this->rowCount != 2 || $this->rowCount != $this->columnCount){
            return false;
        }
        return $this->getElement(0, 0) * $this->getElement(1, 1) - $this->getElement(0, 1) * $this->getElement(1, 0);
    }
    
    /**
     * 打印矩阵
     */
    public function printMatrix(){
        foreach ($this->matrix as $row){
            foreach ($row as $element){
                echo $element . " ";
            }
            echo "\n";
        }
    }

    /**
     * 校验是否是一个矩阵
     *
     * @param array $matrix 被校验的数组
     *
     * @return array(
     *      'rows'   => 2, // 行数
     *      'columns' => 3, // 列数
     * )
     * | bool false // 非矩阵
     */
    private static function checkMatrix($matrix){
        if (!is_array($matrix)){
            return false;
        }
        $result = array(
            'rows'   => 0,
            'columns' => 0,
        );
        $result['rows'] = count($matrix); // 获得行数
    
        foreach ($matrix as $val){
            if ($result['columns'] == 0){
                $result['columns'] = count($val); // 获得列数
                continue;
            }
            if ($result['columns'] != count($val)){
                return false; // 每行列数不等，非矩阵
            }
        }
        return $result;
    }
}
