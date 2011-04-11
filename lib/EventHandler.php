<?php

class EventHandler
{
	private $conf;

	function __construct()
	{
		$this->conf = new Conf();
	}

	function runEvent($type, $event)
	{
        global $polling;
        if(!$polling) { // avoids issues while loading pages.
            return;
        }

        $widgets = WidgetWrapper::getInstance(false);

        $widgets->iterate('runEvents', array(
                              array(
                                  'type' => 'allEvents',
                                  'data' => $event,
                                  )));
        $widgets->iterate('runEvents', array(
                              array(
                                  'type' => $type,
                                  'data' => $event,
                                  )));
        
        // Outputting any RPC calls.
        MovimRPC::commit();
	}
}

?>
