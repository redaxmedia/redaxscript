rs.modules.Debugger.process = () =>
{
	Object.keys(rs.modules.Debugger.dataArray).forEach(dataValue =>
	{
		console.info(dataValue.toUpperCase());
		console.table(rs.modules.Debugger.dataArray[dataValue]);
	});
};

/* run as needed */

if (rs.modules.Debugger.init)
{
	rs.modules.Debugger.process();
}
