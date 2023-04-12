<div id="votes" class="block_grey">
	<h4 class="title">{title}</h4>
	<div class="vote_more"><a href="#" onclick="ShowAllVotes(); return false;">Інші опитування...</a></div>
	[votelist]
	<form method="post" name="vote">
	[/votelist]
		<div class="vote_list">
			{list}
		</div>
	[voteresult]
		<div class="vote_votes grey">Проголосувало: {votes}</div>
	[/voteresult]
	[votelist]
		<input type="hidden" name="vote_action" value="vote">
		<input type="hidden" name="vote_id" id="vote_id" value="{vote_id}">
		<button title="Голосувати" class="btn btn-white" type="submit" onclick="doVote('vote'); return false;" ><b>Голосувати</b></button>
		<button title="Результати опитування" class="btn-border" type="button" onclick="doVote('results'); return false;" >
			<svg class="icon icon-votes"><use xlink:href="#icon-votes"></use></svg>
			<span class="title_hide">Результати опитування</span>
		</button>
	</form>
	[/votelist]
</div>