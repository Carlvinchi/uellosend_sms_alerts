<?php

require_once __DIR__ . '/functions.php';

// Hook for invoice creation (due)
add_hook('InvoiceCreation', 1, function($vars) {
    $result = select_query('tblinvoices', 'userid, total, id', ['id' => $vars['invoiceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);
    $serviceResult = select_query('tblinvoiceitems', 'description', ['invoiceid' => $vars['invoiceid']]);
    $serviceData = mysql_fetch_array($serviceResult);

    $message = "Hello " . $userData['firstname'] . ", an invoice has been created for your service (" . $serviceData['description'] . "). Amount: " . $data['total'] . ", Invoice Number: " . $data['id'];
    send_sms_alert($data['userid'], 'InvoiceCreation', $message);
});

// Hook for invoice reminder
add_hook('InvoicePaymentReminder', 1, function($vars) {
    $result = select_query('tblinvoices', 'userid, total, id', ['id' => $vars['invoiceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);
    $serviceResult = select_query('tblinvoiceitems', 'description', ['invoiceid' => $vars['invoiceid']]);
    $serviceData = mysql_fetch_array($serviceResult);

    $message = "Hello " . $userData['firstname'] . ", this is a reminder for your invoice for the service (" . $serviceData['description'] . "). Amount: " . $data['total'] . ", Invoice Number: " . $data['id'];
    send_sms_alert($data['userid'], 'InvoiceReminder', $message);
});

// Hook for invoice payment
add_hook('AddInvoicePayment', 1, function($vars) {
    $result = select_query('tblinvoices', 'userid, total, id', ['id' => $vars['invoiceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);
    $serviceResult = select_query('tblinvoiceitems', 'description', ['invoiceid' => $vars['invoiceid']]);
    $serviceData = mysql_fetch_array($serviceResult);

    $message = "Hello " . $userData['firstname'] . ", your invoice for the service (" . $serviceData['description'] . ") has been paid. Amount: " . $data['total'] . ", Invoice Number: " . $data['id'];
    send_sms_alert($data['userid'], 'InvoicePaid', $message);
});

// Hook for add transaction
add_hook('AddTransaction', 1, function($vars) {
    $userResult = select_query('tblclients', 'firstname', ['id' => $vars['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", a new transaction has been added. Amount: " . $vars['amountin'] . ", Transaction ID: " . $vars['transid'];
    send_sms_alert($vars['userid'], 'AddTransaction', $message);
});


// Hook for invoice refund
add_hook('InvoiceRefunded', 1, function($vars) {
    $result = select_query('tblinvoices', 'userid, total, id', ['id' => $vars['invoiceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);
    $serviceResult = select_query('tblinvoiceitems', 'description', ['invoiceid' => $vars['invoiceid']]);
    $serviceData = mysql_fetch_array($serviceResult);

    $message = "Hello " . $userData['firstname'] . ", your invoice payment for the service (" . $serviceData['description'] . ") has been refunded. Amount: " . $data['total'] . ", Invoice Number: " . $data['id'];
    send_sms_alert($data['userid'], 'InvoiceRefund', $message);
});


// Hook for ticket open
add_hook('TicketOpen', 1, function($vars) {
    $result = select_query('tbltickets', 'userid, tid, title', ['id' => $vars['ticketid']]);
    $data = mysql_fetch_array($result);

    
    $message = "New ticket opened: " . $data['title'] . " (Ticket ID: " . $data['tid'] . ")";
    
    send_admin_alert("TicketOpened", $message);
});

// Hook for user reply to a ticket
add_hook('TicketUserReply', 1, function($vars) {
    $result = select_query('tbltickets', 'userid, tid, title', ['id' => $vars['ticketid']]);
    $data = mysql_fetch_array($result);

    
    $message = "User replied to ticket: " . $data['title'] . " (Ticket ID: " . $data['tid'] . ")";

    send_admin_alert("TicketUserReply", $message);
});

// Hook for admin reply to a ticket
add_hook('TicketAdminReply', 1, function($vars) {
    $result = select_query('tbltickets', 'userid, tid, title', ['id' => $vars['ticketid']]);
    $data = mysql_fetch_array($result);

    $message = "Admin replied to your ticket: " . $data['title'] . " (Ticket ID: " . $data['tid'] . ")";
    send_sms_alert($data['userid'], "TicketAdminReply", $message);
});

// Hook for ticket close
add_hook('TicketClose', 1, function($vars) {
    $result = select_query('tbltickets', 'userid, tid, title', ['id' => $vars['ticketid']]);
    $data = mysql_fetch_array($result);
    
    $message = "Your ticket has been closed: " . $data['title'] . " (Ticket ID: " . $data['tid'] . ")";
    send_sms_alert($data['userid'], "TicketClosed", $message);
});


// Hook for service suspend
add_hook('ServiceSuspend', 1, function($vars) {
    $result = select_query('tblhosting', 'userid, domain', ['id' => $vars['serviceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname, phonenumber', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your service (" . $data['domain'] . ") has been suspended.";
    send_sms_alert($data['userid'], "ServiceSuspend", $message);
});

// Hook for service unsuspend
add_hook('ServiceUnsuspend', 1, function($vars) {
    $result = select_query('tblhosting', 'userid, domain', ['id' => $vars['serviceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname, phonenumber', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your service (" . $data['domain'] . ") has been unsuspended.";
    send_sms_alert($data['userid'], "ServiceUnsuspend", $message);
});

// Hook for service terminate
add_hook('ServiceTerminate', 1, function($vars) {
    $result = select_query('tblhosting', 'userid, domain', ['id' => $vars['serviceid']]);
    $data = mysql_fetch_array($result);
    $userResult = select_query('tblclients', 'firstname, phonenumber', ['id' => $data['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your service (" . $data['domain'] . ") has been terminated.";
    send_sms_alert($data['userid'], "ServiceTerminate", $message);
});

// Hook for client addition
add_hook('ClientAdd', 1, function($vars) {

    $userResult = select_query('tblclients', 'firstname', ['id' => $vars['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your account has been created successfully.";
    send_sms_alert($vars['userid'], 'ClientAdd', $message);

});

// add new user
add_hook('ClientAdd', 1, function($vars) {

    $userResult = select_query('tblclients', 'firstname, lastname', ['id' => $vars['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello admin, new user with name : " . $userData['firstname']  . " - ".$userData['lastname']." has signed up.";

    send_admin_alert("ClientAdd", $message);
    
});

// Hook for password reset
add_hook('ClientChangePassword', 1, function($vars) {

    $userResult = select_query('tblclients', 'firstname', ['id' => $vars['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your account password has been reset or changed.";
    send_sms_alert($vars['userid'], 'ClientChangePassword', $message);

});



// Hook for client edit
add_hook('ClientEdit', 1, function($vars) {
    $userResult = select_query('tblclients', 'firstname', ['id' => $vars['userid']]);
    $userData = mysql_fetch_array($userResult);

    $message = "Hello " . $userData['firstname'] . ", your profile has been updated";
    send_sms_alert($vars['userid'], 'ClientEdit', $message);

});

/*



// Hook for module creation
add_hook('AfterModuleCreate', 1, function($vars) {
    send_sms_alert($vars['params']['clientsdetails']['userid'], 'AfterModuleCreate');
});



// Hook for order creation
add_hook('OrderCreated', 1, function($vars) {
    send_sms_alert($vars['userid'], 'OrderCreated');
});

*/
?>