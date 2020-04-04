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
<title><?php echo $sitename;?>--凭据管理</title>
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
		.header p {
			font-size: 14px;
		}
</style>
</head>

<div class="am-g">
<div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">

<ul class="am-nav am-nav-tabs am-nav-justify">
  <li><a href="sent.php">发送消息</a></li>
  <li><a href="time.php">定时发送</a></li>
  <li class="am-active"><a href="token.php">凭据管理</a></li>
  <li><a href="log.php">日志查看</a></li>
  <li><a href="index.php">安全退出</a></li>
</ul>

<div class="header">
  <div class="am-g">
    <h1><?php echo $sitename;?>--凭据管理</h1>
  </div>
  <hr>
</div>
<?php
echo $welcome;
echo $_SESSION['Loginusername'];
echo "<br><br>";
?>

<div class="am-panel-group" id="accordion">
  <div class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-1'}">
        已保存的Token
      </h4>
    </div>
    <div id="do-not-say-1" class="am-panel-collapse am-collapse am-scrollable-horizontal">
      <div class="am-panel-bd">
<table class="am-table am-table-bordered">
    <thead>
        <tr>
            <th>tid</th>
            <th>name</th>
            <th>token</th>
        </tr>
    </thead>
    <tbody>
<?php
$datas = $database->select("token", ["tid","name","token"], ["uid" => $_SESSION['Loginuid']]);
foreach($datas as $data)
      {
        echo"<tr>";
        echo "<td>".$data["tid"] ."</td><td>".$data["name"]."</td><td>".$data["token"]."</td>";
        echo"</tr>";
      }
?>
    </tbody>
</table>
</div>
</div>
</div>
</div>
<br />
添加新的Token：
<hr />
<form method="post" class="am-form" action="_token.php?method=add&t=<?php echo $timestamp; ?>&r=<?php echo $random; ?>&token=<?php echo $token; ?>">
      <label for="name">名称：</label>
      <input type="text" name="name" id="name" value="" required="true">
      <br>
      <label for="token">Token:</label>https://oapi.dingtalk.com/robot/send?access_token=
      <input type="text" name="token" id="token" value="" required="true"> 
      <br>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="添 加" class="am-btn am-btn-primary am-btn-sm am-fl">
      </div>
    </form>
  
<br />
删除保存的Token：
<hr />
 <form method="post" class="am-form" action="_token.php?method=del&t=<?php echo $timestamp ?>&r=<?php echo $random ?>&token=<?php echo $token ?>">
      <select multiple data-am-selected="{searchBox: 1,btnWidth: '100%'}" name="urls[]" id="urls[]" required="true">
      <?php
      foreach($datas as $data)
      {
        echo "<option value=". $data["tid"] .">".$data["tid"]."-".$data["name"]."</option>";
      }
      ?>
      </select>
      <br>
      <br>
      <div class="am-cf">
        <input type="submit" name="" value="删 除" class="am-btn am-btn-danger am-btn-sm am-fl">
      </div>
    </form>
<hr />
<p>©<?php echo date('Y'); ?> Design by Charlie.Pan|<?php echo $sitename;echo " V";echo $version;?>|仅供团队开发测试使用|<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo $icp; ?></a></p>
</div>
</div>
</body>
</html>