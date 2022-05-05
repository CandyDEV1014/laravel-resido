<?php

return [
    'name'     => 'Subscriber',
    'settings' => [
        'email'             => [
            'templates' => [
                'title'       => 'Newsletter',
                'description' => 'Config newsletter email templates',
                'to_admin'    => [
                    'title'       => 'Email send to admin',
                    'description' => 'Template for sending email to admin',
                ],
                'to_user'     => [
                    'title'       => 'Email send to user',
                    'description' => 'Template for sending email to subscriber',
                ],
            ],
        ],
        'title'             => 'Newsletter',
        'description'       => 'Settings for newsletter',
        'mailchimp_api_key' => 'Mailchimp API Key',
        'mailchimp_list_id' => 'Mailchimp List ID',
        'mailchimp_list' => 'Mailchimp List',
        'sendgrid_api_key'  => 'Sendgrid API Key',
        'sendgrid_list_id'  => 'Sendgrid List ID',
        'sendgrid_list'     => 'Sendgrid List',
    ],
    'statuses' => [
        'subscribed'   => 'Subscribed',
        'unsubscribed' => 'Unsubscribed',
    ],
    'email_for_sub' => 'Email For Subscriber',
    'email_send' => 'Send Email'
];
