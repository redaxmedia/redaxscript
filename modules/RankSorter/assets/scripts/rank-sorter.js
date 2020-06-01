rs.modules.RankSorter.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.RankSorter.optionArray,
		...optionArray
	};
	const tbodyList = document.querySelectorAll(OPTION.selector);
	const moveList = document.querySelectorAll(OPTION.element.move);

	if (tbodyList)
	{
		tbodyList.forEach(tbodyElement =>
		{
			const dragula = window.dragula(
			[
				tbodyElement
			], OPTION.dragula);

			/* handle dragend */

			dragula.on('dragend', () =>
			{
				const childrenList = tbodyElement.childNodes;

				fetch(OPTION.sortRoute,
				{
					credentials: 'same-origin',
					method: 'POST',
					headers:
					{
						'Content-Type': 'application/json',
						'X-Requested-With': 'XMLHttpRequest'
					},
					body: JSON.stringify(
					{
						table: rs.registry.tableParameter,
						rankArray: Object.keys(childrenList).map((childrenValue => childrenList[childrenValue].id.replace('row-', '')))
					})
				})
				.then(() => OPTION.reload ? location.reload() : null)
				.catch(() => null);
			});
		});
	}
	if (moveList)
	{
		moveList.forEach(move =>
		{
			move.addEventListener('touchmove', event => event.preventDefault());
		});
	}
};

/* run as needed */

if (rs.modules.RankSorter.init && rs.modules.RankSorter.dependency)
{
	rs.modules.RankSorter.process(rs.modules.RankSorter.optionArray);
}
