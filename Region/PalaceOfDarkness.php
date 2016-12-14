<?php namespace Randomizer\Region;

use Randomizer\Support\LocationCollection;
use Randomizer\Location;
use Randomizer\Region;
use Randomizer\Item;
use Randomizer\World;

class PalaceOfDarkness extends Region {
	protected $name = 'Dark Palace';

	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Location("[dungeon-D1-1F] Dark Palace - big key room", 0xEA37, $this),
			new Location("[dungeon-D1-1F] Dark Palace - jump room [right chest]", 0xEA3A, $this),
			new Location("[dungeon-D1-1F] Dark Palace - jump room [left chest]", 0xEA3D, $this),
			new Location("[dungeon-D1-1F] Dark Palace - big chest", 0xEA40, $this),
			new Location("[dungeon-D1-1F] Dark Palace - compass room", 0xEA43, $this),
			new Location("[dungeon-D1-1F] Dark Palace - spike statue room", 0xEA46, $this),
			new Location("[dungeon-D1-B1] Dark Palace - turtle stalfos room", 0xEA49, $this),
			new Location("[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]", 0xEA4C, $this),
			new Location("[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]", 0xEA4F, $this),
			new Location("[dungeon-D1-1F] Dark Palace - statue push room", 0xEA52, $this),
			new Location("[dungeon-D1-1F] Dark Palace - maze room [top chest]", 0xEA55, $this),
			new Location("[dungeon-D1-1F] Dark Palace - maze room [bottom chest]", 0xEA58, $this),
			new Location("[dungeon-D1-B1] Dark Palace - shooter room", 0xEA5B, $this),
			new Location("Heart Container - Helmasaur King", 0x180153, $this),
		]);
	}

	public function fillBaseItems($my_items) {
		$locations = $this->locations->filter(function($location) {
			return $this->boss_location_in_base || $location->getName() != "Heart Container - Helmasaur King";
		});

		// Big Key, Map, Compass, 6 keys
		$keyable_locations = [
			"[dungeon-D1-1F] Dark Palace - big key room",
			"[dungeon-D1-1F] Dark Palace - jump room [right chest]",
			"[dungeon-D1-1F] Dark Palace - jump room [left chest]",
			"[dungeon-D1-1F] Dark Palace - compass room",
			"[dungeon-D1-1F] Dark Palace - spike statue room",
			"[dungeon-D1-B1] Dark Palace - turtle stalfos room",
			"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]",
			"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]",
			"[dungeon-D1-1F] Dark Palace - statue push room",
			"[dungeon-D1-B1] Dark Palace - shooter room",
		];

		$this->locations["[dungeon-D1-1F] Dark Palace - big key room"]->fill(Item::get("Key"), $my_items);
		while(!$locations->getEmptyLocations()->filter(function($location) use ($keyable_locations) {
			return in_array($location->getName(), $keyable_locations);
		})->random()->fill(Item::get("Key"), $my_items));
		while(!$locations->getEmptyLocations()->filter(function($location) use ($keyable_locations) {
			return in_array($location->getName(), $keyable_locations);
		})->random()->fill(Item::get("Key"), $my_items));
		while(!$locations->getEmptyLocations()->filter(function($location) use ($keyable_locations) {
			return in_array($location->getName(), $keyable_locations);
		})->random()->fill(Item::get("Key"), $my_items));
		while(!$locations->getEmptyLocations()->filter(function($location) use ($keyable_locations) {
			return in_array($location->getName(), $keyable_locations);
		})->random()->fill(Item::get("Key"), $my_items));
		while(!$locations->getEmptyLocations()->filter(function($location) use ($keyable_locations) {
			return in_array($location->getName(), $keyable_locations);
		})->random()->fill(Item::get("Key"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("BigKey"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("Map"), $my_items));

		while(!$locations->getEmptyLocations()->random()->fill(Item::get("Compass"), $my_items));

		return $this;
	}

	public function initNoMajorGlitches() {
		$this->locations["[dungeon-D1-1F] Dark Palace - jump room [right chest]"]->setRequirements(function($locations, $items) {
			return $items->canShootArrows();
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - jump room [left chest]"]->setRequirements(function($locations, $items) {
			return $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
				|| ($items->canShootArrows() && $items->has('Hammer'))
				|| ($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"])
						&& $items->canShootArrows());
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - big chest"]->setRequirements(function($locations, $items) {
			return $items->has('Lamp')
				&& $locations->itemInLocations(Item::get('BigKey'), [
					"[dungeon-D1-1F] Dark Palace - jump room [left chest]",
					"[dungeon-D1-1F] Dark Palace - jump room [right chest]",
					"[dungeon-D1-1F] Dark Palace - compass room",
					"[dungeon-D1-1F] Dark Palace - spike statue room",
					"[dungeon-D1-B1] Dark Palace - turtle stalfos room",
					"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]",
					"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]",
					"[dungeon-D1-1F] Dark Palace - statue push room",
					"[dungeon-D1-1F] Dark Palace - maze room [top chest]",
					"[dungeon-D1-1F] Dark Palace - maze room [bottom chest]",
					"[dungeon-D1-B1] Dark Palace - shooter room",
				])
				&& (($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
				|| ($items->canShootArrows()
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
				|| ($items->canShootArrows() && $items->has('Hammer')
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3)));

		});

		$this->locations["[dungeon-D1-1F] Dark Palace - compass room"]->setRequirements(function($locations, $items) {
			return ($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
			|| ($items->canShootArrows()
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
			|| ($items->canShootArrows() && $items->has('Hammer')
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3));
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - spike statue room"]->setRequirements(function($locations, $items) {
			return ($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
			|| ($items->canShootArrows()
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
			|| ($items->canShootArrows() && $items->has('Hammer')
				&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3));
		});

		$this->locations["[dungeon-D1-B1] Dark Palace - turtle stalfos room"]->setRequirements(function($locations, $items) {
			return $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
				|| ($items->canShootArrows() && $items->has('Hammer'))
				|| ($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"])
						&& $items->canShootArrows());
		});

		$this->locations["[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]"]->setRequirements(function($locations, $items) {
			return $items->has('Lamp')
				&& (($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
				|| ($items->canShootArrows()
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
				|| ($items->canShootArrows() && $items->has('Hammer')
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3)));
		});

		$this->locations["[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]"]->setRequirements(function($locations, $items) {
			return $items->has('Lamp')
				&& (($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
				|| ($items->canShootArrows()
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
				|| ($items->canShootArrows() && $items->has('Hammer')
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3)));
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - statue push room"]->setRequirements(function($locations, $items) {
			return $items->canShootArrows();
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - maze room [top chest]"]->setRequirements(function($locations, $items) {
			return $items->has('Lamp')
				&& (($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
				|| ($items->canShootArrows()
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
				|| ($items->canShootArrows() && $items->has('Hammer')
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3)));
		});

		$this->locations["[dungeon-D1-1F] Dark Palace - maze room [bottom chest]"]->setRequirements(function($locations, $items) {
			return $items->has('Lamp')
				&& (($locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-B1] Dark Palace - shooter room"])
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"]))
				|| ($items->canShootArrows()
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]"]))
				|| ($items->canShootArrows() && $items->has('Hammer')
					&& $locations->itemInLocations(Item::get('Key'), ["[dungeon-D1-1F] Dark Palace - statue push room", "[dungeon-D1-1F] Dark Palace - jump room [right chest]", "[dungeon-D1-1F] Dark Palace - jump room [left chest]", "[dungeon-D1-B1] Dark Palace - turtle stalfos room"], 3)));
		});

		$this->locations["[dungeon-D1-B1] Dark Palace - shooter room"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["Heart Container - Helmasaur King"]->setRequirements(function($locations, $items) {
			return $locations->itemInLocations(Item::get('Key'), [
				"[dungeon-D1-1F] Dark Palace - big key room",
				"[dungeon-D1-1F] Dark Palace - jump room [left chest]",
				"[dungeon-D1-1F] Dark Palace - jump room [right chest]",
				"[dungeon-D1-1F] Dark Palace - big chest",
				"[dungeon-D1-1F] Dark Palace - compass room",
				"[dungeon-D1-1F] Dark Palace - spike statue room",
				"[dungeon-D1-B1] Dark Palace - turtle stalfos room",
				"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]",
				"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]",
				"[dungeon-D1-1F] Dark Palace - statue push room",
				"[dungeon-D1-B1] Dark Palace - shooter room",
			], 6)
			&& $locations->itemInLocations(Item::get('BigKey'), [
				"[dungeon-D1-1F] Dark Palace - jump room [left chest]",
				"[dungeon-D1-1F] Dark Palace - jump room [right chest]",
				"[dungeon-D1-1F] Dark Palace - compass room",
				"[dungeon-D1-1F] Dark Palace - spike statue room",
				"[dungeon-D1-B1] Dark Palace - turtle stalfos room",
				"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [left chest]",
				"[dungeon-D1-B1] Dark Palace - room leading to Helmasaur [right chest]",
				"[dungeon-D1-1F] Dark Palace - statue push room",
				"[dungeon-D1-1F] Dark Palace - maze room [top chest]",
				"[dungeon-D1-1F] Dark Palace - maze room [bottom chest]",
				"[dungeon-D1-B1] Dark Palace - shooter room",
			])
			&& $items->has('Hammer') && $items->has('Lamp') && $items->canShootArrows();
		});

		$this->can_complete = function($locations, $items) {
			return $this->canEnter($locations, $items) && $items->has('Hammer') && $items->has('Lamp') && $items->canShootArrows();
		};

		$this->can_enter = function($locations, $items) {
			return $items->has('MoonPearl') && $items->canAccessPyramid();
		};

		return $this;
	}
}