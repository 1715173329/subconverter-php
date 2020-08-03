<?php
if(!$_GET['url']){
    echo "Fuck You!";
    exit();
}
require('sub.class.php');
$ua=$_SERVER['HTTP_USER_AGENT'];
if(stripos($input,"ssr://") or stripos($input,"ss://") or stripos($input,"ssr://")){
    $count=get_url_count($_GET['url']);
    $skey=get_url_type($_GET['url']);
    $s=get_url_config($_GET['url']);
}else{
    $f=get_url($_GET['url']);
    $count=get_url_count($f);
    $skey=get_url_type($f);
    $s=get_url_config($f);
}
$name=array();
$ips=array();
if(stripos($ua,"clash")!==false or $_GET['target']=="clash"){
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(!in_array($cipher,$supcipher)){
                continue;
            }
            if($obfs=="tls1.2_ticket_auth" and $_GET['fastauth']=="true"){
                $obfs="tls1.2_ticket_fastauth";
            }
            if(in_array($remarks,$name)){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
        $ips=array();
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
                echo "      - ".$remarks."\n";
                array_push($name,$remarks);
            }elseif($skey[$key]=="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(!in_array($cipher,$supcipher)){
                continue;
            }
            if(in_array($remarks,$name)){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
                echo "      - ".$remarks."\n";
                array_push($name,$remarks);
            }elseif($skey[$key]=="ssr"){
            if(get_ssr_all($nt)==0){
                continue;
            }else{
                list($node,$port,$type,$protocol,$cipher,$obfs,$password,$obfsparam,$protoparam,$remarks,$group)=get_ssr_all($nt);
            }
            if(!in_array($cipher,$supcipher)){
                continue;
            }
            if(in_array($remarks,$name)){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            array_push($name,$remark);
            echo "      - ".$remark."\n";
                }
            }
    }
    $handle=fopen('clashlist.txt', "r");
    echo fread($handle, filesize ('clashlist.txt'));
    fclose($handle);
}
}elseif(stripos(urldecode($ua),"Quantumult X")!==false or $_GET['target']=="quanx"){
    $handle=fopen('quanxhead.txt', "r");
    echo fread($handle, filesize ('quanxhead.txt'));
    fclose($handle);
    $icons = file('quanxiconlist.txt', FILE_IGNORE_NEW_LINES);
    $names = file('quanxnamelist.txt', FILE_IGNORE_NEW_LINES);
    foreach($names as $k => $n){
        $name=array();
        $ips=array();
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
    $ips=array();
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
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
            if($obfs=="tls1.2_ticket_auth" and $_GET['fastauth']=="true"){
                $obfs="tls1.2_ticket_fastauth";
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
                echo "shadowsocks = ".$node.":".$port.", method=".$cipher.", password=".$password.", ssr-protocol:".$protocol.", ";
                if($protoparam!==""){
                    echo "ssr-protocol-param=".$protoparam.", ";
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            echo "vmess = ".$node.":".$port.", method=chacha20-ietf-poly1305, password=".$id.", ";
            if($net=="ws"){
                echo "obfs=".$net.", obfs-host=".$host.", obfs-uri=".$path.", ";
            }elseif($net=="over-tls"){
                echo "obfs=".$net.", obfs-host=".$host.", obfs-uri=".$path.", tls-verification=true, ";
            }
            echo "tag=".$remark."\n";
        }
    }
    echo "\n[filter_local]\nGEOIP,CN,🎯Direct\nFINAL,🖐️Missing\n\n[rewrite_local]\n[mitm]";
}elseif(stripos(urldecode($ua),"Surge")!==false or $_GET['target']=="surge"){
    if($_GET['nodelist']=="true"){
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            array_push($name,$remarks);
            echo $remarks." = ss, ".$node.", ".$port.", encrypt-method=".$cipher.", password=".$password.", ";
            if($obfsmode!==""){
                echo "obfs=".$obfsmode.", ";
            }
            if($obfshost!==""){
                echo "obfs-host=".$obfshost.", ";
            }
            if($_GET['surge-tfo']=="true"){
                echo "tfo=true, ";
            }else{
                echo "tfo=false, ";
            }
            if($_GET['surge-udprelay']=="true"){
                echo "udp-relay=true ";
            }else{
                echo "udp-relay=false ";
            }
            echo "\n";
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            echo $remark;
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            array_push($name,$remark);
            echo $remark." = vmess, ".$node.", ".$port.", username=".$id.", ";
            if($tls=="true"){
                echo "tls=true, ";
            }else{
                echo "tls=false, ";
            }
            if($net=="ws"){
                echo "ws=true, ws-path=".$path.", sni=".$host.", ws-headers=Host:".$host.", ";
            }
            echo "skip-cert-verify=0, ";
            if($_GET['surge-tfo']=="true"){
                echo "tfo=true, ";
            }else{
                echo "tfo=false, ";
            }
            if($_GET['surge-udprelay']=="true"){
                echo "udp-relay=true ";
            }else{
                echo "udp-relay=false ";
            }
            echo "\n";
            }
        }
    }else{
        $handle=fopen('surgehead.txt', "r");
        echo fread($handle, filesize ('surgehead.txt'));
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
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            array_push($name,$remarks);
            echo $remarks." = ss, ".$node.", ".$port.", encrypt-method=".$cipher.", password=".$password.", ";
            if($obfsmode!==""){
                echo "obfs=".$obfsmode.", ";
            }
            if($obfshost!==""){
                echo "obfs-host=".$obfshost.", ";
            }
            if($_GET['surge-tfo']=="true"){
                echo "tfo=true, ";
            }else{
                echo "tfo=false, ";
            }
            if($_GET['surge-udprelay']=="true"){
                echo "udp-relay=true ";
            }else{
                echo "udp-relay=false ";
            }
            echo "\n";
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
            if(in_array($remark,$name) or $remark===0){
                continue;
            }
            echo $remark;
            $type=get_v2_type($nt);
            $aid=get_v2_aid($nt);
            $id=get_v2_id($nt);
            $host=get_v2_host($nt);
            if($host==="" or $host==="none" and $net=="ws"){
                $host=$node;
            }elseif($host==="" or $host==="none"){
                $host=$node;
            }
            $path=get_v2_path($nt);
            $cipher="auto";
            $tls=get_v2_tls($nt);
            if(in_array(gethostbyname($node).":".$port,$ips) and $_GET['iprepeat']==="true"){
                continue;
            }
            array_push($ips,gethostbyname($node).":".$port);
            array_push($name,$remark);
            echo $remark." = vmess, ".$node.", ".$port.", username=".$id.", ";
            if($tls=="true"){
                echo "tls=true, ";
            }else{
                echo "tls=false, ";
            }
            if($net=="ws"){
                echo "ws=true, ws-path=".$path.", sni=".$host.", ws-headers=Host:".$host.", ";
            }
            echo "skip-cert-verify=0, ";
            if($_GET['surge-tfo']=="true"){
                echo "tfo=true, ";
            }else{
                echo "tfo=false, ";
            }
            if($_GET['surge-udprelay']=="true"){
                echo "udp-relay=true ";
            }else{
                echo "udp-relay=false ";
            }
            echo "\n";
            }
        }
        echo "\n[Proxy Group]\n";
        $groups=file('surgegroup.txt', FILE_IGNORE_NEW_LINES);
        foreach($groups as $key => $group){
            if($group==="🔘Select"){
                echo "🔘Select = select, 🎯Direct";
                foreach($name as $l){
                    echo ", ".$l;
                }
            }elseif($group==="🎯Direct"){
                echo "🎯Direct = select, DIRECT";
            }elseif($group==="🚫Ban"){
                echo "🚫Ban = select, REJECT, DIRECT";
            }else{
                echo $group." = select, 🔘Select, 🎯Direct";
                foreach($name as $l){
                    echo ", ".$l;
                }
            }
            echo "\n";
        }
        echo "\n";
        $handle=fopen('surgelist.txt', "r");
        echo fread($handle, filesize ('surgelist.txt'));
        fclose($handle);
    }
}
?>