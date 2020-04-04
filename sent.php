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
  <head lang="en">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?php echo $sitename;?>--发送信息</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <link rel="stylesheet" href="assets/css/amazeui.min.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/amazeui.ie8polyfill.min.js"></script>
  <script src="assets/js/amazeui.min.js"></script>
  <script src="assets/js/modernizr.js"></script>

<script>
  $(document).ready(function(){
  $("input#add").click(function(){addSpot(this);
  });
});

function addSpot(obj) {
$('div#spots').append(
    '<div class="am-input-group">' +
    '<input type="text" name="atMobile[]" class="am-form-field"/>' +
    '<span class="am-input-group-btn">'+
    '<input type="button" id="remove" name="remove" class="remove am-btn am-btn-primary am-btn-sm" value="删除本行"></input>'+
    '</span></div>').find("input.remove").click(function(){
    $(this).parent().parent().remove();
    $('input#add').show();
  });
};
</script>

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
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
 <ul class="am-nav am-nav-tabs am-nav-justify">
  <li class="am-active"><a href="sent.php">发送信息</a></li>
  <li><a href="time.php">定时发送</a></li>
  <li><a href="token.php">凭据管理</a></li>
  <li><a href="log.php">日志查看</a></li>
  <li><a href="index.php">安全退出</a></li>
</ul>
<div class="header">
  <div class="am-g">
  <h1><?php echo $sitename;?>--发送信息</h1>
  <p>Design by Charlie.Pan</p>
  </div>
  <hr>
</div>


    <h3>发送消息</h3>
    <hr>
    <br>


    <?php 
    if($_GET['r']=='success'){echo '<p class="am-alert am-alert-success">推送成功！快去群里看看吧。</p>'; }
    if($_GET['r']=='wrong'){echo '<p class="am-alert am-alert-danger">刚刚的推送失败鸟。错误信息是'.$_GET['e'].'</p>'; }
    if($_GET['r']=='illgal'){echo '<p class="am-alert am-alert-warning">不合法的访问请求！</p>'; }
    ?>

    <form method="post" class="am-form" action="_sent.php?t=<?php echo $timestamp ?>&r=<?php echo $random ?>&token=<?php echo $token ?>">
      <label for="urls[]">WebHook Token：</label>
      <br />
      <select multiple data-am-selected="{searchBox: 1,btnWidth: '100%'}" name="urls[]" id="urls[]" required="true">
      <?php $datas = $database->select("token", ["tid","name"], ["uid" => $_SESSION['Loginuid']]);
      foreach($datas as $data)
      {
        echo "<option value=". $data["tid"] .">".$data["name"]."</option>";
      }
      ?>
      </select>
      <br>
      <label for="type1">消息类型：
      <br>
      <select name="type1" id="type1" data-am-selected>
      <option value="text">text</option>
      <option value="link">link</option>
      <option value="markdown">markdown</option>
      </select>
      </label>
      <br>
      <label for="messages">消息内容:</label>
      <textarea name="messages" id="messages" required="true" rows=5></textarea>
      <br>
      <label for="titles">消息标题(当且仅当【link】/【markdown】有效且必填，【text】无效无需填写)</label>
      <input type="text" name="titles" id="titles"/>
      <br>
      <label for="messageUrl">点击消息跳转的URL：（当且仅当【link】有效且必填）</label>
      <input type="text" name="messageUrl" id="messageUrl" />
      <br>
      <label for="picUrl">图片URL：（当且仅当【link】有效但选填）</label>
      <input type="text" name="picUrl" id="picUrl" />
      <br>
      <label for="spots">被@人的手机号：（当且仅当【text】有效但选填，没有请留空）</label>
      <div id="spots">
      <div class="am-input-group">
      <input type="text" name="atMobile[]" class="am-form-field"/>
      <span class="am-input-group-btn">
        <input type="button" id="add" name="add" class="am-btn am-btn-primary am-btn-sm" value="添加一行"></input>
      </span>
      </div>
    </div>
      <br>
      <label for="isAtAll">AT群内全员？（当且仅当【text】有效）
      <br />
      <select name="isAtAll" id="isAtAll" data-am-selected>
      <option value="false">否</option>
      <option value="true">是</option>
      </select>
      </label>
      <br>
      <br>
      <div class="am-cf">
        <input type="submit" name="" value="推 送" class="am-btn am-btn-primary am-btn-sm am-fl">
      </div>
    </form>
    <hr>
    <p>©<?php echo date('Y'); ?> Design by Charlie.Pan|<?php echo $sitename;echo " V";echo $version;?>|仅供团队开发测试使用|<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo $icp; ?></a></p>
  </div>
</div>
</body>
</html>