<?php
$pass = "web";//web为默认密码，自行修噶
if (isset($_POST['Any-Proxy'])) {
    setcookie("Any-Proxy", $_POST['Any-Proxy'], time()+3600*24*366);
    header("Refresh:0");
}
if (strpos($_SERVER['REQUEST_URI'], $pass) != false) {
//新增路径密码 方便直接访问 格式：http://域名.com/pass/https://www.baidu.com    
$_SERVER['REQUEST_URI']= str_replace($pass."/", "", $_SERVER['REQUEST_URI']);
}
elseif ($_COOKIE['Any-Proxy'] != $pass) {
	header('HTTP/1.1 403');
    exit('<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"><style>body {margin: 0;width: 100vw;height: 100vh;background: #ecf0f3;display: flex;align-items: center;text-align: center;justify-content: center;place-items: center;overflow: hidden;font-family: poppins;}.container {margin-top:-50%;position: relative;width: 20rem;border-radius: 20px;padding: 2rem;box-sizing: border-box;background: #ecf0f3;box-shadow: 14px 14px 20px #cbced1, -14px -14px 20px white;}label,input,button {display: block;text-align: center;width: 100%;padding: 0;border: none;outline: none;box-sizing: border-box;margin-bottom: 4px;font-size: 1rem;}label {font-size: 1.5rem;letter-spacing: 1rem}input {background: #ecf0f3;padding: 10px;padding-left: 20px;height: 50px;font-size: 14px;border-radius: 50px;box-shadow: inset 6px 6px 6px #cbced1, inset -6px -6px 6px white;}button {margin-top: 20px;background: #1DA1F2;height: 40px;border-radius: 20px;cursor: pointer;font-weight: 900;box-shadow: 6px 6px 6px #cbced1, -6px -6px 6px white;transition: 0.5s;color: #fff;}button:hover {box-shadow: none;}</style></head><body><meta charset="UTF-8"><form method="post"><div class="container"> <label>密码</label> <input type="password" name="Any-Proxy" placeholder="后台$pass参数"> <button type="submit">访问</button> </div></form></body></html>');
}

$host = $_SERVER['HTTP_HOST'];
$path = $_SERVER['REQUEST_URI'];
$url = $_POST['urlss'];
$https = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https")) ? "https://" : "http://";
//$anyip值为1发送服务器IP头，值为2则发送随机IP，值为3发送客户端IP，仅在部分网站中有效
$anyip = 1;
if (substr($path, -2) == "~q") {
    del_cookie();
    header("Location: " . $https . $host);
    exit;
}
if ($url) {
    if (substr($url, 0, 4) != "http") {
        $url = "http://" . $url;
    }
    header("Location: " . $https . $host . "/" . $url);
    exit;
} elseif (substr($path, 1, 7) == "http://" || substr($path, 1, 8) == "https://") {
    if (!$url) {
        $url = substr($path, 1);
    }
    if (substr($url, 0, 4) != "http") {
        $url = "http://" . $url;
    }
    $PageUrl = parse_url($url);
    $PageUrl['query'] ? $query = "?" . $PageUrl['query'] : $query = "";
    $http = $PageUrl['scheme'] . "://";
    $PageUrls = $https . $host . $PageUrl['path'] . $query;
} elseif (!substr($path, 1)) {
    exit('<html><head><meta charset="utf-8"><meta name="viewport" content="width=520, user-scalable=no, target-densitydpi=device-dpi"><title>代理访问_Any-Proxy</title><link rel="stylesheet" type="text/css" href="//s0.pstatp.com/cdn/expire-1-M/bootswatch/3.4.0/paper/bootstrap.min.css"><style type="text/css">.row{margin-top:100px}.page-header{margin-bottom:90px}.expand-transition{margin-top:150px;-webkit-transition:all.5s ease;transition:all.5s ease}</style></head><body><div id="app" class="container"><div class="row row-xs"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-10 col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"><div class="page-header"><h3 class="text-center h3-xs">Any-Proxy</h3></div><form method="post"><div class="form-group " id="input-wrap"><label class="control-label" for="inputContent">请输入需访问的链接：</label><input type="text" id="inputContent" class="form-control" name="urlss" placeholder="http://" required="required"></div><div class="text-right"><input type="submit" class="input_group_addon btn btn-primary" value="代理" style="width:20rem;margin: 0 auto;display: table;"></div></div></form></div></div><div align="center" class="expand-transition"><p>在当前链接末尾输入<b style="color: #f00;"> ~q </b>可以退出当前页面回到首页</p><p>在域名后面加上链接地址即可访问，如 ' . $https . $host . '/http://ip38.com/ </p></div></div><footer class="footer navbar-fixed-bottom" style="text-align:center"><div class="container"><p>请勿访问您当地法律所禁止的网页，否则后果自负。</p><p>©Powered by <a href="https://github.com/yitd/Any-Proxy">Any-Proxy</a></p></div></footer></body></html>');
}
//代理的域名及使用的协议最后不用加/
$target_host = $http . $PageUrl['host'];
//处理代理的主机得到协议和主机名称
$protocal_host = parse_url($target_host);
//以.分割域名字符串
$rootdomain = explode(".", $host);
//获取数组的长度
$lenth = count($rootdomain);
//获取顶级域名
$top = "." . $rootdomain[$lenth - 1];
//获取主域名
$root = "." . $rootdomain[$lenth - 2];
//判断请求的域名或ip是否合法
if (strstr($target_host, ".") === false || $protocal_host['host'] == $host) {
    echo "<script>alert('请求的域名有误！');window.location.href='" . $https . $host . "';</script>";
    exit;
}
$PageIP = gethostbyname($protocal_host['host']);
if (filter_var($PageIP, FILTER_VALIDATE_IP)) {
    if (filter_var($PageIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        echo "<script>alert('请求的ip被禁止！');window.location.href='" . $https . $host . "';</script>";
        exit;
    }
} else {
    echo "<script>alert('请求的域名有误！');window.location.href='" . $https . $host . "';</script>";
    exit;
}
// set URL and other appropriate options
$aAccess = curl_init();
curl_setopt($aAccess, CURLOPT_URL, $target_host . $PageUrl['path'] . $query);
curl_setopt($aAccess, CURLOPT_HEADER, true);
curl_setopt($aAccess, CURLOPT_RETURNTRANSFER, true);
curl_setopt($aAccess, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($aAccess, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($aAccess, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($aAccess, CURLOPT_TIMEOUT, 10);
curl_setopt($aAccess, CURLOPT_BINARYTRANSFER, true);
//关系数组转换成字符串，每个键值对中间用=连接，以; 分割
function array_to_str($array) {
    $string = "";
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (!empty($string)) $string.= "; " . $key . "=" . $value;
            else $string.= $key . "=" . $value;
        }
    } else {
        $string = $array;
    }
    return urldecode($string);
}
if ($_SERVER['HTTP_REFERER']) {
    $referer = str_replace($host, $protocal_host['host'], $_SERVER['HTTP_REFERER']);
}
if ($anyip == "1") {
    $remoteip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ($anyip == "2") {
    $remoteip = mt_rand(1, 255) . "." . mt_rand(1, 255) . "." . mt_rand(1, 255) . "." . mt_rand(1, 255);
} elseif (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $remoteip = $_SERVER['REMOTE_ADDR'];
} else {
    $remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
//$headers = get_client_header();
$headers = array();
$headers[] = "Accept-language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$headers[] = "Referer: " . $referer;
$headers[] = "CLIENT-IP: " . $remoteip;
$headers[] = "X-FORWARDED-FOR: " . $remoteip;
$headers[] = "Cookie: " . array_to_str($_COOKIE);
$headers[] = "user-agent: " . $_SERVER['HTTP_USER_AGENT'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $headers[] = "Content-Type: " . $_SERVER['CONTENT_TYPE'];
    curl_setopt($aAccess, CURLOPT_POST, 1);
    curl_setopt($aAccess, CURLOPT_POSTFIELDS, http_build_query($_POST));
}
curl_setopt($aAccess, CURLOPT_HTTPHEADER, $headers);
// grab URL and pass it to the browser
$sResponse = curl_exec($aAccess);
//判断请求url是否被重定向
$locurl = parse_url(curl_getinfo($aAccess, CURLINFO_EFFECTIVE_URL));
if ($locurl['scheme'] . "://" . $locurl['host'] != $protocal_host['scheme'] . "://" . $protocal_host['host']) {
    header("Location: " . $https . $host . "/" . curl_getinfo($aAccess, CURLINFO_EFFECTIVE_URL));
    exit;
}
list($headerstr, $sResponse) = parse_header($sResponse);
$headarr = explode("\r\n", $headerstr);
foreach ($headarr as $h) {
    if (strlen($h) > 0) {
        if (strpos($h, 'ETag') !== false) continue;
        if (strpos($h, 'Connection') !== false) continue;
        if (strpos($h, 'Cache-Control') !== false) continue;
        if (strpos($h, 'Content-Length') !== false) continue;
        if (strpos($h, 'Transfer-Encoding') !== false) continue;
        if (strpos($h, 'HTTP/1.1 100 Continue') !== false) continue;
        if (strpos($h, 'Strict-Transport-Security') !== false) continue;
        if (strpos($h, 'Set-Cookie') !== false) {
            $targetcookie = $h . ";";
            //如果返回到客户端cookie不正常可把下行中的$root . $top换成$host
            $res_cookie = preg_replace("/domain=.*?;/", "domain=" . $root . $top . ";", $targetcookie);
            $h = substr($res_cookie, 0, strlen($res_cookie) - 1);
            header($h, false);
        } else {
            header($h);
        }
    }
}
function del_cookie() {
    foreach ($_COOKIE as $key => $value) {
        if ($key == "Any-Proxy") continue;
        setcookie($key, null, time() - 3600, "/");
    }
}
function get_client_header() {
    $headers = array();
    foreach ($_SERVER as $k => $v) {
        if (strpos($k, 'HTTP_') === 0) {
            $k = strtolower(preg_replace('/^HTTP/', '', $k));
            $k = preg_replace_callback('/_\w/', 'header_callback', $k);
            $k = preg_replace('/^_/', '', $k);
            $k = str_replace('_', '-', $k);
            if ($k == 'Host') continue;
            $headers[] = "$k: $v";
        }
    }
    return $headers;
}
function header_callback($str) {
    return strtoupper($str[0]);
}
function parse_header($sResponse) {
    list($headerstr, $sResponse) = explode("\r\n\r\n", $sResponse, 2);
    $ret = array(
        $headerstr,
        $sResponse
    );
    if (preg_match('/^HTTP\/1\.1 \d{3}/', $sResponse)) {
        $ret = parse_header($sResponse);
    }
    return $ret;
}
//解决中文乱码
$charlen = stripos($sResponse, "charset");
if (stristr(substr($sResponse, $charlen, 18) , "GBK") || stristr(substr($sResponse, $charlen, 18) , "GB2312")) {
    $sResponse = mb_convert_encoding($sResponse, "UTF-8", "GBK,GB2312,BIG5");
}
header("Pragma: no-cache");
// close cURL resource, and free up system resources
$pregRule = "/=[\'|\"](?!\/\/)(?:\/)(.*?)[\'|\"]/s";
$sResponse = preg_replace($pregRule, '="/' . $protocal_host['scheme'] . '://' . $protocal_host['host'] . '/${1}${2}"', $sResponse);
$pregRule = "/=[\'|\"](?:http)(.*?)[\'|\"]/";
$sResponse = preg_replace($pregRule, '="/http${1}${3}"', $sResponse);
$pregRule = "/=[\'|\"](?:\/\/)(.*?)[\'|\"]/";
$sResponse = preg_replace($pregRule, '="/' . $http . '${1}${3}"', $sResponse);
//以下两行代码可添加base
$pregRule = "/<head>/";
$sResponse = preg_replace($pregRule, '<head><base href="' . $https . $host . '/' . $protocal_host['scheme'] . '://' . $protocal_host['host'] . '/">', $sResponse);
curl_close($aAccess);
echo $sResponse;
