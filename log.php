<?php
define('IN_SYS', TRUE);
session_start();
if(!isset($_SESSION['Loginuid'])||!isset($_SESSION['Loginusername']))
{
    header("Location: index.php?r=illgal");
}
require_once "config.php";
$timestamp=time();
$random=rand();
$token= md5($sysname.$pass.$timestamp.$random);
?>

<html>
<head>
<meta charset="UTF-8">
<title><?php echo $sitename;?>--日志查看</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
  <link rel="stylesheet" href="assets/css/amazeui.min.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/amazeui.ie8polyfill.min.js"></script>
  <script src="assets/js/amazeui.min.js"></script>
  <script src="assets/js/modernizr.js"></script>
<style>
.header {
	text-align: center;
}
	.header h1 {
		font-size: 150%;
		color: #333;
		margin-top: 15px;
	}
</style>
</head>
<div class="am-g">
<div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">

<ul class="am-nav am-nav-tabs am-nav-justify">
  <li><a href="sent.php">发送消息</a></li>
  <li><a href="time.php">定时发送</a></li>
  <li><a href="token.php">凭据管理</a></li>
  <li class="am-active"><a href="log.php">日志查看</a></li>
  <li><a href="index.php">安全退出</a></li>
</ul>

<div class="header">
<div class="am-g">
<h1><?php echo $sitename;?>--日志查看</h1>
    <p>Design by Charlie.Pan</p>
</div>
<hr />
</div>

<?php
echo $welcome;
echo $_SESSION['Loginusername'];
echo "<br><br>";
echo "Logs：";
?>
<hr />
<table class="am-table am-table-bordered">
    <thead>
        <tr>
            <th>lid</th>
            <th>tname</th>
            <th>ttoken</th>
            <th>type</th>
            <th>status</th>
            <th>createtime</th>
        </tr>
    </thead>
    <tbody>
<?php
$datas = $database->select("logs", ["lid","tname","ttoken","type","status","createtime"], ["uid" => $_SESSION['Loginuid']]);
foreach($datas as $data)
      {
        
        if($data["status"]=="1"){
            $statusres="成功！";
        }else if ($data["status"]=="-1"){
            $statusres="失败！";
        }else{
            $statusres="未知值：".$data["status"];
        }

        if($data["type"]=="1"){
            $typeres="登录";
        }elseif($data["type"]=="2"){
            $typeres="注销";
        }elseif($data["type"]=="4"){
            $typeres="普通发送";
        }elseif($data["type"]=="5"){
            $typeres="增加凭据";
        }elseif($data["type"]=="6"){
            $typeres="删除凭据";
        }elseif($data["type"]=="9"){
            $typeres="定时发送";
         }elseif($data["type"]=="100"){
            $typeres="定时服务访问监控";
        }elseif($data["type"]=="101"){
            $typeres="增加定时";
        }elseif($data["type"]=="102"){
            $typeres="删除定时";
        }else{
            $typeres=$data["type"];
        }

        $datan1 = $database->select("token", ["tid","name"], ["AND" =>["token" => $data["ttoken"],"uid" => $_SESSION['Loginuid']]]);
        if(!isset($datan1[0]["tid"]) && empty($datan1[0]["tid"]) && !isset($datan1[0]["name"]) && empty($datan1[0]["name"]))
        {
            $tokenres=$data["ttoken"];
        }else{
            $tokenres=$datan1[0]["tid"]."--".$datan1[0]["name"];
        }

        echo"<tr>";
        echo "<td>".$data["lid"] ."</td><td>".$data["tname"]."</td><td>".$tokenres."</td><td>".$typeres."</td><td>".$statusres."</td><td>".$data["createtime"]."</td>";
        echo"</tr>";
      }
?>
    </tbody>
</table>
<br />
<hr />
<p>©<?php echo date('Y'); ?> Design by Charlie.Pan|<?php echo $sitename;echo " V";echo $version;?>|仅供团队开发测试使用|<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo $icp; ?></a></p>
</div>
</div>

</body>
</html>