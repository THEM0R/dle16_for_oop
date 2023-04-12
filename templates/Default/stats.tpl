<article class="box story">
	<div class="box_in dark_top stats_head">
		<h1 class="title">Статистика сайту</h1>
		<ul>
			<li class="stats_d"><b>За добу</b> <span>{news_day} новин та {comm_day} коментарів, зареєстровано {user_day} користувачів</span></li>
			<li class="stats_w"><b>За тиждень</b> <span>{news_week} новин та {comm_week} коментарів, зареєстровано {user_week} користувачів</span></li>
			<li class="stats_m"><b>За місяць</b> <span>{news_month} новин та {comm_month} коментарів, зареєстровано {user_month} користувачів</span></li>
		</ul>
	</div>
	<div class="box_in">
		<div class="statistics">
			<div class="stat_group">
				<h5 class="blue">Новини</h5>
				<ul>
					<li>Загальна кількість новин <b class="right">{news_num}</b></li>
					<li>З них опубліковано <b class="right">{news_allow}</b></li>
					<li>Опубліковано на головній <b class="right">{news_main}</b></li>
					<li>Очікують модерації <b class="right">{news_moder}</b></li>
				</ul>
			</div>
			<div class="stat_group">
				<h5 class="blue">Користувачі</h5>
				<ul>
					<li>Загальна кількість користувачів <b class="right">{user_num}</b></li>
					<li>З них забанено <b class="right">{user_banned}</b></li>
				</ul>
			</div>
			<div class="stat_group">
				<h5 class="blue">Коментарі</h5>
				<ul>
					<li>Кількість коментарів <b class="right">{comm_num}</b></li>
					<li><a href="/?do=lastcomments">Переглянути останні</a></li>
				</ul>
			</div>
			<p class="grey">Загальний розмір бази даних: {datenbank}</p>
		</div>
		<h4 class="heading">Кращі користувачі</h4>
		<div class="table_top_users">
			<table class="userstop">{topusers}</table>
		</div>
	</div>
</article>