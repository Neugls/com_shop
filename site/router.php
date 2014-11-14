<?php
/**
 * SEO Router
 * 
 * @package		Shop
 * @subpackage	Component
 * @license		GNU/GPL
 */

// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

function ShopBuildRoute(&$query){
	$segments = array();
	if(!empty($query['view'])){
		$segments[] = $query['view'];
		unset($query['view']);
	}
	if(!empty($query['layout'])){
		$segments[] = $query['layout'];
		unset($query['layout']);
	}
	if(!empty($query['id'])){
		$segments[] = $query['id'];
		unset($query['id']);
	}
	if(empty($query['Itemid'])){
		$params = JComponentHelper::getParams('com_shop');
		$query['Itemid'] = $params->get('params.default_id');
	}
	return $segments;
}

function ShopParseRoute($segments){
	$query	= array();
	$query['view'] = $segments[0];
	if(isset($segments[1])){
		$query['layout'] = $segments[1];
	}
	if(isset($segments[2])){
		$parts = explode(":", $segments[2]);
		$query['id'] = array_shift($parts);
	}

	return $query;
}
