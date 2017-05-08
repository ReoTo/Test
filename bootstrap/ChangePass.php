<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>パスワード変更</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/roguin.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class = "container">
      <div class="wrapper">
        <form action="" method="post" name="Login_Form" class="form-signin">       

<?php
  
  # 変更ボタンが押されたら
  if (isset($_POST['Submit'])) {
    # 新しいパスワード
    $NewPass = $_POST['NewPassword'];
    # 新しいパスワード（確認）
    $ReNewPass = $_POST['ReNewPassword'];
    # 入力された新しいパスワードの文字数カウント
    $len = mb_strlen($NewPass, "UTF-8");
    # 文字数が6文字以上10文字以内のとき
    if ((6 <= $len) && ($len <= 10)) {    
      # 文字数カウント（正し、半角は１カウント、全角は2カウント）
      $wdt = mb_strwidth($NewPass, "UTF-8");
      # 全角が含まれていない
      if($len == $wdt) {
        # 新しいパスワードと新しいパスワード（確認）が一致したとき
        if (strstr($NewPass, $ReNewPass)) {
          # データベース接続
          try {
            # データベース設定
            $dsn = 'mysql:host=localhost;dbname=pbl1;charset=utf8';
            $user = 'root';
            $password = '';
            # データベース接続
            $pdo = new PDO($dsn, $user, $password);

            # ユーザーID取得
            #$userid = $_POST['id'];
#Test用
$userid = '0K01011';
            # そのユーザーのパスワードを取得 
            $stmt = $pdo -> prepare("SELECT password FROM User WHERE userid = ?");
            $stmt -> bindValue(1, $userid);
            $stmt -> execute();
            if ($rows = $stmt -> fetch()) {
              $pass = $rows["password"];
            }

            # パスワードが一致したなら
            if (strstr($_POST['Password'], $pass)) {

              # パスワード変更
              $stmt = $pdo -> prepare("UPDATE User SET password = ? WHERE userid = ?");
              $stmt-> bindValue(1, $NewPass);
              $stmt-> bindValue(2, $userid);
              $stmt-> execute();

            #パスワードが違うとき
            } else {
              $error = "パスワードが違います";
            }
          # データベース接続失敗  
          } catch (PDOException $e) {
            exit('データベース接続失敗。'.$e->getMessage());
          }
        # 新しいパスワードと新しいパスワード（確認）が一致しなかったとき        
        } else {
          $error = "新しいパスワードと新しいパスワード（確認）が一致しません";
        }
      # 全角が含まれている時
      } else {
        $error = "半角で入力して下さい";
      }
    # 文字数6文字未満、１1字以上の時
    } else {
      $error = "6文字以上10字以内で入力してください";
    }
  }
?>
          <h3 class="form-signin-heading">
<?php
    # エラーがある時だけ
    if ( isset($error)) {
      # エラー表示
      echo $error;
    }
?>
          </h3>
          <hr class="colorgraph"><br>

          <!-- 今のパスワード入力 -->
          <input type="password" class="form-control" name="Password" placeholder="現在のパスワードを入力" required="" autofocus="" style="width: 100%" /> 
          <!-- 新しいパスワード入力 -->        
          <input type="password" class="form-control" name="NewPassword" placeholder="新しいパスワードを入力（半角英数記号で6字以上10字以内）" required="" style="width: 100%"/> 
          <!-- 新しいパスワード入力（確認） -->
          <input type="password" class="form-control" name="ReNewPassword" placeholder="確認のため再度パスワードを入力" required="" style="width: 100%"/> 
          <center>
            <div class="btn-toolbar">
              <div class="btn-group" style="width: 49%">
                <!-- 戻る -->
                <button class="btn btn-lg btn-primary btn-block" value="Back"　type="button" onclick="history.back()">戻る</button>
              </div>        
              <div class="btn-group" style="width: 49%">
                <!-- 変更ボタン -->
                <button class="btn btn-lg btn-primary btn-block" name="Submit" value="Change" type="Submit">変更</button>        
              </div>
            </div>
          </center>
        </form>     
      </div>
    </div>
  </body>
</html>