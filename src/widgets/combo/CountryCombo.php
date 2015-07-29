<?php

/*
 * Client Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */

namespace hipanel\modules\client\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

/**
 * Class Client.
 */
class CountryCombo extends Combo
{
    /** @inheritdoc */
    public $type = 'client/client';

    /** @inheritdoc */
    public $name = 'login';

    /** @inheritdoc */
    public $url = '/client/client/search';

    /** @inheritdoc */
    public $_return = ['id'];

    /** @inheritdoc */
    public $_rename = ['text' => 'login'];

    /**
     * @var string the type of client
     *             Used by [[getFilter]] to generate filter
     *
     * @see getFilter()
     */
    public $clientType;

    /** @inheritdoc */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'type'  => ['format' => $this->clientType],
            'order' => ['format' => ['loginlike' => 'desc']],
        ]);
    }
}
