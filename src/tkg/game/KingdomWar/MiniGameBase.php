<?php

namespace tkg\game\KingdomWar;


abstract class MiniGameBase {		
	protected $plugin;
	public function __construct(KWPlugIn $plugin) {
		if($plugin === null){
			throw new \InvalidStateException("plugin may not be null");
		}
		$this->plugin = $plugin;
	}
	
	protected function getManager() {
		return $this->plugin->kwManager;
	}
	protected function getPlugin() {
		return $this->plugin;
	}
	protected function getMsg($key) {
		return $this->plugin->kwMessages->getMessageByKey ( $key );
	}
	protected function getSetup() {
		return $this->plugin->kwSetup;
	}
	protected function getBuilder() {
		return $this->plugin->kwBuilder;
	}
	
	protected function getGameKit() {
		return $this->plugin->kwGameKit;
	}
	
	protected function getLog() {
		return $this->plugin->getLogger();
	}
	
	protected function log($msg) {
		return $this->plugin->getLogger()->info($msg);
	}
	
	protected function getConfig($key, $defaultValue=null) {
		return $this->plugin->getConfig ()->get ( $key, $defaultValue);
	}
	
	protected function setConfig($key, $value) {
		return $this->plugin->getConfig ()->set ( $key, $value );
	}
}