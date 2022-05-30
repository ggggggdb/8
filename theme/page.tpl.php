<style>
body {background-color: white; color: black; font-family: "Bitstream Vera Sans", Tahoma, Verdana, Arial, sans-serif; font-size: 100%;}
h1 {text-align:center;}
h2, .hr {border-top: 1px dotted gray;}
table {border: 1px dotted gray;}
th {background-color: #ccc; font-size: 100%;}
td {height: 2em; padding: 1px 2px 1px 2px; font-size: 100%;}
input, select {font-size: 100%;}
.a td {background-color: #e0e0e0;}
.a, .b {height: 1.2em;}
.a td form, .b td form {margin:0;}
a, a:visited {color: #339; text-decoration: underline; font-weight: 700;}
form {margin-top: 15px;}
.form{max-width: 960px;
    text-align: center;
    margin: 0 auto;
}
input {font-size: 100%;margin-top: 1px;margin-bottom: 1px;}
ul {margin-bottom: 1em;}
li {margin-bottom: 0.3em;}
</style>

<?php
if (!empty($c['front']['messages'])) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($c['front']['messages'] as $message) {
    print($message);
  }
  print('</div>');
}
?>
  <div class="form">
  <form action="" method="POST">
    <label> ФИО </label> <br>
    <input name="name" <?php if ($c['front']['errors']['name']) {print 'class="error"';} ?> value="<?php print $c['front']['values']['name']; ?>" /> <br>
    <label> Почта </label> <br>
    <input name="email" type="email" <?php if ($c['front']['errors']['email']) {print 'class="error"';} ?> value="<?php print $c['front']['values']['email']; ?>"/> <br>
    <label> Год рождения </label> <br>
    <select name="year" <?php if ($c['front']['errors']['year']) {print 'class="error"';} ?>>
      <option value="Выбрать">Выбрать</option>
    <?php
        for($i=1800;$i<=2022;$i++){
          if($c['front']['values']['year']==$i){
            printf("<option value=%d selected>%d год</option>",$i,$i);
          }
          else{
            printf("<option value=%d>%d год</option>",$i,$i);
          }
        }
    ?>
    </select> <br>
    <label> Ваш пол </label> <br>
    <div <?php if ($c['front']['errors']['sex']) {print 'class="error"';} ?>>
      <input name="sex" type="radio" value="M" <?php if($c['front']['values']['sex']=="M") {print 'checked';} ?>/> Мужчина
      <input name="sex" type="radio" value="W" <?php if($c['front']['values']['sex']=="W") {print 'checked';} ?>/> Женщина
    </div>
    <label> Сколько у вас конечностей </label> <br>
    <div <?php if ($c['front']['errors']['limb']) {print 'class="error"';} ?>>
      <input name="limb" type="radio" value="1" <?php if($c['front']['values']['limb']=="1") {print 'checked';} ?>/> 1 
      <input name="limb" type="radio" value="2" <?php if($c['front']['values']['limb']=="2") {print 'checked';} ?>/> 2 
      <input name="limb" type="radio" value="3" <?php if($c['front']['values']['limb']=="3") {print 'checked';} ?>/> 3 
      <input name="limb" type="radio" value="4" <?php if($c['front']['values']['limb']=="4") {print 'checked';} ?>/> 4 
    </div>
    <label> Выберите суперспособности </label> <br>
    <select name="power[]" size="3" multiple <?php if ($c['front']['errors']['powers']) {print 'class="error"';} ?>>
      <option value="прохождение сквозь стены" <?php if($c['front']['values']['ghost']==1){print 'selected';} ?>>Прохождение сквозь стены</option>
      <option value="бессмертие" <?php if($c['front']['values']['immortal']==1){print 'selected';} ?>>Бессмертие</option>
      <option value="левитация" <?php if($c['front']['values']['levitation']==1){print 'selected';} ?>>Левитация</option>
    </select> <br>
    <label> Краткая биография </label> <br>
    <textarea name="bio" rows="10" cols="15"><?php print $c['front']['values']['bio']; ?></textarea> <br>
    <?php 
    $cl_e='';
    $ch='';
    if($c['front']['values']['check']=='on' and !empty($_SESSION['login'])){
      $ch='checked';
    }
    if ($c['front']['errors']['check']) {
      $cl_e='class="error"';
    }
    if(empty($_SESSION['login'])){
    print('
    <div  '.$cl_e.' >
    <input name="check" type="checkbox" '.$ch.'> Вы согласны с пользовательским соглашением <br>
    </div>');}
    ?>
    <input type="submit" value="Отправить"/>
  </form>
  <?php
  if(empty($_SESSION['login'])){
   echo'
   <div class="login">
    <p>Если у вас есть аккаунт, вы можете <a href="login">войти</a></p>
   </div>';
  }
  else{
    echo '
    <div class="logout">
      <form action="" method="post">
        <input name="logout" type="submit" value="Выйти">
      </form>
    </div>';
  } ?>
  </div>
</body>