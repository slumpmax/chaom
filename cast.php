<?php
require 'config.php';

$pushId = 'Cfe4bbf1546b7cd1ac96a0f7799d8d07f';
//$pushId = 'Cadd8fc18c504a40d0ef893527100f087';
$ch = curl_init();

$strUrl = "https://api.line.me/v2/bot/message/push";
$arrPostData = array();
$arrPostData['to'] = $pushId;
$arrPostData['messages'][0]['type'] = "text";
$arrPostData['messages'][0]['text'] = "วันเพ็ญเดือนสิบสองน้ำก็นองเต็มตลิ่ง";
$arrPostData['messages'][1]['type'] = "text";
$arrPostData['messages'][1]['text'] = "เราทั้งหลายชายหญิงสนุกกันจริงวันลอยกระทง";

curl_setopt($ch, CURLOPT_URL,$strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);

curl_close ($ch);
?>