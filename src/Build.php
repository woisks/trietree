<?php
declare(strict_types=1);

namespace Woisk\TrieTree;

/**
 * Class Build
 *
 * @package Woisk\TrieTree
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/6/3 15:00
 */
class Build
{
    /**
     * nodeTree  2019/6/3 15:00
     *
     * @var  array
     */
    protected $nodeTree = [];

    /**
     * getTree 2019/6/3 15:00
     *
     *
     * @return array
     */
    public function getTree()
    {
        return $this->nodeTree;
    }

    /**
     * creatTree 2019/6/3 14:39
     *
     * @param string $str
     * @param string $name
     * @param array  $data
     *
     * @return $this
     */
    public function creatTree(string $str, string $name = '', array $data = [])
    {
        $childTree = &$this->nodeTree;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {

            $ascii_code = ord($str[$i]);
            $code = null;
            $word = null;
            $is_end = false;

            if (($ascii_code >> 7) == 0) {

                $code = dechex(ord($str[$i]));
                $word = $str[$i];

            } elseif (($ascii_code >> 4) == 15) {    //1111 xxxx, 四字节

                if ($i < $len - 3) {
                    $code = dechex(ord($str[$i])) . dechex(ord($str[$i + 1])) . dechex(ord($str[$i + 2])) . dechex(ord($str[$i + 3]));
                    $word = $str[$i] . $str[$i + 1] . $str[$i + 2] . $str[$i + 3];
                    $i += 3;
                }

            } elseif (($ascii_code >> 5) == 7) {    //111x xxxx, 三字节

                if ($i < $len - 2) {
                    $code = dechex(ord($str[$i])) . dechex(ord($str[$i + 1])) . dechex(ord($str[$i + 2]));
                    $word = $str[$i] . $str[$i + 1] . $str[$i + 2];
                    $i += 2;
                }

            } elseif (($ascii_code >> 6) == 3) {    //11xx xxxx, 2字节

                if ($i < $len - 1) {
                    $code = dechex(ord($str[$i])) . dechex(ord($str[$i + 1]));
                    $word = $str[$i] . $str[$i + 1];
                    $i++;
                }

            }

            if ($i == ($len - 1)) {
                $is_end = true;
                $str = $name;

            }
            $childTree = &$this->appendWordToTree($childTree, $code, $word, $is_end, $data, $str);
        }
        unset($childTree);

        return $this;
    }


    /**
     * appendWordToTree 2019/6/3 18:47
     *
     * @param array  $tree
     * @param string $code
     * @param string $word
     * @param bool   $end
     * @param array  $data
     * @param string $full_str
     *
     * @return mixed
     */
    private function &appendWordToTree(array &$tree, string $code, string $word, bool $end = false, array $data = [], string $full_str = '')
    {
        if (!isset($tree[$code])) {
            $tree[$code] = [
                'end'   => $end,
                'child' => [],
                'value' => $word,
            ];
        }

        if ($end) {
            $tree[$code]['end'] = true;

            $is_change = false;
            if (isset($tree[$code]["list"]) && count($tree[$code]["list"]) > 0) {
                foreach ($tree[$code]["list"] as &$node) {
                    if ($node['word'] == $full_str) {
                        $node['data'] = $data;
                        $is_change = true;
                        break;
                    }
                }
            }
            if (!$is_change) {
                $tree[$code]['list'][] = ['word' => $full_str, 'data' => $data];
            }

        }

        return $tree[$code]['child'];
    }


}