<?php
namespace Redaxscript;

/**
 * parent class to generate a flash message
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Messenger
 * @author JimMorrison723
 */

class Messenger
{
    /**
     * instance of the messenger class
     *
     * @var array
     */

    protected $_messenger;


    /**
     * message title
     *
     * @var string
     */

    protected $_title;

    /**
     * message string
     *
     * @var array
     */

    protected $_message;

    /**
     * redirect link
     *
     * @var array
     */

    protected $_action = array();

    /**
     * options array
     *
     * @var array
     */

    protected $_options = array();

    /**
     * constructor of the class
     *
     * @since 3.0.0
     *
     */

    public function __construct()
    {

    }

    /**
     * init
     *
     * @since 3.0.0
     *
     */

    public function init()
    {

    }

    /**
     * setAction
     *
     * @since 3.0.0
     * @param string $name action name
     * @param string $route action route
     */

    public function setAction($name, $route)
    {
        if (!empty($name))
            $this->_action = [
                "action" => $name,
                "route" => $route,
            ];
    }

    /**
     * success message
     *
     * @since 3.0.0
     *
     * @param array $msg message text
     * @param string $title message title
     * @return string
     */

    public function success($msg = null, $title = null)
    {
        return $this->render($msg, "success", $title);
    }

    /**
     * warning message
     *
     * @since 3.0.0
     *
     * @param array $msg message text
     * @param string $title message title
     * @return string
     */

    public function warning($msg = null, $title = null)
    {
        return $this->render($msg, "warning", $title);
    }

    /**
     * error message
     *
     * @since 3.0.0
     *
     * @param array $msg message text
     * @param string $title message title
     * @return string
     */

    public function error($msg = null, $title = null)
    {
        return $this->render($msg, "error", $title);
    }

    /**
     * info message
     *
     * @since 3.0.0
     *
     * @param array $msg message text
     * @param string $title message title
     * @return string
     */

    public function info($msg = null, $title = null)
    {
        return $this->render($msg, "info", $title);
    }

    /**
     * redirect user
     *
     * @since 3.0.0
     *
     */

    public function redirect()
    {

    }

    /**
     * render
     *
     * @since 3.0.0
     *
     * @param array $messageData
     * @param string $type
     * @param string $title
     * @return string
     */

    public function render($messageData = null, $type = null, $title = null)
    {
        $messageData ?: $this->_message;
        $type ?: $this->_message;

        $output = Hook::trigger('messageStart');

        if ($title != null)
        {
            $headingElement = new Html\Element();
            $headingElement->init('h2', array(
                'class' => 'rs-title-note rs-note-' . $type
            ));
            $headingElement->text($title);
            $output .= $headingElement->render();
        }

        $divElement = new Html\Element();
        $divElement->init('div', array(
            'class' => 'rs-box-note rs-note-' . $type
        ));

        /* Put messageData in a list */
        if (is_array($messageData))
        {

            $itemElement = new Html\Element();
            $itemElement->init('li');
            $listElement = new Html\Element();
            $listElement->init('ul', array(
                'class' => $this->_options['className']['list']
            ));
            $outputItem = null;

            /* collect item output */

            foreach ($messageData as $value)
            {
                $outputItem .= '<li>' . $value . '</li>';
            }
            $divElement->html($listElement->html($outputItem));

        }

        /* If just one message, no need for list */

        else
        {
            $divElement->text($messageData);
        }

        $output .= $divElement->render();

        /* Place */
        if (!empty($this->_action))
        {
            $anchorElement = new Html\Element();
            $anchorElement->init('a');
            $anchorElement->attr('href', $this->_action['route'])->text($this->_action['action']);

            $output .= $anchorElement->render();
        }
        $output .= Hook::trigger('messageEnd');

        return $output;
    }
}