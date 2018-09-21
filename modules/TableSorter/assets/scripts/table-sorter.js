rs.modules.TableSorter.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.TableSorter.config,
		...config
	};
	const tbodyList = document.querySelectorAll(CONFIG.selector);

	if (tbodyList)
	{
		tbodyList.forEach(tbody =>
		{
			const dragula = window.dragula(
			[
				tbody
			], CONFIG.dragula);

			/* handle dragend */

			dragula.on('dragend', element =>
			{
				window.fetch(CONFIG.sortUrl,
				{
					method: 'POST',
					body: JSON.stringify(
					{
						table: rs.registry.tableParameter,
						currentId: element.id,
						previousId: element.previousSibling ? element.previousSibling.id : -1,
						nextId: element.nextSibling ? element.nextSibling.id : -1
					})
				});
			});
		});
	}
};

/* run as needed */

if (rs.modules.TableSorter.init && rs.modules.TableSorter.dependency)
{
	rs.modules.TableSorter.execute(rs.modules.TableSorter.config);
}
