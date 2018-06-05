<?php
require 'config.php';

$content = file_get_contents('php://input');
$arrContent = json_decode($content, true);

$event = $arrContent['events'][0];
$replyToken = $event['replyToken'];
$userId = $event['source']['userId'];
$groupId = $event['source']['groupId'];
$roomId = $event['source']['roomId'];
$ch = curl_init();

switch ($event['source']['type']) {
  case 'group': $groupName = '[G]'; break;
  case 'room': $groupName = '[R]'; break;
  default: $groupName = '';
}
if ($userId != '') {
  $strUrl = "https://api.line.me/v2/bot/profile/$userId";
  curl_setopt($ch, CURLOPT_URL, $strUrl);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_POST, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  $arrProfile = json_decode($result, true);
  $displayName .= $arrProfile['displayName'];
} else $displayName = '';

if ($groupId != '') {
  $strUrl = "https://api.line.me/v2/bot/profile/$groupId";
  curl_setopt($ch, CURLOPT_URL, $strUrl);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_POST, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  $groupName = $result;
}

$strUrl = "https://api.line.me/v2/bot/message/reply";
$arrPostData = array();
switch ($event['type']) {
  case 'message':
    switch ($event['message']['type']) {
      case 'text':
        $text = $event['message']['text'];
        switch ($text) {
          case 'สวัสดี':
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] = "สวัสดี $displayName";
            break;
          case 'ชื่ออะไร':
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] = "ชะอมไง";
            break;
          case 'ทำอะไรได้บ้าง':
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] = "ทำได้แค่เพียงห่วงใย";
            break;
        }
        break;
      case 'sticker':
        $text = json_encode($event['message']);
        $arrPostData['replyToken'] = $replyToken;
        $arrPostData['messages'][0]['type'] = "sticker";
        $arrPostData['messages'][0]['packageId'] = 1;
        $arrPostData['messages'][0]['stickerId'] = 1;
        break;
      default:
        $text = '[MESSAGE: '.$event['message']['type'].']';
    }
    break;
  case 'follow':
    $arrPostData['replyToken'] = $replyToken;
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "ขอบคุณ $displayName ที่เพิ่มชะอมเป็นเพื่อน";
    $arrPostData['messages'][1]['type'] = "text";
    $arrPostData['messages'][1]['text'] = "ชะอมเป็นระบบตอบกลับอัตโนมัติ สามารถตอบกลับได้บางข้อความเท่านั้นนะ ลองพิมพ์ข้อความบางอย่างดูสิ";
    $arrPostData['messages'][2]['type'] = "text";
    $arrPostData['messages'][2]['text'] = "ถ้ามีข้อความส่งจากชะอมมากเกินไป จะปิดแจ้งเตือนก็ได้นะ";
    $text = "[FOLLOW] $displayName";
    break;
  case 'unfollow':
    $text = "[UNFOLLOW] $displayName";
    break;
  case 'join':
    $arrPostData['replyToken'] = $replyToken;
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "ชะอมมาแล้วจ้า สวัสดีรอบห้อง";
    $text = "[JOIN] $displayName";
    break;
  case 'leave':
    $text = "[LEAVE] $displayName";
    break;
  case 'postback':
    $text = "[POSTBACK] $displayName";
    break;
  case 'beacon':
    $text = "[BEACON] $displayName";
    break;
  case 'accountLink':
    $text = "[ACCOUNTLINK] $displayName";
    break;
  default:
    $text = '[EVENT: '.$event['type'].'] '.$displayName;
}
curl_setopt($ch, CURLOPT_URL, $strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);

$strUrl = "https://api.line.me/v2/bot/message/push";
$arrPostData = array();
$arrPostData['to'] = "$slumpId";
$arrPostData['messages'][0]['type'] = "text";
$arrPostData['messages'][0]['text'] = "$groupName$displayName: $text\n\n$content";

curl_setopt($ch, CURLOPT_URL, $strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);

curl_close ($ch);
?>