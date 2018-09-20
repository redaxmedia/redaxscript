rs.modules.TableSorter =
{
	init: true,
	dependency: typeof window.dragula === 'function',
	config:
	{
		selector: 'table.rs-admin-js-sort tbody',
		dragula:
		{
			moves: (element, container, handle) =>
			{
				return handle.classList.contains('rs-admin-col-move') && handle.classList.contains('rs-admin-is-active');
			}
		}
	}
};
