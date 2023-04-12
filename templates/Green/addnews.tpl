<article class="block story">
	<h1 class="title h2">{header-title}</h1>
	<ul class="ui-form">
		<li class="form-group imp">
			<label for="title">Заголовок</label>
			<input type="text" name="title" id="title" value="{title}" class="wide" required>
		</li>
		[urltag]
		<li class="form-group imp">
			<label for="alt_name">URL новини</label>
			<input type="text" name="alt_name" id="alt_name" value="{alt-name}" class="wide">
		</li>
		[/urltag]
		<li class="form-group imp">
			<label for="category">Категорія</label>
			{category}
		</li>
		<li class="form-group">
			<label><a href="#" onclick="$('.addvote').toggle();return false;"><span class="plus_icon circle"><span>+</span></span> Додати Опитування</a></label>
		</li>
		<li class="form-group addvote" style="display:none;">
			<label for="vote_title" >Заголовок опитування</label>
			<input type="text" name="vote_title" value="{votetitle}" class="wide" />
		</li>
		<li class="form-group addvote" style="display:none;">
			<label for="frage" >Запитання</label>
			<input type="text" name="frage" value="{frage}" class="wide" />
		</li>
		<li class="form-group addvote" style="display:none;">
			<label for="vote_body" >Список відповідей</label>
			<textarea name="vote_body" rows="5" class="wide" placeholder="Кожен новий рядок є новим варіантом відповіді">{votebody}</textarea><label><input type="checkbox" name="allow_m_vote" value="1" {allowmvote}> Дозволити вибір декількох варіантів</label>
		</li>
		<li class="form-group imp">
			<label for="short_story">Короткий зміст</label>
			[not-wysywyg]
			<div class="bb-editor">
				{bbcode}
				<textarea name="short_story" id="short_story" onfocus="setFieldName(this.name)" rows="8" class="wide" required>{short-story}</textarea>
			</div>
			[/not-wysywyg]
			{shortarea}
		</li>
		<li class="form-group">
			<label for="full_story">Повний зміст</label>
			[not-wysywyg]
			<div class="bb-editor">
				{bbcode}
				<textarea name="full_story" id="full_story" onfocus="setFieldName(this.name)" rows="18" class="wide" >{full-story}</textarea>
			</div>
			[/not-wysywyg]
			{fullarea}
		</li>
		<li class="form-group">
			<table style="width:100%">
				{xfields}
			</table>
		</li>
		<li class="form-group">
			<label for="alt_name">Ключові слова</label>
			<input placeholder="Введіть через кому" type="text" name="tags" id="tags" value="{tags}" maxlength="150" autocomplete="off" class="wide">
		</li>
		<li class="form-group">
			<div class="admin_checkboxs">{admintag}</div>
		</li>
	[recaptcha]
		<li class="form-group">{recaptcha}</li>
	[/recaptcha]
	[question]
		<li class="form-group">
			<label for="question_answer">{question}</label>
			<input placeholder="Введіть відповідь" type="text" name="question_answer" id="question_answer" class="wide" required>
		</li>
	[/question]
	</ul>
	<p style="margin: 20px 0 0 0;" class="grey"><span style="color: #e85319">*</span> — поля, позначені зірочкою, обов'язкові для заповнення.</p>
	<div class="form_submit">
		[sec_code]
			<div class="c-capcha">
				{sec_code}
				<input placeholder="Повторіть код" title="Введіть код, вказаний на зображенні" type="text" name="sec_code" id="sec_code" required>
			</div>
		[/sec_code]
		<button class="btn" type="submit" name="add"><b>Додати</b></button>
		<button id="add_news_preview" class="btn" onclick="preview()" type="submit" name="nview"><b>Перегляд</b></button>
	</div>
</article>