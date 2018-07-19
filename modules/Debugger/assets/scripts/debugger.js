rs.modules.Debugger.execute = () =>
{
	Object.keys(rs.modules.Debugger.data).forEach(dataValue =>
	{
		const data = rs.modules.Debugger.data[dataValue];

		console.log(dataValue.toUpperCase() + ' (' + dataValue.length + ')');
		console.table(data);
	});
};

/* run as needed */

if (rs.modules.Debugger.init)
{
	rs.modules.Debugger.execute();
}
