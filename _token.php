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
header("Location: index.php?r=illgal");
}else{

if($method=="add")
{
    $name=$_POST["name"];
    $token=$_POST["token"];
    $id = $database->insert("token", [
    "token" => $token,
    "name" => $name,
    "uid" => $_SESSION["Loginuid"]]);
    $id = $database->insert("logs", ["ttoken" => $token,"tname" => $name,"type" => "5","uid" => $_SESSION["Loginuid"]]);
header("Location: token.php");
}elseif($method=="del")
{

$tids=$_POST["urls"];
$n = count($tids);
for($i=0;$i<$n;$i++)
{
$tid = $tids[$i];
$datas = $database->select("token", ["uid","name"], ["tid" => $tid]);
if($datas[0]["uid"]==$_SESSION["Loginuid"]){
$database->delete("token", ["tid" => $tid]);
$id = $database->insert("logs", ["tname" => $datas[0]["name"],"type" => "6","uid" => $_SESSION["Loginuid"]]);
 header("Location: token.php");
}else{
 header("Location: index.php?r=illgal");
}

}
}else{
    header("Location: index.php?r=illgal");
}

}?>