<article class="box story searchpage">
	<div class="box_in">
		<h1 class="title">Пошук на сайті</h1>
		<div id="searchtable" name="searchtable" class="searchtable">
[simple-search]
<table style="width:100%;">
  <tr>
    <td class="search">
      <div style="margin:10px;">
                {searchfield}<br /><br />
                <input type="button" class="bbcodes" name="dosearch" id="dosearch" value="Знайти" onclick="javascript:list_submit(-1); return false;">
                <input type="button" class="bbcodes" name="dofullsearch" id="dofullsearch" value="Розширений пошук" onclick="javascript:full_submit(1); return false;">
            </div>

        </td>
    </tr>
</table>
[/simple-search]
[extended-search]
<table style="width:100%;">
  <tr>
    <td class="search">
      <div align="center">
        <table style="width:100%;">

        <tr style="vertical-align: top;">
				<td class="search">
					<fieldset style="margin:0px">
						<legend>Пошук за змістом</legend>
						<table style="width:100%;padding:3px;">
						<tr>
						<td class="search">
							<div>Слова для пошуку</div>
							<div>{searchfield}</div>
							{word-option}
						</td>
						</tr>
						<tr>
						<td class="search">
							{search-area}
						</td>
						</tr>
						</table>
					</fieldset>
				</td>

				<td class="search" valign="top">					
					<fieldset style="margin:0px">
						<legend>Пошук за іменем</legend>
						<table style="width:100%;">
						<tr>
						<td class="search">
							<div>Ім'я користувача</div>
							<div id="userfield">{userfield}<br /><label>{user-option}Точне співпадіння</label></div>
						</td>
						</tr>
						</table>
					</fieldset>
				</td>
				</tr>

				<tr style="vertical-align: top;">

				<td width="50%" class="search">
					<fieldset style="margin:0px">
						<legend>Шукати публікації з</legend>
						<div style="padding:3px">
							{news-option}
							{comments-num} коментарями
						</div>
					</fieldset>

					<fieldset style="padding-top:10px">
						<legend>Часовий проміжок</legend>

						<div style="padding:3px">					
							{date-option}
							{date-beforeafter}
						</div>
					</fieldset>

					<fieldset style="padding-top:10px">
						<legend>Сортування результатів</legend>
							<div style="padding:3px">
								{sort-option}
								{order-option}
							</div>
					</fieldset>

					<fieldset style="padding-top:10px">
						<legend>Показувати результати як</legend>

						<table style="width:100%;padding:3px;">
						<tr align="left" valign="middle">
						<td align="left" class="search">Результати пошуку як:&nbsp;
							{view-option}
						</td>
						</tr>

						</table>
					</fieldset>
				</td>

				<td width="50%" class="search" valign="top">
					<fieldset style="margin:0px">
						<legend>Пошук по розділах</legend>
							<div style="padding:3px">
								<div>{category-option}</div>
							</div>

					</fieldset>
				</td>
				</tr>

        <tr>
                <td class="search" colspan="2">
                    <div style="margin-top:6px">
                        <input type="button" class="bbcodes" style="margin:0px 20px 0 0px;" name="dosearch" id="dosearch" value="Шукати" onclick="javascript:list_submit(-1); return false;">
                        <input type="button" class="bbcodes" style="margin:0px 20px 0 20px;" name="doclear" id="doclear" value="Скинути" onclick="javascript:clearform('fullsearch'); return false;">
                        <input type="reset" class="bbcodes" style="margin:0px 20px 0 20px;" name="doreset" id="doreset" value="Повернути">
                    </div>

                </td>
                </tr>

        </table>
      </div>
    </td>
  </tr>
</table>
[/extended-search]
		</div>
		[searchmsg]<div class="search_result_num grey">{searchmsg}</div>[/searchmsg]
	</div>
</article>