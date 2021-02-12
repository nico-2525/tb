<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title><mission_5-1></title>
    </head>
     
    <body>
        <?php
        //"Notice"の非表示
        error_reporting(E_ALL & ~E_NOTICE);
        //DB接続設定
	    $dsn = 'データ名';
	    $user = 'ユーザー名';
	    $password = 'パスワード';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //DB内にテーブルを作る
        $sql = "CREATE TABLE IF NOT EXISTS huloom"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
        . "date DATETIME,"
        . "password TEXT"
	    .");";
        $stmt = $pdo->query($sql);
    
       /* //DBのテーブル一覧を表示
        $sql ='SHOW TABLES huloom';
	    $result = $pdo -> query($sql);
	    foreach ($result as $row){
		    echo $row[0];
		    echo '<br>';
	    }
	    echo "<hr>";

        //作成したテーブルの構成詳細を確認
        $sql = "SHOW CREATE TABLE huloom";
        $result = $pdo -> query($sql);
        foreach($result as $row){
            echo $row[0];
        }
        echo "<hr>";*/
        ?>

        <?php
        //パスワード取得するために番号定義(削除か編集かは入力番号でチェック)
        if(!empty($_POST["delete"])){
            $delete = $_POST["delete"];
            $id = $delete;
        }elseif(!empty($_POST["edit"])){
            $edit = $_POST["edit"];
            $pass3 = $_POST["pass3"];
            $id = $edit;
        }

        $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql='SELECT * FROM huloom WHERE id=:id';
        $stmt=$pdo->prepare($sql);                   // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);// ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();// ←SQLを実行する。
        $results=$stmt->fetchAll();
        foreach($results as $row){
            $passcheck=$row['password'];
        }

        //編集要素を取り出す
        if(!empty($edit)&&$passcheck==$pass3){//編集のときは、上記で編集対象のパスワードを取得している
            $id=$edit;
            $sql = 'SELECT * FROM huloom WHERE id=:id';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id',$id,PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //以下3つはフォームで表示させるための変数
                $editnumber=$row['id'];
                $editname=$row['name'];
                $editcomment=$row['comment'];
            }
        }
        ?>

        <form action="" method="POST" ><!--送信用フォーム valueであらかじめいれることばを定義。passwordにすると文字が見えなくなる-->
        【投稿フォーム】<br>
        <input type="text" name="name" placeholder="名前" value=<?php if(!empty($edit)&&$passcheck==$pass3){echo $editname;}?>><br>
        <input type="text" name="comment" placeholder="コメント" value=<?php if(!empty($edit)&&$passcheck==$pass3){echo $editcomment;}?>><br>
        <input type="hidden" name="edit2" placeholder="編集する投稿番号" value=<?php echo $editnumber;?>><br>
        <input type="text" name="pass1" placeholder="パスワード" value=<?php echo $passcheck;?>>
        <input type="submit" name="submit" value="送信">
        </form>

        <form action=""method="POST" ><!--削除フォーム-->
        【削除フォーム】<br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        </form>

        <form action=""method="POST" ><!--編集フォーム-->
        【編集フォーム】<br>
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
        </form>

        <?php
        //投稿
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $pass1=$_POST["pass1"];
        $edit2=$_POST["edit2"];
        
        /*//削除
        $delete=$_POST["delete"];*/
        $pass2=$_POST["pass2"];
        /*//編集
        $edit=$_POST["edit"];
        $pass3=$_POST["pass3"];*/
        //投稿機能
        if(!empty($name)&&!empty($comment)&&empty($edit2)&&!empty($pass1)){
            //必要なデータ
            $date = date("Y-m-d h:i:s");
            //パスワード設定
            $pass=$pass1;
            //DBへ送信
	        $sql=$pdo->prepare("INSERT INTO huloom (name, comment,date,password) VALUES (:name, :comment, :date, :password)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
	        $sql -> execute();
        }

        //削除機能
        if(!empty($delete)&&$pass2==$passcheck){
            $id = $delete;
	        $sql = 'delete from huloom where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
	        $stmt->execute();
        }

        //編集機能
        if(/*!empty($edit)&&*/!empty($edit2)&&$pass3==$passcheck){
            $id = $_POST["edit2"]; //変更する投稿番号
            /*$name = $_POST["name"];
            $comment = $_POST["comment"]; */

            $sql = "UPDATE huloom SET name=:name,comment=:comment WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        ?>

        

        <?php
        //表示機能
        $sql = 'SELECT * FROM huloom';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row["id"].",";
            echo $row["name"].",";
            echo $row["comment"]."<br>";
            echo $row["date"];
            echo $row["password"].'<br>';
            echo "<hr>";
        }

        ?>

        <?php
        //表示機能
        if(!empty($name)&&!empty($comment)&&empty($pass1)){
            echo "パスワードが未入力です。<br>";
        }elseif(!empty($delete)&&empty($pass2)){
                echo "パスワードが未入力です。<br>";
        }elseif(!empty($edit)&&empty($pass3)){
            echo "パスワードが未入力です。<br>";
        }
        ?>
        
       
    </body>
</html>