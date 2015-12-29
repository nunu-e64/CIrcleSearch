<?php session_start()?>

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

<div id="main">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a><br><a href="about.html">京大サークルサーチとは</a></div>

<?php
mb_internal_encoding("UTF-8");


///////////////////////////////////////////////
//MySQL接続テンプレ////////////////////////////
///////////////////////////////////////////////
include "DBManager.php";
$link = Connect_mysql();
///////////////////////////////////////////////
//MySQLデータベース接続テンプレ////////////////
///////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////
/////////↓データ変換・代入関数///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
function sessionstart(){	//セッション開始時の定義と初期値設定
	$_SESSION['name']='';
	$_SESSION['explain']='';
	$_SESSION['comment_1']='';
	$_SESSION['member']=-1;
	$_SESSION['place']='';
	$_SESSION['frequency']=-1;
	$_SESSION['weekday']=1;
	$_SESSION['sports_minded']=-1;
	$_SESSION['box']=-1;
	$_SESSION['intercollegiate']=-1;
	$_SESSION['money']=-1;
	$_SESSION['comment_2']='';
	$_SESSION['tags']='';
	$_SESSION['url']='';
	$_SESSION['address']='';
	$_SESSION['open_address']=0;
	$_SESSION['telephone']=-1;
	$_SESSION['pass']='';
}
function session_to_value(){	//セッション変数からローカル変数への代入
	global $name, $explain, $member, $place, $frequency, $weekday, $sports_minded, $box , $intercollegiate, $money, $url, $address,  $open_address, $telephone, $comment_1, $comment_2, $tags, $pass;
	global $page, $mode, $ok , $weekstring;

	$name = $_SESSION['name'];
	$explain = $_SESSION['explain'];
	$comment_1 = $_SESSION['comment_1'];
	$member = $_SESSION['member'];
	$place = $_SESSION['place'];
	$frequency = $_SESSION['frequency'];
	$weekday = $_SESSION['weekday'];
	$sports_minded = $_SESSION['sports_minded'];
	$box = $_SESSION['box'];
	$intercollegiate = $_SESSION['intercollegiate'];
	$money = $_SESSION['money'];
	$comment_2 = $_SESSION['comment_2'];
	$tags = $_SESSION['tags'];
	$url = $_SESSION['url'];
	$address = $_SESSION['address'];
	$open_address = $_SESSION['open_address'];
	$telephone = $_SESSION['telephone'];
	$pass = $_SESSION['pass'];
}
function value_to_session(){	//ローカル変数からセッション変数への代入
	global $name, $explain, $member, $place, $frequency, $weekday, $sports_minded, $box , $intercollegiate, $money, $url, $address,  $open_address, $telephone, $comment_1, $comment_2, $tags, $pass;
	global $page, $mode, $ok , $weekstring;

	$_SESSION['name']=$name;
	$_SESSION['explain']=$explain;
	$_SESSION['comment_1']=$comment_1;
	$_SESSION['member']=$member;
	$_SESSION['place']=$place;
	$_SESSION['frequency']=$frequency;
	$_SESSION['weekday']=$weekday;
	$_SESSION['sports_minded']=$sports_minded;
	$_SESSION['box']=$box;
	$_SESSION['intercollegiate']=$intercollegiate;
	$_SESSION['money']=$money;
	$_SESSION['comment_2']=$comment_2;
	$_SESSION['tags']=$tags;
	$_SESSION['url']=$url;
	$_SESSION['address']=$address;
	$_SESSION['open_address']=$open_address;
	$_SESSION['telephone']=$telephone;
	$_SESSION['pass']=$pass;
}
function post_to_value(){	//スーパーグローバル変数からローカル変数への代入
	global $name, $explain, $member, $place, $frequency, $weekday, $sports_minded, $box , $intercollegiate, $money, $url, $address,  $open_address, $telephone, $comment_1, $comment_2, $tags, $pass;
	global $page, $mode, $ok , $weekstring;

	if(isset($_POST['name']))			$name = $_POST['name'];
	if(isset($_POST['explain']))		$explain = $_POST['explain'];
	if(isset($_POST['comment_1']))		$comment_1 = $_POST['comment_1'];
	if(isset($_POST['member']))			$member = $_POST['member'];
	if(isset($_POST['place']))			$place = $_POST['place'];
	if(isset($_POST['frequency']))		$frequency = $_POST['frequency'];
	
	if(isset($_POST['weekday'])){	
		$weekday=1;	$weekstring='';
		while($i = each($_POST['weekday'])){
			$weekkey = 1;
			switch ($i[1]) {
		   		case 'Monday':
		   			$weekkey=2;
		   			break;
		   		case 'Tuesday':
		   			$weekkey=3;
		   			break;
		   		case 'Wednesday':
		   			$weekkey=5;
		   			break;
		   		case 'Thursday':
		   			$weekkey=7;
		   			break;
		   		case 'Friday':
		   			$weekkey=11;
		   			break;
		   		case 'Saturday':
		   			$weekkey=13;
		   			break;
		   		case 'Sunday':
		   			$weekkey=17;
		   			break;
			}
			$weekday = $weekday * $weekkey;
		}
	}	
	if(isset($_POST['sports_minded'])){
		if ($_POST['sports_minded'] == "YES" ) {
			$sports_minded = 1;
		}elseif ($_POST['sports_minded'] == "NO" ) {
			$sports_minded = 0;
		} else {
			$sports_minded = -1;
		}
	}	
	if(isset($_POST['box'])){
		if ($_POST['box'] == "YES" ) {
			$box = 1;
		}elseif ($_POST['box'] == "NO" ) {
			$box = 0;
		} else {
			$box = -1;
		}
	}	
	if(isset($_POST['intercollegiate'])){
		if ($_POST['intercollegiate'] == "YES" ) {
			$intercollegiate = 1;
			$ok['intercollegiate'] = True;
		}elseif ($_POST['intercollegiate'] == "NO" ) {
			$intercollegiate = 0;
			$ok['intercollegiate'] = True;
		} else {
			$intercollegiate = -1;
			$ok['intercollegiate'] = True;
		}
	}		
	if(isset($_POST['money']))			$money = $_POST['money'];
	if(isset($_POST['comment_2']))		$comment_2 = $_POST['comment_2'];
	if(isset($_POST['tags']))			$tags = $_POST['tags'];
	if(isset($_POST['url']))			$url = $_POST['url'];
	if(isset($_POST['address']))		$address = $_POST['address'];
	
	if(isset($_POST['open_address'])){
		if(($address<>'') and ($_POST['open_address']=="YES")){$open_address = 1;}else{$open_address=0;}
	}
			
	if(isset($_POST['telephone']))		$telephone = $_POST['telephone'];
	if(isset($_POST['pass']))			$pass = $_POST['pass'];
}//スーパーグローバル変数からローカル変数への代入

//////////////////////////////////////////////////////////////////////////////////
/////////↑データ変換・代入関数///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////
/////////↓ロード処理（エラー判定とページ決定、モード判断）///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
function valuecheck()	//入力済み内容のエラーチェック
{
	global $name, $explain, $member, $place, $frequency, $weekday, $sports_minded, $box , $intercollegiate, $money, $url, $address,  $open_address, $telephone, $comment_1, $comment_2, $tags, $pass;
	global $page, $mode, $ok , $weekstring;
	
	$valuegood = True;	
		//登録データ入力は4ページからなる（1～3：データ入力とエラー時の修正　4：確認画面）
		//モードは３つ（0:input入力・確認,1:errorエラー,3:revise修正）
		
	switch($page){
	case 1:
		break;
	case 2:				
		if (strlen($name)) {
			$result = mysql_query("SELECT count(*) AS c FROM t_circle3 WHERE circle_name = '$name'");
			//$result = mysql_query("SELECT COUNT>0 AS IS_EXIST FROM (SELECT count(*) AS COUNT FROM t_circle3 WHERE circle_name = $name) AS DAMMY");
				if (!$result) {die('SELECTクエリーが失敗しました。'.mysql_error());	}
			$row = mysql_fetch_array($result);
			if (!$row['c']){
				$ok['name'] = True;
			}else{
				$valuegood = False;
				$ok['name'] = False;	
			}
		} else {
			$name = '';
			$valuegood = False;
			$ok['name'] = False;
		}
		
		if(!strlen($explain)){
			$valuegood = False;
			$ok['explain'] = False;
		}else{
			$ok['explain'] = True;
		}
		
		if((!strlen($comment_1)) or (mb_strwidth($comment_1, mb_internal_encoding())>1600)){
			$valuegood = False;
			$ok['comment_1'] = False;
		}else{
			$ok['comment_1'] = True;
		}
				
		break;
	
	case 3:	
		if (strlen($member)) {
			if (!is_numeric($member) or ($member < 0)){
				$valuegood = False;
				$ok['member'] = False;
			}else{
				$ok['member'] = True;
			}
		} else {
			$member = -1;
			$ok['member'] = True;
		}
		
		if(mb_strwidth($place,mb_internal_encoding()) > 800){
			$valuegood = False;
			$ok['place'] = False;
		}else{
			$ok['place'] = True;
		}
		
		$ok['frequency'] = True;		
		$ok['weekday'] = True;		 
		$ok['sports_minded'] = True;			
		$ok['box'] = True;		
		$ok['intercollegiate'] = True;
		
		if (strlen($money)) {
			if (!is_numeric($money) or $money < 0){
				$valuegood = False;
				$ok['money'] = False;
			}else{
				$ok['money'] = True;
			}
		} else {
			$money = -1;
			$ok['money'] = True;
		}
				
		if(mb_strwidth($tags,mb_internal_encoding())>400){
			$valuegood = False;
			$ok['tags'] = False;
		}else{
			$ok['tags'] = True;
		}
		
		if(mb_strwidth($comment_2,mb_internal_encoding())>800){
			$valuegood = False;
			$ok['comment_2'] = False;
		}else{
			$ok['comment_2'] = True;
		}
		
		break;

	case4:
		$ok['url'] = True;
		$ok['address'] = True;
		
		if ((!is_numeric($telephone) or ($telephone < 0)) and strlen($telephone)){
			$valuegood = False;
			$ok['telephone'] = False;
		}else{
			$ok['telephone'] = True;
		}
		
		$ok['pass'] = True;

	}

	if($valuegood==False){
		if($mode=="input" and $page>1)$page=$page-1;
		if($mode=="revise" and $page<3)$page=$page+1;
		$mode="error";
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////
/////////↑ロード処理（エラー判定とページ決定、モード判断）///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////↓メイン/////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['page'])){$page=$_POST['page'];}else{$page=1;}
	if (isset($_POST['mode'])){$mode=$_POST['mode'];}else{$mode="input";}
	
	if (!isset($_SESSION['name'])){
		sessionstart();
		session_to_value();
	}else{
		//echo($_COOKIE['PHPSESSID'] . "<br>");
		//echo($mode . "<br>");
		//echo($page . "<br>");
		session_to_value();
		post_to_value();
		valuecheck();
		//echo($mode . "<br>");
		//echo($page . "<br>");
	}
	
	value_to_session();	
	showscreen();
	//0:input,1:error,3:revise
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////↑メイン/////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////
///////↓表示データ処理用関数//////////////
///////////////////////////////////////////
function output($value){
	$outputstring = '';	
	$outputstring = htmlspecialchars($value, ENT_QUOTES);
	
	if($outputstring==-1)$outputstring = "";
	return $outputstring;
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
		    	if($value % 11==0){$weekstring=$weekstring . '金/';}break;
		    case 5:
		    	if($value % 13==0){$weekstring=$weekstring . '土/';}break;
		    case 6:
		    	if($value % 17==0){$weekstring=$weekstring . '日/';}break;
	    }
	}
	$weekstring = preg_replace("/\/$/", "" , $weekstring);
	return $weekstring;
}
///////////////////////////////////////////
///////↑表示データ処理用関数//////////////
///////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////
///////↓画面表示/////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
function showscreen(){
	global $name, $explain, $member,$place, $frequency, $weekday, $sports_minded, $box, $intercollegiate, $money ,$url, $address, $open_address, $telephone, $comment_1, $comment_2, $tags, $pass;
	global $mode, $page, $ok, $weekstring;
	
	switch ($mode) {
		case "input":
		case "error":
		case "revise":
?>	
	<div style="text-align: left; padding-left: 20px">
	<h2>-サークルデータ登録</h2>
	はじめにこちらをご覧ください→<a href="about.html">京大サークルサーチとは</a><br><br>
	サークルの情報を入力してください。<br>
	
	<?php if(!isset($page))$page=1;?>
		
		<?php if($page==1)echo("<font color=\"red\">");?>
		１．必須項目
		<?php if($page==1)echo("</font>");?>
		<?php if($page==2)echo("<font color=\"red\">");?>
		２．自由項目
		<?php if($page==2)echo("</font>");?>
		<?php if($page==3)echo("<font color=\"red\">");?>
		３．パスワード他
		<?php if($page==3)echo("</font>");?>
		
	</div>
	<hr>
	
	<?php
	switch($page){
	case 1: ?>
		<form method="POST" action="input.php">
		<table class="editform">	
			<tr><td>サークル名</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['name']==False){if($name==""){echo 'サークル名は必須項目です！<br>';}else{echo 'この名前は既に使われています';};}?></div>
				<input type="text" name="name" value= "<?php echo(output($name)); ?>" maxlength="30" style="width:90%;"></td></tr>
			
			<tr><td>何をするサークルですか？<br />（30字以内）</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['explain']==False){echo 'サークルの概要は必須項目です！<br>';}?></div>
				<input type="text" name="explain" value="<?php echo(output($explain)); ?>" maxlength="30" style="width:90%;">
				<br>簡潔にサークルの概要を記入ください。（検索結果一覧で表示されます）<br>例：他大の学生もOKの京大公認硬式テニスサークルです</td></tr>
		
			<tr><td>紹介や宣伝</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['comment_1']==False){echo '未記入または文が長すぎます。詳細な説明は400字以内の必須項目です！';}?></div>
				<textarea name="comment_1" cols=60 rows=8><?php echo(output($comment_1)); ?></textarea><br>サークル詳細情報画面に表示されます。アピールポイントや新入生へのメッセージなどご自由にお書きください。</td></tr>
		</table><br>
			<input type="hidden" name="page" value= "<?php echo($page+1);?>" >
			<div style="text-align:center;"><input type="submit" value="次へ"></div>
		</form>
	<?php break; ?>
	
	<?php case 2: ?>
	・情報が多いほど検索もされやすくユーザへのアピールにも効果的です。できるだけ多くの項目に入力をお願いします。<br>
	
		<form method="POST" action="input.php">
		<table class="editform">
			<tr><td>メンバー数<br />（半角数字）</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['member']==False){echo '値が不正です。<br>';}?></div>
				<input type="text" name="member" value="<?php echo(output($member)); ?>" maxlength="5" style="width:100px;">人</td></tr>
			
			<tr><td>活動場所・時間</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['place']==False){echo '文が長すぎます。活動場所・時間は800字以内の自由項目です！';}?></div>
				<textarea name="place" cols=60 rows=5><?php echo(output($place)); ?></textarea><br>（例：平日は17時～19時、土日は12時～15時、場所は鴨川の河川敷です。毎日活動しています！）</td></tr>
			
			<tr><td>活動頻度</td>
			<td><select name="frequency">
		        <option value="-1" <?php if($frequency==-1){echo 'selected';}?> >不定期・その他</option>
		        <option value="1" <?php if($frequency==1){echo 'selected';}?> >週1日以下</option>
		        <option value="2" <?php if($frequency==2){echo 'selected';}?> >週2日</option>
		        <option value="3" <?php if($frequency==3){echo 'selected';}?> >週3日</option>
		        <option value="4" <?php if($frequency==4){echo 'selected';}?> >週4日</option>
		        <option value="5" <?php if($frequency==5){echo 'selected';}?> >週5日</option>
		        <option value="6" <?php if($frequency==6){echo 'selected';}?> >週6日</option>
		        <option value="7" <?php if($frequency==7){echo 'selected';}?> >週7日</option>
		    </select></td></tr>
		    
			<tr><td>活動曜日</td>
			<td><input type="checkbox" name="weekday[]" value="Monday" <?php if($weekday % 2 == 0){echo 'checked';}?> >月
				<input type="checkbox" name="weekday[]" value="Tuesday" <?php if($weekday % 3 == 0){echo 'checked';}?> >火
				<input type="checkbox" name="weekday[]" value="Wednesday" <?php if($weekday % 5 == 0){echo 'checked';}?> >水
				<input type="checkbox" name="weekday[]" value="Thursday" <?php if($weekday % 7 == 0){echo 'checked';}?> >木
				<input type="checkbox" name="weekday[]" value="Friday" <?php if($weekday % 11 == 0){echo 'checked';}?> >金
				<input type="checkbox" name="weekday[]" value="Saturday" <?php if($weekday % 13 == 0){echo 'checked';}?> >土
				<input type="checkbox" name="weekday[]" value="Sunday" <?php if($weekday % 17 == 0){echo 'checked';}?> >日
			</td></tr>
			
			<tr><td>属性</td>
				<td><input type="radio" name="sports_minded" value="NO" <?php if($sports_minded==0){echo 'checked';}?> >サークル
				<input type="radio" name="sports_minded" value="YES" <?php if($sports_minded==1){echo 'checked';}?> >体育会
				<input type="radio" name="sports_minded" value="NOUSE" <?php if($mode=="input" or $sports_minded==-1){echo 'checked';}?> >指定しない
			</td></tr>
			
			<tr><td>ボックス(部室)の有無</td>
				<td><input type="radio" name="box" value="YES" <?php if($box==1){echo 'checked';}?> >あり
				<input type="radio" name="box" value="NO" <?php if($box==0){echo 'checked';}?> >なし
				<input type="radio" name="box" value="NOUSE" <?php if($mode=="input" or $box==-1){echo 'checked';}?> >指定しない
			</td></tr>
			
			<tr><td>構成員</td>
				<td><input type="radio" name="intercollegiate" value="NO" <?php if($intercollegiate==0){echo 'checked';}?> >オンリーサークル
				<input type="radio" name="intercollegiate" value="YES" <?php if($intercollegiate==1){echo 'checked';}?> >外部生あり（インカレ）
				<input type="radio" name="intercollegiate" value="NOUSE" <?php if($mode=="input" or $intercollegiate==-1){echo 'checked';}?> >指定しない
			</td></tr>
		  		
			<tr><td>会費</td>
				<td><div class='formerror'><?php if($mode=="error" and $ok['money']==False){echo '値が不正です。会費は半角数字で入力してください！<br>';}?></div>
				年<input type="text" name="money" value="<?php echo(output($money)); ?>" maxlength="8" style="width:100px;">円</td></tr>
			
			<tr><td>その他追加情報・補足など（任意）</td>
				<td><div class='formerror'><?php if($mode=="error" and $ok['comment_2']==False){echo '文が長すぎます。詳細な説明は400字以内の自由項目です！';}?></div>
				<textarea name="comment_2" cols=60 rows=8><?php echo(output($comment_2)); ?></textarea><br>サークル詳細情報画面に表示される説明です。<br>普段の活動内容やアピールポイント、新入生へのメッセージなどご自由にお書きください。</td></tr>
			
			<tr><td>検索用タグ</td>
				<td><div class='formerror'><?php if($mode=="error" and $ok['tags']==False){echo '文が長すぎます。検索タグは200字以内の自由項目です！';}?></div>
				<textarea name="tags" cols=60 rows=3><?php echo(output($tags)); ?></textarea><br>（例：運動#公認#ボランティア系#音楽系#ボックス有#合宿有）<br>ワード検索用のタグです。サークルの特徴を#で区切って書いてください。</td></tr>
			
		</table><br>
			<input type="hidden" name="page" value= "<?php echo($page+1);?>" > 
			<div style="text-align:center;"><input type="submit" value="次へ"></div>
		</form>
	<?php break; ?>
	
	<?php case 3: ?>
		<form method="POST" action="input.php">
		<table class="editform">
			<tr><td>URL（任意）</td>
			<td><input type="text" name="url" value="<?php echo(output($url)); ?>" maxlength="100" style="width:300px;"><br>例：http://www.kyoto-○○circle.com</td></tr>
			
			<tr><td>メールアドレス<br />（任意）</td>
			<td><input type="text" name="address" value="<?php echo(output($address)); ?>" maxlength="50" style="width:300px;"><br>
				<input type="checkbox" name="open_address" value="YES" <?php if($open_address==1){echo 'checked';}?>>サークル情報ページに連絡先として公開を希望<br>
				サービスの更新時や追加データが必要となった時の連絡先です。なるべくご記入お願いします。</td></tr>
				
			<tr><td>電話番号（任意）<br>（半角数字のみでハイフンなし）</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['telephone']==False){echo '値が不正です。電話番号は半角数字のみで入力してください！<br>';}?></div>
				<input type="text" name="telephone" value="<?php echo(output($telephone)); ?>" maxlength="15"><br>
				サークル情報ページに<b>連絡先として公開を希望される場合</b>記入してください。</td></tr>
			
			<tr><td>編集・削除用パス<br>（推奨）</td>
			<td><div class='formerror'><?php if($mode=="error" and $ok['pass']==False){echo 'パスコード設定エラー';}?></div>
				<input type="text" name="pass" value="<?php echo(output($pass)); ?>" maxlength="10"><br>サークル情報を後から自分で編集、削除するためのパスワードです。<br />管理人にメールいただくよりも早く簡単に登録情報の修正が可能になります。</td></tr>
			</table><br>
			<input type="hidden" name="page" value= "<?php echo($page+1);?>" >
			
		・<b>登録情報はデータ管理や検索をよりよくするために、編集する可能性があります。<br>
		その場合も趣旨の保全に努めますが、ご承知のうえ登録をお願いします。</b>（例：検索タグを書き加えるなど）
		
			<div style="text-align:center;"><input type="submit" value="次へ"></div>
		</form>
		
		<?php break;
		
	case 4:
	////////////////////////////////////////////////////////////////////////////
	//確認画面表示//////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	?>
	<div style="text-align: left; padding-left: 20px">
	<h2>-サークルデータ入力確認</h2>
	<span class="warning">※まだ登録は完了していません！</span><br>入力内容を確認の上、「登録」をクリックしてください。
	</div>
	<hr>
	
		<table class="editform">
		<tr><td class="col1">サークル名</td>
		<td><?php echo output($name) ?></td></tr>
		
		<tr><td>簡単な説明</td>
		<td><?php echo nl2br(output($explain),false) ?></td></tr>
		
		<tr><td>紹介や宣伝</td>
		<td><?php echo nl2br(output($comment_1),false) ?></td></tr>
		
		<tr><td>メンバー数</td>
		<td><?php if($member>=0){echo output($member) . '人';} ?></td></tr>
			
		<tr><td>活動場所・時間</td>
		<td><?php echo nl2br(output($place),false); ?></td></tr>	
		
		<tr><td>活動頻度</td>
		<td><?php
			switch($frequency){
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
	    
		<tr><td>活動曜日</td>	
		<td><?php echo weekoutput($weekday);?></td></tr>
		
		<tr><td>属性</td>
		<td><?php if($sports_minded==0){echo 'サークル';}elseif($sports_minded==1){echo '体育会';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td>ボックス（部室）の有無</td>
		<td><?php if($box==0){echo 'なし';}elseif($box==1){echo 'あり';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td>構成員</td>
		<td><?php if($intercollegiate==0){echo 'オンリーサークル';}elseif($intercollegiate==1){echo '外部生あり（インカレ）';}else{echo '指定しない';} ?></td></tr>
	  		
		<tr><td>会費</td>
		<td><?php if($money>=0 and strlen($money)){echo ('年' . output($money) . '円');} ?></td></tr>
		
		<tr><td>その他追加情報・補足など</td>
		<td><?php echo nl2br(output($comment_2), false); ?></td></tr>
		
		<tr><td>検索用タグ</td>
		<td><?php echo nl2br(output($tags),false); ?></td></tr>	
		
		<tr><td>URL</td>
		<td><?php echo output($url); ?></td></tr>
		
		<tr><td>メールアドレス</td>
		<td><?php echo output($address); ?><br>
		検索ページに連絡先として公開を希望<?php if($open_address==1){echo 'する';}else{echo 'しない';} ?></td></tr>
		
		<tr><td>電話番号（公開用）</td>
		<td><?php if($telephone>0){echo output($telephone);} ?></td></tr>
		
		<tr><td>編集・削除用パスワード</td>
		<td><?php echo nl2br(output($pass),false); ?></td></tr>	
	</table><br>
	<?php
	//////////////////////////////////////////////////////
	//確認画面表示ここまで////////////////////////////////
	//////////////////////////////////////////////////////
	?>
	
	<!--POST送信用フォーム （修正＆本登録）-->
	<table style="margin-left:auto; margin-right:auto;">
	<tr>
	<td>
		<form method="POST" action="input.php">
			<input type="hidden" name="mode" value= "revise">
			<input type="hidden" name="page" value= 1>
			<input type="submit" value="修正">
		</form>
	</td>
	<td width="150px"></td>
	<td>
		<form method="POST" action="insert.php">
			<input type="hidden" name="name" value="<?php echo($name) ?>" >
			<input type="hidden" name="explain" value= "<?php echo (nl2br($explain,false)) ?>" >
			<input type="hidden" name="member" value= <?php echo ($member) ?> >
			<input type="hidden" name="place" value= "<?php echo (nl2br($place,false)) ?>" >
			<input type="hidden" name="frequency" value= <?php echo ($frequency) ?> >
			<input type="hidden" name="weekday" value= <?php echo ($weekday) ?> >
			<input type="hidden" name="sports_minded" value= <?php echo ($sports_minded) ?> >
			<input type="hidden" name="box" value= <?php echo ($box) ?> >
			<input type="hidden" name="intercollegiate" value= <?php echo ($intercollegiate) ?> >
			<input type="hidden" name="money" value= <?php echo ($money) ?> >
			<input type="hidden" name="url" value= <?php echo ($url) ?> >
			<input type="hidden" name="address" value= <?php if($open_address==1){echo ($address);}else{echo ('..' . $address);} ?> >
			<input type="hidden" name="telephone" value= <?php echo ($telephone) ?> >
			<input type="hidden" name="comment" value= "<?php echo (nl2br($comment_1 . "<br><br>--<br><br>" . $comment_2,false)) ?>" >
			<input type="hidden" name="tags" value= "<?php echo (nl2br($tags,false)) ?>" >
			<input type="hidden" name="pass" value= "<?php echo (nl2br($pass,false)) ?>" >
			<input type="submit" value="登録">
		</form>
	</td>
	</tr>
	</table>
<?php
	}	//$pageのswitchとじる
	}	//$modeのswitchとじる
}		//showscreenとじる
//////////////////////////////////////////////////////////////////////////////////
///////↑画面表示/////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

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