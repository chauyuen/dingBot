<?php
$pass="SecertKey";
$timestampBase=time();
$timestamp=$_GET['t'];
$random=$_GET['r'];
$token= $_GET['token'];
$tokenBase= md5($pass.$timestamp.$random);

if (($timestampBase-$timestamp)>300 || $token!=$tokenBase) {
    header("Location: index.php?r=illgal");
} else {
    $urls=$_POST['urls'];
    $messages=$_POST['messages'];
    $isAtAll=$_POST['isAtAll'];
    $type1=$_POST['type1'];
    $titles=$_POST['titles'];
    $messageUrl=$_POST['messageUrl'];
    $picUrl=$_POST['picUrl'];
    $atMobile=$_POST['atMobile'];
    setcookie("Lasteseturl", $urls, time()+43200);



    $urls="https://oapi.dingtalk.com/robot/send?access_token=".$urls;

    if ($type1 == "text") {
        $data = array('msgtype' => 'text','text' => array('content' => $messages,),'at' => array('atMobiles' => $atMobile,'isAtAll' => $isAtAll,),);
    } elseif ($type1 == "link") {
        $data = array('msgtype' => 'link','link' => array('text' => $messages,'title' => $titles,'picUrl' =>$picUrl ,'messageUrl' => $messageUrl,),);
    } elseif ($type1 == "markdown") {
        $data = array('msgtype' => 'markdown','markdown' => array('title' => $titles,'text' => $messages,),);
    } else {
        header("Location: index.php?r=illgal");
    }


    $data_string = json_encode($data);
$ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_URL, "$urls");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $retBase = curl_exec($ch);
    curl_close($ch);
$ret=json_decode($retBase, true);
    if ($ret["errmsg"]=="ok" && $ret["errcode"]=="0") {
        header("Location: index.php?r=success");
    } else {
        header("Location: index.php?r=wrong&e=".$retBase);
    }
}
