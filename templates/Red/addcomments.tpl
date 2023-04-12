<div class="block addcomments_form">
	<div class="wrp">
		<div class="grid_1_4 grid_last right">
			<h4 class="ultrabold">Додати коментар</h4>
			<p class="grey">Автору буде дуже приємно отримати зворотний зв'язок на свою новину.</p>
		</div>
		<div class="grid_3_4">
			<ul class="ui-form">
			[not-logged]
				<li class="form-group combo">
					<div class="combo_field"><input placeholder="Ваше ім'я" type="text" name="name" id="name" class="wide" required></div>
					<div class="combo_field"><input placeholder="Ваш e-mail" type="email" name="mail" id="mail" class="wide"></div>
				</li>
			[/not-logged]
				<li id="comment-editor">
					{editor}
					[image-upload]
						<a onclick="ShowCommentsUploader(); return false" href="#">Прикріпити зображення</a>
						<div id="hidden-image-uploader" style="display: none">{image-upload}</div>
					[/image-upload]
				</li>
			[allow-comments-subscribe]
				<li>{comments-subscribe}</li>
			[/allow-comments-subscribe]
			[recaptcha]
				<li>{recaptcha}</li>
			[/recaptcha]
			[question]
				<li class="form-group">
					<label for="question_answer">{question}</label>
					<input placeholder="Відповідь" type="text" name="question_answer" id="question_answer" class="wide" required>
				</li>
			[/question]
			</ul>
			<div class="form_submit">
			[sec_code]
				<div class="c-capcha">
					{sec_code}
					<input placeholder="Повторіть код" title="Введіть код, вказаний на зображенні" type="text" name="sec_code" id="sec_code" required>
				</div>
			[/sec_code]
				<button class="btn" type="submit" name="submit" title="Опублікувати коментар"><b class="ultrabold">Опублікувати коментар</b></button>
			</div>
		</div>
		<div class="clr"></div>
	</div>
</div>