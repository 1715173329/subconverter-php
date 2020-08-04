<?php
function unicodeDecode($unicode_str){
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}
function hexDecode_one($s){
    $s = str_replace('\x','',$s);
    return preg_replace('/(\w{2})/e',"chr(hexdec('\\1'))",$s);
}
function base64_replace($input){
    return str_replace("-","+",str_replace("_","/",$input));
}
function check_cipher($input){
    $passciphers=file('clashcipher.txt', FILE_IGNORE_NEW_LINES);
    foreach($passciphers as $passcipher){
        if($input===$passcipher){
            return 1;
        }
    }
    return 0;
}
function get_v2_node($input){
    $num1=stripos($input,'"add":"')+6;
    $num2=strpos($input,'"',$num1+1);
    $output=substr($input,$num1+1,$num2-$num1-1);
    return $output;
}
function get_v2_port($input){
    $num1=stripos($input,'"port":')+6;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+1,$num2-$num1-1);
    return $output;
}
function get_v2_remark($input){
    if(!strpos($input,'"remark":"')){
    $num1=stripos($input,'"ps":"')+5;
    $num2=strpos($input,',',$num1+1);
    $output=unicodeDecode(substr($input,$num1+1,$num2-$num1-2));
    if(stripos($output,": ")!==false){
                return 0;
            }
    return $output;
    }
    $num1=stripos($input,'"remark":"')+9;
    $num2=strpos($input,',',$num1+1);
    $output=unicodeDecode(substr($input,$num1+1,$num2-$num1-2));
    if(stripos($output,": ")!==false){
                return 0;
            }
    return $output;
}
function get_v2_net($input){
    if(!strpos($input,'"net":"')){
        return "none";
    }
    $num1=stripos($input,'"net":"')+5;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+2,$num2-$num1-3);
    return $output;
}
function get_v2_type($input){
    if(!strpos($input,'"type":"')){
        return "none";
    }
    $num1=stripos($input,'"type":"')+6;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+2,$num2-$num1-3);
    return $output;
}
function get_v2_host($input){
    if(!strpos($input,'"host":"')){
        return "none";
    }
    $num1=stripos($input,'"host":"')+6;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+2,$num2-$num1-3);
    return $output;
}
function get_v2_path($input){
    if(!strpos($input,'"path":"')){
        return "none";
    }
    $num1=stripos($input,'"path":"')+6;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+2,$num2-$num1-3);
    return $output;
}
function get_v2_aid($input){
    if(!strpos($input,'"aid":')){
        return 0;
    }
    $num1=stripos($input,'"aid":')+4;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+2,$num2-$num1-2);
    return $output;
}
function get_v2_id($input){
    if(!strpos($input,'"id":')){
        return "";
    }
    $num1=stripos($input,'"id":')+3;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+3,$num2-$num1-4);
    return $output;
}
function get_v2_tls($input){
    if(!strpos($input,'"tls":')){
        return "false";
    }
    $num1=stripos($input,'"id":')+4;
    $num2=strpos($input,',',$num1+1);
    $output=substr($input,$num1+3,$num2-$num1-4);
    if($output==="true" or $output==="tls"){
        return "true";
    }else{
        return "false";
    }
}
function get_ssr_all($nt){
            $node=strtok($nt,":");
            if(!strpos($node,".")){
                return 0;
            }
            $nodenum=strpos($nt,":");
            $portnum=strpos($nt,":",$nodenum+1);
            $port=substr($nt,$nodenum+1,$portnum-$nodenum-1);
            $type="ssr";
            $protocolnum=strpos($nt,":",$portnum+1);
            $protocol=substr($nt,$portnum+1,$protocolnum-$portnum-1);
            $ciphernum=strpos($nt,":",$protocolnum+1);
            $cipher=substr($nt,$protocolnum+1,$ciphernum-$protocolnum-1);
            if(check_cipher($cipher)==0){
                return 0;
            }
            $obfsnum=strpos($nt,":",$ciphernum+1);
            $obfs=substr($nt,$ciphernum+1,$obfsnum-$ciphernum-1);
            $passwordnum=strpos($nt,"/",$obfsnum+1);
            $password=base64_decode(substr($nt,$obfsnum+1,$passwordnum-$obfsnum-1));
            $obfsparamnum1=strpos($nt,"obfsparam=")+9;
            $obfsparamnum2=strpos($nt,"&",$obfsparamnum1+1);
            if(!$obfsparamnum2){
                $obfsparam=str_replace(" ",'',base64_decode(base64_replace(substr($nt,$obfsparamnum1+1))));
            }else{
            if($obfsparamnum2-$obfsparamnum1==1){
                $obfsparam="";
            }else{
                $obfsparam=str_replace(" ",'',base64_decode(base64_replace(substr($nt,$obfsparamnum1+1,$obfsparamnum2-$obfsparamnum1-1))));
            }
            }
            $protoparamnum1=strpos($nt,"protoparam=")+10;
            $protoparamnum2=strpos($nt,"&",$protoparamnum1+1);
            if(!$protoparamnum2){
                $protoparam=str_replace(" ",'',base64_decode(base64_replace(substr($nt,$protoparamnum1+1))));
            }else{
            if($protoparamnum2-$protoparamnum1==1){
                $protoparam="";
            }else{
                $protoparam=str_replace(" ",'',base64_decode(base64_replace(substr($nt,$protoparamnum1+1,$protoparamnum2-$protoparamnum1-1))));
            }
            }
            if(!strpos($nt,"remarks=")){
                $remarks="".$node.":".$port."";
            }else{
                $remarksnum1=strpos($nt,"remarks=")+7;
                $remarksnum2=strpos($nt,"&",$remarksnum1+1);
                if(!$remarksnum2){
            if(!$preg){
                $remarks=rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1))));
            }else{
                if(preg_match($preg,rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1)))))){
                    $remarks=rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1))));
                }else{
                    return 0;
                }
            }
                }else{
                if(!$preg){
                $remarks=rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1,$remarksnum2-$remarksnum1-1))));
            }else{
                if(preg_match($preg,rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1,$remarksnum2-$remarksnum1-1)))))){
                    $remarks=rtrim(base64_decode(base64_replace(substr($nt,$remarksnum1+1,$remarksnum2-$remarksnum1-1))));
                }else{
                    return 0;
                }
            }
                }
            }
            if(stripos($remarks,": ")!==false){
                return 0;
            }
            if(!strpos($nt,"group=")){
                $group="".$node.":".$port."";
            }else{
                $groupnum1=strpos($nt,"group=")+5;
                $groupnum2=strpos($nt,"&",$groupnum1+1);
                if(!$groupnum2){
                    $group=base64_decode(base64_replace(substr($nt,$groupnum1+1)));
                }else{
                    $group=base64_decode(base64_replace(substr($nt,$groupnum1+1,$groupnum2-$groupnum1-1)));
                }
            }
            $output[]=$node;
            $output[]=$port;
            $output[]=$type;
            $output[]=$protocol;
            $output[]=$cipher;
            $output[]=$obfs;
            $output[]=$password;
            $output[]=$obfsparam;
            $output[]=$protoparam;
            $output[]=$remarks;
            $output[]=$group;
            return $output;
}
function get_ss_all($nt){
            $pa=base64_decode(base64_replace(strtok($nt,"@")));
            $panum=strpos($nt,"@");
            $cipher=strtok($pa,":");
            $ciphernum=strpos($pa,":");
            $password=substr($pa,$ciphernum+1);
            $type="ss";
            $nodenum=strpos($nt,":",$panum+1);
            $node=str_replace(" ","-",substr($nt,$panum+1,$nodenum-$panum-1));
            $remarksnum=strpos($nt,"#");
            $portnum=strpos($nt,"/");
            $port=substr($nt,$nodenum+1,$portnum-$nodenum-1);
            if(!$preg){
                $remarks=rtrim(rawurldecode(substr($nt,$remarksnum+1)));
            }else{
                if(preg_match($preg,rtrim(rawurldecode(substr($nt,$remarksnum+1))))){
                    $remarks=rtrim(rawurldecode(substr($nt,$remarksnum+1)));
                }else{
                    return 0;
                }
            }
            if($port==false){
                $portnum=strpos($nt,"#");
                $port=substr($nt,$nodenum+1,$portnum-$nodenum-1);
            }
            if(!is_numeric($port)){
                $port1=preg_match_all('/\d/S',$port, $matches);
                $port=implode('',$matches[0]);
            }
            if(stripos($remarks,": ")!==false){
                return 0;
            }
            $plugin="";
            $obfsmode="";
            $obfshost="";
            if(stripos($nt,"plugin")!==false){
                $pluginnum=stripos($nt,"plugin")+6;
                $plugin=substr($nt,$pluginnum+1,stripos($nt,";",$pluginnum+1)-$pluginnum-1);
                if($plugin=="obfs-local" or $plugin=="obfs local"){
                    $plugin="obfs";
                }
                $obfsmodenum=stripos($nt,"obfs=")+4;
                $obfsmode=substr($nt,$obfsmodenum+1,stripos($nt,";",$obfsmodenum+1)-$obfsmodenum-1);
                $obfshostnum=stripos($nt,"obfs host=")+9;
                $obfshost=substr($nt,$obfshostnum+1,stripos($nt,"#",$obfshostnum+1)-$obfshostnum-1);
            }
            $output[]=$cipher;
            $output[]=$password;
            $output[]=$type;
            $output[]=$node;
            $output[]=$remarks;
            $output[]=$port;
            $output[]=$plugin;
            $output[]=$obfsmode;
            $output[]=$obfshost;
            return $output;
}
function get_url_type($input){
    $s=array();
    $count=0;
    $skey=array();
    $subs=explode("\n",$input);
    foreach($subs as $subkey => $sub){
        if(strpos($sub,'vmess://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,8))));
            array_push($skey,"vmess");
            $count++;
        }elseif(strpos($sub,'ss://')!==false){
            array_push($s,urldecode(base64_replace(substr($sub,5))));
            array_push($skey,"ss");
            $count++;
        }elseif(strpos($sub,'ssr://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,6))));
            array_push($skey,"ssr");
            $count++;
        }
    }
    return $skey;
}
function get_url_config($input){
    $s=array();
    $count=0;
    $skey=array();
    $subs=explode("\n",$input);
    foreach($subs as $subkey => $sub){
        if(strpos($sub,'vmess://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,8))));
            array_push($skey,"vmess");
            $count++;
        }elseif(strpos($sub,'ss://')!==false){
            array_push($s,urldecode(base64_replace(substr($sub,5))));
            array_push($skey,"ss");
            $count++;
        }elseif(strpos($sub,'ssr://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,6))));
            array_push($skey,"ssr");
            $count++;
        }
    }
    return $s;
}
function get_url_count($input){
    $s=array();
    $count=0;
    $skey=array();
    $subs=explode("\n",$input);
    foreach($subs as $subkey => $sub){
        if(strpos($sub,'vmess://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,8))));
            array_push($skey,"vmess");
            $count++;
        }elseif(strpos($sub,'ss://')!==false){
            array_push($s,urldecode(base64_replace(substr($sub,5))));
            array_push($skey,"ss");
            $count++;
        }elseif(strpos($sub,'ssr://')!==false){
            array_push($s,base64_decode(base64_replace(substr($sub,6))));
            array_push($skey,"ssr");
            $count++;
        }
    }
    return $count;
}
function get_url($input){
$txt="";    
if(stripos($input,"|")!==false){
    $url=explode("|",$input);
    foreach($url as $u){
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $u);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $txt1 = curl_exec($ch);
    if(stripos($txt1,"ssd://")!==false){
        $txt=$txt."\n".get_ssd_config($txt1);
    }else{
        $txt=$txt."\n".base64_decode($txt1);
    }
        curl_close($ch);
    }
}else{
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $input);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $txt1 = curl_exec($ch);
    if(stripos($txt1,"ssd://")!==false){
        $txt=get_ssd_config($txt1);
    }else{
        $txt=base64_decode($txt1);
    }
    curl_close($ch);
}
return $txt;
}
function get_ssd_config($input){
    $out="";
    $s=str_replace(array("\r\n", "\r", "\n"), "", base64_decode(substr(base64_replace($input),6)));
    $ss=substr($s,stripos($s,'"servers":')+9);
    $ssds=explode("{",$ss);
    foreach($ssds as $key => $ssd){
        if($key==0){
            continue;
        }
        $nodenum1=stripos($ssd,'"server":')+8;
        $nodenum2=stripos($ssd,",",$nodenum1+1);
        $node=substr($ssd,$nodenum1+2,$nodenum2-$nodenum1-3);
        $remarksnum1=stripos($ssd,'"remarks":')+9;
        $remarksnum2=stripos($ssd,",",$remarksnum1+1);
        $remarks=substr($ssd,$remarksnum1+2,$remarksnum2-$remarksnum1-3);
        $passwordnum1=stripos($ssd,'"password":')+10;
        $passwordnum2=stripos($ssd,",",$passwordnum1+1);
        $password=substr($ssd,$passwordnum1+2,$passwordnum2-$passwordnum1-3);
        $ciphernum1=stripos($ssd,'"encryption":')+12;
        $ciphernum2=stripos($ssd,",",$ciphernum1+1);
        $cipher=substr($ssd,$ciphernum1+2,$ciphernum2-$ciphernum1-3);
        $portnum1=stripos($ssd,'"port":')+6;
        $portnum2=stripos($ssd,",",$portnum1+1);
        $port=substr($ssd,$portnum1+1,$portnum2-$portnum1-1);
        $obfsnum1=stripos($ssd,'"plugin":')+8;
        $obfsnum2=stripos($ssd,",",$obfsnum1+1);
        $obfs=substr($ssd,$obfsnum1+2,$obfsnum2-$obfsnum1-3);
        $obfsopitionsnum1=stripos($ssd,'"plugin_options":')+16;
        $obfsopitionsnum2=stripos($ssd,",",$obfsopitionsnum1+1);
        $obfsopitions=substr($ssd,$obfsopitionsnum1+2,$obfsopitionsnum2-$obfsopitionsnum1-3);
        if($obfs==="simple-obfs"){
            $output="ss://".base64_encode($cipher.":".$password)."@".$node.":".$port."/?plugin=obfs-local%3B".str_replace('+','%20',urlencode($obfsopitions))."#".str_replace('+','%20',urlencode(unicodeDecode($remarks)));
        }else{
        $output="ss://".base64_encode($cipher.":".$password)."@".$node.":".$port."#".str_replace('+','%20',urlencode(unicodeDecode($remarks)));
        }
        $out=$out."\n".$output;
    }
    return $out;
}
?>