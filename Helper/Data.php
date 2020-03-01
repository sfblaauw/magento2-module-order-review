<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /*
     * Configuration
     */
    const XML_PATH_DAYS = 'review/general/days';

    /**
     * Returns the number of days.
     *
     * @return int
     */
    public function getDays(): int
    {
        return (int) $this->scopeConfig->getValue(self::XML_PATH_DAYS, ScopeInterface::SCOPE_STORE);
    }
}
