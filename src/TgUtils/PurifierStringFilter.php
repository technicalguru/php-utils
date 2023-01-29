<?php

namespace TgUtils;

class PurifierStringFilter extends AbstractStringFilter {

	public static $INSTANCE;

	protected $purifier;

	public function __construct() {
		parent::__construct();
		$config = $this->getConfig();
		$this->purifier = new \HTMLPurifier($config);
	}

	public function filterString($s) {
		return $this->purifier->purify($s);
	}

	protected function getConfig() {
		$config = \HTMLPurifier_Config::createDefault();
		$config->set('HTML.DefinitionID', 'simple');
		$config->set('HTML.DefinitionRev', 1);
		$config->set('HTML.AllowedElements', array('br', 'p', 'div', 'li', 'ol', 'ul', 'i', 'b', 'strong', 'a', 'h4', 'h5','table','tr','td','th'));
		$config->set('HTML.AllowedAttributes', array(
			'a.href', 'a.class', 'a.style', 
			'p.style', 'div.style', 
			'li.style', 'ol.style', 'ul.style', 
			'i.style', 'b.style', 'strong.style', 
			'h4.style', 'h5.style',
			'table.style','table.class','tr.style','td.colspan','td.rowspan','td.style','th.colspan','th.rowspan','th.style','tr.class','td.class',
		));
		return $config;
	}
}
PurifierStringFilter::$INSTANCE = new PurifierStringFilter();

