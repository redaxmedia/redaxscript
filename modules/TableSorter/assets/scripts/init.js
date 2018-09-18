rs.modules.TableSorter =
{
	init: true,
	dependency: typeof window.dragula === 'function',
	config:
	{
		selector: 'tbody.rs-admin-js-sort',
		dragula:
		{
			removeOnSpill: true
		}
	}
};
