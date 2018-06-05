<?php
require 'line_config.php';
$ch = curl_init();

$strUrl = "https://chaomthai.com/line_hook.php";
$arrPostData = array();
$arrPostData['events'][0]['replyToken'] = '00000000000000000000000000000000';
$arrPostData['events'][0]['type'] = 'message';
$arrPostData['events'][0]['timestamp'] = '1528104320686';
$arrPostData['events'][0]['source']['type'] = 'user';
$arrPostData['events'][0]['source']['userId'] = $slumpId;
$arrPostData['events'][0]['message']['id'] = '100001';
$arrPostData['events'][0]['message']['type'] = 'text';
$arrPostData['events'][0]['message']['text'] = 'Hello World';

curl_setopt($ch, CURLOPT_URL, $strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);

curl_close ($ch);
?>