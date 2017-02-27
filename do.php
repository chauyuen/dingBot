<html>
<?php

$pass="11223344556677889900";//秘钥，提高安全性，两个页面要一致。
$timestampBase=time();//获取当前UNIX时间戳
$timestamp=$_GET['t'];//获取首页以GET传过来的时间戳
$random=$_GET['r'];//获取首页以GET传过来的随机数
$token= $_GET['token'];//获取首页以GET传过来的token
$tokenBase= md5("DTRobotV1".$pass.$timestamp.$random);//Token计算

if(($timestampBase-$timestamp)>90 || $token!=$tokenBase)//判定，时间相差是否大于90S或者token是否吻合
{
//时间相差大于90S或者token不吻合，回到首页，非法访问！
header("Location: index.php?r=illgal");
}else{

//获取首页传过来的参数
$urls=$_POST['urls'];
$messages=$_POST['messages'];
$isAtAll=$_POST['isAtAll'];
$type1=$_POST['type1'];
$titles=$_POST['titles'];
$messageUrl=$_POST['messageUrl'];
$picUrl=$_POST['picUrl'];
//保存个Cookies吧，这样可以自动填写首页token。省的每次都填。
setcookie("Lasteseturl",$urls,time()+3600);


//构造URL，首页只让写Token了。
$urls="https://oapi.dingtalk.com/robot/send?access_token=".$urls;

//根据选择的type不同，构造不同的数据，根据官网开发者中心转换来。
if ($type1 == "text"){
$data = array ('msgtype' => 'text','text' => array ('content' => $messages,),'at' => array ('atMobiles' => array (),'isAtAll' => $isAtAll,),);
}elseif ($type1 == "link"){
$data = array ('msgtype' => 'link','link' => array ('text' => $messages,'title' => $titles,'picUrl' =>$picUrl ,'messageUrl' => $messageUrl,),);
}


$data_string = json_encode($data);
//echo $data_string;

//Curl模拟POST，没啥好说的
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
curl_setopt($ch, CURLOPT_URL, "$urls");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
$retBase = curl_exec($ch);
curl_close($ch);
//echo $ret;

//根据返回值做出判断，不是成功直接抛出返回的JSON。
$ret=json_decode($retBase,true);
if ($ret["errmsg"]=="ok" && $ret["errcode"]=="0" )
{
header("Location: index.php?r=success");
}else{
header("Location: index.php?r=wrong&e=".$retBase);
}
//echo "<script>alert('操作应该成功了吧!你去群里看看啊');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
?>
</html>