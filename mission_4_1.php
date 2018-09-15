<?php
//データベース内にテーブル作成(id,name,comment,time,pass)AUTO_INCREMENT PRIMARY KEY
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';

$pdo = new PDO($dsn,$user,$password);

$sql = "CREATE TABLE mission_4"
."("
."id INT NOT NULL,"
."name char(32),"
."comment TEXT,"
."time TEXT,"
."pass TEXT"
.");";
$stmt = $pdo->query($sql);
?>

<?php
$name = $_POST['name'];
$coments = $_POST['comment'];
$time = date("Y/m/d H:i:s");
$pass = $_POST['password'];


//$pdo->query('ALTER TABLE mission_4 AUTO_INCREMENT = 1');

//編集番号、内容保持
if(!empty($_POST['editID'])){
	$sql = 'SELECT*FROM mission_4';
	$results = $pdo->query($sql);
	foreach($results as $row){
		if($row['id'] == $_POST['editID']){
			$Epass = $row['pass'];
		}
	}	
	if($_POST['editpassword'] == $Epass){
		$hiddenID = $_POST['editID'];
		$sql = 'SELECT*FROM mission_4 ORDER BY id';
		$results = $pdo -> query($sql);
		foreach($results as $row){
			if($row['id'] == $hiddenID){
				$hiddenName = $row['name'];
				$hiddenCom = $row['comment'];
				$hiddenpass = $row['pass'];
			}
		}
	} else {
		echo "パスワードが違います";
	}
}


//データを編集or入力
if(!empty($_POST['eF'])){
	//編集モード
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$time = date("Y/m/d H:i:s");
	$pass = $_POST['password'];

	$editNM = $name;
	$editCM = $comment;
	$editTM = $time;
	$editPW = $pass;
	$editID = $_POST['eF'];
	$sql = "update mission_4 set name='$editNM',comment='$editCM',time='$editTM',pass='$editPW' where id =$editID";
	$result = $pdo->query($sql);
} elseif(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password'])){
	//入力モード
	$sql = $pdo->prepare("INSERT INTO mission_4(id,name,comment,time,pass) VALUES(:id,:name,:comment,:time,:pass)");
	
	$results=$pdo->query('SELECT*FROM mission_4 ORDER BY id');
	foreach($results as $row){
		$id = $row['id'];
	}

	$id++;
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$time = date("Y/m/d H:i:s");
	$pass = $_POST['password'];

	$sql->bindParam(':id',$id,PDO::PARAM_STR);
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':time',$time,PDO::PARAM_STR);
	$sql->bindParam(':pass',$pass,PDO::PARAM_STR);

	$sql->execute();
}

//削除
if(!empty($_POST['deleteID'])){
	$sql = 'SELECT*FROM mission_4';
	$results = $pdo->query($sql);
	foreach($results as $row){
		if($row['id'] == $_POST['deleteID']){
			$dpass = $row['pass'];
		}
	}
	if($_POST['delpassword'] == $dpass){
		$dle = $_POST['deleteID'];
		$sql ="delete from mission_4 where id=$dle";
		$result = $pdo->query($sql);

	} else{
		echo "パスワードが違います";
	}
}


//下にフォーム
?>


<!DOCTYPE html>
<head>
<meta charset = "UTF-8">
<title>mission_4</title>
</head>
<html>
<form action="mission_4ex.php" method="post">
	<input type='text' name='name' placeholder='名前' value="<?php echo $hiddenName ?>"><br>
	<input type='text' name='comment' placeholder='コメント' value="<?php echo $hiddenCom ?>"><br>
	<input type='text' name='password' placeholder='パスワード' value="<?php echo $hiddenpass ?>">
	<input type='hidden' name='eF' value="<?php echo $hiddenID ?>">
	<input type='submit' value='送信'><br>
</form>

<form action="mission_4ex.php" method="post">
	<br><input type='text' name='deleteID' placeholder='削除対象番号' value=''><br>
	<input type='text' name='delpassword' placeholder='パスワード' value=''>
	<input type='submit' value='削除'><br>
</form>

<form action="mission_4ex.php" method="post">
	<br><input type='text' name='editID' placeholder='編集対象番号' value=''><br>
	<input type='text' name='editpassword' placeholder='パスワード' value=''>
	<input type='submit' value='編集'><br>
</form>

<?php unset($hiddenName); unset($hiddenCom); unset($hiddenID); unset($hiddenpass); ?>


<?php
//表示
$sql = 'SELECT*FROM mission_4 ORDER BY id';
$results = $pdo->query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['time'].'<br>';
}

?>

</html>