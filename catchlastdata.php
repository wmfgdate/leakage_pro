<?php
$servername = "163.18.59.103";
$username = "wmlax123";
$password = "relax123";
$dbname = "wmlax123";

// 連結
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("connect_fail: " . $conn->connect_error);
} 
 
$sql = "SELECT * FROM `lastdata`";   
//      SELECT * FROM `statetion`  --all
//      SELECT * FROM statetion ORDER BY Time DESC LIMIT 1  --last 
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 輸出值
    while($row = $result->fetch_assoc()) {
        echo  "a" . $row["device1"]. "-" . $row["device2"]. "-" . $row["device3"]. "-" . $row["device4"]. "z";
    }
} else {
    echo "0 结果";
}
$conn->close();

//測試OK


?>

