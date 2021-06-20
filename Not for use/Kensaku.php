<?php
//funcs.phpを読み込む
require_once('funcs.php');

//1.  DB接続します
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_bm;charset=utf8;host=localhost','root','root');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//２．SQL文を用意(データ取得：SELECT)
$stmt = $pdo->prepare("SELECT * FROM gs_bm_table");

//3. 実行
$status = $stmt->execute();

//4．データ表示
$view="";
$resultArr = [];
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  //PHP処理
  //”配列$result”に全てのデータを代入できます。
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= "<option>";
    $view .= h($result['name']);
    $view .= "</option>";
    array_push($resultArr, $result);
  }
    $json .= json_encode($resultArr);
    var_dump($json);
  }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ブックマーク</title>
  <link rel="stylesheet" href="css/range.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>

<body id="main">
<!-- Head[Start] -->

<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
<!--ボタン-->

  <select id="book_list">
       <?= $view ?>
  </select>

  <button id="book_list_btn">見る</button>
</header>
<!-- Head[End] -->

<form method="post" action="insert.php">
        <dl id="input_form">
                    <div class="input_form_each">
                        <dt class="input_form_left">
                            <p class="input_form_left_title">書籍名</p>
                        </dt>
                        <dd>
                        <input type="text" id="book_title" name="title">
                        </dd>
                    </div>
​
                    <div class="input_form_each">
                        <dt class="input_form_left">
                            <p class="input_form_left_title">書籍URL</p>
                        </dt>
                        <dd>
                            <textarea id="book_url" cols="100" rows="7" name="book_url"></textarea>
                        </dd>
                    </div>
​
                    <div class="input_form_each">
                        <dt class="input_form_left">
                            <p class="input_form_left_title">コメント</p>
                        </dt>
                        <dd>
                            <textarea id="book_comment" cols="100" rows="7" name="book_comment"></textarea>
                        </dd>
                    </div>
        </dl>
    </form> 

</body>
</html>

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- JQuery -->

<script>
const data = JSON.parse('<?=$json?>');
console.log(data);
​
$("#book_list_btn").on("click",function(){    
        
    const book_title_miru = $("#book_list").val();
    console.log(book_title_miru);
    $("#book_title").val(book_title_miru); 
    $("#book_url").val("");
    $("#book_comment").val("");
​
    var book_title_Ref = data.find(element => element.name === book_title_miru);
    console.log(book_title_Ref);
​
    let link = book_title_Ref.url;
    $("#book_url").val(link);
​
    let comment = book_title_Ref.naiyou;
    $("#book_comment").val(comment);
​
})



</script>
