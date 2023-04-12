<article class="post static">
  <h1 class="title">Користувач: {usertitle}</h1>
  Повне ім'я: {fullname}<br />
  Дата реєстрації: {registration}<br />
  Останні відвідини: {lastdate}<br />
  Група: <font color="red">{status}</font>[time_limit] в групі до: {time_limit}[/time_limit]<br /><br />
  Місце проживання: {land}<br />
  Трохи про себе:<br />{info}<br /><br />
  Кількість публікацій: {news-num}<br />
  [ {news} ]<br /><br />
  Кількість коментарів: {comm-num}<br />
  [ {comments} ]<br /><br />
  [ {email} ]<br />
  [ {pm} ]<br />
  {edituser}
</article>
[not-logged]
<div id="options" style="display:none;">
  <div class="ux-form">
    <h3>Редагування інформації</h3>
    <ul class="ui-form">
      <li><input placeholder="E-mail" type="email" name="email" value="{editmail}" class="f_input f_wide"><div>{hidemail}</div></li>
      <li><br /></li>
      <li><input placeholder="Ваше Ім'я" type="text" name="fullname" value="{fullname}" class="f_input f_wide"></li>
      <li><input placeholder="Місце проживання" type="text" name="land" value="{land}" class="f_input f_wide"></li>
      <li><br /></li>
      <li><input placeholder="Старе гасло" type="password" name="altpass" class="f_input f_wide"></li>
      <li><input placeholder="Нове гасло" type="password" name="password1" class="f_input f_wide"></li>
      <li><input placeholder="Повторіть" type="password" name="password2" class="f_input f_wide"></li>
      <li><br /></li>
      <li><textarea name="allowed_ip" rows="2" class="f_textarea f_wide">{allowed-ip}</textarea><br />
        Ваша поточна IP: <b>{ip}</b><br /><div style="color:red;font-size:11px;">* Увага! Будьте пильні при зміні даного налаштування. Доступ до Вашого аккаунту буде доступний лише з тієї IP-адреси або підмережі, яку Ви вкажете. Ви можете вказати декілька IP-адрес, по одній адресі на кожен рядок.<br />Приклад: 192.48.25.71 або 129.42.*.*</div>
      </li>
      <li><br /></li>
      <li><label for="image">Аватар:</label><input type="file" name="image" class="f_input f_wide"><p><input type="checkbox" name="del_foto" value="yes">  Видалити аватар</p></li>
      <li><br /></li>
      <li><textarea placeholder="Про себе" name="info" rows="2" class="f_textarea f_wide">{editinfo}</textarea></li>
      <li><textarea placeholder="Підпис" name="signature" rows="2" class="f_textarea f_wide">{editsignature}</textarea></li>
    </ul>
    <div class="submitline">
      <button name="submit" class="btn f_wide" type="submit">Зберегти</button>
      <input name="submit" type="hidden" id="submit" value="submit">
    </div>
  </div>
</div>
[/not-logged]