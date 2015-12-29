<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  	<link rel="stylesheet" href="style/style.css" type="text/css">
	<title>京大サークルサーチγ</title>
</head>
<body>
<?php
include "Lib.php";
ShowGoogleTag();
?>


<hr><br><br>
<div id="backcolor">
<h1><a href="index.php">京大サークルサーチγ</a></h1>
<div style="text-align:right;"><a href="input.php">サークルデータ登録はこちら</a></div>


<?php
mb_internal_encoding("UTF-8");

///////////////////////////////////////////////
//MySQL接続テンプレ////////////////////////////
include "DBManager.php";
$link = Connect_mysql();
//MySQLデータベース接続テンプレ////////////////
///////////////////////////////////////////////

$res_fielddef = mysql_list_fields( 'db_circle', 't_circle3', $link );

//各列の定義情報を表示する。
//通常のリソースとは扱い方が異なるため注意。
for( $i = 0; $i < mysql_num_fields( $res_fielddef ); $i ++ ){
	//列名
	print mysql_field_name( $res_fielddef, $i );
	//データ型
	print mysql_field_type( $res_fielddef, $i );
	//データ長
	print mysql_field_len( $res_fielddef, $i );
	//データのフラグ（"not_null", "primary_key" など）
	print mysql_field_flags( $res_fielddef, $i ) . "<br>";
}




$query="SELECT * FROM t_circle3 ORDER BY circle_id ASC";
$result = mysql_query($query);	//テーブルと取得データ選択
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

echo $query . '<br><br>';
while ($nowdata = mysql_fetch_assoc($result)) {
	echo $nowdata['circle_id']. '<br>';
	echo $nowdata['circle_name']. '<br>';
	echo $nowdata['circle_explain']. '<br>';
	echo $nowdata['circle_comment']. '<br><br>';
}
$result = mysql_query($query);	//テーブルと取得データ選択
if (!$result) {
    die('エラー：登録が失敗しました。'.mysql_error()."<br><br>$query");
}

////////////////////////////////////////
//MySQL切断テンプレ/////////////////////
Close_mysql($link);
//MySQL切断テンプレ/////////////////////
////////////////////////////////////////

?>
</div>
<div id="footer">
<hr>
連絡先：<font color="blue">kyodai.circlesearch●gmail.com</font>　（●を@に変えてください）<br>
サービスに関する意見など、ご気軽にメールください。
</div><br><br>
</body>
</html>
