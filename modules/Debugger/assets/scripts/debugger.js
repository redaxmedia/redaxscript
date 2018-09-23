rs.modules.Debugger.execute = () =>
{
	Object.keys(rs.modules.Debugger.data).forEach(dataValue =>
	{
		const data = rs.modules.Debugger.data[dataValue];
		const total = Object.keys(rs.modules.Debugger.data[dataValue]).length;

		console.log(dataValue.toUpperCase() + ' (' + total + ')');
		console.table(data);
	});
};

/* run as needed */

if (rs.modules.Debugger.init)
{
	rs.modules.Debugger.execute();
}
