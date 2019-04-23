<?php
$url = $_GET['url'];

if ($url=="") {
    echo "API is active.";
}

function open_web($url) {
 $ch = curl_init($url);

 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
 curl_setopt($ch, CURLOPT_TIMEOUT, 60);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

 $contents = curl_exec($ch);
 curl_close($ch);

 return $contents;
}

function check_valid($url) {
 $ch = curl_init($url);

 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
 curl_setopt($ch, CURLOPT_TIMEOUT, 60);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 $contents = curl_exec($ch);
 $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 curl_close($ch);
 
 return $status;
}

$instagram_web = open_web($url);

$check_akun1 = explode('"is_private":',$instagram_web);
$check_akun2 = explode(',"is_verified"',$check_akun1[1]);
$check_akun = $check_akun2[0];

$check_type1 = explode('__typename":"',$instagram_web);
$check_type2 = explode('","',$check_type1[1]);
$check_type = $check_type2[0];

if ($check_akun=="true") {
     $json = array(
    "success" => "false",
    "reason" => "can't get image/video from private account."
    );
    $json = json_encode($json);
    print $json;
}

if ($check_type=="GraphImage") {
    $linkgambar1 = explode( 'display_url":"' , $instagram_web );
    $linkgambar2 = explode('","display_resources"' , $linkgambar1[1] );
    $linkgambar = $linkgambar2[0];
    $linkgambar = str_replace("\u0026","&",$linkgambar);
    $check_gambar = check_valid($linkgambar);
    
    if ($check_gambar==200) {
        $json = array(
            "success" => "true",
            "link_image" => $linkvideo
            );
        $json = json_encode($json);
        print $json;
    } else {
       $json = array(
           "success" => "false",
           "reason" => "image not found"
           );
        $json = json_encode($json);
        print $json;
    }
}

if ($check_type=="GraphVideo") {
    $linkvideo1 = explode( '"video_url":"' , $instagram_web );
    $linkvideo2 = explode('","' , $linkvideo1[1] );
    $linkvideo = $linkvideo2[0];
    $linkvideo = str_replace("\u0026","&",$linkvideo);
    $check_video = check_valid($linkvideo);

    if ($check_video==200) {
        $json = array(
            "success" => "true",
            "link_video" => $linkvideo
            );
        $json = json_encode($json);
        print $json;
    } else {
        $json = array(
            "success" => "false",
            "reason" => "video not found"
            );
        $json = json_encode($json);
        print $json;
    }

}

?>
