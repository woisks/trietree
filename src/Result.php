<?php
declare(strict_types=1);


namespace Woisk\TrieTree;


/**
 * Class Result
 *
 * @package Woisk\TrieTree
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/6/3 15:10
 */
class Result
{
    /**
     * nodeTree  2019/6/3 20:25
     *
     * @var  array
     */
    private $nodeTree = [];

    /**
     * getTreeWord 2019/6/3 18:47
     *
     * @param string $word
     * @param int    $deep
     *
     * @return array
     */
    public function words(string $word, int $deep = 0)
    {
        $search = trim($word);
        if ($deep === 0) {
            $deep = 999;
        }
        $word_keys = $this->convertStrToH($search);
        $tree = &$this->nodeTree;
        $key_count = count($word_keys);
        $words = [];
        foreach ($word_keys as $key => $val) {
            if (isset($tree[$val])) {
                //检测当前词语是否已命中
                if ($key == $key_count - 1 && $tree[$val]['end'] == true) {

                    $words = array_merge($words, $tree[$val]['list']);

                }
                $tree = &$tree[$val]["child"];
            } else {

                return [];
            }
        }
        $this->_getTreeWord($tree, $deep, $words);

        return $words;
    }

    /**
     * _getTreeWord 2019/6/3 18:47
     *
     * @param       $child
     * @param       $deep
     * @param array $words
     *
     * @return void
     */
    private function _getTreeWord(&$child, $deep, &$words = [])
    {
        foreach ($child as $node) {
            if ($node['end'] == true) {
                $words = array_merge($words, $node['list']);
            }
            if (!empty($node['child']) && $deep >= count($words)) {
                $this->_getTreeWord($node['child'], $deep, $words);
            }
        }
    }

    /**
     * convertStrToH 2019/6/3 18:47
     *
     * @param string $str
     *
     * @return array
     */
    private function convertStrToH(string $str)
    {
        $len = strlen($str);
        $chars = [];
        for ($i = 0; $i < $len; $i++) {
            $ascii_code = ord($str[$i]);
            if (($ascii_code >> 7) == 0) {
                $chars[] = dechex(ord($str[$i]));
            } elseif (($ascii_code >> 4) == 15) {    //1111 xxxx, 四字节
                if ($i < $len - 3) {
                    $chars[] = dechex(ord($str[$i])) . dechex(ord($str[$i + 1])) . dechex(ord($str[$i + 2])) . dechex(ord($str[$i + 3]));
                    $i += 3;
                }
            } elseif (($ascii_code >> 5) == 7) {    //111x xxxx, 三字节
                if ($i < $len - 2) {
                    $chars[] = dechex(ord($str[$i])) . dechex(ord($str[$i + 1])) . dechex(ord($str[$i + 2]));
                    $i += 2;
                }
            } elseif (($ascii_code >> 6) == 3) {    //11xx xxxx, 2字节
                if ($i < $len - 1) {
                    $chars[] = dechex(ord($str[$i])) . dechex(ord($str[$i + 1]));
                    $i++;
                }
            }
        }

        return $chars;
    }

}