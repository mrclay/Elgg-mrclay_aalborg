<?php

namespace UFCOE\Elgg;

/**
 * Manage lists of Elgg menu items (during prepare hook) without losing sanity
 */
class MenuList
{
	protected $items = array();
	protected $names = array();

	/**
	 * @param MenuList|array $items
	 */
	public function __construct($items = array())
	{
		if (!$items instanceof MenuList) {
			$items = (array)$items;
		}
		$this->appendList($items);
	}

	/**
	 * @param \ElggMenuItem $item an item
	 * @return \ElggMenuItem
	 */
	public function push(\ElggMenuItem $item)
	{
		$this->remove($item);
		$this->items[] = $item;
		$this->names[] = $item->getName();
		return $item;
	}

	/**
	 * @return MenuList
	 */
	public function removeAll()
	{
		$this->items = $this->names = array();
		return $this;
	}

	/**
	 * @param MenuList|array $items
	 * @return MenuList
	 */
	public function appendList($items)
	{
		if ($items instanceof MenuList) {
			$items = $items->getItems();
		}
		foreach ($items as $item) {
			$this->push($this->normalizeItem($item));
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name)
	{
		return in_array($name, $this->names);
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param null $replacement
	 * @return MenuList
	 */
	public function splice($offset, $length = 0, $replacement = null)
	{
		if ($replacement instanceof \ElggMenuItem) {
			/* @var \ElggMenuItem $replacement */
			$replacementItems = array($replacement);
			$replacementNames = array($replacement->getName());
		} else {
			if (! $replacement instanceof MenuList) {
				$replacement = new MenuList((array) $replacement);
			}
			$replacementItems = $replacement->getItems();
			$replacementNames = $replacement->getNames();
		}
		array_splice($this->items, $offset, $length, $replacementItems);
		array_splice($this->names, $offset, $length, $replacementNames);
		return $this;
	}

	/**
	 * @param int $offset
	 * @param int|null $length
	 * @return MenuList
	 */
	public function slice($offset, $length = null)
	{
		return new MenuList(array_slice($this->items, $offset, $length));
	}

	/**
	 * @param string|int $item1 item name or position
	 * @param string|int $item2 item name or position
	 * @return bool success
	 */
	public function swap($item1, $item2)
	{
		$pos1 = $this->search($item1);
		$pos2 = $this->search($item2);
		if ($pos1 === false || $pos2 === false) {
			return false;
		}
		if ($pos1 !== $pos2) {
			$tempItem = $this->items[$pos1];
			$this->items[$pos1] = $this->items[$pos2];
			$this->items[$pos2] = $tempItem;
			$tempName = $this->names[$pos1];
			$this->names[$pos1] = $this->names[$pos2];
			$this->names[$pos2] = $tempName;
		}
		return true;
	}

	/**
	 * @param string|int|\ElggMenuItem $itemToInsert
	 * @param int $pos
	 * @return bool success
	 */
	public function move($itemToInsert, $pos)
	{
		$removedItem = $this->remove($itemToInsert);
		if ($removedItem) {
			$itemToInsert = $removedItem;
		}
		if ($pos === -1 || $pos === count($this->names)) {
			$this->push($itemToInsert);
		} else {
			if ($pos < 0) {
				$pos += 1;
			}
			$pos = $this->normalizePosition($pos, true);
			$this->splice($pos, 0, $itemToInsert);
		}
		return true;
	}

	/**
	 * Return the position of an item (or false if not found)
	 *
	 * @param string|int|\ElggMenuItem $item
	 * @return mixed false if not found
	 */
	public function search($item)
	{
		if ($item instanceof \ElggMenuItem) {
			/* @var \ElggMenuItem $item */
			$item = $item->getName();
		}
		if (is_string($item)) {
			return array_search($item, $this->names);
		} else {
			return $this->normalizePosition((int) $item);
		}
	}

	/**
	 * @param string|int|\ElggMenuItem $item
	 * @param bool $removeFromList
	 * @return null|\ElggMenuItem
	 */
	public function get($item, $removeFromList = false)
	{
		$pos = $this->search($item);
		if ($pos === false) {
			return null;
		}
		$item = $this->items[$pos];
		if ($removeFromList) {
			unset($this->items[$pos], $this->names[$pos]);
			$this->items = array_values($this->items);
			$this->names = array_values($this->names);
		}
		return $item;
	}

	/**
	 * @param string|int|\ElggMenuItem $item
	 * @return \ElggMenuItem|null
	 */
	public function remove($item)
	{
		return $this->get($item, true);
	}

	/**
	 * @param string|int|\ElggMenuItem $item
	 * @param \ElggMenuItem $replacement
	 * @return bool success
	 */
	public function replace($item, \ElggMenuItem $replacement)
	{
		$pos = $this->search($item);
		if ($pos !== false) {
			$this->set($replacement, $pos);
			return true;
		}
		return false;
	}

	/**
	 * @param \ElggMenuItem $item
	 * @param int $pos
	 * @return bool
	 */
	public function set(\ElggMenuItem $item, $pos)
	{
		$this->remove($item);
		if ($pos != count($this->items)) {
			$pos = $this->normalizePosition($pos);
			if (false === $pos) {
				return false;
			}
		}
		$this->items[$pos] = $item;
		$this->names[$pos] = $item->getName();
		return true;
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return array
	 */
	public function getNames()
	{
		return $this->names;
	}

	/**
	 * Sort by item text
	 */
	public function sort($valueExtractor = 'UFCOE\Elgg\MenuList::extractText')
	{
		if (count($this->items) >= 2) {
			// substitute array of values to be compared (so we can use fast native
			// sort) then use rearranged keys to reassemble the array of objects
			foreach ($this->items as $item) {
				$valuesCompared[] = call_user_func($valueExtractor, $item);
			}
			asort($valuesCompared);
			$items = $this->items;
			$this->removeAll();
			foreach (array_keys($valuesCompared) as $key) {
				$this->push($items[$key]);
			}
		}
	}

	static public function extractText(\ElggMenuItem $item) {
		return $item->getText();
	}

	/**
	 * @param \ElggMenuItem|array $item
	 * @return \ElggMenuItem
	 */
	protected function normalizeItem($item)
	{
		if (!$item instanceof \ElggMenuItem) {
			$item = \ElggMenuItem::factory($item);
		}
		return $item;
	}

	/**
	 * Convert negative offset to positive, optionally limiting return value to a valid offset
	 *
	 * @param int $pos
	 * @param bool $bind always return valid array offset
	 * @return int|bool false if $pos would be out of bounds
	 */
	protected function normalizePosition($pos, $bind = false)
	{
		$count = count($this->items);
		if ($pos < 0) {
			$pos = $count + $pos;
		}
		if ($bind) {
			return min(max(0, $pos), $count - 1);
		} else {
			return ($pos >= 0 && $pos <= ($count - 1))
				? $pos
				: false;
		}
	}
}