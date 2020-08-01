<?php
$servername = "rm-bp17qtu2zb6xlofacyo.mysql.rds.aliyuncs.com";
$username = "trass";
$password = "929b1ea66d02a43f";
$dbname = "sub";
 
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
?>