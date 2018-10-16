rs.modules.TableSorter.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.TableSorter.config,
		...config
	};
	const tbodyList = document.querySelectorAll(CONFIG.selector);
	const moveList = document.querySelectorAll(CONFIG.element.move);

	if (tbodyList)
	{
		tbodyList.forEach(tbody =>
		{
			const dragula = window.dragula(
			[
				tbody
			], CONFIG.dragula);

			/* handle dragend */

			dragula.on('dragend', () =>
			{
				const childrenList = tbody.childNodes;

				fetch(CONFIG.sortUrl,
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
				.then(() => CONFIG.reload ? location.reload() : null);
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
	rs.modules.TableSorter.execute(rs.modules.TableSorter.config);
}
