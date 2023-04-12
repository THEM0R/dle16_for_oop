<article class="box story[fixed] fixed_story[/fixed] fullstory">
	<div class="box_in">
		[not-group=5]
		<ul class="story_icons">
			<li class="fav_btn">
				[add-favorites]<span title="Додати в закладки"><svg class="icon icon-fav"><use xlink:href="#icon-fav"></use></svg></span>[/add-favorites]
				[del-favorites]<span title="Прибрати з закладок"><svg class="icon icon-star"><use xlink:href="#icon-star"></use></svg></span>[/del-favorites]
			</li>
			<li class="edit_btn">
				[edit]<i title="Редагувати">Редагувати</i>[/edit]
			</li>
		</ul>
		[/not-group]
		<h2 class="title" itemprop="headline">{title}</h2>
		<div class="text share-content" itemprop="articleBody">
			{full-story}
			[edit-date]<p class="editdate grey">Новину відредагував: <b>{editor}</b> - {edit-date}<br>
			[edit-reason]Причина: {edit-reason}[/edit-reason]</p>[/edit-date]
		</div>
		{pages}
		<div class="story_tools">
			<div class="category">
				<svg class="icon icon-cat"><use xlink:href="#icon-cat"></use></svg>
				{link-category}
			</div>
			[rating]
				<div class="rate">
					[rating-type-1]<div class="rate_stars">{rating}</div>[/rating-type-1]
					[rating-type-2]
					<div class="rate_like">
					[rating-plus]
						<svg class="icon icon-love"><use xlink:href="#icon-love"></use></svg>
						{rating}
					[/rating-plus]
					</div>
					[/rating-type-2]
					[rating-type-3]
					<div class="rate_like-dislike">
						[rating-plus]<span title="Подобається"><svg class="icon icon-like"><use xlink:href="#icon-like"></use></svg></span>[/rating-plus]
						{rating}
						[rating-minus]<span title="Не подобається"><svg class="icon icon-dislike"><use xlink:href="#icon-dislike"></use></svg></span>[/rating-minus]
					</div>
					[/rating-type-3]
					[rating-type-4]
					<div class="rate_like-dislike">
						<span class="ratingtypeplusminus ratingplus">{likes}</span>
						[rating-plus]<span title="Подобається"><svg class="icon icon-like"><use xlink:href="#icon-like"></use></svg></span>[/rating-plus]
						<span class="ratingtypeplusminus ratingminus">{dislikes}</span>
						[rating-minus]<span title="Не подобається"><svg class="icon icon-dislike"><use xlink:href="#icon-dislike"></use></svg></span>[/rating-minus]
					</div>
					[/rating-type-4]
				</div>
			[/rating]
		</div>
		[fixed]<span class="fixed_label" title="Важлива новина"></span>[/fixed]
	</div>
	<div class="meta">
		<ul class="right">
			<li class="complaint" title="Скарга">[complaint]<svg class="icon icon-bad"><use xlink:href="#icon-bad"></use></svg><span class="title_hide">Скарга</span>[/complaint]</li>
			<li class="grey" title="Переглядів: {views}"><svg class="icon icon-views"><use xlink:href="#icon-views"></use></svg> {views}</li>
			<li title="Коментарів: {comments-num}">[com-link]<svg class="icon icon-coms"><use xlink:href="#icon-coms"></use></svg> {comments-num}[/com-link]</li>
		</ul>
		<ul class="left">
			<li class="story_date"><svg class="icon icon-info"><use xlink:href="#icon-info"></use></svg> {author}<span class="grey"> від </span><time datetime="{date=Y-m-d}" class="grey" itemprop="datePublished">[day-news]{date}[/day-news]</time></li>
		</ul>
	</div>
	<meta itemprop="author" content="{login}">
</article>
<div class="rightside">
	{include file="modules/rightside_fullstory.tpl"}
</div>
<div class="box next-prev">
	[prev-url]<a href="{prev-url}" class="btn">Попередня публікація</a>[/prev-url]
	[next-url]<a href="{next-url}" class="btn right">Наступна публікація</a>[/next-url]
</div>
[banner_header]
<div class="box banner">
	{banner_header}
</div>
[/banner_header]
<div class="comments">
	<div class="box">
		[comments]<h4 class="heading">Коментарі <span class="grey hnum">{comments-num}</span></h4>[/comments]
		<div class="com_list">
			{comments}
		</div>
	</div>
	{navigation}
	{addcomments}
</div>