<div class="block story">
	<h1 class="title h2">Відновлення гасла</h1>
	<ul class="ui-form">
		<li class="form-group">
			<label for="lostname">Логін або E-mail</label>
			<input type="text" name="lostname" id="lostname" class="wide" required>
		</li>
	[recaptcha]
		<li>{recaptcha}</li>
	[/recaptcha]
	</ul>
	<div class="form_submit">
		[sec_code]
			<div class="c-capcha">
				{code}
				<input placeholder="Повторіть код" title="Введіть код, вказаний на зображенні" type="text" name="sec_code" id="sec_code" required>
			</div>
		[/sec_code]
		<button class="btn" name="submit" type="submit">Відновити</button>
	</div>
</div>