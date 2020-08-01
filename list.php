<?php
    require('sub.class.php');
    $url=base64_decode(base64_replace($_GET['url']));
    $group=base64_decode(base64_replace($_GET['group']));
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $txt = curl_exec($ch);
    curl_close($ch);
    $lists=explode("\n",$txt);
    foreach($lists as $list){
        if(substr($list,0,1)==="#"){
            continue;
        }
        if(stripos($list,"no-resolve")!==false){
            echo substr_replace($list,$group.",",stripos($list,",",stripos($list,",")+1)+1,0);
            echo "\n";
        }else{
            echo $list.",".$group;
            echo "\n";
        }
    }
?>