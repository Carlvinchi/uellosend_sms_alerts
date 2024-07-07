<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function uellosend_sms_alerts_config() {
    return [
        'name' => 'UelloSend WHMCS SMS Alerts',
        'description' => 'WHMCS addon for sending SMS alerts to users and admins. Invoice and Ticket alerts, visit www.uellosend.com',
        'version' => '1.0',
        'author' => 'UviTech, Inc.',
        'fields' => [
            'api_key' => [
                'FriendlyName' => 'API KEY',
                'Type' => 'text',
                'Size' => '300',
                'Default' => '',
                'Description' => 'Enter your UelloSend API Key',
            ],
            'sender_id' => [
                'FriendlyName' => 'Sender ID',
                'Type' => 'text',
                'Size' => '12',
                'Default' => '',
                'Description' => 'Enter the sender ID for the SMS, it cannot be more than 11 characters e.g UviTech',
            ],
			'site_url' => [
                'FriendlyName' => 'Your Site URL',
                'Type' => 'text',
                'Size' => '200',
                'Default' => '',
                'Description' => 'Enter the url for your website',
            ],
            'admin_phones' => [
                'FriendlyName' => 'Admin Phone Numbers',
                'Type' => 'text',
                'Size' => '200',
                'Default' => '+233.543524033,+233.553597909',
                'Description' => 'Enter the admin phone numbers in this format +233.543524055,+233.553597900 separate multiple numbers with comma',
            ],
            
        ],
    ];
}

function uellosend_sms_alerts_activate() {
    return [
        'status' => 'success',
        'description' => 'Module activated successfully',
    ];
}

function uellosend_sms_alerts_deactivate() {
    return [
        'status' => 'success',
        'description' => 'Module deactivated successfully',
    ];
}

function uellosend_sms_alerts_upgrade($vars) {
    $version = $vars['version'];

    if ($version < 1.1) {
        // Perform upgrade steps for version 1.1
    }
}
?>