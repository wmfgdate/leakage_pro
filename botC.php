<?php


/*###############################################################################
將該PHP網頁上傳到有SSL憑證的Server上，再到https://developers.line.me將你的Line
bot的Webhook URL改成這個PHP的網址位置(記得要先Enabled Use webhooks)。
################################################################################*/


//單引號中輸入你的LINE bot的Channel access token(很長很長非常長的那段).
$access_token ='6dZFYl1B6JNIsDI0nU3P2al037BYVMSYPc0zvWpSV+fhirggTYtNQNIkTtaY+gcIz3TWLitd3/uEGs9CEZ7uoskZ/fcJbeBOHBZ88Q6SzieyKouOHPPKKgy1owckXSVV8FCKgIJUCNxDKjeZ98wECwdB04t89/1O/w1cDnyilFU=';


/*#############################Annotation#########################################
							Function name->Push function
以下程式是在你透過網路GET的方式傳送你要傳送的訊息及目的地時用的。
用法：
【http://你的伺服器位置/botC?userid=要傳送的UserID(非LineID)&message=要傳送的訊息】
輸入以上網址就可以將訊息傳到某用戶上。
#############################Annotation end.####################################*/
if( isset($_GET['userid']) && isset($_GET['message'])){
$push_data = [
  "to" => $_GET['userid'],
  "messages" => [
	[
      "type" => "text",
      "text" => $_GET['message']
    ]
  ]
]; 
$ch = curl_init("https://api.line.me/v2/bot/message/push");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($push_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$access_token
));
$result = curl_exec($ch);
//fwrite($file, $result."\n");  
//fclose($file);
curl_close($ch);
}
//###########################Push function end.#######################################




/*#############################Annotation#########################################
							Function name->Reply function
以下程式是在你的LINE bot收到訊息時，LINE Server會將LINE bot收到的訊息Post到該網頁，該網頁就可以將收到訊息進行處理，並可回傳一些處理解果，
例如:輸入"ID"，LINE bot 就會回傳你的UserID給你。
#############################Annotation end.####################################*/

$servername = "163.18.59.103";
$username = "wmlax123";
$password = "relax123";
$dbname = "wmlax123";


$json_string = file_get_contents('php://input'); 
$json_obj = json_decode($json_string);

$event = $json_obj->{"events"}[0];
$userID = $event->{"source"}->{"userId"};
$type  = $event->{"message"}->{"type"};
$message = $event->{"message"};
$reply_token = $event->{"replyToken"};
$stuednt_array=explode(" ",$message->{"text"});

if($stuednt_array[0]=='指令'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "早安"."  "."晚安"
    ]
  	
  ]
]; 
}
/*----------------------------------------*/
if($stuednt_array[0]=='!help'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "您好!我不想幫你"
    ]
  	
  ]
]; 
}
/*----------------------------------------*/
if($stuednt_array[0]=='ID'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "你的UserID：".$userID
    ]
  ]
]; 
}
/*----------------------------------------*/
if($stuednt_array[0]=='87?'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "你才87"
    ]
  ]
]; 
}
/*-----------------------------------------*/
if($stuednt_array[0]=='早安'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "早安!垃圾"
    ]
  ]
]; 
}
/*------------------------------------------*/
if($stuednt_array[0]=='晚安'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "晚安!肥宅 ㄏㄧㄏㄧ"
    ]
  ]
]; 
}
/*-----------------------------------------*/
if($stuednt_array[0]=='周李紘'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "真他媽低能 ㄇㄉ"
    ]
  ]
]; 
}
/*------------------------------------------*/

if($stuednt_array[0]=='鄭景鴻'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "真他媽低能 ㄇㄉ"
    ]
  ]
]; 
}
/*------------------------------------------*/

if($stuednt_array[0]=='幹'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "Why u 說髒話?"
    ]
  ]
]; 
}
/*------------------------------------------*/

if($stuednt_array[0]=='吳宗翰'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "哪時要被我電?"
    ]
  ]
]; 
}
/*------------------------------------------*/
if($stuednt_array[0]=='關閉水閥'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "水閥關閉中"
    ]
  ]
]; 

/*------------------------------------更新狀態-----------------------------------------*/
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

	$sql = "SELECT * FROM `lastdata`";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// 輸出值
		while($row = $result->fetch_assoc()) {
			//echo  "" . $row["device1"]. " - " . $row["device2"]. " - " . $row["device3"]. " - " . $row["device4"]. "<br>";
			$c=$row["device3"];
			$d=$row["device4"];	
		}	
	} else {
    echo "0 结果";
		}
/*--------------------更新statetion的值-------------------------*/

$sql = "INSERT INTO `statetion`(`device1`, `device2`, `device3`, `device4`) VALUES (0,0,$c,$d)";

if ($conn->query($sql) === TRUE) {
    //echo "insert success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


/*--------------------更新lastdata的值-------------------------*/

$sql = "UPDATE `lastdata` SET `device1`=0,`device2`=0,`device3`=$c,`device4`=$d WHERE 1";

if ($conn->query($sql) === TRUE) {
    //echo "update success <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	







$conn->close();
	
	
	
	
	
	
}

}//end 關閉水閥

/*------------------------------------------*/
if($stuednt_array[0]=='開啟水閥'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "水閥開啟中"
    ]
  ]
]; 
$servername = "163.18.59.103";
$username = "wmlax123";
$password = "relax123";
$dbname = "wmlax123";
/*------------------------------------更新狀態-----------------------------------------*/
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

	$sql = "SELECT * FROM `lastdata`";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// 輸出值
		while($row = $result->fetch_assoc()) {
			//echo  "" . $row["device1"]. " - " . $row["device2"]. " - " . $row["device3"]. " - " . $row["device4"]. "<br>";
			$c=$row["device3"];
			$d=$row["device4"];	
		}	
	} else {
    echo "0 结果";
		}
		
/*--------------------更新lastdata的值-------------------------*/

$sql = "UPDATE `lastdata` SET `device1`=1,`device2`=1,`device3`=$c,`device4`=$d WHERE 1";

if ($conn->query($sql) === TRUE) {
    echo "update success <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	

}


/*--------------------更新statetion的值-------------------------*/

$sql = "INSERT INTO `statetion`(`device1`, `device2`, `device3`, `device4`) VALUES (1,1,$c,$d)";

if ($conn->query($sql) === TRUE) {
    echo "insert success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;		
		
		
		





$conn->close();
}
	

}//end 開啟水閥
/*------------------------------------------*/




/*----------------mode_chage_auto--------------------------*/
if($stuednt_array[0]=='自動模式'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "自動模式ing"
    ]
  ]
]; 

/*------------------------------------更新狀態-----------------------------------------*/
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

	$sql = "SELECT * FROM `lastdata`";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// 輸出值
		while($row = $result->fetch_assoc()) {
			//echo  "" . $row["device1"]. " - " . $row["device2"]. " - " . $row["device3"]. " - " . $row["device4"]. "<br>";
			$a=$row["device1"];
			$b=$row["device2"];
			$c=$row["device3"];
				
		}	
	} else {
    echo "0 结果";
		}
/*--------------------更新statetion的值-------------------------*/

$sql = "INSERT INTO `statetion`(`device1`, `device2`, `device3`, `device4`) VALUES ($a,$b,$c,1)";

if ($conn->query($sql) === TRUE) {
    //echo "insert success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


/*--------------------更新lastdata的值-------------------------*/

$sql = "UPDATE `lastdata` SET `device1`=$a,`device2`=$b,`device3`=$c,`device4`=1 WHERE 1";

if ($conn->query($sql) === TRUE) {
    //echo "update success <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	







$conn->close();
	
	
	
	
	
	
}

}//end mode_chage_auto

/*------------------------------------------*/


/*----------------mode_chage_handing--------------------------*/
if($stuednt_array[0]=='手動模式'){

$post_data = [
  "replyToken" => $reply_token,
  "messages" => [
    [
      "type" => "text",
      "text" => "手動模式ing"
    ]
  ]
]; 

/*------------------------------------更新狀態-----------------------------------------*/
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

	$sql = "SELECT * FROM `lastdata`";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// 輸出值
		while($row = $result->fetch_assoc()) {
			//echo  "" . $row["device1"]. " - " . $row["device2"]. " - " . $row["device3"]. " - " . $row["device4"]. "<br>";
			$a=$row["device1"];
			$b=$row["device2"];
			$c=$row["device3"];
				
		}	
	} else {
    echo "0 结果";
		}
/*--------------------更新statetion的值-------------------------*/

$sql = "INSERT INTO `statetion`(`device1`, `device2`, `device3`, `device4`) VALUES ($a,$b,$c,0)";

if ($conn->query($sql) === TRUE) {
    //echo "insert success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


/*--------------------更新lastdata的值-------------------------*/

$sql = "UPDATE `lastdata` SET `device1`=$a,`device2`=$b,`device3`=$c,`device4`=0 WHERE 1";

if ($conn->query($sql) === TRUE) {
    //echo "update success <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	







$conn->close();
	
	
	
	
	
	
}

}//end mode_chage_auto

/*------------------------------------------*/
$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$access_token
));
$result = curl_exec($ch);
//fwrite($file, $result."\n");  
//fclose($file);
curl_close($ch);
//###########################Reply function end.#######################################

?>