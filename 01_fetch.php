<?php
if(!file_exists(__DIR__ . '/raw/lastId')) {
  file_put_contents(__DIR__ . '/raw/lastId', '0');
}
$lastId = intval(file_get_contents(__DIR__ . '/raw/lastId'));
$objects = array();

while(++$lastId) {
  $objects[] = $lastId;
  if($lastId % 200 === 0) {
    $targetFile = __DIR__ . '/raw/data_' . $lastId . '.json';
    if(!file_exists($targetFile)) {
      $q = implode(',', $objects);
      $json = shell_exec("curl -k 'https://map.coa.gov.tw/portal/sharing/servers/bdfbd5edc5c243ff929d61853997d58f/rest/services/Farmland_survey/L21_%E6%96%B0%E5%A2%9E%E5%B7%A5%E5%BB%A0106Q4/MapServer/0/query?objectIds={$q}&outFields=*&returnGeometry=true&f=json' -H 'Host: map.coa.gov.tw' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0' -H 'Accept: */*' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate, br' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Referer: https://map.coa.gov.tw/farmland/survey.html' -H 'Connection: keep-alive'");
      $json = gzdecode($json);
      $obj = json_decode($json, true);
      if(isset($obj['features'][0])) {
        file_put_contents($targetFile, $json);
      }
    } else {
      $obj = json_decode(file_get_contents($targetFile), true);
    }
    file_put_contents(__DIR__ . '/raw/lastId', $lastId);
    if(!isset($obj['features'][0])) {
      die('done - ' . $lastId);
    }
    $objects = array();
  }
}
