<?php
$servername = "163.18.59.103";
$username = "wmlax123";
$password = "relax123";
$dbname = "wmlax123";

$a=$_GET["device1"];
$b=$_GET["device2"];
$c=$_GET["device3"];
$d=$_GET["device4"];


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
/*------------------檢查GET d1~d4 是否有-1 若有則數值不變---*/
$sql = "SELECT * FROM `lastdata`";   

$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 輸出值
    while($row = $result->fetch_assoc()) {
        //echo  "" . $row["device1"]. " - " . $row["device2"]. " - " . $row["device3"]. " - " . $row["device4"]. "<br>";
		if($_GET["device1"]==-1){
			$a=$row["device1"];
		}
		if($_GET["device2"]==-1){
			$b=$row["device2"];	
		}
		if($_GET["device3"]==-1){
			$c=$row["device3"];
		}
		if($_GET["device4"]==-1){
			$d=$row["device4"];	
		}
	}
} else {
    echo "0 结果";
}

/*--------------------更新lastdata的值-------------------------*/

$sql = "UPDATE `lastdata` SET `device1`=$a,`device2`=$b,`device3`=$c,`device4`=$d WHERE 1";

if ($conn->query($sql) === TRUE) {
    echo "update success <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
/*--------------------更新statetion的值-------------------------*/

$sql = "INSERT INTO `statetion`(`device1`, `device2`, `device3`, `device4`) VALUES ($a,$b,$c,$d)";

if ($conn->query($sql) === TRUE) {
    echo "insert success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>