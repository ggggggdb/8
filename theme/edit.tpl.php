<style>
  .form1{
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
  }
  .error {
    border: 2px solid red;
  }
  .button {
  font: bold 11px Arial;
  text-decoration: none;
  background-color: #EEEEEE;
  color: #333333;
  padding: 2px 6px 2px 6px;
  border-top: 1px solid #CCCCCC;
  border-right: 1px solid #333333;
  border-bottom: 1px solid #333333;
  border-left: 1px solid #CCCCCC;
}
</style>
<body>
<?php
if (!empty($c['edit']['messages'])) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($c['edit']['messages'] as $message) {
    print($message);
  }
  print('</div>');
}
?>
  <div class="form1">
  <form action="" method="POST">
    <label> ФИО </label> <br>
    <input name="name" <?php if ($c['edit']['errors']['name']) {print 'class="error"';} ?> value="<?php print $c['edit']['values']['name']; ?>" /> <br>
    <label> Почта </label> <br>
    <input name="email" type="email" <?php if ($c['edit']['errors']['email']) {print 'class="error"';} ?> value="<?php print $c['edit']['values']['email']; ?>"/> <br>
    <label> Год рождения </label> <br>
    <select name="year" <?php if ($c['edit']['errors']['year']) {print 'class="error"';} ?>>
      <option value="Выбрать">Выбрать</option>
    <?php
        for($i=1800;$i<=2022;$i++){
          if($c['edit']['values']['year']==$i){
            printf("<option value=%d selected>%d год</option>",$i,$i);
          }
          else{
            printf("<option value=%d>%d год</option>",$i,$i);
          }
        }
    ?>
    </select> <br>
    <label> Ваш пол </label> <br>
    <div <?php if($c['edit']['errors']['sex']) {print 'class="error"';} ?>>
      <input name="sex" type="radio" value="M" <?php if($c['edit']['values']['sex']=="M") {print 'checked';} ?>/> Мужчина
      <input name="sex" type="radio" value="W" <?php if($c['edit']['values']['sex']=="W") {print 'checked';} ?>/> Женщина
    </div>
    <label> Сколько у вас конечностей </label> <br>
    <div <?php if ($c['edit']['errors']['limb']) {print 'class="error"';} ?>>
      <input name="limb" type="radio" value="1" <?php if($c['edit']['values']['limb']=="1") {print 'checked';} ?>/> 1 
      <input name="limb" type="radio" value="2" <?php if($c['edit']['values']['limb']=="2") {print 'checked';} ?>/> 2 
      <input name="limb" type="radio" value="3" <?php if($c['edit']['values']['limb']=="3") {print 'checked';} ?>/> 3 
      <input name="limb" type="radio" value="4" <?php if($c['edit']['values']['limb']=="4") {print 'checked';} ?>/> 4 
    </div>
    <label> Выберите суперспособности </label> <br>
    <select name="power[]" size="3" multiple <?php if ($c['edit']['errors']['powers']) {print 'class="error"';} ?>>
      <option value="бессмертие" <?php if(!empty($c['edit']['values']['immortal'])){print 'selected';} ?>>Бессмертие</option>
      <option value="прохождение сквозь стены" <?php if(!empty($c['edit']['values']['ghost'])){print 'selected';} ?>>Прохождение сквозь стены</option>
      <option value="левитация" <?php if(!empty($c['edit']['values']['levitation'])){print 'selected';} ?>>Левитация</option>
    </select> <br>
    <label> Краткая биография </label> <br>
    <textarea name="bio" rows="10" cols="15"><?php print $c['edit']['values']['bio']; ?></textarea> <br>
    <input type="submit" name='action' value="Edit"/>
    <input type="submit" name='action' value="Delete"/>
  </form>
    <p>
    <form action="" method="post">
        <input type="submit" name="action" value="Назад">
    </form>
    </p>
  </div>
</body>