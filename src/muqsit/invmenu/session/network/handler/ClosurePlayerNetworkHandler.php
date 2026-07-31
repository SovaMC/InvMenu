<?php

declare(strict_types=1);

namespace muqsit\invmenu\session\network\handler;

use Closure;
use muqsit\invmenu\session\network\NetworkStackLatencyEntry;

final class ClosurePlayerNetworkHandler implements PlayerNetworkHandler{

	/**
	 * @param Closure(Closure, int) : NetworkStackLatencyEntry $creator
	 * @param (Closure(int) : bool)|null $window_acknowledgement_predicate
	 */
	public function __construct(
		readonly private Closure $creator,
		readonly private ?Closure $window_acknowledgement_predicate = null
	){}

	public function createNetworkStackLatencyEntry(Closure $then, int $protocolId) : NetworkStackLatencyEntry{
		return ($this->creator)($then, $protocolId);
	}

	public function supportsWindowAcknowledgement(int $protocolId) : bool{
		return $this->window_acknowledgement_predicate !== null ?
			($this->window_acknowledgement_predicate)($protocolId) :
			$protocolId >= self::MIN_PROTOCOL_WINDOW_ACKNOWLEDGEMENT;
	}
}
