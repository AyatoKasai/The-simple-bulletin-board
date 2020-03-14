<!DOCTYPE html>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
    <head>
	<title>簡易掲示板</title>
	  <link rel="stylesheet" href="mission_5.css" type="text/css">	
    </head>
    <body>
<?php



// データベースへの接続。
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password =  'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//データベース内にテーブルを作成する。テーブル作成の際にはcreateコマンドを使う。
	$sql = "CREATE TABLE IF NOT EXISTS mission5tb"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass char(32),"
	. "date char(32)"
	.");";
	$stmt = $pdo->query($sql);

$db_host = 'localhost';
$db_name = 'tb210911db';
$db_user = 'tb-210911';
$db_pass = 'X9ZB8mNzTn';
 
// データベースへ接続する
$link = mysqli_connect( $db_host, $db_user, $db_pass, $db_name );
if ( $link !== false ) {
   $msg     = '';
    $err_msg = '';
//新規投稿機能
	if ( isset( $_POST['send'] ) === true ) {
		if ($_POST['editNO'] == '' ){
        $name     = $_POST['name']   ;
        $comment = $_POST['comment'];
	$pass = $_POST['pass'];
	$date = date("Y年m月d日 H:i:s");
			if ( $name !== '' && $comment !== '' && $pass !=='') {
		$query = " INSERT INTO mission5tb ( "
		. "   name,    "
                . "   comment, "
		. "   pass,    "
		. "   date    "
                . " ) VALUES ( "
                . "'" . mysqli_real_escape_string( $link, $name ) ."', "
                . "'" . mysqli_real_escape_string( $link, $comment ) ."', "
                . "'" . mysqli_real_escape_string( $link, $pass ) . "',"
                . "'" . mysqli_real_escape_string( $link, $date ) . "'"
                 ." ) ";
		$res   = mysqli_query( $link, $query );
            
			if ( $res !== false ) {
			$msg = '書き込みに成功しました';
			}else{
			$err_msg = '書き込みに失敗しました';
}
			}else{
			$err_msg = '名前とコメントとパスワードを記入してください';
	        	}
		}else{
			if ( $_POST['pass'] !=='') {

//編集機能実行プログラム 
// UPDATE文を変数に格納
		$sql = "UPDATE mission5tb SET name = :name , comment = :comment, date = :date  WHERE id = :id AND pass = :pass";
// 更新する値と該当のIDは空のまま、SQL実行の準備をする
		$stmt = $pdo->prepare($sql);
// 更新する値と該当のIDを配列に格納する
		$params = array(':name'=>$_POST["name"],':comment'=>$_POST["comment"],':date'=>date("Y年m月d日 H:i:s"),':id'=>$_POST["editNO"],':pass'=>$_POST['pass'] );
// 連想配列の値をそれぞれのプレースホルダにセットし、executeでSQLを実行
	// コードが見やすいように改行
  $stmt->execute($params);
// 更新完了のメッセージ
		echo '更新完了しました';
		}
			}
	}else{
//削除機能
	//削除ボタンを押すと反応するプログラム
		if ( isset( $_POST['delete'] ) === true  && $_POST['pass'] !==''  ) {
		$sql = "DELETE FROM mission5tb WHERE id = :id AND pass = :pass";
		$stmt = $pdo->prepare($sql);

// 削除するレコードのIDを配列に格納する
		$params = array(':id'=>$_POST['dnum'],':pass'=>$_POST['pass']);
 
// 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
		$stmt->execute($params);
 
// 削除完了のメッセージ
		echo '削除完了しました';
		}

	}
//書き込み機能
	$query  = "SELECT id, name, comment, pass,date FROM mission5tb order by id ASC";
    	$res    = mysqli_query( $link,$query );
    	$data = array();
    	while( $row = mysqli_fetch_assoc( $res ) ) {
        array_push( $data, $row);
    	}
    	asort( $data );
			} else {
    			echo "データベースの接続に失敗しました";
			}
//編集機能
	//編集ボタンを押すと反応するプログラム
	if ( isset( $_POST['edit'] ) === true  && $_POST['pass'] !=='' ) {
//編集したい番号を入れて編集ボタンを押すと、名前、コメント、編集番号が各フォームに表示されるプログラム

//投稿番号と編集対象番号が一致したらその投稿の「名前」と「コメント」を取得
	$sql = 'SELECT * FROM mission5tb WHERE id = :id AND pass = :pass';
// 更新する値と該当のIDは空のまま、SQL実行の準備をする
	$stmt = $pdo->prepare($sql);
// 更新する値と該当のIDを配列に格納する
	$params = array(':id'=>$_POST['editcode'],':pass'=>$_POST['pass']);
// 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
	$stmt->execute($params);
	$results = $stmt->fetchAll();
		foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
//投稿のそれぞれの値を取得し変数に代入
		 $editnumber = $row['id'];
		 $editname =  $row['name'];
		 $editcomment =  $row['comment'];
//既存の投稿フォームに、上記で取得した「名前」と「コメント」の内容が既に入っている状態で表示させる
//formのvalue属性で対応
		}
	}

// データベースへの接続を閉じる
mysqli_close( $link );
?>
	<h1>簡易掲示板</h1>
        <form method="post" action="">
	<h2>投稿</h2>
	<p>
		
			<td>
			 <input type="text" name="name"  placeholder = "名前を入力" value="<?php if(isset($editname)) {echo $editname;} ?>" />
			</td>
	</p>
	 <td colspan="2" align="center"> 
			<td>
			<input type="text" name="comment"  placeholder = "コメントを入力" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>">
				  <input type="hidden" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>"/>
				<p><input type="text" name="pass" placeholder="パスワードを入力" value="<?php if(isset($editpass)) {echo $editpass;} ?>"> 
			</td>
           <input type="submit" name="send" value="送信" />
	</p>
	<td colspan="2" align="center"> 
        </form>
	<form method="post" action="">
	<h3>削除</h3>
			<input type="text" name="dnum" placeholder="削除したい番号を入力"/>
			<p><input type="text" name="pass" placeholder="パスワードを入力" value="<?php if(isset($editpass)) {echo $editpass;} ?>"> 
	<input type="submit" name="delete" value="削除"/></p>
	</form>
	<form method="post" action="">
	<h4>編集</h4>
			 <input type="text" name="editcode" placeholder="編集したい番号を入力"/>
			<p> <input type="text" name="pass" placeholder="パスワードを入力" value="<?php if(isset($editpass)) {echo $editpass;} ?>"> 
	<input type="submit"name="edit" value="編集"/></p>
	</form>
	<h5>投稿内容</h5>
        <!-- ここに、書き込まれたデータを表示する -->
    </body>
</html>
<?php
//表示機能
    if ( $msg     !== '' ) echo '<p>' . $msg . '</p>';
    if ( $err_msg !== '' ) echo '<p style="color:#f00;">' . $err_msg . '</p>';
    foreach( $data as $key => $val ){
       echo $val['id'] . ' ' . $val['name'] . ' ' . $val['comment']. ' ' .$val['date']. ' ' .  '<br>';
    }
?>