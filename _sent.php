<?php 
define('IN_SYS', TRUE);
session_start(); 
require_once 'config.php';
if(!isset($_SESSION['Loginuid'])||!isset($_SESSION['Loginusername']))
{
    header("Location: index.php?r=illgal");
}
$method=$_GET["method"];
$timestampBase=time();
$timestamp=$_GET['t'];
$random=$_GET['r'];
$token= $_GET['token'];
$tokenBase= md5($sysname.$pass.$timestamp.$random);

if(($timestampBase-$timestamp)>1200 || $token!=$tokenBase)
{
header("Location: sent.php?r=illgal");
}else{

$urls=$_POST['urls'];
$messages=$_POST['messages'];
$isAtAll=$_POST['isAtAll'];
$type1=$_POST['type1'];
$titles=$_POST['titles'];
$messageUrl=$_POST['messageUrl'];
$picUrl=$_POST['picUrl'];
$atMobile=$_POST['atMobile'];

if ($type1 == "text"){
$data = array ('msgtype' => 'text','text' => array ('content' => $messages,),'at' => array ('atMobiles' => $atMobile,'isAtAll' => $isAtAll,),);
}elseif ($type1 == "link"){
$data = array ('msgtype' => 'link','link' => array ('text' => $messages,'title' => $titles,'picUrl' =>$picUrl ,'messageUrl' => $messageUrl,),);
}elseif ($type1 == "markdown"){
$data = array ('msgtype' => 'markdown','markdown' => array ('title' => $titles,'text' => $messages,),);
}else{
header("Location: sent.php?r=illgal");
}


$data_string = json_encode($data);

$n = count($urls);
$suc=0;
$err="error:";
for($i=0;$i<$n;$i++)
{
$tokenBase = $urls[$i];
$tokentrue = $database->select("token", ["token","uid"], ["tid" => $tokenBase]);

if ($tokentrue[0]["uid"] == $_SESSION["Loginuid"])
{
$url = $tokentrue[0]["token"];
$url1="https://oapi.dingtalk.com/robot/send?access_token=".$url;
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
curl_setopt($ch, CURLOPT_URL, "$url1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
$retBase = curl_exec($ch);
curl_close($ch);
$ret=json_decode($retBase,true);
}else{
    $retBase=$tokenBase."不属于你啊。";
    $ret="Error!";
    }
if ($ret["errmsg"]=="ok" && $ret["errcode"]=="0" )
{
    $suc=$suc+1;
    $id = $database->insert("logs", ["ttoken" => $url,"tmessages" => $data_string,"type" => "4","status" => "1","uid" => $_SESSION["Loginuid"]]);
}else{
    $err=$err."***".$retBase."***";
    $id = $database->insert("logs", ["ttoken" => $url,"tmessages" => $data_string,"error" => $retBase,"type" => "4","status" => "-1","uid" => $_SESSION["Loginuid"]]);
}
}

if($suc==$n){
header("Location: sent.php?r=success");
}else{
header("Location: sent.php?r=wrong&e=".$err);
}

}
?>