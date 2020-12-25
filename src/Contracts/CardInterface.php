<?php namespace ANet\Contracts;

use net\authorize\api\contract\v1 as AnetAPI;

interface CardInterface
{
    /**
     * It will set card numbers given by the user
     * @param int $cardNumbers
     * @return $this
     */
    public function setNumbers(int $cardNumbers): self;

    /**
     * It will set cvv numbers
     * @param int $cvvNumbers
     * @return $this
     */
    public function setCVV(int $cvvNumbers): self;

    /**
     * It will allow user to set name on the card
     * @param string $name
     * @return $this
     */
    public function setNameOnCard(string $name): self;

    /**
     * it will allow settings amount of charge in cents
     * @param int $cents
     * @return $this
     */
    public function setAmountInCents(int $cents): self;

    /**
     * it will allow settings amount of charge in dollars
     * @param float $amount
     * @return $this
     */
    public function setAmountInDollars(float $amount): self;

    /**
     * Sets month of expiry of card
     * @param int|String $month
     * @return $this
     */
    public function setExpMonth($month) : self;

    /**
     * It will set year with the format of YYYY
     * @param int $year (in format YYYY e.g. 2043)
     * @return $this
     */
    public function setExpYear(int $year) : self;

    /**
     * it will set the type of credit card
     * @param string $type
     * @return $this
     */
    public function setType(string $type = 'Credit') : self;

    /**
     * It sets brand name of the card like visa, master, etc.
     * @param string $brandName
     * @return $this
     */
    public function setBrand(string $brandName) : self;

    /**
     * If all given information is correct it will try charging the user
     * @return AnetAPI\CreateTransactionResponse
     */
    public function charge(): AnetAPI\CreateTransactionResponse;

    /**
     * It will return brand name if not given then null
     * @return string|null
     */
    public function getBrand() : ?string;

    /**
     * It will return the type of card like Credit, Debit, Gift, returns null if not provided
     * @return string|null
     */
    public function getType() : ?string;

    /**
     * It will return card number if exists otherwise will return null
     * @return string|null
     */
    public function getNumbers() : ?string;

    /**
     * It will return cvv given or null if not given
     * @return int|null
     */
    public function getCVV(): ?int;

    /**
     * It will return name on card if not given then returns null
     * @return string|null
     */
    public function getNameOnCard() : ?string;

    /**
     * It will return amount set in cents otherwise null
     * @return int|null
     */
    public function getAmountInCents() : ?int;

    /**
     * It will return amount in dollars if not set then null
     * @return float|null
     */
    public function getAmountInDollars() : ?float;

    /**
     * It get set exp month or null if not already set
     * @return int|null
     */
    public function getExpMonth() : ?int;

    /**
     * returns year in format YYYY, if not set then null would be returned
     * @return int|null
     */
    public function getExpYear() : ?int;
}
