<?php

namespace Medoo;

if(!defined('IN_SYS')) {
exit('禁止访问');
}

require_once (dirname(__FILE__) . '/assets/sql/Medoo.php');

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => '',//数据库名
    'server' => '',//数据库服务器ip或域名
    'username' => '',//数据库用户名
    'password' => '',//数据库密码
    'charset' => 'utf8mb4'
]);

$sitename="";//站点名称
$sysname="";//随便填为了安全
$pass="";//随便填为了安全
$icp="";//备案号
//以下非请勿动
$version="1.5";

$h=date('G');
if ($h<11) $welcome='早上好,';
else if ($h<17) $welcome='下午好,';
else $welcome='晚上好,';

if (getenv("HTTP_CLIENT_IP"))  
$ip = getenv("HTTP_CLIENT_IP");  
else if(getenv("HTTP_X_FORWARDED_FOR"))  
$ip = getenv("HTTP_X_FORWARDED_FOR");  
else if(getenv("REMOTE_ADDR"))  
$ip = getenv("REMOTE_ADDR");  
else $ip = "Unknow";