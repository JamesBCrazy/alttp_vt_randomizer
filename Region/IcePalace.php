<?php namespace Randomizer\Region;

use Randomizer\Item;
use Randomizer\Location;
use Randomizer\Region;
use Randomizer\Support\LocationCollection;
use Randomizer\World;

class IcePalace extends Region {
	protected $name = 'Ice Palace';

	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Location("[dungeon-D5-B1] Ice Palace - Big Key room", 0xE9A4, $this),
			new Location("[dungeon-D5-B1] Ice Palace - compass room", 0xE9D4, $this),
			new Location("[dungeon-D5-B2] Ice Palace - map room", 0xE9DD, $this),
			new Location("[dungeon-D5-B3] Ice Palace - spike room", 0xE9E0, $this),
			new Location("[dungeon-D5-B4] Ice Palace - above Blue Mail room", 0xE995, $this),
			new Location("[dungeon-D5-B5] Ice Palace - b5 up staircase", 0xE9E3, $this),
			new Location("[dungeon-D5-B5] Ice Palace - big chest", 0xE9AA, $this),
			new Location("Heart Container - Kholdstare", 0x180157, $this),
		]);
	}

	public function fillBaseItems($my_items) {
		$locations = $this->locations->filter(function($location) {
			return $this->boss_location_in_base || $location->getName() != "Heart Container - Kholdstare";
		});
		// Big Key, Map, Compass, 2 keys
		while(!$locations->getEmptyLocations()->filter(function($location) {
			return in_array($location->getName(), [
				"[dungeon-D5-B1] Ice Palace - compass room",
				"[dungeon-D5-B4] Ice Palace - above Blue Mail room",
				"[dungeon-D5-B5] Ice Palace - big chest",
				"[dungeon-D5-B3] Ice Palace - spike room",
				"[dungeon-D5-B5] Ice Palace - b5 up staircase",
			]);
		})->random()->fill(Item::get("Key"), $my_items));
		while(!$locations->getEmptyLocations()->filter(function($location) {
			return in_array($location->getName(), [
				"[dungeon-D5-B4] Ice Palace - above Blue Mail room",
				"[dungeon-D5-B1] Ice Palace - Big Key room",
				"[dungeon-D5-B5] Ice Palace - big chest",
				"[dungeon-D5-B2] Ice Palace - map room",
				"[dungeon-D5-B3] Ice Palace - spike room",
				"[dungeon-D5-B5] Ice Palace - b5 up staircase",
			]);
		})->random()->fill(Item::get("Key"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("BigKey"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("Map"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("Compass"), $my_items));

		return $this;
	}

	public function initNoMajorGlitches() {
		$this->locations["[dungeon-D5-B1] Ice Palace - Big Key room"]->setRequirements(function($locations, $items) {
			return $items->has('Hammer') && $items->canLiftRocks();
		});

		$this->locations["[dungeon-D5-B1] Ice Palace - compass room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-D5-B2] Ice Palace - map room"]->setRequirements(function($locations, $items) {
			return $items->has('Hammer') && $items->canLiftRocks();
		});

		$this->locations["[dungeon-D5-B3] Ice Palace - spike room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-D5-B4] Ice Palace - above Blue Mail room"]->setRequirements(function($locations, $items) {
			return $items->canMeltThings();
		});

		$this->locations["[dungeon-D5-B5] Ice Palace - b5 up staircase"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["[dungeon-D5-B5] Ice Palace - big chest"]->setRequirements(function($locations, $items) {
			return $locations->itemInLocations(Item::get('BigKey'), [
					"[dungeon-D5-B1] Ice Palace - compass room",
					"[dungeon-D5-B3] Ice Palace - spike room",
					"[dungeon-D5-B4] Ice Palace - above Blue Mail room",
					"[dungeon-D5-B5] Ice Palace - b5 up staircase",
				])
				|| ($locations->itemInLocations(Item::get('BigKey'), [
						"[dungeon-D5-B1] Ice Palace - Big Key room",
						"[dungeon-D5-B2] Ice Palace - map room",
					])
					&& $items->has('Hammer') && $items->canLiftRocks());
		});

		$this->locations["Heart Container - Kholdstare"]->setRequirements(function($locations, $items) {
			return $locations->itemInLocations(Item::get('BigKey'), [
				"[dungeon-D5-B1] Ice Palace - Big Key room",
				"[dungeon-D5-B1] Ice Palace - compass room",
				"[dungeon-D5-B2] Ice Palace - map room",
				"[dungeon-D5-B3] Ice Palace - spike room",
				"[dungeon-D5-B4] Ice Palace - above Blue Mail room",
				"[dungeon-D5-B5] Ice Palace - b5 up staircase",
			])
			&& $locations->itemInLocations(Item::get('Key'), [
				"[dungeon-D5-B1] Ice Palace - Big Key room",
				"[dungeon-D5-B1] Ice Palace - compass room",
				"[dungeon-D5-B2] Ice Palace - map room",
				"[dungeon-D5-B3] Ice Palace - spike room",
				"[dungeon-D5-B4] Ice Palace - above Blue Mail room",
				"[dungeon-D5-B5] Ice Palace - b5 up staircase",
				"[dungeon-D5-B5] Ice Palace - big chest",
			], 2)
			&& $items->has('Hammer') && $items->canMeltThings();
		});

		$this->can_complete = function($locations, $items) {
			return $this->canEnter($locations, $items) && $items->has('Hammer');
			(($locations->itemInLocations(Item::get('BigKey'), ["[dungeon-D5-B1] Ice Palace - compass room"])
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D5-B4] Ice Palace - above Blue Mail room", "[dungeon-D5-B5] Ice Palace - b5 up staircase"], 2))
			|| ($locations->itemInLocations(Item::get('BigKey'), ["[dungeon-D5-B4] Ice Palace - above Blue Mail room"])
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D5-B1] Ice Palace - compass room", "[dungeon-D5-B5] Ice Palace - b5 up staircase"], 2))
			|| ($locations->itemInLocations(Item::get('BigKey'), ["[dungeon-D5-B5] Ice Palace - b5 up staircase"])
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D5-B1] Ice Palace - compass room", "[dungeon-D5-B4] Ice Palace - above Blue Mail room"], 2)))
			|| ($items->has('Hookshot') && $locations->itemInLocations(Item::get('BigKey'), ["[dungeon-D5-B1] Ice Palace - compass room", "[dungeon-D5-B4] Ice Palace - above Blue Mail room", "[dungeon-D5-B5] Ice Palace - b5 up staircase"]))
			|| ($locations->itemInLocations(Item::get('BigKey'), ["[dungeon-D5-B1] Ice Palace - Big Key room", "[dungeon-D5-B2] Ice Palace - map room", "[dungeon-D5-B3] Ice Palace - spike room", "[dungeon-D5-B5] Ice Palace - big chest"])
			 	&& ($items->has('Hookshot') || $locations->itemInLocations(Item::get('Key'), ["[dungeon-D5-B1] Ice Palace - compass room", "[dungeon-D5-B4] Ice Palace - above Blue Mail room", "[dungeon-D5-B5] Ice Palace - b5 up staircase"])));
		};

		$this->can_enter = function($locations, $items) {
			return $items->has('MoonPearl') && $items->has('TitansMitt') && $items->canMeltThings();
		};

		return $this;
	}
}