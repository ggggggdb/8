<style>
  table{
    margin: 0 auto;
    text-align: center;
  }
  table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<table>
<tr>
  <th>Имя</th>
  <th>Почта</th>
  <th>Год рождения</th>
  <th>Пол</th>
  <th>Кол-во конечностей</th>
  <th>Суперсилы</th>
  <th>Биография</th>
</tr>
<?php
foreach ($c['admin'] as $id => $row) {
?>
  
  <tr>
    <td><?php print($row['name']); ?></td>
    <td><?php print($row['email']); ?></td>
    <td><?php print($row['year']); ?></td>
    <td><?php print($row['sex']); ?></td>
    <td><?php print($row['limb']); ?></td>
    <td><?php foreach($row['powers'] as $pwr){print($pwr.' <br>');}; ?></td>
    <td><?php print($row['bio']); ?></td>
    <td>
      <form action="admin/<?php print($row['id']); ?>" method="POST">
        <input type="submit" name="action" value="Удалить">
      </form>
    </td>
    <td>
      <form action="admin/<?php print($row['id']); ?>/edit" method="post">
        <input type="submit" name="action" value="Изменить">
      </form>
    </td>
  </tr>
<?php  
}
?>
</table>
