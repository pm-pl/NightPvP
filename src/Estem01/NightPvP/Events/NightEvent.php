<?php

namespace Estem01\NightPvP\Events;

use Estem01\NightPvP\Main;
use pocketmine\world\World;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class NightEvent implements Listener {

    private Main $main;
    private Config $config;

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $main->getConfig();
    }

    public function onPVP(EntityDamageByEntityEvent $event) {
        $damager = $event->getDamager();
        $world = $damager->getWorld()->getFolderName();
        $time = $damager->getWorld()->getTimeOfDay();
        $allowedWorlds = $this->config->get("allowed-worlds", []);

        if($damager instanceof Player && $time >= World::TIME_FULL_NIGHT && $time <= World::TIME_FULL_DAY && in_array($world, $allowedWorlds)) {
            $event->cancel();

            if($this->config->get("error-message-type") == "popup") {
                $damager->sendPopup($this->config->get("error-no-pvp"));
            } else {
                $damager->sendMessage($this->config->get("error-no-pvp"));
            }
        }
    }
}