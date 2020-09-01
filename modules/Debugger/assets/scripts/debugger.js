rs.modules.Debugger.process = () =>
{
	Object.keys(rs.modules.Debugger.dataObject).forEach(dataValue =>
	{
		console.info(dataValue.toUpperCase());
		console.table(rs.modules.Debugger.dataObject[dataValue]);
	});
};

/* run as needed */

if (rs.modules.Debugger.init)
{
	rs.modules.Debugger.process();
}
