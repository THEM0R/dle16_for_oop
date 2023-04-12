<article class="post static">
  <h1 class="title">
    [registration]Реєстрація[/registration]
    [validation]Продовження реєстрації[/validation]
  </h1>
    [registration]
      <b>Вітаємо, шанований відвідувачу нашого сайту!</b><br />
      Реєстрація на нашому сайті дозволить Вам бути його повноцінним учасником.
      Ви зможете додавати новини на сайт, залишати свої коментарі, переглядати прихований текст і багато іншого.
      <br />В разі виникнення проблем з реєстрацією, зверніться до <a href="/index.php?do=feedback">адміністратора</a> сайту.
    [/registration]
    [validation]
      <b>Шанований відвідувачу,</b><br />
      Ваш аккаунт був зареєстрований на нашому сайті,
      проте інформація про Вас є неповною, тому заповніть додаткові поля у Вашому профілі.
    [/validation]
</article>
<div class="ux-form">
  <ul class="ui-form">
    [registration]
      <li>
        <div class="combofield">
          <input placeholder="Логін" type="text" name="name" id="name" class="f_input f_wide">
          <input class="bbcodes" title="Check" onclick="CheckLogin(); return false;" type="button" value="Перевірити">
        </div>
        <div class="clr" id='result-registration'></div>
      </li>
      <li>
        <input placeholder="Гасло" type="password" name="password1" id="password1" class="f_input f_wide">
      </li>
      <li>
        <input placeholder="Повторіть гасло" type="password" name="password2" id="password2" class="f_input f_wide">
      </li>
      <li>
        <input placeholder="E-mail" type="email" name="email" id="email" class="f_input f_wide">
      </li>
      [question]
      <li>
        Запитання: <b>{question}</b>
        <div><input placeholder="Відповідь" type="text" name="question_answer" id="question_answer" class="f_input f_wide" ></div>
      </li>
      [/question]
      [sec_code]
      <li>
        <div class="c-captcha-box">
          <label for="sec_code">Повторіть код:</label>
          <div class="c-captcha">
            {reg_code}
            <input title="Введіть код, вказаний на картинці" type="text" name="sec_code" id="sec_code" class="f_input" >
          </div>
        </div>
      </li>
      [/sec_code]
      [recaptcha]
      <li>
        <div>Введіть слова</div>
        {recaptcha}
      </li>
      [/recaptcha]
      [/registration]
      [validation]
      <li><input placeholder="Ваше ім'я" type="text" id="fullname" name="fullname" class="f_input f_wide"></li>
      <li><input placeholder="Місцезнаходження" type="text" id="land" name="land" class="f_input f_wide"></li>
      <li><input placeholder="ICQ" type="text" id="icq" name="icq" class="f_input f_wide"></li>
      <li><textarea placeholder="Про себе" id="info" name="info" rows="3" class="f_textarea f_wide"></textarea></li>
      <li><label for="image">Аватар:</label><input type="file" id="image" name="image" class="f_input f_wide"></li>
      [/validation]
  </ul>
  <div class="submitline">
    <button name="submit" class="btn f_wide" type="submit">Зареєструватися</button>
  </div>
</div>