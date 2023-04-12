<div class="block_grey">
	<h4 class="title">{question}</h4>
	<div class="vote_more"><a href="#" onclick="ShowAllVotes(); return false;">Інші опитування...</a></div>
		<div class="vote_list">
			{list}
		</div>
	[voted]
		<div class="vote_votes grey">Проголосувало: {votes}</div>
	[/voted]
	[not-voted]
		<button title="Голосувати" class="btn btn-white" type="submit" onclick="doPoll('vote', '{news-id}'); return false;" ><b>Голосувати</b></button>
		<button title="Результати опитування" class="btn-border" type="button" onclick="doPoll('results', '{news-id}'); return false;">
			<svg class="icon icon-votes"><use xlink:href="#icon-votes"></use></svg>
			<span class="title_hide">Результати опитування</span>
		</button>
	[/not-voted]
</div>