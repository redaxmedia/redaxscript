rs.modules.Experiments.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Experiments.optionArray,
		...optionArray
	};
	const variation = window.cxApi.chooseVariation();

	/* run action */

	if (typeof OPTION.action[variation] === 'function')
	{
		OPTION.action[variation]();
	}
};

/* run as needed */

if (rs.modules.Experiments.init && rs.modules.Experiments.dependency)
{
	rs.modules.Experiments.process(rs.modules.Experiments.optionArray);
}
