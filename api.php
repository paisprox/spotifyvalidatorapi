<?php
set_time_limit(0);
header("Content-type: application/json");

$out = array();
if(empty($_GET["email"])) {
    $out["status"] = false;
    $out["msg"] = "empty param";
}else{
    $email = $_GET["email"];
    $out["status"] = true;
    $out["body"]["email"] = $email;
    $header = array("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8","Accept-Encoding: gzip, deflate, br","Accept-Language: en-US,en;q=0.9","Connection: keep-alive","Host: www.spotify.com","Upgrade-Insecure-Requests: 1","User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    $check = curl("https://www.spotify.com/id/xhr/json/isEmailAvailable.php?email=".$email."", $header);
    if(preg_match("#false#", $check)) $out["body"]["status"] = "live";
    else if(preg_match("#true#", $check)) $out["body"]["status"] = "dead";
    else $out["body"]["status"] = "unknown";
}
echo json_encode($out);

function getStr($source, $start, $end) {
    $a = explode($start, $source);
    $b = explode($end, $a[1]);
    return $b[0];
}
function curl($url, $header = 0) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    }
    $x = curl_exec($ch);
    curl_close($ch);
    return $x;
}