<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_save_order_log($order_id, $user_id,  $old = '', $new = '',  $timestamp = TIME)
{   $find_id= db_get_field("SELECT order_id FROM ?:changes_logs WHERE order_id = ?i", $order_id);
    if ($find_id) {
        $data = db_query("UPDATE ?:changes_logs SET ?u WHERE order_id = ?i", array(
        'user_id' => $user_id,
        'old' => $old,
        'new' => $new,
        'timestamp' => $timestamp
    ),$order_id);
    }else{
        $data = db_query("INSERT INTO ?:changes_logs SET ?u", array(
        'order_id' => $order_id,
        'user_id' => $user_id,
        'old' => $old,
        'new' => $new,
        'timestamp' => $timestamp
    ));
    }
    
    
    return $data;
}

function fn_get_order_logs()
{
    $logs = db_get_array("SELECT logs.*, users.firstname, users.lastname FROM ?:changes_logs as logs "
        . " LEFT JOIN ?:users as users USING(user_id) ORDER BY logs.order_id ASC"
    );

    return $logs;

}

/**
 * Hooks
 */

function fn_changes_logs_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order)
{
    if ($status_to != $status_from) {
        $user_id = 0;
        if (AREA == 'A' && $place_order != true) {
            $user_id = $_SESSION['auth']['user_id'];
        }

        $old = $order_statuses[$status_from]['description'];
        $new = $order_statuses[$status_to]['description'];
        if (!$place_order && $status_to != 'N') {
            fn_save_order_log($order_info['order_id'], $user_id, $old, $new , TIME);
        }
    }
}

function fn_changes_logs_place_order($order_id, $order_status, $cart, $auth)
{

    fn_save_order_log($order_id, $_SESSION['auth']['user_id'], 'Открыт','—', TIME);
}

function fn_changes_logs_delete_order($order_id)
{
    $old_status = db_get_field("SELECT old FROM ?:changes_logs WHERE order_id = ?i", $order_id);
    fn_save_order_log($order_id, $_SESSION['auth']['user_id'], $old_status,'Заказ удален', TIME);
}

/**
 * \ Hooks
 */