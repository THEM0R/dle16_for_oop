[not-group=5]
<a id="login-btn" class="logged ico">{login}</a>
<div id="lg-dialog" title="Про користувача" class="wideDialog">
	<a id="lg-close" class="thd">Закрити</a>
	<ul id="usermenu">
		<li><a href="{profile-link}">Мій профіль</a></li>
		<li><a href="{pm-link}">ПП ({new-pm} | {all-pm})</a></li>
		<li><a href="{favorites-link}">Мої закладки</a></li>
		<li><a href="{stats-link}">Статистика</a></li>
		<li><a href="{newposts-link}">Огляд непрочитаного</a></li>
		<li><a href="{logout-link}">Завершити сеанс!</a></li>
	</ul>
</div>
[/not-group]
[group=5]
<a id="login-btn" class="ico">Увійти</a>
<div id="lg-dialog" title="Авторизація" class="wideDialog">
	<a id="lg-close" class="thd">Закрити</a>
	<form class="login-form" method="post" action="">
		<ul>
			<li><label for="login_name">{login-method}</label>
			<input class="f_input" type="text" name="login_name" id="login_name" ></li>
			<li><label for="login_password">Гасло:</label>
			<input class="f_input" type="password" name="login_password" id="login_password" ></li>
		</ul>
		<div class="submitline">
			<button onclick="submit();" type="submit" title="Увійти" class="btn f_wide">Увійти</button>
		</div>
		<div class="log-links">
			<a href="{lostpassword-link}">Забули гасло?</a> |
			<a href="{registration-link}">Реєстрація</a>
		</div>
		<input name="login" type="hidden" id="login" value="submit">
	</form>
</div>
[/group]