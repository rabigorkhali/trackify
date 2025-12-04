<?php

$getMethod = 'get';
$postMethod = 'post';
$putMethod = 'put';
$deleteMethod = 'delete';

$homeBaseUrl = '/home';
$userBaseUrl = '/users';
$roleBaseUrl = '/roles';
$configBaseUrl = '/configs';
$pageBaseUrl = '/pages';
$fileManagerUrl = '/file-manager';
$postCategoryUrl = '/post-categories';
$postUrl = '/posts';
$testimonialUrl = '/testimonials';
$teamUrl = '/teams';
$contactUsUrl = '/contact-us';
$eventUrl = '/events';
$menuBaseUrl = '/menus';
$monitorUrl = '/monitor';
$redirectionUrl = '/redirections';
$activityUrl = '/activities';
$partnerUrl = '/partners';
$sliderUrl = '/sliders';
$newsletterSubscriptionUrl = '/newsletter-subscriptions';
$emailUrl = '/emails';
$smsUrl = '/sms';
$projectUrl = '/projects';
$ticketUrl = '/tickets';
$ticketStatusUrl = '/ticket-statuses';
$ticketLabelUrl = '/ticket-labels';

return [
    // routes entered in this array are accessible by any user no matter what role is given
    'permissionGrantedbyDefaultRoutes' => [
        [
            'url' => $homeBaseUrl,
            'method' => $getMethod,
        ],
        [
            'url' => '/logout',
            'method' => $getMethod,
        ],
        [
            'url' => '/dashboard',
            'method' => $getMethod,
        ],
        [
            'url' => '/profile',
            'method' => $getMethod,
        ],
        [
            'url' => '/profile/*',
            'method' => $putMethod,
        ],
        [
            'url' => '/change-password',
            'method' => $getMethod,
        ],
        [
            'url' => '/change-password',
            'method' => $putMethod,
        ],
        // Notification routes - accessible to all authenticated users
        [
            'url' => '/notifications',
                        'method' => $getMethod,
                ],
                [
            'url' => '/notifications/recent',
                        'method' => $getMethod,
                ],
                [
            'url' => '/notifications/unread-count',
                        'method' => $getMethod,
                    ],
        [
            'url' => '/notifications/*/mark-read',
            'method' => $postMethod,
        ],
        [
            'url' => '/notifications/mark-all-read',
                                    'method' => $postMethod,
        ],
        [
            'url' => '/notifications/*',
                                'method' => $deleteMethod,
        ],
        [
            'url' => '/notifications/clear-read',
                                    'method' => $postMethod,
                                ],
        // Ticket attachment routes - accessible to all authenticated users
        [
            'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*/upload-attachments',
                                    'method' => $postMethod,
        ],
        [
            'url' => $ticketUrl.'/*/upload-attachments',
                                    'method' => $postMethod,
        ],
        [
            'url' => '/ticket-attachments/*',
                                'method' => $deleteMethod,
                            ],
        // Ticket update routes - accessible to all authenticated users
        [
            'url' => '/tickets-update-assignee',
            'method' => $postMethod,
        ],
        [
            'url' => '/tickets-update-status',
            'method' => $postMethod,
        ],
        // Ticket comment routes - accessible to all authenticated users
        [
            'url' => '/ticket-comments',
            'method' => $postMethod,
        ],
        [
            'url' => '/ticket-comments/*',
            'method' => $deleteMethod,
        ],
        // Ticket checklist routes - accessible to all authenticated users
        [
            'url' => '/ticket-checklists',
            'method' => $postMethod,
        ],
        [
            'url' => '/ticket-checklists/*',
            'method' => $putMethod,
        ],
        [
            'url' => '/ticket-checklists/*',
            'method' => $deleteMethod,
        ],
    ],

    // All the routes are accessible by super user by default
    // routes entered in this array are not accessible by super user
    'permissionDeniedToSuperUserRoutes' => [],

    'modules' => [
        // DASHBOARD
        [
            'name' => 'Dashboard',
            'icon' => "<i class='fa fa-home'></i>",
                            'hasSubmodules' => false,
            'route' => $homeBaseUrl,
            'routeIndexName' => 'home.index',
            'routeName' => 'home',
                            'permissions' => [
                                [
                    'name' => 'View Dashboard',
                                    'route' => [
                        'url' => $homeBaseUrl,
                                        'method' => $getMethod,
                            ],
                        ],
                        [
                    'name' => 'Backup Database',
                                    'route' => [
                        'url' => '/backup-database',
                                        'method' => $getMethod,
                            ],
                        ],
                        [
                    'name' => 'Backup Project',
                                    'route' => [
                        'url' => '/backup-project',
                                        'method' => $getMethod,
                            ],
                        ],
                        [
                    'name' => 'Generate Sitemap',
                                    'route' => [
                        'url' => '/generate-sitemap',
                                        'method' => $getMethod,
                    ],
                ],
            ],
        ],

        // CONTENT MANAGEMENT GROUP
        // [
        //     'name' => 'Content Management',
        //     'icon' => "<i class='fa fa-file-text'></i>",
        //     'hasSubmodules' => true,
        //     'routeName' => 'content-management',
        //     'routeIndexNameMultipleSubMenu' => ['events.index', 'testimonials.index', 'partners.index', 'sliders.index'],
        //     'submodules' => [
        //         [
        //             'name' => 'Events',
        //             'icon' => "<i class='fa fa-cube'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $eventUrl,
        //             'routeIndexName' => 'events.index',
        //             'routeName' => 'events',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Event',
        //                     'route' => [
        //                         'url' => $eventUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Event',
        //                     'route' => [
        //                         [
        //                             'url' => $eventUrl . '/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $eventUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Event',
        //                     'route' => [
        //                         [
        //                             'url' => $eventUrl . '/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $eventUrl . '/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Event',
        //                     'route' => [
        //                         'url' => $eventUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete  Event Gallery',
        //                     'route' => [
        //                         'url' => $eventUrl . '/delete-gallery/*',
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'Testimonials',
        //             'icon' => "<i class='fa fa-user-friends'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $testimonialUrl,
        //             'routeIndexName' => 'testimonials.index',
        //             'routeName' => 'testimonials',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Testimonial',
        //                     'route' => [
        //                         'url' => $testimonialUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Testimonial',
        //                     'route' => [
        //                         [
        //                             'url' => $testimonialUrl . '/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $testimonialUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Testimonial',
        //                     'route' => [
        //                         [
        //                             'url' => $testimonialUrl . '/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $testimonialUrl . '/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Testimonial',
        //                     'route' => [
        //                         'url' => $testimonialUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ]
        //             ],
        //         ],
        //         [
        //             'name' => 'Partners',
        //             'icon' => "<i class='fa fa-user-graduate'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $partnerUrl,
        //             'routeIndexName' => 'partners.index',
        //             'routeName' => 'partners',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Partner',
        //                     'route' => [
        //                         'url' => $partnerUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Partner',
        //                     'route' => [
        //                         [
        //                             'url' => $partnerUrl . '/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $partnerUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Partner',
        //                     'route' => [
        //                         [
        //                             'url' => $partnerUrl . '/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $partnerUrl . '/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Partner',
        //                     'route' => [
        //                         'url' => $partnerUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ]
        //             ],
        //         ],
        //         [
        //             'name' => 'Sliders',
        //             'icon' => "<i class='fa fa-solid fa-sliders'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $sliderUrl,
        //             'routeIndexName' => 'sliders.index',
        //             'routeName' => 'sliders',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Slider',
        //                     'route' => [
        //                         'url' => $sliderUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Slider',
        //                     'route' => [
        //                         [
        //                             'url' => $sliderUrl . '/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $sliderUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Slider',
        //                     'route' => [
        //                         [
        //                             'url' => $sliderUrl . '/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $sliderUrl . '/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Slider',
        //                     'route' => [
        //                         'url' => $sliderUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ]
        //             ],
        //         ],
        //     ],
        // ],

        // COMMUNICATION & MARKETING GROUP
        // [
        //     'name' => 'Communication',
        //     'icon' => "<i class='fa fa-bullhorn'></i>",
        //     'hasSubmodules' => true,
        //     'routeName' => 'communication-marketing',
        //     'routeIndexNameMultipleSubMenu' => ['contact-us.index', 'emails.index', 'sms.index', 'newsletter-subscriptions.index'],
        //     'submodules' => [
        //         [
        //             'name' => 'Contact Us',
        //             'icon' => "<i class='fa fa-phone'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $contactUsUrl,
        //             'routeIndexName' => 'contact-us.index',
        //             'routeName' => 'contact-us',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Contact Us',
        //                     'route' => [
        //                         'url' => $contactUsUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Contact Us',
        //                     'route' => [
        //                         'url' => $contactUsUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ]
        //             ],
        //         ],
        //         [
        //             'name' => 'Email Management',
        //             'icon' => "<i class='fa fa-mail-forward'></i>",
        //             'hasSubmodules' => true,
        //             'routeName' => 'email-management',
        //             'routeIndexName' => 'emails.index',
        //             'routeIndexNameMultipleSubMenu' => ['emails.index'], //use for opening sidenav menu only
        //             'permissions' => [
        //                 [
        //                     'name' => 'Access Email Management',
        //                     'route' => [
        //                         'url' => $emailUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //             ],
        //             'submodules' => [
        //                 [
        //                     'name' => 'Emails',
        //                     'icon' => "<i class='fa fa-users'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl,
        //                     'routeIndexName' => 'emails.index',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Emails',
        //                             'route' => [
        //                                 'url' => $emailUrl,
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Create Emails',
        //                             'route' => [
        //                                 [
        //                                     'url' => $emailUrl . '/create',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $emailUrl,
        //                                     'method' => $postMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Edit Emails',
        //                             'route' => [
        //                                 [
        //                                     'url' => $emailUrl . '/*/edit',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $emailUrl . '/*',
        //                                     'method' => $putMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Delete Emails',
        //                             'route' => [
        //                                 'url' => $emailUrl . '/*',
        //                                 'method' => $deleteMethod,
        //                             ],
        //                         ]
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Inbox',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl . '?status=inbox',
        //                     'routeIndexName' => 'emails.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $emailUrl . '?status=inbox',
        //                     'manualIndexNameForActiveTab' => '?status=inbox',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Inbox',
        //                             'route' => [
        //                                 'url' => $emailUrl . '?status=inbox',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Sending',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl . '?status=send-now',
        //                     'routeIndexName' => 'emails.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $emailUrl . '?status=send-now',
        //                     'manualIndexNameForActiveTab' => '?status=send-now',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Send Now',
        //                             'route' => [
        //                                 'url' => $emailUrl . '?status=send-now',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Sent',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl . '?status=sent',
        //                     'routeIndexName' => 'emails.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $emailUrl . '?status=sent',
        //                     'manualIndexNameForActiveTab' => '?status=sent',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Sent',
        //                             'route' => [
        //                                 'url' => $emailUrl . '?status=sent',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Failed',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl . '?status=failed',
        //                     'routeIndexName' => 'emails.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $emailUrl . '?status=failed',
        //                     'manualIndexNameForActiveTab' => '?status=failed',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Failed',
        //                             'route' => [
        //                                 'url' => $emailUrl . '?status=failed',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Draft',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $emailUrl . '?status=draft',
        //                     'routeIndexName' => 'emails.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $emailUrl . '?status=draft',
        //                     'manualIndexNameForActiveTab' => '?status=draft',
        //                     'routeName' => 'emails',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Draft',
        //                             'route' => [
        //                                 'url' => $emailUrl . '?status=draft',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'SMS Management',
        //             'icon' => "<i class='fa fa-sms'></i>",
        //             'hasSubmodules' => true,
        //             'routeName' => 'sms-management',
        //             'routeIndexName' => 'sms.index',
        //             'routeIndexNameMultipleSubMenu' => ['sms.index'], //use for opening sidenav menu only
        //             'permissions' => [
        //                 [
        //                     'name' => 'Access SMS Management',
        //                     'route' => [
        //                         'url' => $smsUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //             ],
        //             'submodules' => [
        //                 [
        //                     'name' => 'SMS',
        //                     'icon' => "<i class='fa fa-sms'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl,
        //                     'routeIndexName' => 'sms.index',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View SMS',
        //                             'route' => [
        //                                 'url' => $smsUrl,
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Create SMS',
        //                             'route' => [
        //                                 [
        //                                     'url' => $smsUrl . '/create',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $smsUrl,
        //                                     'method' => $postMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Edit SMS',
        //                             'route' => [
        //                                 [
        //                                     'url' => $smsUrl . '/*/edit',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $smsUrl . '/*',
        //                                     'method' => $putMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Delete SMS',
        //                             'route' => [
        //                                 'url' => $smsUrl . '/*',
        //                                 'method' => $deleteMethod,
        //                             ],
        //                         ]
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Inbox',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl . '?status=inbox',
        //                     'routeIndexName' => 'sms.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $smsUrl . '?status=inbox',
        //                     'manualIndexNameForActiveTab' => '?status=inbox',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Inbox',
        //                             'route' => [
        //                                 'url' => $smsUrl . '?status=inbox',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Sending',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl . '?status=send-now',
        //                     'routeIndexName' => 'sms.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $smsUrl . '?status=send-now',
        //                     'manualIndexNameForActiveTab' => '?status=send-now',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Send Now',
        //                             'route' => [
        //                                 'url' => $smsUrl . '?status=send-now',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Sent',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl . '?status=sent',
        //                     'routeIndexName' => 'sms.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $smsUrl . '?status=sent',
        //                     'manualIndexNameForActiveTab' => '?status=sent',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Sent',
        //                             'route' => [
        //                                 'url' => $smsUrl . '?status=sent',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Failed',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl . '?status=failed',
        //                     'routeIndexName' => 'sms.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $smsUrl . '?status=failed',
        //                     'manualIndexNameForActiveTab' => '?status=failed',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Failed',
        //                             'route' => [
        //                                 'url' => $smsUrl . '?status=failed',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Draft',
        //                     'icon' => "<i class='fa fa-tags'></i>",
        //                     'hasSubmodules' => false,
        //                     'route' => $smsUrl . '?status=draft',
        //                     'routeIndexName' => 'sms.index',
        //                     'manualIndexName' => rtrim(config('app.url'), '/') . '/'.getSystemPrefix() . $smsUrl . '?status=draft',
        //                     'manualIndexNameForActiveTab' => '?status=draft',
        //                     'routeName' => 'sms',
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Draft',
        //                             'route' => [
        //                                 'url' => $smsUrl . '?status=draft',
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'Newsletter Subscription',
        //             'icon' => "<i class='fa fa-envelope'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $newsletterSubscriptionUrl,
        //             'routeIndexName' => 'newsletter-subscriptions.index',
        //             'routeName' => 'newsletter-subscriptions',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Newsletter Subscription',
        //                     'route' => [
        //                         'url' => $newsletterSubscriptionUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Newsletter Subscription',
        //                     'route' => [
        //                         [
        //                             'url' => $newsletterSubscriptionUrl . '/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $newsletterSubscriptionUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Newsletter Subscription',
        //                     'route' => [
        //                         [
        //                             'url' => $newsletterSubscriptionUrl . '/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $newsletterSubscriptionUrl . '/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Newsletter Subscription',
        //                     'route' => [
        //                         'url' => $newsletterSubscriptionUrl . '/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ]
        //             ],
        //         ],
        //     ],
        // ],

        // USER & ACCESS MANAGEMENT GROUP
        [
            'name' => 'User Management',
            'icon' => "<i class='fa fa-users'></i>",
            'hasSubmodules' => true,
            'routeName' => 'user-access-management',
            'routeIndexNameMultipleSubMenu' => ['users.index', 'roles.index'], // use for opening sidenav menu only
            'submodules' => [
                [
                    'name' => 'Users',
                    'icon' => "<i class='fa fa-user'></i>",
                    'hasSubmodules' => false,
                    'route' => $userBaseUrl,
                    'routeIndexName' => 'users.index',
                    'routeName' => 'users',
                    'permissions' => [
                        [
                            'name' => 'View Users',
                            'route' => [
                                'url' => $userBaseUrl,
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'View User Profile',
                            'route' => [
                                'url' => $userBaseUrl.'/*',
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'Create Users',
                            'route' => [
                                [
                                    'url' => $userBaseUrl.'/create',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $userBaseUrl,
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Users',
                            'route' => [
                                [
                                    'url' => $userBaseUrl.'/*/edit',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $userBaseUrl.'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Users',
                            'route' => [
                                'url' => $userBaseUrl.'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Roles',
                    'icon' => "<i class='fa fa-tags'></i>",
                    'hasSubmodules' => false,
                    'route' => $roleBaseUrl,
                    'routeIndexName' => 'roles.index',
                    'routeName' => 'roles',
                    'permissions' => [
                        [
                            'name' => 'View Roles',
                            'route' => [
                                'url' => $roleBaseUrl,
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'Create Roles',
                            'route' => [
                                [
                                    'url' => $roleBaseUrl.'/create',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $roleBaseUrl,
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Roles',
                            'route' => [
                                [
                                    'url' => $roleBaseUrl.'/*/edit',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $roleBaseUrl.'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Roles',
                            'route' => [
                                'url' => $roleBaseUrl.'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                    ],
                ],
            ],
        ],

        // WEBSITE CONTENT GROUP
        // [
        //     'name' => 'Website Content',
        //     'icon' => "<i class='fa fa-globe'></i>",
        //     'hasSubmodules' => true,
        //     'routeName' => 'website-content',
        //     'routeIndexNameMultipleSubMenu' => ['post-categories.index', 'posts.index', 'pages.index', 'redirections.index', 'teams.index'],
        //     'submodules' => [
        //         [
        //             'name' => 'Posts',
        //             'icon' => "<i class='fa fa-signs-post' aria-hidden='true'></i>",
        //             'hasSubmodules' => true,
        //             'routeName' => 'posts',
        //             'routeIndexName' => 'posts.index',
        //             'routeIndexNameMultipleSubMenu' => ['post-categories.index', 'posts.index'],
        //             'permissions' => [
        //                 [
        //                     'name' => 'Access Posts',
        //                     'route' => [
        //                         'url' => $postUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //             ],
        //             'submodules' => [
        //                 [
        //                     'name' => 'Categories',
        //                     'icon' => '<i class="fa fa-cog" aria-hidden="true"></i>',
        //                     'route' => $postCategoryUrl,
        //                     'routeIndexName' => 'post-categories.index',
        //                     'routeName' => 'post-categories',
        //                     'hasSubmodules' => false,
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Category',
        //                             'route' => [
        //                                 'url' => $postCategoryUrl,
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Create Category',
        //                             'route' => [
        //                                 [
        //                                     'url' => $postCategoryUrl.'/create',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $postCategoryUrl,
        //                                     'method' => $postMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Edit Category',
        //                             'route' => [
        //                                 [
        //                                     'url' => $postCategoryUrl.'/*/edit',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $postCategoryUrl.'/*',
        //                                     'method' => $putMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Delete Category',
        //                             'route' => [
        //                                 'url' => $postCategoryUrl.'/*',
        //                                 'method' => $deleteMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Posts',
        //                     'icon' => '<i class="fa fa-cog" aria-hidden="true"></i>',
        //                     'route' => $postUrl,
        //                     'routeIndexName' => 'posts.index',
        //                     'routeName' => 'posts',
        //                     'hasSubmodules' => false,
        //                     'permissions' => [
        //                         [
        //                             'name' => 'View Post',
        //                             'route' => [
        //                                 'url' => $postUrl,
        //                                 'method' => $getMethod,
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Create Post',
        //                             'route' => [
        //                                 [
        //                                     'url' => $postUrl.'/create',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $postUrl,
        //                                     'method' => $postMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Edit Post',
        //                             'route' => [
        //                                 [
        //                                     'url' => $postUrl.'/*/edit',
        //                                     'method' => $getMethod,
        //                                 ],
        //                                 [
        //                                     'url' => $postUrl.'/*',
        //                                     'method' => $putMethod,
        //                                 ],
        //                             ],
        //                         ],
        //                         [
        //                             'name' => 'Delete Post',
        //                             'route' => [
        //                                 'url' => $postUrl.'/*',
        //                                 'method' => $deleteMethod,
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'Pages',
        //             'icon' => "<i class='fa fa-file'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $pageBaseUrl,
        //             'routeIndexName' => 'pages.index',
        //             'routeName' => 'pages',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Page',
        //                     'route' => [
        //                         'url' => $pageBaseUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Page',
        //                     'route' => [
        //                         [
        //                             'url' => $pageBaseUrl.'/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $pageBaseUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Page',
        //                     'route' => [
        //                         [
        //                             'url' => $pageBaseUrl.'/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $pageBaseUrl.'/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Page',
        //                     'route' => [
        //                         'url' => $pageBaseUrl.'/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'Redirections',
        //             'icon' => "<i class='fa fa-link'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $redirectionUrl,
        //             'routeIndexName' => 'redirections.index',
        //             'routeName' => 'redirections',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Redirection',
        //                     'route' => [
        //                         'url' => $redirectionUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Redirection',
        //                     'route' => [
        //                         [
        //                             'url' => $redirectionUrl.'/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $redirectionUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Redirection',
        //                     'route' => [
        //                         [
        //                             'url' => $redirectionUrl.'/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $redirectionUrl.'/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Redirection',
        //                     'route' => [
        //                         'url' => $redirectionUrl.'/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'name' => 'Team',
        //             'icon' => "<i class='fa fa-users'></i>",
        //             'hasSubmodules' => false,
        //             'route' => $teamUrl,
        //             'routeIndexName' => 'teams.index',
        //             'routeName' => 'teams',
        //             'permissions' => [
        //                 [
        //                     'name' => 'View Team',
        //                     'route' => [
        //                         'url' => $teamUrl,
        //                         'method' => $getMethod,
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Create Team',
        //                     'route' => [
        //                         [
        //                             'url' => $teamUrl.'/create',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $teamUrl,
        //                             'method' => $postMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Edit Team',
        //                     'route' => [
        //                         [
        //                             'url' => $teamUrl.'/*/edit',
        //                             'method' => $getMethod,
        //                         ],
        //                         [
        //                             'url' => $teamUrl.'/*',
        //                             'method' => $putMethod,
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     'name' => 'Delete Team',
        //                     'route' => [
        //                         'url' => $teamUrl.'/*',
        //                         'method' => $deleteMethod,
        //                     ],
        //                 ],
        //             ],
        //         ],
        //     ],
        // ],

        // SYSTEM & CONFIGURATION GROUP
        [
            'name' => 'Configuration',
            'icon' => "<i class='fa fa-cogs'></i>",
            'hasSubmodules' => true,
            'routeName' => 'system-configuration',
            'routeIndexNameMultipleSubMenu' => ['file-manager.index', 'configs.index', 'menus.index'],
            'submodules' => [
                [
                    'name' => 'File Manager',
                    'icon' => "<i class='fa fa-folder'></i>",
                    'hasSubmodules' => false,
                    'route' => $fileManagerUrl,
                    'routeIndexName' => 'file-manager.index',
                    'routeName' => 'file-manager',
                    'permissions' => [
                        [
                            'name' => 'Manager File Manager',
                            'route' => [
                                'url' => $fileManagerUrl,
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'CKEditor Upload',
                            'route' => [
                                'url' => '/ckeditor-upload',
                                'method' => $postMethod,
                            ],
                        ],
                    ],
                ],
                        [
                            'name' => 'Configs',
                            'icon' => '<i class="fa fa-cog" aria-hidden="true"></i>',
                            'route' => $configBaseUrl,
                            'routeIndexName' => 'configs.index',
                            'routeName' => 'configs',
                            'hasSubmodules' => false,
                            'permissions' => [
                                [
                                    'name' => 'View Configs',
                                    'route' => [
                                        'url' => $configBaseUrl,
                                        'method' => $getMethod,
                                    ],
                                ],
                                [
                                    'name' => 'Create Config',
                                    'route' => [
                                        'url' => $configBaseUrl,
                                        'method' => $postMethod,
                                    ],
                                ],
                                [
                                    'name' => 'Edit Config',
                                    'route' => [
                                'url' => $configBaseUrl.'/*',
                                        'method' => $putMethod,
                                    ],
                                ],
                                [
                                    'name' => 'Delete Config',
                                    'route' => [
                                'url' => $configBaseUrl.'/*',
                                        'method' => $deleteMethod,
                                    ],
                                ],
                            ],
                        ],
                // [
                //     'name' => 'Menu Builder',
                //     'icon' => '<i class="fa fa-menu" aria-hidden="true"></i>',
                //     'route' => $menuBaseUrl,
                //     'routeIndexName' => 'menus.index',
                //     'routeName' => 'menus',
                //     'hasSubmodules' => false,
                //     'permissions' => [
                //         [
                //             'name' => 'View Menu',
                //             'route' => [
                //                 'url' => $menuBaseUrl,
                //                 'method' => $getMethod,
                //             ],
                //         ],
                //         [
                //             'name' => 'Create Menu',
                //             'route' => [
                //                 'url' => $menuBaseUrl,
                //                 'method' => $postMethod,
                //             ],
                //         ],
                //         [
                //             'name' => 'Edit Menu',
                //             'route' => [
                //                 'url' => $menuBaseUrl.'/*',
                //                 'method' => $putMethod,
                //             ],
                //         ],
                //         [
                //             'name' => 'Delete Menu',
                //             'route' => [
                //                 'url' => $menuBaseUrl.'/*',
                //                 'method' => $deleteMethod,
                //             ],
                //         ],
                //     ],
                // ],
            ],
        ],

        // MONITORING & MAINTENANCE GROUP
        [
            'name' => 'Monitoring',
            'icon' => "<i class='fa fa-chart-line'></i>",
            'hasSubmodules' => true,
            'routeName' => 'monitoring-maintenance',
            'routeIndexNameMultipleSubMenu' => ['activities.index', 'monitor.index'],
            'submodules' => [
                [
                    'name' => 'Activities Tracking',
                    'icon' => "<i class='fa fa-video-camera'></i>",
                    'hasSubmodules' => false,
                    'route' => $activityUrl,
                    'routeIndexName' => 'activities.index',
                    'routeName' => 'activities',
                    'permissions' => [
                        [
                            'name' => 'View Activity',
                            'route' => [
                                'url' => $activityUrl,
                                'method' => $getMethod,
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Resource Monitor',
                    'icon' => "<i class='fa fa-campground'></i>",
                    'hasSubmodules' => false,
                    'route' => $monitorUrl,
                    'routeIndexName' => 'monitor.index',
                    'routeName' => 'monitor',
                    'permissions' => [
                        [
                            'name' => 'View Resource Monitor',
                            'route' => [
                                'url' => $monitorUrl,
                                'method' => $getMethod,
                            ],
                        ],
                    ],
                ],
            ],
        ],

        // PROJECT MANAGEMENT GROUP
        [
            'name' => 'Project Management',
            'icon' => "<i class='fa fa-tasks'></i>",
            'hasSubmodules' => true,
            'routeName' => 'project-management',
            'routeIndexNameMultipleSubMenu' => ['projects.index', 'tickets.index', 'kanban.index', 'ticket-statuses.index', 'ticket-labels.index'],
            'submodules' => [
                [
                    'name' => 'Projects',
                    'icon' => "<i class='fa fa-folder-open'></i>",
                    'hasSubmodules' => false,
                    'route' => $projectUrl,
                    'routeIndexName' => 'projects.index',
                    'routeName' => 'projects',
                    'permissions' => [
                        [
                            'name' => 'View Project',
                            'route' => [
                                'url' => $projectUrl,
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'Create Project',
                            'route' => [
                                [
                                    'url' => $projectUrl.'/create',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $projectUrl,
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Project',
                            'route' => [
                                [
                                    'url' => $projectUrl.'/*/edit',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $projectUrl.'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Project',
                            'route' => [
                                'url' => $projectUrl.'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Tickets',
                    'icon' => "<i class='fa fa-ticket'></i>",
                    'hasSubmodules' => false,
                    'route' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl),
                    'routeIndexName' => 'tickets.index',
                    'routeName' => 'tickets',
                    'permissions' => [
                        [
                            'name' => 'View Tickets',
                            'route' => [
                                'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl),
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'View Ticket Details',
                            'route' => [
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*/show',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*',
                                    'method' => $getMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Create Ticket',
                            'route' => [
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/create',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl),
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Ticket',
                            'route' => [
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*/edit',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Ticket',
                            'route' => [
                                'url' => $projectUrl.'/*/'.str_replace('/', '', $ticketUrl).'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Kanban Board',
                    'icon' => "<i class='fa fa-columns'></i>",
                    'hasSubmodules' => false,
                    'route' => '/kanban',
                    'routeIndexName' => 'kanban.index',
                    'routeName' => 'kanban',
                    'permissions' => [
                        [
                            'name' => 'View Global Kanban',
                            'route' => [
                                'url' => '/kanban',
                                'method' => $getMethod,
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Ticket Status',
                    'icon' => "<i class='fa fa-list-check'></i>",
                    'hasSubmodules' => false,
                    'route' => $ticketStatusUrl,
                    'routeIndexName' => 'ticket-statuses.index',
                    'routeName' => 'ticket-statuses',
                    'permissions' => [
                        [
                            'name' => 'View Ticket Status',
                            'route' => [
                                'url' => $ticketStatusUrl,
                                'method' => $getMethod,
                            ],
                        ],
                        [
                            'name' => 'Create Ticket Status',
                            'route' => [
                                [
                                    'url' => $ticketStatusUrl.'/create',
                                'method' => $getMethod,
                                ],
                                [
                                    'url' => $ticketStatusUrl,
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Ticket Status',
                            'route' => [
                                [
                                    'url' => $ticketStatusUrl.'/*/edit',
                                'method' => $getMethod,
                                ],
                                [
                                    'url' => $ticketStatusUrl.'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Ticket Status',
                            'route' => [
                                'url' => $ticketStatusUrl.'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                        [
                            'name' => 'Update Ticket Status Order',
                            'route' => [
                                'url' => $ticketStatusUrl.'-update-order',
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                ],

                [
                    'name' => 'Ticket Labels',
                    'icon' => "<i class='fa fa-tags'></i>",
                    'hasSubmodules' => false,
                    'route' => $ticketLabelUrl,
                    'routeIndexName' => 'ticket-labels.index',
                    'routeName' => 'ticket-labels',
                    'permissions' => [
                        [
                            'name' => 'View Ticket Labels',
                            'route' => [
                                'url' => $ticketLabelUrl,
                                'method' => $getMethod,
                            ],
                        ],
                                [
                            'name' => 'Create Ticket Label',
                            'route' => [
                                [
                                    'url' => $ticketLabelUrl.'/create',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $ticketLabelUrl,
                                    'method' => $postMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Edit Ticket Label',
                            'route' => [
                                [
                                    'url' => $ticketLabelUrl.'/*/edit',
                                    'method' => $getMethod,
                                ],
                                [
                                    'url' => $ticketLabelUrl.'/*',
                                    'method' => $putMethod,
                                ],
                            ],
                        ],
                        [
                            'name' => 'Delete Ticket Label',
                            'route' => [
                                'url' => $ticketLabelUrl.'/*',
                                'method' => $deleteMethod,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Ticket Statuses
    |--------------------------------------------------------------------------
    |
    | These are the default ticket statuses that will be seeded when you run
    | the TicketStatusSeeder. You can modify these statuses or add new ones.
    | Each status should have: name, color (hex), order, and status (1=active, 0=inactive)
    |
    */
];
