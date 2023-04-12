<div class="poll_block">
	<div class="poll_block_in">
	<h4 class="title">{question}</h4>
		<div class="vote_list">
			{list}
		</div>
	[voted]
		<div class="vote_votes grey">Проголосувало: {votes}</div>
	[/voted]
	[not-voted]
		<button title="Голосувати" class="btn" type="submit" onclick="doPoll('vote', '{news-id}'); return false;" ><b>Голосувати</b></button>
		<button title="Результати" class="btn" type="button" onclick="doPoll('results', '{news-id}'); return false;" ><b>Результати</b></button>
	[/not-voted]
	</div>
</div>