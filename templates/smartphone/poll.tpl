<div id="pollbox" class="gr-box">
	<h4 class="vtitle">{question}</h4>
	<div class="vlist">
		{list}
	</div>
	<div class="vfoot">
		[voted]<span class="small">{votes} голосів</span>[/voted]
		[not-voted]
			<button class="btn" type="submit" onclick="doPoll('vote', '{news-id}'); return false;" >Голосувати</button>
			<button class="btn" type="submit" onclick="doPoll('results', '{news-id}'); return false;">Результати</button>
		[/not-voted]
	</div>
</div>