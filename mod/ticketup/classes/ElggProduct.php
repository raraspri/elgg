<?php
/**
 * Extended class to override the time_created
 */
class ElggProduct extends ElggObject {
	/**
	 * Nombre del prodcuto
	 * @var string
	 * $name
	 */

	/**
	 * Numero de unidades
	 * @var string
	 * $quantity
	 */

	/**
	 * Precio por unidad
	 * @var double
	 * $price
	 */

	/**
	 * Set subtype to ticket.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "product";
	}

    /**
     * Gets the Nombre del prodcuto.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Nombre del prodcuto.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Numero de unidades.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Sets the Numero de unidades.
     *
     * @param string $quantity the quantity
     *
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Gets the Precio por unidad.
     *
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the Precio por unidad.
     *
     * @param double $price the price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }


    /**
     * Gets the IdTicket
     *
     * @return int
     */
    public function getIdTicket()
    {
        return $this->IdTicket;
    }

    /**
     * Sets the IdTicket
     *
     * @param int $IdTicket the IdTicket
     *
     * @return self
     */
    public function setIdTicket($IdTicket)
    {
        $this->IdTicket = $IdTicket;

        return $this;
    }
}