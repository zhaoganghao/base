<?php
namespace common\utils;

class NginxParser
{
    public $mapDate = [
        'pv' => 0,
        'uv' => 0,
    ];
    public $ipArray = [];
    public $map = [];
    public $pv = 0;
    public function parserDate($path){
        $file = fopen($path, "r");
        $pattern="/GET\s([\w-\.\/]*)\??([^\s]*)/";
        $patternIp="/^[\d\.]*/";
        while(!feof($file))
        {
            $line= fgets($file);//fgets()函数从文件指针中读取一行
            preg_match($pattern, $line, $matches);
            if(!$matches){
                continue;
            }
            preg_match($patternIp, $line, $matcheIp);
            $ipInt = ip2long($matcheIp[0]);
            $this->ipArray[$ipInt] = 0;
            if($matches[1] != '/api/recommend'){
                $this->mapDate['pv'] += 1;
            }
        }
        fclose($file);
    }
    public function parserHour($path){
        $file = fopen($path, "r");
        $pattern="/GET\s(\/api\/[\w-\.\/]*)\??([^\s]*)/";
        while(!feof($file))
        {
            $line= fgets($file);//fgets()函数从文件指针中读取一行
            preg_match($pattern, $line, $matches);
            if(!$matches){
                continue;
            }
            if(isset($this->map[$matches[1]])){
                $this->map[$matches[1]] += 1;
            }else{
                $this->map[$matches[1]] = 1;
            }
        }
        fclose($file);
    }
    public function parserMinute($path){
        $file = fopen($path, "r");
        $pattern="/GET\s(\/api\/[\w-\.\/]*)\??([^\s]*)/";
        while(!feof($file))
        {
            $line= fgets($file);//fgets()函数从文件指针中读取一行
            preg_match($pattern, $line, $matches);
            if(!$matches){
                continue;
            }
            $this->pv += 1;
        }
        fclose($file);
    }
    public function parserSourceDate($path){
        $file = fopen($path, "r");
        $pattern="/GET\s(\/api\/[\w-\.\/]*)\??([^\s]*)/";
        while(!feof($file))
        {
            $line= fgets($file);//fgets()函数从文件指针中读取一行
            preg_match($pattern, $line, $matches);
            if(!$matches){
                continue;
            }
            $params = $this->convertUrlQuery($matches[2]);
            $key = isset($params['s']) && $params['s'] ? $params['s'] : "10000";
            if(isset($this->map[$matches[1]]) && isset($this->map[$matches[1]][$key])){
                $this->map[$matches[1]][$key] += 1;
            }elseif(isset($this->map[$matches[1]]) && !isset($this->map[$matches[1]][$key])){
                $this->map[$matches[1]][$key]= 1;
            }else{
                $this->map[$matches[1]] = [$key => 1];
            }
        }
        fclose($file);
    }
    public function updateUvCount(){
        $this->mapDate['uv'] = count($this->ipArray);
    }
    public function parser($path){
        $file = fopen($path, "r");
        $pattern="/GET\s([\w-\.\/]*)\??([^\s]*)/";
        $patternIp="/^[\d\.]*/";
        while(!feof($file))
        {
            $line= fgets($file);//fgets()函数从文件指针中读取一行
            preg_match($pattern, $line, $matches);
            if(!$matches){
                continue;
            }
            preg_match($patternIp, $line, $matcheIp);
            $params = $this->convertUrlQuery($matches[2]);
            if($matches[1] == '/' || $matches[1] == '/index.html'){
                if(isset($params['s']) && $params['s']){
                    if(isset( $this->map['/index.html'][$params['s']])){
                        $this->map['/index.html'][$params['s']] += 1;
                    }else{
                        $this->map['/index.html'][$params['s']] = 1;
                    }
                }else{
                    $this->map['/index.html'][0] += 1;
                }
            }elseif($matches[1] == '/html/search.html'){
                if(isset($params['s']) && $params['s']){
                    if(isset( $this->map['/html/search.html'][$params['s']])){
                        $this->map['/html/search.html'][$params['s']] += 1;
                    }else{
                        $this->map['/html/search.html'][$params['s']] = 1;
                    }
                }else{
                    $this->map['/html/search.html'][0] += 1;
                }
            }elseif($matches[1] == '/html/detail.html'){
                if(isset($params['s']) && $params['s']){
                    if(isset( $this->map['/html/detail.html'][$params['s']])){
                        $this->map['/html/detail.html'][$params['s']] += 1;
                    }else{
                        $this->map['/html/detail.html'][$params['s']] = 1;
                    }
                }else{
                    $this->map['/html/detail.html'][0] += 1;
                }
            }elseif($matches[1] == '/html/more.html'){
                if(isset($params['s']) && $params['s']){
                    if(isset( $this->map['/html/more.html'][$params['s']])){
                        $this->map['/html/more.html'][$params['s']] += 1;
                    }else{
                        $this->map['/html/more.html'][$params['s']] = 1;
                    }
                }else{
                    $this->map['/html/more.html'][0] += 1;
                }
            }
        }
        fclose($file);
    }
    function convertUrlQuery($query)
    {
        if(!$query){
            return [];
        }
        $query = urldecode($query);
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            if($item && count($item) == 2){
                $params[$item[0]] = $item[1];
            }
        }
        return $params;
    }
}

?>
