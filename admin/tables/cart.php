<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableCart extends JTable
{
	/** @var int Primary Key */
	var $cart_id			= null;
	/** @var string Session ID */
	var $cart_session		= null;
	/** @var int Cart Status */
	var $cart_status		= null;
	/** @var datetime Order Date */
	var $cart_checkout		= null;
	/** @var float Cart Subtotal */
	var $cart_subtotal		= null;
	/** @var float Cart Tax */
	var $cart_tax			= null;
	/** @var float Cart Shipping */
	var $cart_shipping		= null;
	/** @var int */
	var $checked_out		= null;
	/** @var datetime */
	var $checked_out_time	= null;
	/** @var datetime Date Modified */
	var $modified			= null;
	/** @var int User ID */
	var $modified_by		= null;
	/** @var datetime Date Created */
	var $created			= null;
	/** @var int User ID */
	var $created_by			= null;
	/** @var int Customer ID */
	var $customer_id		= null;
	/** @var int User ID */
	var $id					= null;
	
	public function __construct(&$db){
		parent::__construct('#__shop_carts', 'cart_id', $db);
		$user = JFactory::getUser();
		$sql = $db->getQuery(true);
		if($user->get('guest') == 1){
		    $session = JFactory::getSession();
		    $session_id = $session->getId();
		    $sql->select("c.`cart_id`");
		    $sql->from("`#__shop_carts` c");
		    $sql->where("( `cart_status` = 0 )");
		    $sql->where("( `cart_session` = '{$session_id}' )");
		    $db->setQuery($sql);
		    if( !$id = $db->loadResult() ){
		        $this->save(array('cart_session' => $session_id));
		    }else{
		        $this->load($id);
		    }
		}else{
		    $user_id = $user->get('id');
		    $sql->select("c.`cart_id`");
		    $sql->from("`#__shop_carts` c");
		    $sql->where("( `cart_status` = 0 )");
		    $sql->where("( `id` = '{$user_id}' )");
		    $db->setQuery($sql);
		    if( !$id = $db->loadResult() ){
		        $this->save(array('id' => $user_id));
		    }else{
		        $this->load($id);
		    }
		}
	}
	
	public function bind($array, $ignore=''){
		if(key_exists('params', $array)){
			if(is_array($array['params'])){
				$registry = new JRegistry();
				$registry->loadArray($array['params']);
				$array['params'] = $registry->toString();
			}
		}
		return parent::bind($array, $ignore);
	}
	
	public function check(){
		return true;
	}
	
	public function store($updateNulls = false){
		$date = JDate::getInstance('now', 'UTC');
		$user = JFactory::getUser();
		if(!$this->cart_id){
			$this->created = $date->toSql(true);
			$this->created_by = $user->get('id');
		}
		$this->modified = $date->toSql(true);
		$this->modified_by = $user->get('id');
		if(!parent::store($updateNulls)){
			return false;
		}
		return true;
	}
}
