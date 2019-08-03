<?php

namespace HolluwaTosin\Installer;

use Carbon\Carbon;

class PurchaseDetails
{
    /**
     * Details of purchase
     *
     * @var string
     */
    protected $details;

    /**
     * PurchaseDetails constructor.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = json_decode($details);
    }

    /**
     * Get Item Name
     *
     * @return string
     */
    public function itemName()
    {
        return $this->details->item->name;
    }

    /**
     * Get Item ID
     *
     * @return int
     */
    public function itemId()
    {
        return (int) $this->details->item->id;
    }

    /**
     * Get license type
     *
     * @return string
     */
    public function license()
    {
        return $this->details->license;
    }

    /**
     * Get license object
     *
     * @return string
     */
    public function domain()
    {
        return $this->details->domain;
    }

    /**
     * Check if licence is Extended
     *
     * @return bool
     */
    public function isExtendedLicense()
    {
        return ($this->details->license == 'Extended License');
    }

    /**
     * Check if licence is Regular
     *
     * @return bool
     */
    public function isRegularLicense()
    {
        return ($this->details->license == 'Regular License');
    }

    /**
     * Support end date
     *
     * @return Carbon
     */
    public function supportedUntil()
    {
        return Carbon::parse($this->details->supported_until);
    }

    /**
     * Support end date
     *
     * @return Carbon
     */
    public function soldAt()
    {
        return Carbon::parse($this->details->sold_at);
    }
}
