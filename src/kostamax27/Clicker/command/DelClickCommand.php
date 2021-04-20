<?php

declare(strict_types=1);

namespace kostamax27\Clicker\command;

use kostamax27\Clicker\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class DelClickCommand extends Command
{
	
	private $main;
	
	public function __construct(Main $main, string $name, string $description, string $permission)
	{
		$this->main = $main;
		
		parent::__construct($name, $description);
		$this->setPermission($permission);
	}
	
	public function execute(CommandSender $sender, string $label, array $args)
	{
		if(!$this->testPermission($sender)) return;
		
		if(!$sender instanceof Player) return $sender->sendMessage("§cUse command in game!");
		
		$this->main->getConfig()->remove("clicker_position");
		$this->main->getConfig()->save();
		unset($this->main->config["clicker_position"]);
		$sender->sendMessage("§cYou have§a successfully§c removed the clicker!");
	}
}
