<?php
// NO DIRECT ACCESS
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableProducts extends JTable
{
	/** @var int Primary Key */
	var $product_id				= null;
	/** @var string Product Name */
	var $product_name			= null;
	/** @var string URL alias */
	var $product_alias			= null;
	/** @var string Product SKU */
	var $product_sku			= null;
	/** @var string Product Description */
	var $product_description	= null;
	/** @var float Product Price */
	var $product_price			= null;
	/** @var string Meta Keywords */
	var $meta_keywords			= null;
	/** @var string Meta Description */
	var $meta_description		= null;
	/** @var int */
	var $ordering				= null;
	/** @var int */
	var $state					= null;
	/** @var string JSON Data */
	var $params					= null;
	/** @var datetime */
	var $publish_up				= null;
	/** @var datetime */
	var $publish_down			= null;
	/** @var int */
	var $checked_out			= null;
	/** @var datetime */
	var $checked_out_time		= null;
	/** @var datetime */
	var $modified				= null;
	/** @var int */
	var $modified_by			= null;
	/** @var datetime */
	var $created				= null;
	/** @var int */
	var $created_by				= null;
	/** @var int */
	var $access					= null;
	/** @var int Category foreign key */
	var $catid					= null;

	/**
	 * Object constructor to set table and key fields.  In most cases this will
	 * be overridden by child classes to explicitly set the table and key fields
	 * for a particular database table.
	 *
	 * @param   string           $table  Name of the table to model.
	 * @param   mixed            $key    Name of the primary key field in the table or array of field names that compose the primary key.
	 * @param   JDatabaseDriver  $db     JDatabaseDriver object.
	 *
	 * @since   1.0
	 */
	public function __construct(& $db){
		parent::__construct('#__shop_products', 'product_id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
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

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @link    http://docs.joomla.org/JTable/check
	 * @since   11.1
	 */
	public function check(){
		// ASSIGN ORDERING IF NECESSARY
		if(is_null($this->ordering)){
			$this->ordering = $this->getNextOrder();
		}
		return true;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/store
	 * @since   1.0
	 */
	public function store($updateNulls = false){
		if(!parent::store($updateNulls)){
			return false;
		}
		$this->reorder();
		return true;
	}
}
