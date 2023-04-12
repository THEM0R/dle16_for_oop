<article class="block story shadow">
	<div class="wrp">
		<div class="head"><h1 class="title h2 ultrabold">Статистика сайту</h1></div>
		<div class="stats_head">
			<ul>
				<li class="stats_d"><b>За добу</b> <span>{news_day} новин та {comm_day} коментарів, зареєстровано {user_day} користувачів</span></li>
				<li class="stats_w"><b>За тиждень</b> <span>{news_week} новин та {comm_week} коментарів, зареєстровано {user_week} користувачів</span></li>
				<li class="stats_m"><b>За місяць</b> <span>{news_month} новин та {comm_month} коментарів, зареєстровано {user_month} користувачів</span></li>
			</ul>
		</div>
	</div>
</article>
<div class="block">
	<div class="wrp">
		<div class="statistics">
			<div class="stat_group">
				<h5>Новини</h5>
				<ul>
					<li>Загальна кількість новин <b class="right">{news_num}</b></li>
					<li>З них опубліковано <b class="right">{news_allow}</b></li>
					<li>Опубліковано на головній <b class="right">{news_main}</b></li>
					<li>Очікує модерації <b class="right">{news_moder}</b></li>
				</ul>
			</div>
			<div class="stat_group">
				<h5>Користувачі</h5>
				<ul>
					<li>Загальна кількість користувачів <b class="right">{user_num}</b></li>
					<li>З них забанено <b class="right">{user_banned}</b></li>
				</ul>
			</div>
			<div class="stat_group">
				<h5>Коментарі</h5>
				<ul>
					<li>Кількість коментарів <b class="right">{comm_num}</b></li>
					<li><a href="/?do=lastcomments">Переглянути останні</a></li>
				</ul>
			</div>
			<p class="grey">Загальний розмір бази даних: {datenbank}</p>
		</div>
	</div>
</div>
<div class="block block_table_top_users">
	<div class="wrp">
		<h4 class="block_title ultrabold">Кращі користувачі</h4>
		<div class="table_top_users">
			<table class="userstop">{topusers}</table>
		</div>
	</div>
</div>