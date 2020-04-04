<?php
define('IN_SYS', TRUE);
session_start(); 
if(!isset($_SESSION['Loginuid'])||!isset($_SESSION['Loginusername']))
{
    header("Location: index.php?r=illgal");
}
require_once 'config.php';
$method=$_GET["method"];
$timestampBase=time();
$timestamp=$_GET['t'];
$random=$_GET['r'];
$token= $_GET['token'];
$tokenBase= md5($sysname.$pass.$timestamp.$random);

if(($timestampBase-$timestamp)>1200 || $token!=$tokenBase)
{
header("Location: index.php?r=illgal");
}else{
if($method=="add")
{
$tokens=$_POST['token'];
$messages=$_POST['messages'];
$isAtAll=$_POST['isAtAll'];
$settimeBase=$_POST['settime'];
$ndays=$_POST['ndays'];
$atMobile=$_POST['atMobile'];
$data = array ('msgtype' => 'text','text' => array ('content' => $messages,),'at' => array ('atMobiles' => $atMobile,'isAtAll' => $isAtAll,),);
$data_string = json_encode($data);

$co0 = count($tokens);
for($i=0;$i<$co0;$i++)
{
$settime=strtotime($settimeBase);
$tokenBase = $tokens[$i];
$tokentrue = $database->select("token", ["token","uid"], ["tid" => $tokenBase]);
if ($tokentrue[0]["uid"] == $_SESSION["Loginuid"])
{$token = $tokentrue[0]["token"];}else{
    header("Location: time.php");
}
if(($settime-time())<=-600){header("Location: time.php");}
for($n=0;$n<$ndays;$n++){
$i1 = $database->insert("crontab", ["token" => $token,"messages" => $data_string,"settime" => $settime,"uid" => $_SESSION["Loginuid"]]);
$i2 = $database->insert("logs", ["tname" => $_SESSION["Loginusername"],"ttoken" => $token,"tmessages" => $data_string,"type" => "101","uid" => $_SESSION["Loginuid"]]);
$settime=$settime+86400;
}
}
header("Location: time.php");
}elseif($method=="del"){
    $cids=$_POST["urls"];
    $co1 = count($cids);
    for($i=0;$i<$co1;$i++)
    {
     $cid = $cids[$i];
     $datas = $database->select("crontab", ["token"], ["cid" => $cid]);
     $i1 = $database->update("crontab", ["status" => "2"],["cid" => $cid]);
     $i2 = $database->insert("logs", ["tname" => $_SESSION["Loginusername"],"ttoken" => $datas[0]["token"],"type" => "102","uid" => $_SESSION["Loginuid"]]);
    }
     header("Location: time.php");
}else{
 header("Location: index.php?r=illgal");
}
}
?>