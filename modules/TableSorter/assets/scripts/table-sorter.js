rs.modules.TableSorter.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.TableSorter.optionArray,
		...optionArray
	};
	const tbodyList = document.querySelectorAll(OPTION.selector);
	const moveList = document.querySelectorAll(OPTION.element.move);

	if (tbodyList)
	{
		tbodyList.forEach(tbody =>
		{
			const dragula = window.dragula(
			[
				tbody
			], OPTION.dragula);

			/* handle dragend */

			dragula.on('dragend', () =>
			{
				const childrenList = tbody.childNodes;

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
				.then(() => OPTION.reload ? location.reload() : null);
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

if (rs.modules.TableSorter.init && rs.modules.TableSorter.dependency)
{
	rs.modules.TableSorter.process(rs.modules.TableSorter.optionArray);
}
