<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<link rel="stylesheet" href="style/style.css" type="text/css">
	<title>京大サークルサーチγ</title>
</head>
<body>
<?php 
include "Lib.php";
ShowGoogleTag();
?>

<div id="main" style="padding-top:50px;">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a><br><a href="about.html">京大サークルサーチとは</a></div>

<form action="search.php" method="GET">
	<input type="text" name="searchwords" value="" maxlength="50" style="width:400px;" class="input">	<!-- 検索ワード入力ボックス -->
  	<input type="submit" value="検索" class="submit">
  	
  	
<?php
mb_internal_encoding("UTF-8");
	

	if((!isset($_GET['option'])) or ($_GET['option'] <> "detailed")){
	
		//簡易検索の時
		echo ('<div class="change_detail"><a href="index.php?option=detailed">▼詳細検索</a></div>');	//詳細検索に切り替え
		
	}else{		
	
		//詳細検索の時		
		//////////////////////////////////////////////////////////////////////
		//////タグ検索準備////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////
		
			///////////////////////////////////////////////
			/////MySQL接続テンプレ/////////////////////////
			///////////////////////////////////////////////
            include "DBManager.php";
            $link = Connect_mysql();
			///////////////////////////////////////////////
			/////MySQL接続テンプレ/////////////////////////
			///////////////////////////////////////////////
			
			
			///////////////////////////////////////////////
			/////登録済みタグを抽出////////////////////////
			///////////////////////////////////////////////
			$query="SELECT circle_tags FROM t_circle3";
			$result = mysql_query($query);	//テーブルと取得データ選択
			if (!$result) {
			    die('SELECTクエリーが失敗しました。'.mysql_error());
			}
			
			$alltag="";
			while ($nowdata = mysql_fetch_assoc($result)) {
				$alltag = $alltag . $nowdata['circle_tags'] . "#";
			}
			while (preg_match('/[#]{2}/', $alltag)){
				$alltag = str_replace("##", "#" , $alltag);
			}
			$taglist = explode("#", $alltag);
			$tag=array("あ" => 1);
			foreach($taglist as $keytag) {
				if(!preg_match('/^[\s]+$/', $keytag) AND strlen($keytag)){
					if(array_key_exists($keytag, $tag)){
						$tag[$keytag] ++ ;
					}else{
						$tag[$keytag] = 1;
					}
				}
			}
			///////////////////////////////////////////////
			/////ここまで（登録済みタグを抽出）////////////
			///////////////////////////////////////////////
			
			////////////////////////////////////////
			//MySQL切断テンプレ/////////////////////
			////////////////////////////////////////
            Close_mysql($link);
			////////////////////////////////////////
			//MySQL切断テンプレ/////////////////////
			////////////////////////////////////////
			
		//////////////////////////////////////////////////////////////////////////////
		//////タグ検索準備ここまで////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////
		
										
		echo '<div class="change_detail"><a href="index.php">▲詳細検索</a></div>';	//簡易検索に切り替え
		?>
		
		<!-- ////////////////////////////////////////////////////////////////
		//////////詳細検索オプション/////////////////////////////////////////
		///////////////////////////////////////////////////////////////////// -->
			<table class="item">
			<tr><td>規模(人数)</td><td>
			<input type="radio" name="member" value="SMALL">小(～20人)
			<input type="radio" name="member" value="MEDIUM">中(20～50人)
			<input type="radio" name="member" value="LARGE">大(50人～)
			<input type="radio" name="member" value="NOUSE" checked>指定しない
			</td></tr>
			<tr><td>活動頻度</td><td>
			<input type="radio" name="frequency" value="LOW">週1日以下
			<input type="radio" name="frequency" value="MEDIUM">週2日～4日
			<input type="radio" name="frequency" value="HIGH">週5日以上
			<input type="radio" name="frequency" value="NOUSE" checked>指定しない
			</td></tr>
			<tr><td>活動曜日</td><td>
			<input type="checkbox" name="weekday[]" value="Monday" >月
			<input type="checkbox" name="weekday[]" value="Tuesday" >火
			<input type="checkbox" name="weekday[]" value="Wednesay" >水
			<input type="checkbox" name="weekday[]" value="Thursday" >木
			<input type="checkbox" name="weekday[]" value="Friday" >金
			<input type="checkbox" name="weekday[]" value="Saturday" >土
			<input type="checkbox" name="weekday[]" value="Sunday" >日
			</td></tr>
			<tr><td>構成員</td><td>
			<input type="radio" name="intercollegiate" value="NO">オンリーサークル
			<input type="radio" name="intercollegiate" value="YES">外部生あり（インカレ）
			<input type="radio" name="intercollegiate" value="NOUSE" checked>指定しない
			</td></tr>
			<tr><td>タグ</td><td>
				<?php
					$i = 0;
					foreach($tag as $key => $num){
						$i = $i + 1;
						echo ('<input type="checkbox" name="searchtag[]" value="'.$key.'">' .$key.'['.$num.']');
						if ($i==3){
							echo("<br>");
							$i = 0;
						}
					}
				?>
			</td></tr></table>
		<!-- ////////////////////////////////////////////////////////////////
		/////////詳細検索オプション（ここまで）//////////////////////////////
		///////////////////////////////////////////////////////////////////// -->
<?php	
	}

?>

</form>	<!-- search.phpへのform(GET)とじる -->
</div>	<!-- id[main]とじる -->

<div id="footer">
<hr>
連絡先：<font color="blue">kyodai.circlesearch●gmail.com</font>　（●を@に変えてください）<br>
サービスに関する意見など、ご気軽にメールください。
</div>
</body>
</html>