<?php

return [
    'name'        => 'plugins/newsletter::newsletter.settings.email.templates.title',
    'description' => 'plugins/newsletter::newsletter.settings.email.templates.description',
    'templates'   => [
        'subscriber_email' => [
            'title'       => 'plugins/newsletter::newsletter.settings.email.templates.to_user.title',
            'description' => 'plugins/newsletter::newsletter.settings.email.templates.to_user.description',
            'subject'     => '{{ site_title }}: Subscription Confirmed!',
            'can_off'     => true,
        ],
        'admin_email'      => [
            'title'       => 'plugins/newsletter::newsletter.settings.email.templates.to_admin.title',
            'description' => 'plugins/newsletter::newsletter.settings.email.templates.to_admin.description',
            'subject'     => 'New user subscribed your newsletter',
            'can_off'     => true,
        ],
    ],
    'variables'   => [
        'newsletter_name'             => 'Full name of user who subscribe newsletter',
        'newsletter_email'            => 'Email of user who subscribe newsletter',
        'newsletter_unsubscribe_link' => 'Link for unsubscribe newsletter',
    ],
];
