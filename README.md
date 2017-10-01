# QueryList-Rule-Baidu
QueryList Plugin: Baidu searcher. 

QueryList插件：百度搜索引擎

## Installation for QueryList4
```
composer require jaeger/querylist-rule-baidu
```

## API
- Baidu **baidu($pageNumber = 10)**:get Baidu Searcher.

class **Baidu**:
- Baidu **search($keyword)**:set search keyword.
- Baidu **setHttpOpt(array $httpOpt = [])**：Set the http option,see: [GuzzleHttp options](http://docs.guzzlephp.org/en/stable/request-options.html)
- int **getCount()**:Get the total number of search results.
- int **getCountPage()**:Get the total number of pages.
- Collection **page($page = 1,$realURL = false)**:Get search results

## Usage
- Installation Plugin

```php
use QL\QueryList;
use QL\Ext\Baidu;

$ql = QueryList::getInstance();
$ql->use(Baidu::class);
//or Custom function name
$ql->use(Baidu::class,'baidu');
```
- Example-1

```
$baidu = $ql->baidu(10)
$searcher = $baidu->search('QueryList');
$count = $searcher->getCount();
$data = $searcher->page(1);
$data = $searcher->page(2);

$searcher = $baidu->search('php');
$countPage = $searcher->getCountPage();
for ($page = 1; $page <= $countPage; $page++)
{
    $data = $searcher->page($page);
}
```

- Example-2

```
$searcher = $ql->baidu()->search('QueryList');
$data = $searcher->setHttpOpt([
    // Set the http proxy
    'proxy' => 'http://222.141.11.17:8118',
   // Set the timeout time in seconds
    'timeout' => 30,
])->page(1);
```

- Example-3

```
$baidu = $ql->baidu(3)
$searcher = $baidu->search('QueryList');

$data = $searcher->page(1);
print_r($data->all());

// Get real url
$data = $searcher->page(1,true);
print_r($data->all());
```
Out:

```
Array
(
    [0] => Array
        (
            [title] => QueryList|基于phpQuery的无比强大的PHP采集工具
            [link] => http://www.baidu.com/link?url=qRAXrUIcrxuLQ4Pn_rL25HvpDwugxgLkmwB74wTBuLflWaDTNY1d27gdxMwddbfn
        )
    [1] => Array
        (
            [title] => 介绍- QueryList指导文档
            [link] => http://www.baidu.com/link?url=NgoB517LCcb7tt37_x74uF0N-8pfhSemhA5qoB0SHf8HY9P_MwKbN80nf9zvd3V5
        )
    [2] => Array
        (
            [title] => PHP 用QueryList抓取网页内容 - wb145230 - 博客园
            [link] => http://www.baidu.com/link?url=kDkpY9eZ6CsiT1SWomRWEYPauHseHn2FseSdPnsOoulWCkD3DK6QMT75urFGHLyeG_M9yTD0BCm-s5jGQRi_S_
        )

)

Array
(
    [0] => Array
        (
            [title] => QueryList|基于phpQuery的无比强大的PHP采集工具
            [link] => http://www.querylist.cc/
        )
    [1] => Array
        (
            [title] => 介绍- QueryList指导文档
            [link] => http://doc.querylist.cc/
        )
    [2] => Array
        (
            [title] => PHP 用QueryList抓取网页内容 - wb145230 - 博客园
            [link] => http://www.cnblogs.com/wb145230/p/4716403.html
        )
)

```
