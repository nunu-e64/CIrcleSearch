<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<link rel="stylesheet" href="style/style.css" type="text/css">
	<title>京大サークルサーチγ - サークル情報</title>
</head>
<body>
<?php 
include "Lib.php";
ShowGoogleTag();
?>


<?php
mb_internal_encoding("UTF-8");

///////////////////////////////////////////////////
//↓スーパーグローバル変数をローカル変数に代入/////
///////////////////////////////////////////////////
if (isset($_GET['id']) and is_numeric($_GET['id'])) {
	$id = htmlspecialchars($_GET['id'], ENT_QUOTES);
} else {
	if (isset($_GET['idlisturl'])){
		$idlist = explode("-" , htmlspecialchars($_GET['idlisturl'], ENT_QUOTES));
		if (isset($_GET['key']) and is_numeric($_GET['key'])) {
			$key = htmlspecialchars($_GET['key'], ENT_QUOTES);
		} else {
			$key = 0;
		}
		if(is_numeric($idlist[$key])){
			$id = $idlist[$key];
		}else{
			$id = 0;
		}		
	} else {
		$id = 0;
	}
}
///////////////////////////////////////////////////
//↑スーパーグローバル変数をローカル変数に代入/////
///////////////////////////////////////////////////

///////////////////////////////////////////////////
//↓ヘッダー///////////////////////////////////////
///////////////////////////////////////////////////
?>
<div id="main">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" ismap widrh="50%" height="50%" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a><br><a href="about.html">京大サークルサーチとは</a></div>

<div class = "backlink">
<?php 
if(isset($idlist)){
	echo ("<a href=\"search.php?key=" . $key . "&idlisturl=" . implode('-' , $idlist) . "\">←検索結果に戻る</a>");
}else{
	echo ("<a href=\"index.php\">トップページに戻る</a>");
} 
?>
</div>	<!-- class[backlink]とじる -->
<?php
//////////////////////////////////////////////////
//↑ヘッダー//////////////////////////////////////
//////////////////////////////////////////////////



///////////////////////////////////////////////
//MySQL接続テンプレ////////////////////////////
///////////////////////////////////////////////
include "DBManager.php";
$link = Connect_mysql();
///////////////////////////////////////////////
//MySQLデータベース接続テンプレ////////////////
///////////////////////////////////////////////

///////////////////////////////////////////////
////データ取得/////////////////////////////////
///////////////////////////////////////////////
$id = mysql_real_escape_string($id);
$query="SELECT * FROM t_circle3 WHERE circle_id = $id";
$result = mysql_query($query);	//テーブルと取得データ選択
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
///////////////////////////////////////////////
////データ取得/////////////////////////////////
///////////////////////////////////////////////

////////////////////////////////////////////////
/////////画面表示処理用関数/////////////////////
////////////////////////////////////////////////
function output($value){
	$outputstring = '';	
	$outputstring = htmlspecialchars($value, ENT_QUOTES);
	
	if($outputstring==-1)$outputstring = "";
	echo $outputstring;
}
function weekoutput($value){
	$weekstring='';
	for($i = 0; $i<7; $i++){
		switch ($i) {
		   	case 0:
		   		if($value % 2==0){$weekstring=$weekstring . '月/';}	break;
		   	case 1:
		   		if($value % 3==0){$weekstring=$weekstring . '火/';}	break;
		   	case 2:
		   		if($value % 5==0){$weekstring=$weekstring . '水/';}	break;
			case 3:
		   		if($value % 7==0){$weekstring=$weekstring . '木/';}	break;
		   	case 4:
		    	if($value % 11==0){$weekstring=$weekstring . '金/';}	break;
		    case 5:
		    	if($value % 13==0){$weekstring=$weekstring . '土/';}	break;
		    case 6:
		    	if($value % 17==0){$weekstring=$weekstring . '日/';}	break;
	    }
	}
	
	$weekstring = preg_replace("/\/$/", "" , $weekstring);
	return $weekstring;
}
////////////////////////////////////////////////
/////////画面表示処理用関数/////////////////////
////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////
/////////メイン表示/////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
$nowdata = mysql_fetch_array($result);
$taglist = explode("#" , $nowdata['circle_tags']);
	
	///////////////////////////////////////////////////////
	///検索にヒットした他のサークル情報へのリンク（上部）//
	///////////////////////////////////////////////////////
	echo("<div id=\"pagelink\">");
	if(isset($key) and $key > 0){
		echo "<a href=\"circledata.php?key=" , $key-1 , "&idlisturl=" . implode('-' , $idlist) . "\">←前のサークル</a>";
	}
	if(isset($key) and $key < count($idlist)-1){
		echo "　" . "<a href=\"circledata.php?key=" , $key+1 , "&idlisturl=" . implode('-' , $idlist) . "\">次のサークル→</a>";
	}
	echo("</div>");
	///////////////////////////////////////////////////////
	///検索にヒットした他のサークル情報へのリンク（上部）//
	///////////////////////////////////////////////////////
	
	//////////////////////////////////////////////////////
	///↓idに応じたサークル情報を表示/////////////////////
	//////////////////////////////////////////////////////	
	?>
	<table id="circledata">
		<tr><td class="koumoku">サークル名</td>
		<th><?php echo($nowdata['circle_name'])?></th></tr>
		
		<tr><td class="koumoku">説明</td>
		<td class="naiyou" style="text-align: left;"><?php echo($nowdata['circle_explain'])?></td></tr>
		
		<tr><td class="koumoku">メンバー数</td>
		<td class="naiyou"><?php if($nowdata['circle_member']>0){echo $nowdata['circle_member'] . '人';} ?></td></tr>
			
		<tr><td class="koumoku">活動場所・時間</td>
		<td class="naiyou" style="text-align: left;"><?php echo($nowdata['circle_place']); ?></td></tr>	
		
		<tr><td class="koumoku">活動頻度</td>
		<td class="naiyou"><?php
			switch($nowdata['circle_frequency']){
			case 1:
				echo '週1日以下';		break;
			case 2:
				echo '週2日';			break;
			case 3:
				echo '週3日';			break;
			case 4:
				echo '週4日';			break;
			case 5:
				echo '週5日';			break;
			case 6:
				echo '週6日';			break;
			case 7:
				echo '週7日';			break;
			case -1:
				echo '不定期・その他';			break;}?></td></tr>
	    
		<tr><td class="koumoku">活動曜日</td>
		<td class="naiyou"><?php echo(weekoutput($nowdata['circle_day']));?></td></tr>
		
		<tr><td class="koumoku">属性</td>
		<td class="naiyou"><?php if($nowdata['circle_sports_minded']<=0){echo 'サークル';}elseif($nowdata['circle_sports_minded']==1){echo '体育会';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td class="koumoku">ボックス（部室）の有無</td>
		<td class="naiyou"><?php if($nowdata['circle_box']<=0){echo 'なし';}elseif($nowdata['circle_box']==1){echo 'あり';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td class="koumoku">構成員</td>
		<td class="naiyou"><?php if($nowdata['circle_intercollegiate']==0){echo 'オンリーサークル';}elseif($nowdata['circle_intercollegiate']==1){echo '外部生あり（インカレ）';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td class="koumoku">会費</td>
		<td class="naiyou"><?php if($nowdata['circle_money']>=0 and strlen($nowdata['circle_money'])){echo '年' . $nowdata['circle_money'] . '円';} ?></td></tr>
			
		<tr><td class="koumoku">URL</td>
		<td class="naiyou"><a href="<?php echo($nowdata['circle_url']); ?>"><?php echo($nowdata['circle_url']); ?></a></td></tr>
		
		<tr><td class="koumoku">メールアドレス</td>
		<td class="naiyou"><?php if(preg_match("/^\.\./",$nowdata['circle_address'])<>1)echo($nowdata['circle_address']); ?></td></tr>
		
		<tr><td class="koumoku">電話番号</td>
		<td><?php if($nowdata['circle_telephone']>0)echo($nowdata['circle_telephone'])?></td></tr>
		
		<tr><td class="koumoku">コメント</td>
		<td class="naiyou" style="text-align: left;"><?php echo($nowdata['circle_comment'])?></td></tr>
		
		<tr><td class="koumoku">タグ</td>
		<td class="naiyou" style="text-align: left;">
		<?php for($i=0; $i<count($taglist); $i++){
			echo("<a href=\"search.php?tag=" . $taglist[$i] . "\">");
			echo($taglist[$i]);
			echo("</a> ");
		}?>
		</td></tr>
	</table>
	<?php
	//////////////////////////////////////////////////////
	///↑idに応じたサークル情報を表示/////////////////////
	//////////////////////////////////////////////////////
	
	//////////////////////////////////////////////////////
	///↓パスワード入力ボックス、編集・削除ボタンの設置///
	//////////////////////////////////////////////////////
	if($nowdata['circle_pass']<>""){
		$address = preg_replace('/^\.\./', "" , $nowdata['circle_address']);
		if(preg_match("/^\.\./", $nowdata['circle_address'])===1){$open_address=1;}else{$open_address=0;}
		if((isset($_SERVER['HTTPS'])) and ($_SERVER['HTTPS']=="on")){$pro = "https://";}else{$pro = "http://";}
		$original_url = $pro . $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF'] . "?" . $_SERVER["QUERY_STRING"];
?>
		<div id="editpass">
		<form method="POST" action="edit.php">
		  編集用PASS：
		  <input type="text" name="input_pass" maxlength="10">
		  <input type="hidden" name="original_url" value= <?php echo ($original_url) ?> >
		  <input type="submit" name="edit" value="編集">
		  <input type="submit" name="delete" value="削除">
			<input type="hidden" name="confirm" value=2 >
			<input type="hidden" name="id" value= <?php echo ($nowdata['circle_id']) ?> >
			<input type="hidden" name="name" value= <?php echo ($nowdata['circle_name']) ?> >
			<input type="hidden" name="explain" value= "<?php echo (preg_replace('/<br>/', '', $nowdata['circle_explain'])) ?>" >
			<input type="hidden" name="member" value= <?php if($nowdata['circle_member']==-1){echo "";}else{echo ($nowdata['circle_member']);} ?> >
			<input type="hidden" name="place" value= "<?php echo (preg_replace('/<br>/', '', $nowdata['circle_place'])) ?>" >
			<input type="hidden" name="frequency" value= <?php echo ($nowdata['circle_frequency']) ?> >
			<input type="hidden" name="weekday" value= <?php echo ($nowdata['circle_day']) ?> >
			<input type="hidden" name="sports_minded" value= <?php echo ($nowdata['circle_sports_minded']) ?> >
			<input type="hidden" name="box" value= <?php echo ($nowdata['circle_box']) ?> >
			<input type="hidden" name="intercollegiate" value= <?php echo ($nowdata['circle_intercollegiate']) ?> >
			<input type="hidden" name="money" value= <?php if($nowdata['circle_money']==-1){echo "";}else{echo ($nowdata['circle_money']);} ?> >
			<input type="hidden" name="url" value= <?php echo ($nowdata['circle_url']) ?> >
			<input type="hidden" name="address" value= <?php echo ($address) ?> >
			<input type="hidden" name="telephone" value= <?php echo ($nowdata['circle_telephone']) ?> >
			<input type="hidden" name="open_address" value= <?php echo ($open_address) ?> >
			<input type="hidden" name="comment" value=  "<?php echo (preg_replace('/<br>/', '', $nowdata['circle_comment'])) ?>" >
			<input type="hidden" name="tags" value=  "<?php echo (preg_replace('/<br>/', '', $nowdata['circle_tags'])) ?>" >
			<input type="hidden" name="pass" value= <?php echo ($nowdata['circle_pass']) ?> >
		</form>
		<br>	  
		</div> <!-- id[editpass]とじる -->
	<?php ;} 
	//////////////////////////////////////////////////////
	///↑パスワード入力ボックス、編集・削除ボタンの設置///
	//////////////////////////////////////////////////////
	?>

<div id="pagelink">
<?php	
	///////////////////////////////////////////////////////
	///検索にヒットした他のサークル情報へのリンク（下部）//
	///////////////////////////////////////////////////////
	if(isset($key) and $key > 0){
		echo "<a href=\"circledata.php?key=" , $key-1 , "&idlisturl=" . implode('-' , $idlist) . "\">←前のサークル</a>";
	}
	if(isset($key) and $key < count($idlist)-1){
		echo "　" . "<a href=\"circledata.php?key=" , $key+1 , "&idlisturl=" . implode('-' , $idlist) . "\">次のサークル→</a>";
	}
	///////////////////////////////////////////////////////
	///検索にヒットした他のサークル情報へのリンク（下部）//
	///////////////////////////////////////////////////////
?>
</div>

<?php
////////////////////////////////////////////////////////////////////////
////////↑ページ表示ここまで////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////


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