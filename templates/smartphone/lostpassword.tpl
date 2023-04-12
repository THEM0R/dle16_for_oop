<article class="post static">
	<h1>Відновлення гасла</h1>
</article>
<div class="ux-form">
	<ul class="ui-form">
		<li><input placeholder="Логін або E-mail" class="f_input f_wide" type="text" name="lostname" id="lostname"></li>
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
		<button name="submit" class="btn f_wide" type="submit">Відновити</button>
	</div>
</div>