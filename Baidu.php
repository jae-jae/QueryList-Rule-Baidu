<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 2017/10/1
 * Baidu searcher
 */

namespace QL\Ext;

use QL\Contracts\PluginContract;
use QL\QueryList;

class Baidu implements PluginContract
{
    protected $ql;
    protected $keyword;
    protected $pageNumber = 10;
    protected $httpOpt = [];
    const API = 'https://www.baidu.com/s';
    const RULES = [
      'title' => ['h3','text'],
      'link' => ['h3>a','href']
    ];
    const RANGE = '.result';

    public function __construct(QueryList $ql, $pageNumber)
    {
        $this->ql = $ql->rules(self::RULES)->range(self::RANGE);
        $this->pageNumber = $pageNumber;
    }

    public static function install(QueryList $queryList, ...$opt)
    {
        $name = $opt[0] ?? 'baidu';
        $queryList->bind($name,function ($pageNumber = 10){
            return new Baidu($this,$pageNumber);
        });
    }

    public function setHttpOpt(array $httpOpt = [])
    {
        $this->httpOpt = $httpOpt;
        return $this;
    }

    public function search($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    public function page($page = 1,$realURL = false)
    {
        return $this->query($page)->query()->getData(function ($item) use($realURL){
            $realURL && $item['link'] = $this->getRealURL($item['link']);
            return $item;
        });
    }

    public function getCount()
    {
        $count = 0;
        $text =  $this->query(1)->find('.nums')->text();
        if(preg_match('/[\d,]+/',$text,$arr))
        {
            $count = str_replace(',','',$arr[0]);
        }
        return (int)$count;
    }

    public function getCountPage()
    {
        $count = $this->getCount();
        $countPage = ceil($count / $this->pageNumber);
        return $countPage;
    }

    protected function query($page = 1)
    {
        $this->ql->get(self::API,[
            'wd' => $this->keyword,
            'rn' => $this->pageNumber,
            'pn' => $this->pageNumber * ($page-1)
        ],$this->httpOpt);
        return $this->ql;
    }

    protected  function getRealURL($url)
    {
        //得到百度跳转的真正地址
        $header = get_headers($url,1);
        if (strpos($header[0],'301') || strpos($header[0],'302'))
        {
            if(is_array($header['Location']))
            {
                //return $header['Location'][count($header['Location'])-1];
                return $header['Location'][0];
            }
            else
            {
                return $header['Location'];
            }
        }
        else
        {
            return $url;
        }
    }

}