rs.modules.TableSorter =
{
	init: true,
	dependency: typeof window.dragula === 'function',
	config:
	{
		selector: 'table.rs-admin-js-sort tbody',
		element:
		{
			move: 'td.rs-admin-col-move'
		},
		sortUrl: rs.registry.parameterRoute + 'module/table-sorter/sort/' + rs.registry.token,
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
