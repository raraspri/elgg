<?php
/**
 * Extended class to override the time_created
 */
class ElggTicketup extends ElggObject {

	/**
	 * Nombre de la tienda 
	 * @var string
	 * $shop
	 */

	/**
	 * Fecha de la compra
	 * @var string
	 * $date
	 */

	/**
	 * Lista de los productos
	 * @var array
	 * $products
	 */

	/**
	 * Identificador del qr (file) relacionado
	 * @var int
	 * $photo
	 */
	


	/**
	 * Set subtype to ticket.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "ticketup";
	}
	

    /**
     * Gets the Nombre de la tienda.
     *
     * @return string
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Sets the Nombre de la tienda.
     *
     * @param string $shop the shop
     *
     * @return self
     */
    public function setShop($shop)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Gets the Fecha de la compra.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the Fecha de la compra.
     *
     * @param string $date the date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Gets the Lista de los productos.
     *
     * @return array
     */
    public function getProducts()
    {
        $list_guids_products = json_decode($this->products);
        $list_objects_products = array();

        foreach ($list_guids_products as $guid) {
            $product = get_entity($guid);
            if(elgg_instanceof($product, 'object', 'product')){
                $list_objects_products[] = $product;
            }
        }


        return $list_objects_products;
    }

    /**
     * Sets the Lista de los productos.
     *
     * @param array $products the products
     *
     * @return self
     */
    public function setProducts($products)
    {
        $this->products = json_encode($this->getGuidFromListProducts($products));

        return $this;
    }

    /**
     * Gets the Identificador del qr (file) relacionado.
     *
     * @return int
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Sets the Identificador del qr (file) relacionado.
     *
     * @param int $photo the photo
     *
     * @return self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    private function getGuidFromListProducts($list){
        $guids = array();
        foreach ($list as $element) {
            $guids[] = $element->guid;
        }
        return $guids;
    }
}