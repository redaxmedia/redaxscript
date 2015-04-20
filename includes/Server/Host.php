<?php
namespace Redaxscript\Server;

/**
 * children class to get host
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

class Host extends ServerAbstract
{
	/**
	 * get the output
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		$output = $this->_request->getServer('HTTP_HOST');
        if (empty($output))
        {
            $output = $this->_request->getServer('SERVER_NAME');

            /* server port */

            $serverPort = $this->_request->getServer('SERVER_PORT');
            if ($serverPort !== 80)
            {
                $output .= ':' . $serverPort;
            }
        }
		return $output;
	}
}
