<article class="post static">
  <h1>Зворотній зв'язок</h1>
</article>
<div class="ux-form">
  <ul class="ui-form">
    [not-logged]
    <li><input placeholder="Ваше і'мя" type="text" maxlength="35" name="name" class="f_input f_wide"></li>
    <li><input placeholder="E-mail" type="email" maxlength="35" name="email" class="f_input f_wide"></li>
    [/not-logged]
    <li><input placeholder="Заголовок" type="text" maxlength="45" name="subject" class="f_input f_wide"><div style="display: none">{recipient}</div></li>
    <li><textarea placeholder="Повідомлення" name="message" row="3" class="f_textarea f_wide"></textarea></li>
    [sec_code]
    <li>
      <div class="c-captcha-box">
        <label for="sec_code">Повторіть код:</label>
        <div class="c-captcha">
          {code}
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
  <div class="submitline">
    <button name="submit" class="btn f_wide" type="submit">Надіслати</button>
  </div>
</div>