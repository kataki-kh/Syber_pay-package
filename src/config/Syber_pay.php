<?php 

return[

'syberpayURL'=>env('syberpayURL'),
'applicationId'=>env('applicationId'),
'serviceId'=>env('serviceId'),
'salt'=>env('salt'),
'key'=>env('key'),
'middleware'=>env('middleware'),
'order_model'=>env('order_model'),
'order_price_column'=>env('order_price_column'),
'order_payment_status_column'=>env('order_payment_status_column'),
'customer_name_column'=>env('customer_name_column'),
'success_message'=>env('success_message'),
'error_message'=>env('error_message'),
'cancel_message'=>env('cancel_message'),
'success_view'=>env('success_view'),
'error_view'=>env('error_view'),
'cancel_view'=>env('cancel_view'),



];