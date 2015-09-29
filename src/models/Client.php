<?php

/*
 * Client Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */

namespace hipanel\modules\client\models;

use hipanel\helpers\StringHelper;
use hipanel\validators\IpValidator;
use Yii;

class Client extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['id', 'seller_id', 'state_id', 'type_id', 'tariff_id', 'profile_id'], 'integer'],
            [['login', 'seller', 'state', 'type', 'tariff', 'profile'], 'safe'],
            [['state_label', 'type_label'], 'safe'],
            [['balance', 'credit'], 'number'],
            [['count', 'confirm_url', 'language', 'comment', 'name', 'contact', 'currency'], 'safe'],
            [['create_time', 'update_time'], 'date'],
            [['id', 'credit'], 'required', 'on' => 'set-credit'],
            [['id', 'type', 'comment'], 'required', 'on' => ['set-block', 'enable-block', 'disable-block']],
            [['id', 'language'], 'required', 'on' => 'set-language'],
            [['id', 'seller_id'], 'required', 'on' => 'set-seller'],

            [['password', 'client', 'seller_id', 'email'], 'required', 'on' => ['create', 'update']],
            [['email'], 'email', 'on' => ['create', 'update']],

            // Ticket settings
            [['ticket_emails'], 'string', 'max' => 128, 'on' => 'ticket-settings'],
            [['ticket_emails'], 'email', 'on' => 'ticket-settings'],
            [['send_message_text'], 'boolean', 'on' => 'ticket-settings'],

            // Domain settings
            [['nss'], 'string', 'max' => 256, 'on' => 'domain-settings'],
            [['autorenewal', 'whois_protected'], 'boolean', 'on' => 'domain-settings'],

            // Mailings
            [[
                'notify_important_actions',
                'domain_registration',
                'send_expires_when_autorenewed',
                'newsletters',
                'commercial',
            ], 'boolean', 'on' => ['mailing-settings']],

            // IP address restrictions
            [['allowed_ips', 'sshftp_ips'], 'filter', 'filter' => function($value) {
                if (!is_array($value)) {
                    return (mb_strlen($value) > 0 ) ? StringHelper::mexplode($value) : true;
                } else {
                    return $value;
                }
            }, 'on' => ['ip-restrictions']],
            [['allowed_ips', 'sshftp_ips'], 'each', 'rule' => [IpValidator::className()], 'on' => ['ip-restrictions']],

            // Change password
            [['cpassword', 'password', 'repassword'], 'required', 'on' => ['change-password']],
            [['repassword'], 'compare', 'compareAttribute' => 'password', 'on' => ['change-password']],

        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'client_like' => Yii::t('app', 'Client'),
            'seller_like' => Yii::t('app', 'Reseller'),
            'create_time' => Yii::t('app', 'Registered'),
            'update_time' => Yii::t('app', 'Last update'),

            'ticket_emails' => Yii::t('app', 'Email for tickets'),
            'send_message_text' => Yii::t('app', 'Send message text'),

            'allowed_ips' => Yii::t('app', 'Allowed IPs for panel login'),
            'sshftp_ips' => Yii::t('app', 'Default allowed IPs for SSH/FTP accounts'),

            'cpassword' => Yii::t('app', 'Current password'),
            'password' => Yii::t('app', 'New password'),
            'repassword' => Yii::t('app', 'Confirm password'),

        ]);
    }

    public static function canBeSelf($model)
    {
        return Yii::$app->user->is($model->id) || (!Yii::$app->user->can('resell') && Yii::$app->user->can('support') && Yii::$app->user->identity->seller_id == $model->id);
    }
}
