<div class="block story">
	<div class="wrp">
		<div class="grid_3_4 none">
			<div class="head">
				<h1 class="title h2 ultrabold">
					[registration]Реєстрація[/registration]
					[validation]Продовження реєстрації[/validation]
				</h1>
			</div>
			<div class="text regtext">
			[registration]
				Реєстрація на нашому сайті дозволить Вам бути його повноцінним учасником.
				Ви зможете додавати новини на сайт, залишати свої коментарі, переглядати прихований текст та багато іншого.<br>
				<br>У разі виникнення проблем з реєстрацією, зверніться до <a href="/index.php?do=feedback">адміністратора</a> сайту.
			[/registration]
			[validation]
				Ваш аккаунт був зареєстрований на нашому сайті,
				проте інформація про Вас є неповною, тому ОБОВ'ЯЗКОВО заповніть додаткові поля в Вашому профілі.<br>
			[/validation]
			</div>
			<ul class="ui-form">
				[registration]
					<li class="form-group imp">
						<label for="name">Логін</label>
						<div class="login_check">
							<input type="text" name="name" id="name" class="wide" required>
							<button class="btn" title="Перевірити" onclick="CheckLogin(); return false;">Перевірити</button>
						</div>
						<div id="result-registration"></div>
					</li>
					<li class="form-group imp">
						<label for="password1">Гасло</label>
						<input type="password" name="password1" id="password1" class="wide" required>
					</li>
					<li class="form-group imp">
						<label for="password2">Повторіть гасло</label>
						<input type="password" name="password2" id="password2" class="wide" required>
					</li>
					<li class="form-group imp">
						<label for="email">E-mail</label>
						<input type="email" name="email" id="email" class="wide" required>
					</li>
				[question]
					<li class="form-group">
						<label for="question_answer">{question}</label>
						<input placeholder="Введіть відповідь" type="text" name="question_answer" id="question_answer" class="wide" required>
					</li>
				[/question]
				[recaptcha]
					<li>{recaptcha}</li>
				[/recaptcha]
				[/registration]
				[validation]
					<li class="form-group">
						<label for="fullname">Ваше ім'я</label>
						<input type="text" id="fullname" name="fullname" class="wide">
					</li>
					<li class="form-group">
						<label for="land">Місце проживання</label>
						<input type="text" id="land" name="land" class="wide">
					</li>
					<li class="form-group">
						<label for="image">Про себе</label>
						<textarea id="info" name="info" rows="5" class="wide"></textarea>
					</li>
					<li class="form-group">
						<label for="image">Аватар</label>
						<input type="file" id="image" name="image" class="wide">
					</li>
					<li class="form-group">
						<table class="xfields">
							{xfields}
						</table>
					</li>
				[/validation]
			</ul>
			<div class="form_submit">
				[registration]
				[sec_code]
					<div class="c-capcha">
						{reg_code}
						<input placeholder="Повторіть код" title="Введіть код, вказаний на зображенні" type="text" name="sec_code" id="sec_code" required>
					</div>
				[/sec_code]
				[/registration]
				<button class="btn" name="submit"><b class="ultrabold">Зареєструватися</b></button>
			</div>
		</div>
	</div>
</div>