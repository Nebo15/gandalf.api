<?php

namespace App\Services;

use App\Models\User;
use App\Models\Decision;

class Intercom extends BaseEvents
{
    private $intercom;

    public function __construct(\Nebo15\LumenIntercom\Intercom $intercom)
    {
        $this->intercom = $intercom;
    }

    public function userCreateOrUpdate(User $user)
    {
        if (false == env('INTERCOM_ENABLED')) {
            return false;
        }
        $user_data = [
            'user_id' => $user->getId(),
            'email' => $user->email,
            'last_request_at' => time(),
            'custom_attributes' => [
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ],
        ];
        $this->intercom->updateUser($user_data, true);
    }

    public function decisionMake(Decision $decision, array $user_ids)
    {
        if (false == env('INTERCOM_ENABLED')) {
            return false;
        }
        $table_id = strval($decision->table['_id']);
        $variant_id = strval($decision->table['variant']['_id']);
        $meta = [
            'decision_id' => strval($decision->_id),
            'table_id' => $table_id,
            'table_title' => $decision->table['title'],
            'matching_type' => $decision->table['matching_type'],
            'variant_id' => [
                'value' => $variant_id,
                'url' => str_replace(
                    ['{table_id}', '{variant_id}'],
                    [$table_id, $variant_id],
                    config('services.link.admin_variant')
                )
            ],
            'variant_title' => $decision->table['variant']['title'],
        ];

        foreach ($user_ids as $user_id) {
            $this->intercom->createEvent([
                'event_name' => 'decision-made',
                'created_at' => time(),
                'user_id' => $user_id,
                'metadata' => $meta,
            ], true);
        }
    }

    public function generateSecureCode($user_id)
    {
        return hash_hmac('sha256', $user_id, env('INTERCOM_APP_SECRET'));
    }
}
