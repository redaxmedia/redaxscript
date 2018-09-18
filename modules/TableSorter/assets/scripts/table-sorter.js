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
			window.dragula(
			[
				tbody
			], CONFIG.dragula);
		})
	}
};

/* run as needed */

if (rs.modules.TableSorter.init && rs.modules.TableSorter.dependency)
{
	rs.modules.TableSorter.execute(rs.modules.TableSorter.config);
}
