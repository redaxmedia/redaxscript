rs.modules.TableSorter =
{
	init: true,
	dependency: typeof window.dragula === 'function',
	optionArray:
	{
		selector: 'table.rs-admin-js-sort tbody',
		element:
		{
			move: 'table.rs-admin-js-sort td.rs-admin-js-move'
		},
		sortRoute: rs.registry.parameterRoute + 'module/table-sorter/sort/' + rs.registry.token,
		reload: true,
		dragula:
		{
			moves: (element, container, handle) =>
			{
				return handle.classList.contains('rs-admin-col-move') && handle.classList.contains('rs-admin-is-active');
			}
		}
	}
};
