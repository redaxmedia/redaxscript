rs.modules.Experiments.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.Experiments.config,
		...config
	};
	const variation = window.cxApi.chooseVariation();

	/* run action */

	if (typeof CONFIG.action[variation] === 'function')
	{
		CONFIG.action[variation]();
	}
};

/* run as needed */

if (rs.modules.Experiments.init && rs.modules.Experiments.dependency)
{
	rs.modules.Experiments.execute(rs.modules.Experiments.config);
}
