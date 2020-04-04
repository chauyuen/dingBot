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
$username=$_POST["username"];
$password=md5($_POST["password"]);
$datas = $database->select("users", ["uid","status","password"], ["username" => $username]);

if ($datas[0]["password"]!=$password)
    {
        $id = $database->insert("logs", ["tname" => $username,"ttoken" => $ip,"type" => "1","status" => "-1","uid" => "214748364"]);
        header("Location: index.php?r=uperror");
    }elseif($datas[0]["status"]=="-1"){
        $id = $database->insert("logs", ["tname" => $username,"ttoken" => $ip,"type" => "1","status" => "-1","uid" => "214748364"]);
        header("Location: index.php?r=blacklist");
    }elseif($datas[0]["password"]==$password && $datas[0]["status"]!="-1"){
        $uid=$datas[0]["uid"];
        $_SESSION['Loginuid']=$uid;
        $_SESSION['Loginusername']=$username;
        $id = $database->insert("logs", ["tname" => $username,"ttoken" => $ip,"type" => "1","status" => "1","uid" => $_SESSION["Loginuid"]]);
        header("Location: sent.php");
    }else{
        $id = $database->insert("logs", ["tname" => $username,"ttoken" => $ip,"type" => "1","status" => "-1","uid" => "214748364"]);
        header("Location: index.php?r=error");
    }
}

?>