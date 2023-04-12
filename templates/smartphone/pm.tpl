<article class="post static">
  <h1 class="title">Персональні повідомлення</h1>
  [inbox]Вхідні повідомлення[/inbox] <br /> [outbox]Надіслані повідомлення[/outbox] <br /> [new_pm]Надіслати повідомлення[/new_pm]
</article>
[pmlist]
<div class="ux-form">
  <h3>Перелік повідомлень</h3>
  {pmlist}
</div>
[/pmlist]
[newpm]
<div class="ux-form">
  <h3>Надсилання повідомлення</h3>
  <ul class="ui-form">
    <li><input placeholder="Одержувач" type="text" name="name" value="{author}" class="f_input f_wide"></li>
    <li><input placeholder="Тема" type="text" name="subj" value="{subj}" class="f_input f_wide"></li>
    <li><textarea placeholder="Повідомлення" name="comments" id="comments" rows="2" class="f_textarea f_wide">{text}</textarea></li>
    <li><input type="checkbox" name="outboxcopy" value="1"> Зберегти в теці "Надіслані повідомлення"</li>
    [sec_code]
    <li>
      <div class="c-captcha-box">
        <label for="sec_code">Повторіть код:</label>
        <div class="c-captcha">
          {sec_code}
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
  </ul>
  <div class="submitline">
    <button class="btn f_wide" name="add" type="submit" name="submit">Надіслати</button>
  </div>
</div>
[/newpm]
[readpm]
<div class="comment vcard">
  <div class="com-cont clrfix">
    <strong>{subj}</strong><br />
    {text}
  </div>
  <div class="com-inf">
    <span class="arg">Повідомлення від <b class="fn">{author}</b></span>
    <span class="fast">[reply]<b class="thd">Відповісти</b>[/reply]</span>
    <span class="del">[del]<b class="thd">Видалити</b>[/del]</span>
  </div>
</div>
[/readpm]