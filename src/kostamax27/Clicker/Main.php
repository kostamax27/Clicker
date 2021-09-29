<?php

declare(strict_types=1);

namespace kostamax27\Clicker;

use kostamax27\Clicker\command\SetClickCommand;
use kostamax27\Clicker\command\DelClickCommand;
use kostamax27\Clicker\command\ClickCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase {
	
	/** @var array */
	public $config;
	
	/** @var Config */
	private $stats;
	
	private static $instance = null;
	
	public function onLoad() : void {
		$commands =
		[
		  new SetClickCommand($this, "setclick", "Install clicker", "setclick.cmd"),
		  new DelClickCommand($this, "delclick", "Remove clicker", "delclick.cmd"),
		  new ClickCommand($this, "click", "Clicker statistics", "click.cmd")
		];
		
		foreach($commands as $command)
		    $this->getServer()->getCommandMap()->register("Clicker", $command);
	}
	
	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->saveDefaultConfig();
		$this->config = $this->getConfig()->getAll();
		$this->stats = new Config($this->getDataFolder() . "stats.json", Config::JSON);
		self::$instance = $this;
	}
	
	public static function getInstance() {
		if(self::$instance === null) self::$instance = $this;
		return self::$instance;
	}
	
	/**
	 * @return array|null
	 * [
	     "level" => (int) $level, - level
	     "xp" => (int) $xp, - XP
	     "total" => (int) $total, - I earned everything on the clicker,
	     "clicks" => (int) $clicks - made total clicks for the entire time.
	 * ]
	 */
	public function getStats(string $player) {
		$player = strtolower($player);
		return $this->stats->exists($player) ? $this->stats->get($player) : null;
	}
	
	/**
	 * @param string $key - available keys: level, xp, total, clicks.
	 */
	public function setStats(string $player, string $key, int $value) : void {
		$player = strtolower($player);
		
		$stats = $this->getStats($player);
		$stats[$key] = $value;
		
		$this->stats->set($player, $stats);
		$this->stats->save();
	}
	
	public function registerPlayer(string $player) : void {
		$this->stats->set(strtolower($player), 
        [
          "level" => 0,
          "xp" => 0,
          "total" => 0,
          "clicks" => 0
        ]);
		$this->stats->save();
	}
}
