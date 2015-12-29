<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<link rel="stylesheet" href="style/style.css" type="text/css">  
	<title>京大サークルサーチγ - データ登録</title>
</head>
<body>
<?php 
include "Lib.php";
ShowGoogleTag();
?>

<hr><br><br>
<div id="main">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a></div>


<?php
mb_internal_encoding("UTF-8");
	//input.phpやedit.php送られてきた処理内容をデータベースに反映、その後転送

////////////////////////////////////////
//登録ページからとんできたのかチェック//
////////////////////////////////////////
if (!isset($_POST['name'])){
	die("エラー発生！<br><a href='input.php'>サークル情報登録ページへ</a>");
}
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////


///////////////////////////////////////////////
//MySQL接続テンプレ////////////////////////////
///////////////////////////////////////////////
include "DBManager.php";
$link = Connect_mysql();
///////////////////////////////////////////////
//MySQLデータベース接続テンプレ////////////////
///////////////////////////////////////////////


if(isset($_POST['delete'])){

	///////////////////////////////////////////////////
	///↓edit.phpからデータ削除処理を受け取った時//////
	///////////////////////////////////////////////////	
	if(isset($_POST['id'])){
		$id = mysql_real_escape_string($_POST['id']);
	}else{$id=0;
	}
	$name = mysql_real_escape_string($_POST['name']);	
	
	$query="DELETE FROM `t_circle3` WHERE `circle_id` ='$id' AND `circle_name` ='$name'";
	$result = mysql_query($query);
	if (!$result) {
	    die('エラー：削除が失敗しました。'.mysql_error()."<br><br>$query"."<br><br><a href='index.php'>トップページへ戻る</a>");
	}
	echo "<br>削除が完了しました。<br><br><a href='index.php'>トップページへ戻る</a>";	
	///////////////////////////////////////////////////
	///↑edit.phpからデータ削除処理を受け取った時//////
	///////////////////////////////////////////////////
	
}else{

	
	///////////////////////////////////////////////
	///↓INSERT用データのセキュリティチェック//////
	///////////////////////////////////////////////
	if(isset($_POST['id'])){
		$id = mysql_real_escape_string($_POST['id']);
	}else{$id=0;
	}
	$name = mysql_real_escape_string($_POST['name']);
	$explain = mysql_real_escape_string($_POST['explain']);
	$member = mysql_real_escape_string($_POST['member']);
	$place = mysql_real_escape_string($_POST['place']);
	$frequency = mysql_real_escape_string($_POST['frequency']);
	$weekday = mysql_real_escape_string($_POST['weekday']);
	$sports_minded = mysql_real_escape_string($_POST['sports_minded']);
	$box = mysql_real_escape_string($_POST['box']);
	$intercollegiate = mysql_real_escape_string($_POST['intercollegiate']);
	$money = mysql_real_escape_string($_POST['money']);
	$url = mysql_real_escape_string($_POST['url']);
	$address = mysql_real_escape_string($_POST['address']);
	$telephone = mysql_real_escape_string($_POST['telephone']);
	$comment = mysql_real_escape_string($_POST['comment']);
	$tags = mysql_real_escape_string($_POST['tags']);
	$pass = mysql_real_escape_string($_POST['pass']);	
	///////////////////////////////////////////////
	///↑INSERT用データのセキュリティチェック//////
	///////////////////////////////////////////////
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	///↓edit.phpからデータ編集処理を受け取った時＆inout.phpからデータ登録処理を受け取った時//////
	///////////////////////////////////////////////////	//////////////////////////////////////////
	$query= "REPLACE INTO t_circle3 (
		circle_id,
		circle_name, 
		circle_explain, 
		circle_member,
		circle_place,
		circle_frequency,
		circle_day,
		circle_sports_minded,
		circle_box,
		circle_intercollegiate,
		circle_money,
		circle_url,
		circle_address,
		circle_telephone,
		circle_comment,
		circle_tags,
		circle_pass
	) 
	VALUES 
	(
		'$id',
		'$name', 
		'$explain', 
		'$member', 
		'$place',
		'$frequency', 
		'$weekday', 
		'$sports_minded', 
		'$box',
		'$intercollegiate',  
		'$money',
		'$url',   
		'$address', 
		'$telephone',
		'$comment',  
		'$tags',  
		'$pass'
	)";
		
	$result = mysql_query($query);
	if (!$result) {
	    die('エラー：登録が失敗しました。'.mysql_error()."<br><br>$query"."<br><br><a href='input.php'>登録ページへ戻る</a>");
	}
	
	$query = "SELECT circle_id FROM t_circle3 WHERE circle_name = '$name'";
	$result = mysql_query($query);
	if (!$result) {
	    die('エラー：処理にエラーが起きました。'.mysql_error()."<br><br>$query"."<br><br><a href='input.php'>登録ページへ戻る</a>");
	}
	$nowdata = mysql_fetch_array($result);
	
	echo "<br>登録成功！<br><br><a href='circledata.php?id=" . $nowdata['circle_id'] . "'>確認する</a>";
	//////////////////////////////////////////////////////////////////////////////////////////////
	///↑edit.phpからデータ編集処理を受け取った時＆inout.phpからデータ登録処理を受け取った時//////
	///////////////////////////////////////////////////	//////////////////////////////////////////
}

////////////////////////////////////////
//MySQL切断テンプレ/////////////////////
////////////////////////////////////////
Close_mysql($link);
////////////////////////////////////////
//MySQL切断テンプレ/////////////////////
////////////////////////////////////////

?>
</div>	<!-- mainここまで -->
<div id="footer">
<hr>
連絡先：<font color="blue">kyodai.circlesearch●gmail.com</font>　（●を@に変えてください）<br>
サービスに関する意見など、ご気軽にメールください。
</div>
</body>
</html>