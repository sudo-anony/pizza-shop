<?php

return [
    'front_languages' => env('FRONT_LANGUAGES', 'EN,English,IT,Italian,FR,French,DE,German,ES,Spanish,RU,Russian,PT,Portuguese,tr,Turkish,ar,Arabic'),
    'onesignal_android_app_id' => env('ONESIGNAL_ANDROID_APP_ID', ''),
    'onesignal_ios_app_id' => env('ONESIGNAL_IOS_APP_ID', ''),
    'enable_birth_date_on_register' => env('ENABLE_BIRTH_DATE_ON_REGISTER', false),
    'minimum_years_to_register' => env('MINIMUM_YEARS_TO_REGISTER', 18),
    'enable_sms_verification' => env('ENABLE_SMS_VERIFICATION', false),
    'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY', ''),
    'paginate' => env('PAGINATE', 10),
    'cashier_currency' => env('CASHIER_CURRENCY', 'usd'),
    'site_currency' => env('CASHIER_CURRENCY', 'usd'),
    'site_do_currency' => env('DO_CONVERTION', true),
    'do_convertion' => env('DO_CONVERTION', true),
    'time_format' => trim(env('TIME_FORMAT', '24hours')),
    'delivery_interval_in_minutes' => env('DELIVERY_INTERVAL_IN_MINUTES', 30),
    'time_to_prepare_order_in_minutes' => env('TIME_TO_PREPARE_ORDER_IN_MINUTES', 0),
    'app_secret' => env('APP_SECRET'),
    'stripe_secret' => env('STRIPE_SECRET', ''),
    'hide_cod' => env('HIDE_COD', false),
    'enable_stripe' => env('ENABLE_STRIPE', false),
    'stripe_key' => env('STRIPE_KEY', ''),
    'stripe_useVendor' => env('VENDORS_OR_ADMIN_STRIPE', 'admin') == 'vendor',
    'stripe_useAdmin' => env('VENDORS_OR_ADMIN_STRIPE', 'admin') == 'admin',
    'enable_stripe_ideal' => env('ENABLE_STRIPE_IDEAL', true),
    'default_payment' => env('DEFAULT_PAYMENT', 'cod'),
    'google_client_id' => env('GOOGLE_CLIENT_ID', ''),
    'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'facebook_client_id' => env('FACEBOOK_CLIENT_ID', ''),
    'facebook_client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'single_mode' => env('SINGLE_MODE', false),
    'single_mode_id' => env('SINGLE_MODE_ID'),
    'enable_cost_per_distance' => env('ENABLE_COST_PER_DISTANCE', false),
    'enable_cost_per_range' => env('ENABLE_COST_IN_RANGE', false),
    'range_one' => env('RANGE_ONE', '0-0'),
    'range_two' => env('RANGE_TWO', '0-0'),
    'range_three' => env('RANGE_THREE', '0-0'),
    'range_four' => env('RANGE_FOUR', '0-0'),
    'range_five' => env('RANGE_FIVE', '0-0'),
    'range_one_price' => env('RANGE_ONE_PRICE', '1'),
    'range_two_price' => env('RANGE_TWO_PRICE', '1'),
    'range_three_price' => env('RANGE_THREE_PRICE', '1'),
    'range_four_price' => env('RANGE_FOUR_PRICE', '1'),
    'range_five_price' => env('RANGE_FIVE_PRICE', '1'),
    'cost_per_kilometer' => env('COST_PER_KILOMETER', 2),
    'cost_for_start' => env('COST_FOR_START', 5),
    'multi_city' => env('MULTI_CITY', false),
    'min_phone_number' => env('MIN_PHONE_NUMBER', 9),
    'disable_landing' => env('DISABLE_LANDING', false),
    'hide_register_when_disabled_landing' => env('DISABLE_LANDING_AND_HIDE_REGISTER', false),
    'app_locale' => env('APP_LOCALE', 'EN'),
    'app_name' => env('APP_NAME', 'Our project'),
    'qr_landing' => env('QR_LANDING', 'home'),
    'location_search_radius' => env('LOCATION_SEARCH_RADIUS', 50),
    'location_search_radius_driver' => env('LOCATION_SEARCH_RADIUS_DRIVER', 10),
    'most_nearby_limit' => env('MOST_NEARBY_LIMIT', 4),
    'disable_deliver' => env('DISABLE_DELIVER', false),
    'disable_continius_ordering' => env('DISABLE_CONTINIUS_ORDERING', false),
    'demo_restaurant_slug' => env('demo_restaurant_slug', 'leukapizza'),
    'app_url' => env('APP_URL', ''),
    'futy_key' => env('FUTY_KEY', ''),
    'link_to_ts' => env('LINK_TO_TS', '/blog/terms-and-conditions'),
    'link_to_pr' => env('LINK_TO_PR', '/blog/how-it-works'),
    'hide_admin_support' => env('HIDE_ADMIN_SUPPORT', false),
    'front_end_template' => env('FRONT_END_TEMPLATE', 'defaulttemplate'),
    'is_pos_cloud_mode' => env('IS_POS_CLOUD_MODE', false),
    'landing_to_use' => env('LANDING_TO_USE', 'system'),
    'showlandingmanagment' => env('SHOW_LANDING_MANAGMENT', false),
    'is_whatsapp_ordering_mode' => env('IS_WHATSAPP_ORDERING_MODE', false),
    'is_agris_mode' => env('IS_AGRIS_MODE', false),
    'is_facebook_ordering_mode' => env('IS_FACEBOOK_ORDERING_MODE', false),
    'whatsapp_ordering_enabled' => env('WHATSAPP_ORDERING_ENABLED', true),
    'do_regular_checkout_social' => env('DO_REGULAR_CHECKOUT_SOCIAL', false),
    'enable_facebook_ordering' => env('ENABLE_FACEBOOK_ORDERING', false),
    'whatsapp_ordering' => env('IS_WHATSAPP_ORDERING_MODE', false) || env('WHATSAPP_ORDERING_ENABLED', true),
    'facebook_ordering' => env('IS_FACEBOOK_ORDERING_MODE', false) || env('ENABLE_FACEBOOK_ORDERING', false),
    'social_mode' => env('IS_WHATSAPP_ORDERING_MODE', false) || env('IS_FACEBOOK_ORDERING_MODE', false),
    'enable_stripe_connect' => env('ENABLE_STRIPE_CONNECT', false),
    'stripe_fee' => env('STRIPE_FEE', 2.6),
    'stripe_static_fee' => env('STRIPE_STATIC_FEE', 0.3),
    'driver_search_radius' => env('DRIVER_SEARCH_RADIUS', 15),
    'allow_automated_assign_to_driver' => env('ALLOW_AUTOMATED_ASSIGN_TO_DRIVER', 'true'),
    'subscription_processor' => env('SUBSCRIPTION_PROCESSOR', 'Stripe'),
    'free_pricing_id' => env('FREE_PRICING_ID', '1'),
    'driver_percent_from_deliver' => env('DRIVER_PERCENT_FROM_DELIVERY_FEE', 100),
    'vendor_entity_name' => env('VENDOR_ENTITY_NAME', 'Restaurant'),
    'vendor_entity_name_plural' => env('VENDOR_ENTITY_NAME_PLURAL', 'Restaurants'),
    'url_route' => env('URL_ROUTE', 'restaurant'),
    'url_route_plural' => env('URL_ROUTE_PLURAL', 'restaurants'),
    'wildcard_domain_ready' => env('WILDCARD_DOMAIN_READY', false),
    'templates' => env('templates', '/impactfront/img/menu_template_1.jpg,/impactfront/img/menu_template_2.jpg'),
    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'directly_approve_resstaurant' => env('DIRECTLY_APPROVE_RESSTAURANT', false),
    'enable_call_waiter' => env('ENABLE_CALL_WAITER', false),
    'enalbe_change_log_in_update' => env('ENABLE_CHANGELOG_IN_UPDATE', true),
    'app_project_type' => env('APP_PROJECT_TYPE', 'ft'),
    'is_demo' => env('IS_DEMO', false),
    'is_show_credentials' => env('SHOW_DEMO_CREDENTIALS', false),
    'chars_in_menu_list' => env('CHARS_IN_MENU_LIST', 40),
    'extended_license_download_code' => env('EXTENDED_LICENSE_DOWNLOAD_CODE', ''),
    'enable_miltilanguage_menus' => env('ENABLE_MILTILANGUAGE_MENUS', false),
    'onesignal_app_id' => env('ONESIGNAL_APP_ID', ''),
    'twilio_account_sid' => env('TWILIO_ACCOUNT_SID', false),
    'send_sms_notifications' => env('SEND_SMS_NOTIFICATIONS', false),
    'auth0_token' => env('AUTH0_TOKEN', ''),
    'auth_connection' => env('AUTH_CONNECTION', ''),
    'twilio_sid' => env('TWILIO_SID'),
    'twilio_auth_token' => env('TWILIO_AUTH_TOKEN'),
    'twilio_from' => env('TWILIO_FROM', ''),
    'playstore' => env('PLAYSTORE', ''),
    'appstore' => env('APPSTORE', ''),
    'default_country' => env('DEFAULT_COUNTRY', 'US'),
    'send_order_email_to_vendor' => env('ENABLE_SEND_ORDER_MAIL_TO_VENDOR', false),
    'demo_data' => env('DEMO_DATA', true),
    'admin_name' => env('ADMIN_NAME', 'Admin Admin'),
    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => env('ADMIN_PASSWORD', 'secret'),
    'enable_pickup' => env('ENABLE_PICKUP', true),
    'enable_default_cookie_consent' => env('ENABLE_DEFAULT_COOKIE_CONSENT', true),
    'datetime_display_format' => env('DATETIME_DISPLAY_FORMAT', 'd M Y h:i'),
    'datetime_workinghours_display_format_new' => env('DATETIME_WORKING_HOURS_DISPLAY_FORMAT_NEW', 'E HH:mm'),
    'restaurant_link_register_position' => env('RESTAURANT_LINK_REGISTER_POSITION', 'footer'),
    'restaurant_link_register_title' => env('RESTAURANT_LINK_REGISTER_TITLE', 'Register your restaurant'),
    'driver_link_register_position' => env('DRIVER_LINK_REGISTER_POSITION', 'footer'),
    'driver_link_register_title' => env('DRIVER_LINK_REGISTER_TITLE', 'Become driver'),
    'enable_location_search' => env('ENABLE_LOCATION_SEARCH', false),
    'enable_pricing' => env('ENABLE_PRICING', false),
    'enable_search_bar' => env('ENABLE_SEARCH_BAR', true),
    'enable_finances_admin' => env('ENABLE_FINANCES_ADMIN', true) && ! env('MAKE_PURE_SAAS', false),
    'enable_finances_owner' => env('ENABLE_FINANCES_OWNER', true) && ! env('MAKE_PURE_SAAS', false),
    'hide_project_branding' => env('HIDE_PROJECT_BRANDING', true),
    'enable_guest_log' => env('IS_WHATSAPP_ORDERING_MODE', false) ? false : env('ENABLE_GUEST_LOG', true),
    'app_dev' => env('APP_DEV', false),
    'qrsaas_disable_odering' => env('QRSAAS_DISABLE_ODERING', false),
    'restoloyalty_token' => env('RESTOLOYALTY_TOKEN', ''),
    'google_analytics' => env('GOOGLE_ANALYTICS', ''),
    'local_transfer_info' => env('LOCAL_TRANSFER_INFO'),
    'local_transfer_account' => env('LOCAL_TRANSFER_ACCOUNT'),
    'enable_import_csv' => env('ENABLE_IMPORT_CSV', true),
    'share_this_property' => env('SHARE_THIS_PROPERTY', ''),
    'label_on_custom_fields' => env('LABEL_ON_CUSTOM_FIELDS', 'Customer Info'),
    'hideApps' => env('HIDE_APPS_PANEL', false),
    'hideUpdate' => env('HIDE_UPDATE', false),
    'makePureSaaS' => env('MAKE_PURE_SAAS', false),
    'forceUserToPay' => env('FORCE_USERS_TO_PAY', false),
    'default_lat' => env('DEFAULT_LAT', 0),
    'default_lng' => env('DEFAULT_LNG', 0),
    'image_store_location' => env('IMAGE_STORE_LOCATION', ''),
    'image_store_path' => env('IMAGE_STORE_PATH', ''),
    'do_large_image_resize' => env('DO_LARGE_IMAGE_RESIZE', false),
    'unit' => env('UNIT', 'K'),
    'driver_app_link' => env('DRIVER_APP_LINK', ''),
    'client_app_link' => env('CLIENT_APP_LINK', ''),
    'enable_external_system' => env('ENABLE_ADDITIONAL_SYSTEM', false),
    'external_system_url' => env('EXTERNAL_SYSTEM_URL', ''),
    'showVendorBasedOrderIDS' => env('SHOW_VENDOR_BASED_ID', false),
    'showVendorBasedOrderIDSLeadingZeros' => env('SHOW_VENDOR_BASED_ID_LEADING_ZEROS', 6),
    'show_clear_link' => env('SHOW_CLEAR_LINK', false),
];
