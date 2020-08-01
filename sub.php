<?php
if(!$_GET['url']){
    echo "Fuck You!";
    exit();
}
require('sub.class.php');
$ua=$_SERVER['HTTP_USER_AGENT'];
$count=get_url_count($_GET['url']);
$skey=get_url_type($_GET['url']);
$s=get_url_config($_GET['url']);
$name=array();
if(stripos($ua,"clash")!==false or $_GET['target']!=="quanx"){
$txt="";
$preg=$_GET['preg'];
if($count===0){
    echo "Fuck You Mother!";
    exit();
}else{
    $groups = file('clashgroup.txt', FILE_IGNORE_NEW_LINES);
    $supcipher=file('clashcipher.txt', FILE_IGNORE_NEW_LINES);
    $handle=fopen('clashhead.txt', "r");
    echo fread($handle, filesize ('clashhead.txt'));
    fclose($handle);
    foreach($s as $key => $nt){
        if($skey[$key]=="ss"){
            if(get_ss_all($nt)==0){
                continue;
            }else{
                list($cipher,$password,$type,$node,$remarks,$port,$plugin,$obfsmode,$obfshost)=get_ss_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo ' - {name: "'.$remarks.'", server: "'.$node.'", port: '.$port.', type: "ss", cipher: "'.$cipher.'", password: "'.$password.'" ';
            if($plugin!==""){
                echo ', plugin: "'.$plugin.'", plugin-opts: {mode: "'.$obfsmode.'", host: "'.$obfshost.'"}}';
            }else{
                echo "}";
            }
            echo "\n";
            array_push($name,$remarks);
        }elseif($skey[$key]=="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(in_array($cipher,$supcipher)){
                continue;
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo ' - {name: "'.$remarks.'", server: "'.$node.'", port: '.$port.', type: "'.$type.'", cipher: "'.$cipher.'", password: "'.$password.'", protocol: "'.$protocol.'", obfs: "'.$obfs.'", protocol-param: "'.$protoparam.'", obfs-param: "'.$obfsparam.'" }';
            echo "\n";
            array_push($name,$remarks);
        }elseif($skey[$key]=="vmess"){
            $node=get_v2_node($nt);
            $port=get_v2_port($nt);
            $net=get_v2_net($nt);
            if(!$preg){
                $remark=get_v2_remark($nt);
            }else{
                if(preg_match($preg,get_v2_remark($nt))){
                    $remark=get_v2_remark($nt);
                }else{
                    continue;
                }
            }
            if(in_array($remark,$name)){
                continue;
            }
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none" and $net=="wss"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            echo ' - {name: "'.$remark.'", server: "'.$node.'", port: '.$port.', type: "vmess", uuid: "'.$id.'", alterId: '.$aid.', cipher: "auto", tls: '.$tls.'';
            if($net!=="none"){
                echo ', network: "'.$net.'"';
            }
            if($path!=="none"){
                echo ', ws-path: "'.$path.'"';
            }
            if($host!=="none"){
                echo ', ws-header: {Host: '.$host.'}}';
                echo "\n";
            }else{
                echo '}';
                echo "\n";
            }
            array_push($name,$remark);
        }
    }
    echo "proxy-groups:\n";
    foreach($groups as $num => $group){
        $name=array();
        if($group==="🔘Select"){
            echo "  - name: 🔘Select\n    type: select\n    proxies:\n      - 🎯Direct\n";
            foreach($s as $key => $nt){
            if($skey[$key]=="ss"){
                if(get_ss_all($nt)==0){
                continue;
            }else{
                list($cipher,$password,$type,$node,$remarks,$port,$plugin,$obfsmode,$obfshost)=get_ss_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
                echo "      - ".$remarks."\n";
                array_push($name,$remarks);
            }elseif($skey[$key]=="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(in_array($cipher,$supcipher)){
                continue;
            }
            if(in_array($remarks,$name)){
                continue;
            }
            array_push($name,$remarks);
                echo "      - ".$remarks."\n";
            }elseif($skey[$key]=="vmess"){
                    if(!$preg){
                $remark=get_v2_remark($nt);
            }else{
                if(preg_match($preg,get_v2_remark($nt))){
                    $remark=get_v2_remark($nt);
                }else{
                    continue;
                }
            }
            if(in_array($remark,$name)){
                continue;
            }
            array_push($name,$remark);
            echo "      - ".$remark."\n";
                }
            }
            continue;
        }elseif($group==="🎯Direct"){
            echo "  - name: 🎯Direct\n    type: select\n    proxies:\n      - DIRECT\n";
            continue;
        }elseif($group==="🚫Ban"){
            echo "  - name: 🚫Ban\n    type: select\n    proxies:\n      - REJECT\n      - DIRECT\n";
            continue;
        }
        echo "  - name: ".$group."\n    type: select\n    proxies:\n      - 🔘Select\n      - 🎯Direct\n";
            foreach($s as $key => $nt){
            if($skey[$key]=="ss"){
                if(get_ss_all($nt)==0){
                continue;
            }else{
                list($cipher,$password,$type,$node,$remarks,$port,$plugin,$obfsmode,$obfshost)=get_ss_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
                echo "      - ".$remarks."\n";
                array_push($name,$remarks);
            }elseif($skey[$key]=="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(in_array($cipher,$supcipher)){
                continue;
            }
            if(in_array($remarks,$name)){
                continue;
            }
                echo "      - ".$remarks."\n";
                array_push($name,$remarks);
            }elseif($skey[$key]=="vmess"){
                if(!$preg){
                $remark=get_v2_remark($nt);
            }else{
                if(preg_match($preg,get_v2_remark($nt))){
                    $remark=get_v2_remark($nt);
                }else{
                    continue;
                }
            }
            if(in_array($remark,$name)){
                continue;
            }
            array_push($name,$remark);
            echo "      - ".$remark."\n";
                }
            }
    }
    $handle=fopen('clashlist.txt', "r");
    echo fread($handle, filesize ('clashlist.txt'));
    fclose($handle);
}
}elseif(stripos($ua,"Quantumult%20X")!==false or $_GET['target']==="quanx"){
    $handle=fopen('quanxhead.txt', "r");
    echo fread($handle, filesize ('quanxhead.txt'));
    fclose($handle);
    $icons = file('quanxiconlist.txt', FILE_IGNORE_NEW_LINES);
    $names = file('quanxnamelist.txt', FILE_IGNORE_NEW_LINES);
    foreach($names as $k => $n){
        $name=array();
        echo "static=".$n.", ";
        foreach($s as $key => $nt){
            if($skey[$key]=="ss"){
                if(get_ss_all($nt)==0){
                continue;
            }else{
                list($cipher,$password,$type,$node,$remarks,$port,$plugin,$obfsmode,$obfshost)=get_ss_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo $remarks.", ";
            array_push($name,$remarks);
            }elseif($skey[$key]=="ssr"){
                if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo $remarks.", ";
            array_push($name,$remarks);
            }elseif($skey[$key]=="vmess"){
            if(!$preg){
                $remark=get_v2_remark($nt);
            }else{
                if(preg_match($preg,get_v2_remark($nt))){
                    $remark=get_v2_remark($nt);
                }else{
                    continue;
                }
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo $remark.", ";
            array_push($name,$remark);
            }
        }
        echo $icons[$k];
        echo "\n";
    }
    echo "\n[server_remote]\n\n[filter_remote]\n";
    $handle=fopen('quanxfilter.txt', "r");
    echo fread($handle, filesize ('clashlist.txt'));
    fclose($handle);
    echo "\n[rewrite_remote]\n\n[server_local]\n";
    $name=array();
    foreach($s as $key => $nt){
        if($skey[$key]==="ss"){
            if(get_ss_all($nt)==0){
                continue;
            }else{
                list($cipher,$password,$type,$node,$remarks,$port,$plugin,$obfsmode,$obfshost)=get_ss_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
            echo "shadowsocks = ".$node.":".$port.", method=".$cipher.", password=".$password.", ";
            if($plugin!==""){
                echo "obfs=".$obfsmode.", obfs-host=".$obfshost.", ";
            }
            echo "tag=".$remarks."\n";
            array_push($name,$remarks);
        }elseif($skey[$key]==="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(in_array($remarks,$name)){
                continue;
            }
                echo "shadowsocks = ".$node.":".$port.", method=".$cipher.", password=".$password.", ssr-protocol:".$protocol.", ";
                if($protoparam!==""){
                    echo "ssr-protocol-param=".$protocol.", ";
                }
                echo "obfs= ".$obfs.", ";
                if($obfsparam!==""){
                    echo "obfs-host=".$obfsparam.", ";
                }
                echo "tag=".$remarks."\n";
                array_push($name,$remarks);
        }elseif($skey[$key]==="vmess"){
            $node=get_v2_node($nt);
            $port=get_v2_port($nt);
            $net=get_v2_net($nt);
            if(!$preg){
                $remark=get_v2_remark($nt);
            }else{
                if(preg_match($preg,get_v2_remark($nt))){
                    $remark=get_v2_remark($nt);
                }else{
                    continue;
                }
            }
            if(in_array($remark,$name)){
                continue;
            }
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none" and $net=="wss"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            echo "vmess = ".$node.":".$port.", method=chacha20-ietf-poly1305, password=".$id.", ";
            if($net=="ws"){
                echo "obfs=".$net.", obfs-host=".$host.", obfs-uri=".$path.", ";
            }elseif($net=="wss"){
                echo "obfs=".$net.", obfs-host=".$host.", obfs-uri=".$path.", tls-verification=true, ";
            }elseif($net=="over-tls"){
                echo "obfs=".$net.", obfs-host=".$host.", obfs-uri=".$path.", tls-verification=true, ";
            }
            echo "tag=".$remark."\n";
        }
    }
    echo "\n[filter_local]\nGEOIP,CN,🎯Direct\nFINAL,🖐️Missing\n\n[rewrite_local]\n[mitm]";
}
?>