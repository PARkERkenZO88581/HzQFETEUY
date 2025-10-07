<?php
// 代码生成时间: 2025-10-08 03:43:26
class MatrixOperations {

    /**
     * Add two matrices
     *
     * @param array $matrix1 The first matrix
     * @param array $matrix2 The second matrix
     * @return array The sum of the two matrices
     * @throws InvalidArgumentException If matrices are not the same size
     */
    public static function add($matrix1, $matrix2) {
        if (count($matrix1) !== count($matrix2) || count($matrix1[0]) !== count($matrix2[0])) {
            throw new InvalidArgumentException('Matrices must be the same size');
        }

        $result = array();
        foreach ($matrix1 as $i => $row1) {
            $result[] = array_map(function ($val1, $val2) {
                return $val1 + $val2;
            }, $row1, $matrix2[$i]);
        }

        return $result;
    }


    /**
     * Subtract two matrices
     *
     * @param array $matrix1 The first matrix
     * @param array $matrix2 The second matrix
     * @return array The difference of the two matrices
     * @throws InvalidArgumentException If matrices are not the same size
     */
    public static function subtract($matrix1, $matrix2) {
        if (count($matrix1) !== count($matrix2) || count($matrix1[0]) !== count($matrix2[0])) {
            throw new InvalidArgumentException('Matrices must be the same size');
        }

        $result = array();
        foreach ($matrix1 as $i => $row1) {
            $result[] = array_map(function ($val1, $val2) {
                return $val1 - $val2;
            }, $row1, $matrix2[$i]);
        }

        return $result;
    }


    /**
     * Multiply two matrices
     *
     * @param array $matrix1 The first matrix
     * @param array $matrix2 The second matrix
     * @return array The product of the two matrices
     * @throws InvalidArgumentException If the number of columns in the first matrix does not match the number of rows in the second matrix
     */
    public static function multiply($matrix1, $matrix2) {
        if (count($matrix1[0]) !== count($matrix2)) {
            throw new InvalidArgumentException('The number of columns in the first matrix must match the number of rows in the second matrix');
        }

        $result = array();
        foreach ($matrix1 as $row1) {
            $result[] = array_map(function ($val1, $row2) {
                return array_sum(array_map(function ($val1, $val2) {
                    return $val1 * $val2;
                }, $row1, $row2));
            }, $matrix2);
        }

        return $result;
    }


    /**
     * Calculate the determinant of a 2x2 matrix
     *
     * @param array $matrix A 2x2 matrix
     * @return float The determinant of the matrix
     * @throws InvalidArgumentException If the matrix is not 2x2
     */
    public static function determinant2x2($matrix) {
        if (count($matrix) !== 2 || count($matrix[0]) !== 2) {
            throw new InvalidArgumentException('Matrix must be 2x2');
        }

        return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
    }

}
