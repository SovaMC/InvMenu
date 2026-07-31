<?php

declare(strict_types=1);

namespace muqsit\invmenu\type\util;

use Generator;
use pocketmine\block\tile\Chest;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\world\World;

final class InvMenuTypeHelper{

	public const NETWORK_WORLD_Y_MIN = -64;
	public const NETWORK_WORLD_Y_MAX = 320;

	public const LEGACY_NETWORK_WORLD_Y_MIN = 0;
	public const LEGACY_NETWORK_WORLD_Y_MAX = 255;

	public static function getBehindPositionOffset() : Vector3{
		return new Vector3(0, -2, 0);
	}

	public static function isValidYCoordinate(float $y, int $protocolId = ProtocolInfo::CURRENT_PROTOCOL) : bool{
		// worlds were not extended past the 0-255 range until 1.18 - a block sent outside of the range the client
		// knows about is discarded by it, leaving the graphic invisible and the window unopenable
		return $protocolId >= ProtocolInfo::PROTOCOL_1_18_0 ?
			$y >= self::NETWORK_WORLD_Y_MIN && $y <= self::NETWORK_WORLD_Y_MAX :
			$y >= self::LEGACY_NETWORK_WORLD_Y_MIN && $y <= self::LEGACY_NETWORK_WORLD_Y_MAX;
	}

	/**
	 * @param string $tile_id
	 * @param World $world
	 * @param Vector3 $position
	 * @param list<Facing::DOWN|Facing::UP|Facing::NORTH|Facing::SOUTH|Facing::WEST|Facing::EAST> $sides
	 * @return Generator<Vector3>
	 */
	public static function findConnectedBlocks(string $tile_id, World $world, Vector3 $position, array $sides) : Generator{
		if($tile_id === "Chest"){
			// setting a single chest at the spot of a pairable chest sends the client a double chest
			// https://github.com/Muqsit/InvMenu/issues/207
			foreach($sides as $side){
				$pos = $position->getSide($side);
				$tile = $world->getTileAt($pos->x, $pos->y, $pos->z);
				if($tile instanceof Chest && $tile->getPair() !== null){
					yield $pos;
				}
			}
		}
	}
}