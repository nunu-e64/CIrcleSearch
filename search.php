<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<link rel="stylesheet" href="style/style.css" type="text/css">
	<title>京大サークルサーチγ	- 検索結果</title>
</head>
<body>
<?php 
include "Lib.php";
ShowGoogleTag();
?>

<div id="main">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a><br><a href="about.html">京大サークルサーチとは</a></div>

<div class = "backlink"><a href="index.php">←トップページに戻る</a></div>

<?php
mb_internal_encoding("UTF-8");
$DATA_PER_PAGE = 10;		//1ページにいくつのサークル情報を表示するか

///////////////////////////////////////////////
//MySQL接続テンプレ////////////////////////////
///////////////////////////////////////////////
include "DBManager.php";
$link = Connect_mysql();
///////////////////////////////////////////////
//MySQLデータベース接続テンプレ////////////////
///////////////////////////////////////////////


///////////////////////////////////////////////
//↓GETで渡された値の下準備　ここから//////////
///////////////////////////////////////////////
if (isset($_GET['page'])){
	$page = htmlspecialchars($_GET['page'], ENT_QUOTES);
}elseif(isset($_GET['key'])){
	$page = (int)($_GET['key'] / $DATA_PER_PAGE)+1;
}else{
	$page = 1;
}
if (isset($_GET['idlisturl'])){
	$idlist = explode("-" , htmlspecialchars($_GET['idlisturl'], ENT_QUOTES));
}

if (isset($_GET['searchwords']) ) {
	$searchwords = mysql_real_escape_string($_GET['searchwords']);
} else {
	$searchwords = '';
}
if (isset($_GET['tag']) ) {
	$tag = mysql_real_escape_string($_GET['tag']);
} else {
	$tag = '';
}

if (isset($_GET['member']) ) {
	$member = mysql_real_escape_string($_GET['member']);
} else {
	$member = 'NOUSE';
}
if (isset($_GET['frequency']) ) {
	$frequency = mysql_real_escape_string($_GET['frequency']);
} else {
	$frequency = 'NOUSE';
}
if (isset($_GET['intercollegiate']) ) {
	$intercollegiate = mysql_real_escape_string($_GET['intercollegiate']);
} else {
	$intercollegiate = 'NOUSE';
}
///////////////////////////////////////////////
//↑GETで渡された値の下準備　ここから//////////
///////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////
/////↓SELECTクエリに使う検索データ（$task）の準備/////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if(isset($idlist)){

	$task = "circle_id = " . implode(" OR circle_id = " , $idlist); 	//circledata.phpから戻ってきたときやページ移動したとき
	unset($idlist);
	
}else{

	$task = '0=0';
	
	if(isset($_GET['searchtag'])){
		foreach($_GET['searchtag'] as $key => $value){
			$task = "circle_tags LIKE '%$value%'" . " AND " . $task;	//検索タグ（検索オプション）
		}
	}	
	$task = "circle_tags LIKE '%$tag%'" . " AND " . $task;	//検索タグ（タグリンク）
	$task = "(circle_tags LIKE '%$searchwords%' OR circle_name LIKE '%$searchwords%' OR circle_explain LIKE '%$searchwords%' OR circle_comment LIKE '%$searchwords%')" . " AND " . $task;	//検索ワード（サークル名、タグ、簡易紹介、詳細コメントから部分一致で検索）
	switch ($member) {
	    case 'SMALL':
	    	$task = "circle_member <= 20" . " AND " . $task;
	        break;
	    case 'MEDIUM':
	    	$task = "circle_member BETWEEN 20 AND 50" . " AND " . $task;
	        break;
	    case 'LARGE':
	    	$task = "circle_member >= 50" . " AND " . $task;
	        break;
	    default:
	}
	switch ($frequency) {
	    case 'LOW':
	    	$task = "circle_frequency BETWEEN 0 AND 1" . " AND " . $task;
	        break;
	    case 'MEDIUM':
	    	$task = "circle_frequency BETWEEN 2 AND 4" . " AND " . $task;
	        break;
	    case 'HIGH':
	    	$task = "circle_frequency >= 5" . " AND " . $task;
	        break;
	    default:
	}
	if(isset($_GET['weekday'])){
		while($i = each($_GET['weekday'])){
			$weekkey = 1;
			switch ($i[1]) {
	    		case 'Monday':
	    			$weekkey=2;        		break;
	    		case 'Tuesday':
	    			$weekkey=3;        		break;
	    		case 'Wednesday':
	    			$weekkey=5;        		break;
	    		case 'Thursday':
	    			$weekkey=7;        		break;
	    		case 'Friday':
	    			$weekkey=11;       		break;
	    		case 'Saturday':
	    			$weekkey=13;       		break;
	    		case 'Sunday':
	    			$weekkey=17;       		break;
			}
			$task = "circle_day % $weekkey = 0" . " AND " . $task;
		}
	}
	switch ($intercollegiate) {
	    case 'YES':
	    	$task = "circle_intercollegiate = 1" . " AND " . $task;
	        break;
	    case 'NO':
	    	$task = "circle_intercollegiate = 0" . " AND " . $task;
	        break;
	    default:
	}
}
///////////////////////////////////////////////////////////////////////////////////
/////↑SELECTクエリに使う検索データ（$task）の準備/////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////
//↓作成した検索データ（$task）で該当するデータを抽出して配列化/////
////////////////////////////////////////////////////////////////////
$query="SELECT circle_id, circle_name, circle_explain FROM t_circle3 WHERE $task ORDER BY circle_id ASC";
$result = mysql_query($query);	//テーブルと取得データ選択

if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
while ($nowdata = mysql_fetch_assoc($result)) {
	$idlist[] = $nowdata['circle_id'];
	$namelist[] = $nowdata['circle_name'];
	$explainlist[] = $nowdata['circle_explain'];
}
////////////////////////////////////////////////////////////////////
//↑作成した検索データ（$task）で該当するデータを抽出して配列化/////
////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
/////↓取得したデータをtableを利用してページ表示////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
	if(strlen($searchwords)){echo "検索ワード：$searchwords<br>";}
	if(strlen($tag)){echo "検索タグ：$tag<br>";}
		
	if(isset($idlist)){
		echo mysql_num_rows($result) . "件ヒットしました";		//検索ヒット数表示
		$idlisturl = "&idlisturl=" . implode("-" , $idlist);	//ページの行き来の為、検索ヒットしたデータのidをまとめて保持
		
		//////////////////////////////////////////////////////////////////////
		////ここから　検索結果のページ移動リンク//////////////////////////////
		//////////////////////////////////////////////////////////////////////		
		echo "<div id=\"pagelink\">";
		if($page > 1){echo "<a href=\"search.php?page=", $page-1,  $idlisturl, "\">←前のページ</a>"; }
			for($i = 1; $i < (int)((count($idlist)-1)/$DATA_PER_PAGE+2); $i++){
				$pagelink = "　" . "<a href=\"search.php?page=" . $i . $idlisturl . "\"";
				if($i==$page){$pagelink =  $pagelink . " style='text-decoration:none; font-weight: normal;'";}
				$pagelink = $pagelink . ">" .  $i . "</a>";
				echo $pagelink;
			}
		if(isset($idlist) and $page < (int)((count($idlist)-1)/$DATA_PER_PAGE) + 1){echo "　" . "<a href=\"search.php?page=", $page+1, $idlisturl, "\">次のページ→</a>"; }
		echo "</div>";
		//////////////////////////////////////////////////////////////////////
		////ここまで　検索結果ページの移動リンク//////////////////////////////
		//////////////////////////////////////////////////////////////////////	
		
		//////////////////////////////////////////////////////////////////////
		////ここから　今の$pageに合わせてデータを$DATA_PER_PAGE個だけ表示/////
		//////////////////////////////////////////////////////////////////////
		echo "<table id=\"result\">";
		for($i = ($page-1) * $DATA_PER_PAGE; $i < min(count($idlist), $page * $DATA_PER_PAGE); $i++){
			echo "<tr><td class=\"name\"><a href=\"circledata.php?key=" .  $i . $idlisturl . "\">" . $namelist[$i] . "</div></a></td><td class=\"content\">" 
				. $explainlist[$i] . "</td></tr>";
		}
		echo "</table>";
		//////////////////////////////////////////////////////////////////////
		////ここまで　今の$pageに合わせてデータを$DATA_PER_PAGE個だけ表示/////
		//////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////////////////////////////////////
		////ここから　検索結果のページ移動リンク//////////////////////////////
		//////////////////////////////////////////////////////////////////////		
		echo "<div id=\"pagelink\">";
		if($page > 1){echo "<a href=\"search.php?page=", $page-1,  $idlisturl, "\">←前のページ</a>"; }
			for($i = 1; $i < (int)((count($idlist)-1)/$DATA_PER_PAGE+2); $i++){
				$pagelink = "　" . "<a href=\"search.php?page=" . $i . $idlisturl . "\"";
				if($i==$page){$pagelink =  $pagelink . " style='text-decoration:none; font-weight: normal;'";}
				$pagelink = $pagelink . ">" .  $i . "</a>";
				echo $pagelink;
			}
		if(isset($idlist) and $page < (int)((count($idlist)-1)/$DATA_PER_PAGE) + 1){echo "　" . "<a href=\"search.php?page=", $page+1, $idlisturl, "\">次のページ→</a>"; }
		echo "</div>";
		//////////////////////////////////////////////////////////////////////
		////ここまで　検索結果ページの移動リンク//////////////////////////////
		//////////////////////////////////////////////////////////////////////	
	}else{
		echo '検索条件にあてはまるサークルは見つかりませんでした<br>条件を変えてお試し下さい';		//検索該当なし
	}
////////////////////////////////////////////////////////////////////////////////////////
/////↑取得したデータをtableを利用してページ表示////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////
//MySQL切断テンプレ/////////////////////
////////////////////////////////////////
Close_mysql($link);
////////////////////////////////////////
//MySQL切断テンプレ/////////////////////
////////////////////////////////////////


?>
</div>	<!-- id[main]とじる -->
<div id="footer">
<hr>
連絡先：<font color="blue">kyodai.circlesearch●gmail.com</font>　（●を@に変えてください）<br>
サービスに関する意見など、ご気軽にメールください。
</div>
</body>
</html>
