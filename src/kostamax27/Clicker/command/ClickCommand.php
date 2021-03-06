<?php

declare(strict_types=1);

namespace kostamax27\Clicker\command;

use kostamax27\Clicker\Main;
use kostamax27\Clicker\form\StatsForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ClickCommand extends Command {
	
	private $main;
	
	public function __construct(Main $main, string $name, string $description, string $permission) {
		$this->main = $main;
		parent::__construct($name, $description);
		$this->setPermission($permission);
	}
	
	public function execute(CommandSender $sender, string $label, array $args) {
		if(!$this->testPermission($sender)) return;
		if(!$sender instanceof Player) return $sender->sendMessage("§cUse command in game!");
		$sender->sendForm(new StatsForm($sender->getName(), $this->main->config));
	}
}
