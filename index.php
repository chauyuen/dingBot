<?php
define('IN_SYS', TRUE);
session_start();
require_once "config.php";
if(isset($_SESSION['Loginuid'])||isset($_SESSION['Loginusername']))
{
$id = $database->insert("logs", ["tname" => $_SESSION['Loginusername'],"ttoken" => $ip,"type" => "2","status" => "1","uid" => $_SESSION["Loginuid"]]);
session_unset();
session_destroy();
session_start(); 
header("Location: index.php?r=exitsuccess");
}
$timestamp=time();
$random=rand();
$token= md5($sysname.$pass.$timestamp.$random);
?>

<html>
  <head lang="en">
  <meta charset="UTF-8">
  <title><?php echo $sitename;?>--系统登录</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
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
      font-size: 200%;
      color: #333;
      margin-top: 30px;
    }
    .header p {
      font-size: 14px;
    }
  </style>
</head>



<body>
<div class="header">
  <div class="am-g">
    <h1><?php echo $sitename;?>--系统登录</h1>
    <p>Design by Charlie.Pan</p>
  </div>
  <hr />
</div>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h3>登录</h3>
    <hr>
    <br>
    
    <?php 
    if($_GET['r']=='illgal'){echo '<p class="am-alert am-alert-warning">不合法的访问请求！</p>'; }
    if($_GET['r']=='uperror'){echo '<p class="am-alert am-alert-warning">用户名或者密码错误！</p>'; }
    if($_GET['r']=='blacklist'){echo '<p class="am-alert am-alert-danger">黑名单用户，禁止登陆！</p>'; }
    if($_GET['r']=='error'){echo '<p class="am-alert am-alert-danger">未知错误，请联系运营。</p>'; }
    if($_GET['r']=='exitsuccess'){echo '<p class="am-alert am-alert-success">您已成功退出登录</p>'; }
    ?>
    
    <form method="post" class="am-form" action="_index.php?t=<?php echo $timestamp; ?>&r=<?php echo $random; ?>&token=<?php echo $token; ?>">
      <label for="username">用户名:</label>
      <input type="text" name="username" id="username" value="" required="true">
      <br>
      <label for="password">密码:</label>
      <input type="password" name="password" id="password" value="" required="true"> 
      <br>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
      </div>
    </form>
    <hr>
    <p>©<?php echo date('Y'); ?> Design by Charlie.Pan|<?php echo $sitename;echo " V";echo $version;?>|仅供团队开发测试使用|<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo $icp; ?></a></p>
  </div>
</div>
</body>
</html>
