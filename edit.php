<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<link rel="stylesheet" href="style/style.css" type="text/css">
	<title>京大サークルサーチγ - データ編集</title>
</head>
<body>
<?php 
include "Lib.php";
ShowGoogleTag();
?>

<div id="main">
<div id="header"><a href="index.php"><img src="style/logo_b2_2.png" alt="京大サークルサーチ"></a></div>
<div id="headlink"><a href="input.php">サークルデータ登録はこちら</a></div>

<?php
mb_internal_encoding("UTF-8");

///登録データの編集および削除////////////////////////

//////////////////////////////////////////////////////////////
/////ロード処理（各種変数代入、編集内容のエラーの有無確認）///
//////////////////////////////////////////////////////////////
function valuecheck(){
	global $name, $explain, $member, $place, $frequency, $weekday, $sports_minded, $box , $intercollegiate, $money, $url, $address,  $open_address, $telephone, $comment, $tags, $pass;
	global $confirm, $ok , $weekstring;
	
	$valuegood = True;
	//$ok = array("name" => NULL, "explain" => NULL, "member" => NULL, "frequency" => NULL, "weekday" => NULL, "intercollegiate" => NULL, "url" => NULL, "comment" => NULL, "tags" => NULL);
	
	if (isset($_POST['confirm'])){$confirm=$_POST['confirm'];}else{$confirm=0;}
	
	if (isset($_POST['confirm']) and ($confirm==1)){		
		if (strlen($_POST['name'])) {
			$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
			$ok['name'] = True;
		} else {
			$name = '';
			$valuegood = False;
			$ok['name'] = False;
		}
		
		if (isset($_POST['explain']) ) {
			$explain = htmlspecialchars($_POST['explain'], ENT_QUOTES);
			if((!strlen($explain)) or (mb_strwidth($explain,mb_internal_encoding()) > 200)){
				$valuegood = False;
				$ok['explain'] = False;
			}else{
				$ok['explain'] = True;
			}
		} else {
			$explain = '';
			$valuegood = False;
			$ok['explain'] = False;
		}
	
		if (isset($_POST['member']) and (strlen($_POST['member']))) {
			$member = htmlspecialchars($_POST['member'], ENT_QUOTES);
			if ((!is_numeric($member) or ($member < 0)) and strlen($member)){
				$valuegood = False;
				$ok['member'] = False;
			}else{
				$ok['member'] = True;
			}
		} else {
			$member = -1;
			$ok['member'] = True;
		}
		
		if (isset($_POST['place']) ) {
			$place = htmlspecialchars($_POST['place'], ENT_QUOTES);
			if(mb_strwidth($place,mb_internal_encoding()) > 1600){
				$valuegood = False;
				$ok['place'] = False;
			}else{
				$ok['place'] = True;
			}
		} else {
			$place = '';
			$ok['place'] = True;
		}
		
		if (isset($_POST['frequency']) ) {
			$frequency = htmlspecialchars($_POST['frequency'], ENT_QUOTES);
			$ok['frequency'] = True;
		} else {
			$frequency = '-1';
			$ok['frequency'] = True;
		}
		
		if(isset($_POST['weekday'])){
			$weekday=1;	$weekstring='';
			while($i = each($_POST['weekday'])){
				$weekkey = 1;
				switch ($i[1]) {
		    		case 'Monday':
		    			$weekkey=2;	$weekstring=$weekstring . '月/';		break;
		    		case 'Tuesday':
		    			$weekkey=3;	$weekstring=$weekstring . '火/';		break;
		    		case 'Wednesday':
		    			$weekkey=5;	$weekstring=$weekstring . '水/'; 		break;
		    		case 'Thursday':
		    			$weekkey=7;	$weekstring=$weekstring . '木/'; 		break;
		    		case 'Friday':
		    			$weekkey=11;$weekstring=$weekstring . '金/'; 		break;
		    		case 'Saturday':
		    			$weekkey=13;$weekstring=$weekstring . '土/'; 		break;
		    		case 'Sunday':
		    			$weekkey=17;$weekstring=$weekstring . '日/'; 		break;
				}
				$weekday = $weekday * $weekkey;
			}
			$ok['weekday'] = True;
		}else{
			$weekday=1;
			$ok['weekday'] = True;
		}
		 
		if ($_POST['sports_minded'] == "YES" ) {
			$sports_minded = 1;
			$ok['sports_minded'] = True;
		}elseif ($_POST['sports_minded'] == "NO" ) {
			$sports_minded = 0;
			$ok['sports_minded'] = True;
		} else {
			$sports_minded = -1;
			$ok['sports_minded'] = True;
		}
		
		if ($_POST['box'] == "YES" ) {
			$box = 1;
			$ok['box'] = True;
		}elseif ($_POST['box'] == "NO" ) {
			$box = 0;
			$ok['box'] = True;
		} else {
			$box = -1;
			$ok['box'] = True;
		}
		
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
		
		if (isset($_POST['money']) and strlen($_POST['money'])) {
			$money = htmlspecialchars($_POST['money'], ENT_QUOTES);
			if (!is_numeric($money) and strlen($money)){
				$valuegood = False;
				$ok['money'] = False;
			}else{
				$ok['money'] = True;
			}
		} else {
			$money = -1;
			$ok['money'] = True;
		}
		
		if (isset($_POST['url']) ) {
			$url =htmlspecialchars($_POST['url'], ENT_QUOTES);
			$ok['url'] = True;
		} else {
			$url = '';
			$ok['url'] = True;
		}
	
		if (isset($_POST['address']) and strlen($_POST['address'])) {
			$address =htmlspecialchars($_POST['address'], ENT_QUOTES);
			$ok['address'] = True;
		} else {
			$address = '';
			$ok['address'] = True;
		}
		
		if (isset($_POST['open_address']) and $address<>'' ) {
			if ($_POST['open_address']=="YES"){ $open_address = 1;}else{$open_address=0;}
		} else {
			$open_address = 0;
		}
		
		if (isset($_POST['telephone'])) {
			$telephone =htmlspecialchars($_POST['telephone'], ENT_QUOTES);
			if ((!is_numeric($telephone) or ($telephone < 0)) and strlen($telephone)){
				$valuegood = False;
				$ok['telephone'] = False;
			}else{
				$ok['telephone'] = True;
			}
		} else {
			$telephone = -1;
			$ok['telephone'] = True;
		}
		
		if (isset($_POST['comment']) ) {
			$comment =htmlspecialchars($_POST['comment'], ENT_QUOTES);
			if((!strlen($comment)) or (mb_strwidth($comment,mb_internal_encoding())>1600)){
				$valuegood = False;
				$ok['comment'] = False;
			}else{
				$ok['comment'] = True;
			}
		} else {
			$comment = '';
			$valuegood = False;
			$ok['comment'] = False;
		}
		
		if (isset($_POST['tags']) ) {
			$tags = htmlspecialchars($_POST['tags'], ENT_QUOTES);
			if(mb_strwidth($tags,mb_internal_encoding())>400){
				$valuegood = False;
				$ok['tags'] = False;
			}else{
				$ok['tags'] = True;
			}
		} else {
			$tags = '';
			$ok['tags'] = True;
		}
		
		if (isset($_POST['pass']) ) {
			$pass = htmlspecialchars($_POST['pass'], ENT_QUOTES);
			$ok['pass'] = True;
		} else {
			$pass = '';
			$ok['pass'] = True;
		}
	
	}else{
		$valuegood = False;
	}	
	
    return $valuegood;
}
////////////////////////////////////////////////////////////////////////
/////ここまで　ロード処理（各種変数代入、編集内容のエラーの有無確認）///
////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//↓状況に合わせて表示画面選択（1：編集内容にエラーのため再入力画面　2：編集確認画面　3:編集内容の入力画面　4:編集/削除パスワード不一致　5:削除確認画面）//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(valuecheck()){
	showscreen(2);		//確認画面
}elseif(isset($_POST['confirm'])){
	if($confirm<>2){
		showscreen(1);	//入力画面 エラー発生
	}else{	
		if(isset($_POST['edit'])){
			if((isset($_POST['input_pass'])) and ($_POST['input_pass']==$_POST['pass']) ){ 	//編集用パスワードの正誤チェック
				showscreen(3);  //編集
			}else{
				showscreen(4);  //エラー（パスワード不一致）
			}
		}elseif(isset($_POST['delete'])){
			if((isset($_POST['input_pass'])) and ($_POST['input_pass']==$_POST['pass'])){	//編集用パスワードの正誤チェック
				showscreen(5);  //削除確認
			}else{
				showscreen(4);  //エラー（パスワード不一致）
			}		
		}else{
			showscreen(4);
		}
	}
}else{
	showscreen(4);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//↑状況に合わせて表示画面選択（1：編集内容にエラーのため再入力画面　2：編集確認画面　3:編集内容の入力画面　4:編集/削除パスワード不一致　5:削除確認画面）//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////
///////↓表示データ処理用関数//////////////
///////////////////////////////////////////
function output($key, $value, $mode){
	$outputstring='';
	
	switch ($mode){
	case 1:
	case 0:
		$outputstring= $value;
		break;
	case 3:
		$outputstring= $_POST[$key];
	}
	echo $outputstring;
}
///////////////////////////////////////////
///////↑表示データ処理用関数//////////////
///////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////
///////↓画面表示/////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
function showscreen($mode){
	global $name, $explain, $member,$place, $frequency, $weekday, $sports_minded, $box, $intercollegiate, $money ,$url, $address, $open_address, $telephone, $comment, $tags, $pass;
	global $confirm, $ok, $weekstring;

switch ($mode) {	//（1：編集内容にエラーのため再入力画面　2：編集確認画面　3:編集内容の入力画面　4:編集/削除パスワード不一致　5:削除確認画面）
case 4:				//4:編集/削除パスワード不一致
	?>
	<font color="red">エラー：パスワードが違います</font>
	<br>
	<a href="<?php if(isset($_POST['original_url'])){echo($_POST['original_url'] . '">' . 'サークル情報ページ');}else{echo("index.php" . '">' . 'トップページ');} ?>に戻る</a>
	<?php
	break;
	
case 5:				//5:削除確認画面
	echo("<b>サークル名：" . $_POST['name'] . "</b>");
	?>
	<br><br>
	本当に削除しますか？
	<br><br>
	<a href="<?php if(isset($_POST['original_url'])){echo($_POST['original_url']);}else{echo("index.php");} ?>">キャンセルして戻る</a>
	<br><br>
	<form method="POST" action="insert.php">
		<input type="hidden" name="id" value="<?php if(isset($_POST['id'])){echo ($_POST['id']);}else{echo (0);} ?>">
		<input type="hidden" name="name" value="<?php echo ($_POST['name']) ?>">
		<input type="submit" name="delete" value="削除する">
	</form>
	<?php
	break;
	
case 1:				//1：編集内容にエラーのため再入力画面
case 3:				//3:編集内容の入力画面		
	////////////////////////////////////////////////////////////////
	//編集内容入力画面ここから（編集・エラー）//////////////////////
	////////////////////////////////////////////////////////////////
?>
<div style="text-align: left; padding-left: 20px">
<h2>-サークルデータ編集</h2>
サークルの情報を入力してください。<br>
・*のついたものは必須項目、それ以外は自由項目です。<br>
・情報が多いほど検索もされやすくユーザへのアピールにも効果的です。できるだけ多くの項目に入力をお願いします。<br>
・<b>登録情報はデータ管理や検索をよりよくするために、編集する可能性があります。<br>
その場合も趣旨の保全に努めますが、ご承知のうえ登録をお願いします。</b>（例：検索タグを書き加えるなど）
</div>
<hr>
	<form method="POST" action="edit.php">
	<table class="editform">
		<tr><td>サークル名*<br>（変更不可）</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['name']==False){echo 'サークル名は必須項目です！<br>';}?></div>
			<input type="text" name="name" readonly value= "<?php output("name", $name, $mode); ?>" maxlength="50" style="width:300px;"></td></tr>
		
		<tr><td>簡単な説明*<br /></td>
		<td><div class='formerror'><?php if($mode==1 and $ok['explain']==False){echo 'サークルの概要は必須項目です！<br>';}?></div>
			<input type="text" name="explain" value="<?php output("explain", $explain, $mode); ?>" maxlength="30" style="width:90%;">
			<br>簡潔にサークルの概要を記入ください。（検索結果一覧で表示されます）</td></tr>
			<!-- <textarea name="explain" cols=60 rows=5><?php output("explain", $explain, $mode); ?></textarea><br>検索結果一覧ページに表示される文章です。</td></tr> -->
		
		<tr><td>メンバー数<br />（半角数字）</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['member']==False){echo 'メンバー数は半角数字で入力してください！<br>';}?></div>
			<input type="text" name="member" value="<?php output("member", $member, $mode); ?>" maxlength="5" style="width:100px;">人</td></tr>
		
		<tr><td>活動場所・時間</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['place']==False){echo '文が長すぎます。活動場所・時間は800字以内の自由項目です！';}?></div>
			<textarea name="place" cols=60 rows=5><?php output("place", $place, $mode); ?></textarea><br>（例：平日は17時～19時、土日は12時～15時、場所は鴨川の河川敷です。毎日活動しています！）</td></tr>
		
		<tr><td>活動頻度</td>
		<td><select name="frequency">
	        <option value="-1" <?php if(($mode==1 and $frequency==-1) or ($mode==3 and $_POST['frequency']==-1)){echo 'selected';}?> >不定期・その他</option>
	        <option value="1" <?php if(($mode==1 and $frequency==1) or ($mode==3 and $_POST['frequency']==1)){echo 'selected';}?> >週1日以下</option>
	        <option value="2" <?php if(($mode==1 and $frequency==2) or ($mode==3 and $_POST['frequency']==2)){echo 'selected';}?> >週2日</option>
	        <option value="3" <?php if(($mode==1 and $frequency==3) or ($mode==3 and $_POST['frequency']==3)){echo 'selected';}?> >週3日</option>
	        <option value="4" <?php if(($mode==1 and $frequency==4) or ($mode==3 and $_POST['frequency']==4)){echo 'selected';}?> >週4日</option>
	        <option value="5" <?php if(($mode==1 and $frequency==5) or ($mode==3 and $_POST['frequency']==5)){echo 'selected';}?> >週5日</option>
	        <option value="6" <?php if(($mode==1 and $frequency==6) or ($mode==3 and $_POST['frequency']==6)){echo 'selected';}?> >週6日</option>
	        <option value="7" <?php if(($mode==1 and $frequency==7) or ($mode==3 and $_POST['frequency']==7)){echo 'selected';}?> >週7日</option>
	    </select></td></tr>
	    
		<tr><td>活動曜日</td>
		<td><input type="checkbox" name="weekday[]" value="Monday" <?php if(($mode==1 and $weekday % 2 == 0) or ($mode==3 and $_POST['weekday'] % 2 == 0)){echo 'checked';}?> >月
			<input type="checkbox" name="weekday[]" value="Tuesday" <?php if(($mode==1 and $weekday % 3 == 0) or ($mode==3 and $_POST['weekday'] % 3 == 0)){echo 'checked';}?> >火
			<input type="checkbox" name="weekday[]" value="Wednesday" <?php if(($mode==1 and $weekday % 5 == 0) or ($mode==3 and $_POST['weekday'] % 5 == 0)){echo 'checked';}?> >水
			<input type="checkbox" name="weekday[]" value="Thursday" <?php if(($mode==1 and $weekday % 7 == 0) or ($mode==3 and $_POST['weekday'] % 7 == 0)){echo 'checked';}?> >木
			<input type="checkbox" name="weekday[]" value="Friday" <?php if(($mode==1 and $weekday % 11 == 0) or ($mode==3 and $_POST['weekday'] % 11 == 0)){echo 'checked';}?> >金
			<input type="checkbox" name="weekday[]" value="Saturday" <?php if(($mode==1 and $weekday % 13 == 0) or ($mode==3 and $_POST['weekday'] % 13 == 0)){echo 'checked';}?> >土
			<input type="checkbox" name="weekday[]" value="Sunday" <?php if(($mode==1 and $weekday % 17 == 0) or ($mode==3 and $_POST['weekday'] % 17 == 0)){echo 'checked';}?> >日
		</td></tr>
		
		<tr><td>属性</td>
			<td><input type="radio" name="sports_minded" value="NO" <?php if(($mode==1 and $sports_minded==0) or ($mode==3 and $_POST['sports_minded']==0)){echo 'checked';}?> >サークル
			<input type="radio" name="sports_minded" value="YES" <?php if(($mode==1 and $sports_minded==1) or ($mode==3 and $_POST['sports_minded']==1)){echo 'checked';}?> >体育会
			<input type="radio" name="sports_minded" value="NOUSE" <?php if($mode==0 or ($mode==1 and $sports_minded==-1) or ($mode==3 and $_POST['sports_minded']==-1)){echo 'checked';}?> >指定しない
		</td></tr>
		
		<tr><td>ボックス(部室)の有無</td>
			<td><input type="radio" name="box" value="YES" <?php if(($mode==1 and $box==1) or ($mode==3 and $_POST['box']==1)){echo 'checked';}?> >あり
			<input type="radio" name="box" value="NO" <?php if(($mode==1 and $box==0) or ($mode==3 and $_POST['box']==0)){echo 'checked';}?> >なし
			<input type="radio" name="box" value="NOUSE" <?php if($mode==0 or ($mode==1 and $box==-1) or ($mode==3 and $_POST['box']==-1)){echo 'checked';}?> >指定しない
		</td></tr>
		
		<tr><td>構成員</td>
			<td><input type="radio" name="intercollegiate" value="NO" <?php if(($mode==1 and $intercollegiate==0) or ($mode==3 and $_POST['intercollegiate']==0)){echo 'checked';}?> >オンリーサークル
			<input type="radio" name="intercollegiate" value="YES" <?php if(($mode==1 and $intercollegiate==1) or ($mode==3 and $_POST['intercollegiate']==1)){echo 'checked';}?> >外部生あり（インカレ）
			<input type="radio" name="intercollegiate" value="NOUSE" <?php if($mode==0 or ($mode==1 and $intercollegiate==-1) or ($mode==3 and $_POST['intercollegiate']==-1)){echo 'checked';}?> >指定しない
		</td></tr>
	  		
		<tr><td>会費</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['money']==False){echo '会費は半角数字で入力してください！<br>';}?></div>
			年<input type="text" name="money" value="<?php output("money", $money, $mode); ?>" maxlength="8" style="width:100px;">円</td></tr>
		
		<tr><td>URL</td>
		<td><input type="text" name="url" value="<?php output("url", $url, $mode); ?>" maxlength="100" style="width:300px;"><br>例：http://www.kyoto-○○circle.com</td></tr>
		
		<tr><td>メールアドレス<br />（任意）</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['address']==False){echo 'アドレスが一致しません。確認してください。';}?></div>
			<input type="text" name="address" value="<?php output("address", $address, $mode); ?>" maxlength="50" style="width:300px;"><br>
			<input type="checkbox" name="open_address" value="YES" <?php if(($mode==1 and $open_address==1) or ($mode==3 and $_POST['open_address']==1)){echo 'checked';}?>>サークル情報ページに連絡先として公開を希望<br>
			サービスの更新時や追加データが必要となった時の連絡先です。なるべくご記入お願いします。</td></tr>
			
		<tr><td>電話番号<br>（半角数字のみでハイフンなし）</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['telephone']==False){echo '電話番号は半角数字のみで入力してください！<br>';}?></div>
			<input type="text" name="telephone" value="<?php output("telephone", $telephone, $mode); ?>" maxlength="15"><br>
			サークル情報ページに連絡先として公開を希望される場合記入してください。</td></tr>
		
		<tr><td>詳細な説明や紹介文や補足など*</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['comment']==False){echo '未記入または文が長すぎます。詳細な説明は800字以内の必須項目です！';}?></div>
			<textarea name="comment" cols=60 rows=8><?php output("comment", $comment, $mode); ?></textarea><br>サークル詳細情報画面に表示される説明です。<br>普段の活動内容やアピールポイント、新入生へのメッセージなどご自由にお書きください。</td></tr>
		
		<tr><td>検索用タグ</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['tags']==False){echo '文が長すぎます。検索タグは200字以内の自由項目です！';}?></div>
			<textarea name="tags" cols=60 rows=3><?php output("tags", $tags, $mode); ?></textarea><br>（例：サークル#公認#ボランティア系#音楽系#ボックス有#合宿有）<br>ワード検索用のタグです。サークルの特徴を#で区切って書いてください。</td></tr>
		
		<tr><td>編集・削除用パス</td>
		<td><div class='formerror'><?php if($mode==1 and $ok['pass']==False){echo 'パスコード設定エラー';}?></div>
			<input type="text" name="pass" value="<?php output("pass", $pass, $mode); ?>" maxlength="10"><br>サークル情報を後から自分で編集、削除するためのパスワードです。設定は任意です。<br />管理人にメールするよりも早く簡単に登録情報の修正が可能になります。</td></tr>
		
	</table><br>
		<input type="hidden" name="confirm" value= 1 > 
		<input type="hidden" name="id" value= <?php if(isset($_POST['id'])){echo ($_POST['id']);}else{echo (0);} ?>> 
		<div style="text-align:center;"><input type="submit" value="編集内容の確認へ"></div>
	</form>

<?php	
	////////////////////////////////////////////////////////////////
	//編集内容入力画面ここまで（編集・エラー）//////////////////////
	////////////////////////////////////////////////////////////////
	break;
	
case 2:				//2：編集確認画面
	//////////////////////////////////////////////////////////////////////
	///////確認画面表示ここから///////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
?>
<div style="text-align: left; padding-left: 20px">
<h2>-サークルデータ入力確認</h2>
<span class="warning">※まだ登録は完了していません！</span><br>入力内容を確認の上、「登録」をクリックしてください。
</div>
<hr>

	<table class="editform">
	<tr><td class="col1">サークル名</td>
	<td><?php echo $name ?></td></tr>
	
	<tr><td>簡単な説明</td>
	<td><?php echo nl2br($explain,false) ?></td></tr>
	
	<tr><td>メンバー数</td>
	<td><?php if($member>=0){echo $member . '人';} ?></td></tr>
		
	<tr><td>活動場所・時間</td>
	<td><?php echo nl2br($place,false); ?></td></tr>	
	
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
	<td><?php echo $weekstring;	?></td></tr>
	
	<tr><td>属性</td>
	<td><?php if($sports_minded==0){echo 'サークル';}elseif($sports_minded==1){echo '体育会';}else{echo '指定しない';} ?></td></tr>
  		
	<tr><td>ボックス（部室）の有無</td>
	<td><?php if($box==0){echo 'なし';}elseif($box==1){echo 'あり';}else{echo '指定しない';} ?></td></tr>
  		
	<tr><td>構成員</td>
	<td><?php if($intercollegiate==0){echo 'オンリーサークル';}elseif($intercollegiate==1){echo '外部生あり（インカレ）';}else{echo '指定しない';} ?></td></tr>
  		
	<tr><td>会費</td>
	<td><?php if($money>=0 and strlen($money)){echo '年' . "$money" . '円';} ?></td></tr>
		
	<tr><td>URL</td>
	<td><?php echo $url; ?></td></tr>
	
	<tr><td>メールアドレス</td>
	<td><?php echo $address; ?><br>
	検索ページに連絡先として公開を希望<?php if($open_address==1){echo 'する';}else{echo 'しない';} ?></td></tr>
	
	<tr><td>電話番号（公開用）</td>
	<td><?php if($telephone>0){echo $telephone;} ?></td></tr>
	
	<tr><td>詳細な説明や紹介文など</td>
	<td><?php echo nl2br($comment,false); ?></td></tr>
		
	<tr><td>検索用タグ</td>
	<td><?php echo nl2br($tags,false); ?></td></tr>	
	
	<tr><td>編集・削除用パスワード</td>
	<td><?php echo nl2br($pass,false); ?></td></tr>	
</table><br>
<?php
	////////////////////////////////////////////////////////////////////////////
	///////確認画面表示ここまで/////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
?>
	<!--POST送信用フォーム （修正or本登録）-->
	<table style="margin-left:auto; margin-right:auto;">
	<tr>
	<td>
		<form method="POST" action="edit.php">
			<input type="hidden" name="confirm" value=2 >
			<input type="hidden" name="id" value= <?php if(isset($_POST['id'])){echo ($_POST['id']);}else{echo (0);} ?>> 
			<input type="hidden" name="name" value= <?php echo ($name) ?> >
			<input type="hidden" name="explain" value= "<?php echo ($explain) ?>" >
			<input type="hidden" name="member" value= <?php echo ($member) ?> >
			<input type="hidden" name="place" value= "<?php echo ($place) ?> ">
			<input type="hidden" name="frequency" value= <?php echo ($frequency) ?> >
			<input type="hidden" name="weekday" value= <?php echo ($weekday) ?> >
			<input type="hidden" name="sports_minded" value= <?php echo ($sports_minded) ?> >
			<input type="hidden" name="box" value= <?php echo ($box) ?> >
			<input type="hidden" name="intercollegiate" value= <?php echo ($intercollegiate) ?> >
			<input type="hidden" name="money" value= <?php echo ($money) ?> >
			<input type="hidden" name="url" value= <?php echo ($url) ?> >
			<input type="hidden" name="address" value= <?php echo ($address) ?> >
			<input type="hidden" name="telephone" value= <?php echo ($telephone) ?> >
			<input type="hidden" name="open_address" value= <?php echo ($open_address) ?> >
			<input type="hidden" name="comment" value= "<?php echo ($comment) ?>" >
			<input type="hidden" name="tags" value= "<?php echo ($tags) ?> ">
			<input type="hidden" name="pass" value= "<?php echo ($pass) ?> ">
			<input type="hidden" name="input_pass" value= "<?php echo ($pass) ?> ">
			<input type="submit" name="edit" value="やり直し">
		</form>
	</td>
	<td width="150px"></td>
	<td>
		<form method="POST" action="insert.php">
			<input type="hidden" name="id" value= <?php if(isset($_POST['id'])){echo ($_POST['id']);}else{echo (0);} ?>> 
			<input type="hidden" name="name" value= <?php echo ($name) ?> >
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
			<input type="hidden" name="comment" value= "<?php echo (nl2br($comment,false)) ?>" >
			<input type="hidden" name="tags" value= "<?php echo (nl2br($tags,false)) ?>" >
			<input type="hidden" name="pass" value= "<?php echo (nl2br($pass,false)) ?>" >
			<input type="submit" value="登録">
		</form>
	</td>
	</tr>
	</table>
<?php
	}	//switch($mode)とじる
}	//function showscreen($mode)とじる
//////////////////////////////////////////////////////////////////////////////////
///////↑画面表示/////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
?>

</div>	<!-- mainここまで -->
<div id="footer">
<hr>
連絡先：<font color="blue">kyodai.circlesearch●gmail.com</font>　（●を@に変えてください）<br>
サービスに関する意見など、ご気軽にメールください。
</div>
</body>
</html>