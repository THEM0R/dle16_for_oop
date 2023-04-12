<div class="greenmenu">
	<!-- Пошук -->
	<form id="q_search" method="post">
		<div class="q_search">
			<input id="story" name="story" placeholder="Пошук на сайті..." type="search">
			<button class="q_search_btn" type="submit" title="Знайти"><svg class="icon icon-search"><use xlink:href="#icon-search"></use></svg><span class="title_hide">Знайти</span></button>
		</div>
		<input type="hidden" name="do" value="search">
		<input type="hidden" name="subaction" value="search">
	</form>
	<!-- / Пошук -->
	<nav class="menu">
		<a[available=main] class="active"[/available] href="/" title="Головна">Головна</a>
		<a[available=feedback] class="active"[/available] href="/index.php?do=feedback" title="Контакти">Контакти</a>
		<a[available=rules] class="active"[/available] href="/rules.html" title="Правила">Правила</a>
		{catmenu}
	</nav>
</div>