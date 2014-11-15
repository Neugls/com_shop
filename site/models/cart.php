<?php
/**
 * Shop Model
 * 
 * @package		Shop
 * @subpackage	Component
 * @license		GNU/GPL
 */

// CHECK TO ENSURE THIS FILE IS INCLUDED IN JOOMLA!
defined('_JEXEC') or die();

// REQUIRE THE BASE MODEL
jimport( 'joomla.application.component.model' );

class ShopModelCart extends JModelLegacy
{
 	function __construct(){
		parent::__construct();
		$user = JFactory::getUser();
		$this->setState('levels', implode(',', array_unique($user->getAuthorisedViewLevels())));
  	}

	/**
	 * Method to add a product and selected options to a cart.
	 *
	 * @return  boolean    True on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function addToCart()
	{
		$app = JFactory::getApplication();
		$data = $app->input->get('jform', array(), 'array');
		$db = $this->getDbo();
		$sql = $db->getQuery(true);
		$cart = $this->getTable();
		$price_field = "`product_price`";
		$product_id = (int)$data['product_id'];
		JArrayHelper::toInteger($data['options']);
		$option_id = implode(",", $data['options']);
		// CHECK FOR AN ITEM ALREADY IN CART
		$sql->select("`item_id`");
		$sql->from("`#__shop_cart_items`");
		$sql->where("`item_sku` = ".$db->quote($data['product_sku']));
		$sql->where("`cart_id` = {$cart->cart_id}");
		$db->setQuery($sql);
		if($item_id = $db->loadResult()){
		    // IF ONE EXISTS, INCREMENT THE QUANTITY
		    $sql->clear();
		    $sql->update("`#__shop_cart_items`");
		    $sql->set("`item_quantity` = (`item_quantity` + 1)");
		    $sql->where("`item_id` = {$item_id}");
		    $db->setQuery($sql);
		    $db->execute();
		}else{
		    // IF NOT, ADD ONE TO THE DATABASE
		    $sql->clear();
		    $sql->insert($db->quoteName('#__shop_cart_items'));
		    $sql->columns("`item_quantity`, `item_sku`, `product_id`, `cart_id`");
		    $sql->values("1, ".$db->quote($data['product_sku']).", {$product_id}, {$cart->cart_id}");
		    $db->setQuery($sql);
		    $db->execute();
		    $item_id = $db->insertid();
		    $sql->clear();
		    $sql->insert($db->quoteName('#__shop_item_options'));
		    $sql->columns("`item_id`, `option_id`");
		    foreach($data['options'] as $key => $value){
		        if( $value ) $sql->values($item_id.",".$value);
		    }
		    $db->setQuery($sql);
		    $db->execute();
		}
		// UPDATE PRODUCT PRICING AND WEIGHT ON ALL CART ITEMS
		$this->updateCart();
		return true;
	}

	/**
	 * Method to update a product quantity for an item in the cart.
	 *
	 * @return  boolean    True on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function updateItem()
	{
	    $app = JFactory::getApplication();
	    $item_id = $app->input->get('id', 0, 'uint');
	    $item_quantity = $app->input->get('item_quantity', 0, 'int');
	    if($item_id && $item_quantity){
	        $db = $this->getDbo();
	        $sql = $db->getQuery(true);
	        $sql->select("*");
	        $sql->from("`#__shop_cart_items`");
	        $sql->where("`item_id` = {$item_id}");
	        $db->setQuery($sql);
	        $data = $db->loadObject();
	        $qty = ( $data->item_quantity + $item_quantity );
	        if( $qty <= 0){
	            return $this->removeItem();
	        }
	        $sql->clear();
	        $sql->update("`#__shop_cart_items`");
	        $sql->set("`item_quantity` = {$qty}");
	        $sql->where("`item_id` = {$item_id}");
	        $db->setQuery($sql);
	        $db->execute();
	        return true;
	    }else{
	        $this->setError(JText::_('COM_SHOP_MSG_ERROR_ID_OR_QTY'));
	    }
	}

	/**
	 * Method to remove a product and selected options from a cart.
	 *
	 * @return  boolean    True on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function removeItem()
	{
	    $app = JFactory::getApplication();
	    $item_id = $app->input->get('id', 0, 'uint');
	    if($item_id){
	        $db = $this->getDbo();
	        $sql = $db->getQuery(true);
	        $sql->delete("`#__shop_cart_items`");
	        $sql->where("`item_id` = {$item_id}");
	        $db->setQuery($sql);
	        $db->execute();
	        $sql->clear();
	        $sql->delete("`#__shop_item_options`");
	        $sql->where("`item_id` = {$item_id}");
	        $db->setQuery($sql);
	        $db->execute();
	        return true;
	    }else{
	        $this->setError(JText::_('COM_SHOP_MSG_ERROR_ID_NOT_GIVEN'));
	    }
	}

	/**
	 * Method to get a single record.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getData()
    {
    	$cart = $this->getTable();
    	$data = $cart->getProperties();
    	$obj = JArrayHelper::toObject($data);
    	return $obj;
    }

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getList()
    {
        $this->updateCart();
    	$levels	= $this->getState('levels');
    	$sql	= $this->_db->getQuery(true);
    	$cart   = $this->getTable();
    	
    	$sql->select("i.*, p.`product_name`, p.`params`");
    	$sql->select("CONCAT_WS(':', p.product_id, p.product_alias) AS product_slug");
    	$sql->select("(`item_quantity` * `item_price`) AS `line_price`");
    	$sql->from("`#__shop_cart_items` i");
    	$sql->join("left", "`#__shop_products` p USING(`product_id`)");
    	$sql->where("`cart_id` = {$cart->cart_id}");
		$this->_db->setQuery($sql);
		$this->_data = $this->_db->loadObjectList();
		
		return $this->_data;
    }

	/**
	 * Method to get a single record.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getOrderData()
    {
    	$app = JFactory::getApplication();
    	$id = $app->input->get('id', 0, 'uint');
    	$cart = $this->getTable();
    	$cart->load($id);
    	$data = $cart->getProperties();
    	$obj = JArrayHelper::toObject($data);
    	return $obj;
    }

	/**
	 * Method to get an array of data items from a specified cart.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.0
	 */
    public function getOrderItems()
    {
    	$app	= JFactory::getApplication();
    	$id		= $app->input->get('id', 0, 'uint');
    	$sql	= $this->_db->getQuery(true);
    	
    	$sql->select("i.*, p.`product_name`, p.`params`");
    	$sql->select("CONCAT_WS(':', p.product_id, p.product_alias) AS product_slug");
    	$sql->select("(`item_quantity` * `item_price`) AS `line_price`");
    	$sql->from("`#__shop_cart_items` i");
    	$sql->join("left", "`#__shop_products` p USING(`product_id`)");
    	$sql->where("`cart_id` = {$id}");
		$this->_db->setQuery($sql);
		$this->_data = $this->_db->loadObjectList();
		
		return $this->_data;
    }

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  Int cart_id on success, boolean false on failure.
	 *
	 * @since   1.0
	 */
	public function getPayPalData()
	{
		$paypal = array(
			'custom' => 'uint',
			'payer_id' => 'cmd',
			'payer_email' => 'string',
			'payer_status' => 'string',
			'tax' => 'float',
			'first_name' => 'string',
			'last_name' => 'string',
			'address_name' => 'string',
			'address_street' => 'string',
			'address_city' => 'string',
			'address_state' => 'cmd',
			'address_zip' => 'cmd',
			'mc_shipping' => 'float',
			'mc_handling' => 'float',
			'payment_fee' => 'float',
			'payment_gross' => 'float',
			'payment_status' => 'string',
			'txn_id' => 'cmd'
		);
		$app = JFactory::getApplication();
		$data = $app->input->getArray($paypal);
		$db = $this->getDbo();
		$sql = $db->getQuery(true);
		$cart = $this->getTable();
		$customer = $this->getTable('Customers');
		$utc = new DateTimeZone("UTC");
		$now = new DateTime( "now", $utc );
		// LOAD THE CART
		if($cart->cart_id != $data['custom']) $cart->load($data['custom']);
		// FIND THE CUSTOMER
		$sql->select("`customer_id`");
		$sql->from("`#__shop_customers`");
		$sql->where("`customer_email` = ".$db->quote($data['payer_email']));
		$db->setQuery($sql);
		if(!$customer_id = $db->loadResult()){
			// IF CUSTOMER NOT FOUND CREATE ONE
			$customer->save(array(
				'customer_first' => $data['first_name'],
				'customer_last' => $data['last_name'],
				'customer_session' => $cart->cart_session,
				'customer_email' => $data['payer_email']
			));
			$customer_id = $customer->customer_id;
		}else{
			$customer->load($customer_id);
		}
		$cart->customer_id = $customer_id;
		$cart->cart_status = 1;
		$cart->cart_checkout = $now->format("Y-m-d H:i:s");
		$cart->cart_total = $data['payment_gross'];
		$cart->cart_tax = $data['tax'];
		$cart->cart_shipping = $data['mc_shipping'] + $data['mc_handling'];
		$cart->store();
		
		return $cart->cart_id;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  bool  True on success, false on failure.
	 *
	 * @since   1.0
	 */
	protected function updateCart()
	{
		// UPDATE PRODUCT PRICING AND WEIGHT ON ALL CART ITEMS
		$db = $this->getDbo();
		$sql = $db->getQuery(true);
		$cart = $this->getTable();
		$sub = $db->getQuery(true);
		$sub->select("IF(SUM(`option_price`) IS NULL, `product_price`, `product_price` + SUM(`option_price`)) AS `final_price`");
		$sub->select("IF(SUM(`option_weight`) IS NULL, `product_weight`, `product_weight` + SUM(`option_weight`)) AS `final_weight`");
		$sub->select("`item_id`");
		$sub->from("`#__shop_products`");
		$sub->join("left", "`#__shop_cart_items` USING(`product_id`)");
		$sub->join("left", "`#__shop_item_options` USING(`item_id`)");
		$sub->join("left", "`#__shop_options` USING(`option_id`)");
		$sub->where("`cart_id` = {$cart->cart_id}");
		$sub->group("`item_id`");
		
		$sql->clear();
		$sql->update("`#__shop_cart_items` i");
		$sql->join("inner", "(".$sub.") o USING(`item_id`)");
		$sql->set("i.`item_price` = o.`final_price`, i.`item_weight` = o.`final_weight`");
		$sql->where("i.`cart_id` = {$cart->cart_id}");
		
		$db->setQuery($sql);
		$db->execute();
		// UPDATE THE CART SUBTOTAL
		$sql->clear();
		$sql->select("SUM(`item_quantity` * `item_price`)");
		$sql->from("`#__shop_cart_items`");
		$sql->where("`cart_id` = {$cart->cart_id}");
		$db->setQuery($sql);
		$subtotal = $db->loadResult();
		$cart->cart_subtotal = $subtotal;
		$cart->store();
		
		return true;
	}
}
