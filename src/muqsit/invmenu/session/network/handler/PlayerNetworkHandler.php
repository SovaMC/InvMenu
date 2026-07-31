<?php

declare(strict_types=1);

namespace muqsit\invmenu\session\network\handler;

use Closure;
use muqsit\invmenu\session\network\NetworkStackLatencyEntry;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

interface PlayerNetworkHandler{

	/**
	 * Clients below this protocol do not report a packet violation for excess ContainerOpenPackets, which leaves the
	 * server without any way of telling whether a window was opened.
	 */
	public const MIN_PROTOCOL_WINDOW_ACKNOWLEDGEMENT = ProtocolInfo::PROTOCOL_1_20_0;

	public function createNetworkStackLatencyEntry(Closure $then, int $protocolId) : NetworkStackLatencyEntry;

	/**
	 * Whether the client acknowledges every excess ContainerOpenPacket it receives with a packet violation warning.
	 * Windows sent to clients that do not are confirmed by a network stack latency round trip instead.
	 */
	public function supportsWindowAcknowledgement(int $protocolId) : bool;
}
