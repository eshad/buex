<?php

Route::get('/', function (){
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware'=>['auth']],function(){
	
	Route::get('/dashboard','desktopcontroller@view');
	////// Products ////////
	Route::resource('/attribute', 'AttributeController');
	Route::POST('/update_attribute', 'AttributeController@update_attribute');
	Route::resource('/category', 'Category_controller');
	Route::POST('/ajax_category_name', 'Category_controller@ajax_category_name');
	Route::POST('/ajax_category_code', 'Category_controller@ajax_category_code');
	Route::POST('/ajax_image', 'Category_controller@ajax_image');
    Route::resource('/product', 'Product_controller');
	Route::POST('/check_ajax_cate_id', 'Product_controller@ajax_category_name');
	Route::POST('/ajax_take_images', 'Product_controller@ajax_take_images');
	Route::resource('/stock', 'MalaysiaController');
	Route::POST('/take_product_current_stock', 'MalaysiaController@ajax_product_stock');
	////// Products ////////
	
	
	//////////Payment/////////////
Route::get('/manage_payment', 'Payment_Controller@add_manage_payment_view');
Route::get('/manage_payment/{order_id?}', 'Payment_Controller@add_manage_payment_view');
Route::get('/manage_customer_payment/{customer_id?}', 'Payment_Controller@add_customer_payment_view');

	Route::get('/customer_balance', 'Payment_Controller@all_customer_list');
	Route::get('/view_customer_balance/{customer_id?}', 'Payment_Controller@view_customer_balance');
	Route::get('/payment_source', 'Payment_Controller@payment_source_list');
	Route::get('/verify_payment', 'Payment_Controller@verify_payment_list');
	Route::get('/refund', 'Payment_Controller@refund_list');
	Route::POST('/ajax_save_payment_source', 'Payment_Controller@ajax_save_payment_source');
	Route::POST('/ajax_take_payment_source', 'Payment_Controller@ajax_take_payment_source');
	Route::POST('/ajax_update_payment_source', 'Payment_Controller@ajax_update_payment_source');
	Route::POST('/delete_payment_source/{product_source_id?}','Payment_Controller@delete_payment_source');
	Route::POST('/ajax_getuser_orderlist', 'Payment_Controller@ajax_getuser_orderlist');
	Route::POST('/ajax_getuser_credit', 'Payment_Controller@ajax_getuser_credit');
	Route::get('/delete_payment_details/{payment_id?}/{payment_status?}/{redirectlink?}',    'Payment_Controller@delete_payment_details');
	Route::get('/delete_orders_details/{order_id?}/{customer_id?}','Payment_Controller@delete_orders_details'); 
	Route::get('/delete_payment_cust_balance/{payment_id?}/{payment_status?}/{redirectlink?}/{customer_id?}',    'Payment_Controller@delete_payment_cust_balance');
	Route::get('/change_payment_status/{payment_id?}/{payment_status?}', 'Payment_Controller@change_payment_status');
	Route::get('/change_refund_payment_status/{refund_id?}/{refund_status?}', 'Payment_Controller@change_refund_payment_status');
	Route::post('/change_refund_status', 'Payment_Controller@change_refund_status');
	Route::resource('payment', 'Payment_Controller');
	Route::POST('/ajax_payment_ref_number', 'Payment_Controller@ajax_payment_ref_number');
	Route::get('/view_payment_details/{payment_id?}/{type?}/{u_id?}', 'Payment_Controller@view_payment_details');
	Route::get('/payment_history_download_PDF/{customer_id}/{pdftype}','Payment_Controller@downloadPDF');
	Route::get('/refund_download_PDF/{pdftype}','Payment_Controller@refunddownloadpdf');
	//////////Payment/////////////
	
	//////////Notes/////////////
	Route::POST('/ajax_add_source_notes', 'Note_Controller@ajax_addSource_note');
	Route::POST('/ajax_list_source_notes', 'Note_Controller@ajax_getlist_source_notes');
	Route::POST('/ajax_change_acknowledge_notes', 'Note_Controller@ajax_change_acknowledge_notes');
	//////////Notes End/////////////
	
	//////////Orders/////////////
    Route::resource('/order', 'OrderController');
	Route::get('/order/create/{customer_id?}', 'OrderController@create');
	Route::get('/cancel_order_view/{order_id?}', 'OrderController@cancel_edit');
	Route::get('/edit_partial_completed/{order_id?}', 'OrderController@edit_partial_completed');
	Route::get('/view_completed_order/{order_id?}', 'OrderController@view_completed_order');
	Route::get('/completed_order', 'OrderController@completed_order');
	Route::get('/partial_completed_order', 'OrderController@partial_completed_order');
	Route::POST('/add_order_penalty', 'OrderController@add_order_penalty');
	Route::POST('/cancel_order_and_refund', 'OrderController@cancel_order_and_refund');
	Route::POST('/request_cancel_order_and_refund', 'OrderController@request_cancel_order_and_refund');
	Route::POST('/force_active', 'OrderController@force_active');
	Route::POST('/ajax_get_item_details_on_order_page', 'OrderController@ajax_get_item_details_on_order_page');
	Route::get('/dispatch_order/{order_id?}', 'OrderController@dispatch_order');
	Route::get('/dispatch_collect_order/{order_id?}', 'OrderController@dispatch_collect_order');
	Route::get('/order_cash_on_delivery/{order_id?}', 'OrderController@order_cash_on_delivery');
	Route::POST('/submit_dispatch_order', 'OrderController@submit_dispatch_order');
	Route::POST('/submit_dispatch_collect_order', 'OrderController@submit_dispatch_collect_order');
	Route::POST('/submit_cash_on_delivery_order', 'OrderController@submit_cash_on_delivery_order');
	Route::get('/order_move_to_tab2/{order_id?}', 'OrderController@order_move_to_tab2');
	Route::get('/send_order_reminder_mail/{order_id?}', 'OrderController@send_order_reminder_mail');
	Route::get('get_default_order_download_PDF/{order_id?}/{type?}','OrderController@getdefaultorderPdf');
	//////////Orders/////////////
	
	Route::resource('permission', 'PermissionController');
	Route::resource('role', 'RoleController');
	
	///////////user/////////////////////
	Route::resource('user', 'UserController');
	Route::get('/loginasuser/{userid?}', 'UserController@loginasuser');
	Route::get('/backtoadmin', 'UserController@backtoadmin');
	Route::get('inactive_user/{userid?}','UserController@inactive_user');
	Route::get('active_user/{userid?}','UserController@active_user');
	Route::get('sales_agent_list','UserController@sales_agent_list');
	
	
	//////////customer/////////////
	Route::resource('customer', 'CustomerController');
	Route::POST('/ajax_view_customer', 'CustomerController@ajax_view_customer');
	Route::POST('/ajax_check_mobile_duplicate', 'CustomerController@ajax_check_mobile_duplicate');
	Route::POST('/ajax_check_customer_email_duplicate', 'CustomerController@ajax_check_customer_email_duplicate');
	Route::POST('/ajax_check_edit_customer_email_duplicate', 'CustomerController@ajax_check_edit_customer_email_duplicate');
	
	//////////customer/////////////

	///////////////account//////////////
	Route::get('account_all', 'AccountController@user_list');
	Route::get('/user_account/{user_id?}', 'AccountController@show');
	Route::resource('account', 'AccountController');
	//////////////account///////////
	
///////// report //////////////
	Route::get('report', 'ReportDataController@user_list_by_role');
	Route::get('report/sales_details/{user_id?}', 'ReportDataController@user_sales_details');
	Route::get('report/dispatch_collection_report', 'ReportDataController@dispatch_manager_report');
	Route::get('report/customer_balance', 'ReportDataController@all_customer_list');
	Route::get('report/dispatch_manager_report/{user_id?}','ReportDataController@getDispatchRecord');
	Route::get('getdispatch_download_PDF/{order_id?}/{type?}','ReportDataController@getDispatchPdf');

	///////// report //////////////
	
	
	
	
	///////////////commission//////////////
	Route::resource('commission', 'CommissionController');
	Route::POST('/ajax_low_unit_price', 'CommissionController@ajax_low_unit_price');
	Route::POST('/edit_ajax_low_unit_price', 'CommissionController@edit_ajax_low_unit_price');
	//////////////commission///////////
	
	///////////////shipment//////////////
	Route::resource('shipment', 'ShipmentController');
	
	Route::POST('shipment_arrive','ShipmentController@shipment_arrive');
	Route::POST('shipment_pending_arrive','ShipmentController@shipment_pending_arrive');
	Route::get('shipment_view/{shipment_id?}','ShipmentController@shipment_view');
	Route::get('delete_pending_stock/{shipment_line_id?}','ShipmentController@delete_pending_stock');
	Route::get('/shipment_view_pdf/{pdftype}/{shipment_id}','ShipmentController@shipmentViewdownloadpdf');
	//////////////shipment///////////

	Route::GET('/text', 'TextController@change_password');
	Route::POST('/change_my_password_save', 'TextController@change_my_password_save');
	Route::POST('/updateprofileimage', 'TextController@profile_images_upload');
});

//////////////send email check ///////////
Route::get('/send_email_check','CheckSendEmailController@sendeCronOrdersEmail');


Route::get('/home', function (){
	return redirect('dashboard');
});

/*Route::get('/dashboard', function (){
	return view('dashboard');
});*/



Route::get('/update_stock', function (){
	return view('update_stock');
});

Route::get('/order_list', function (){
	return view('order_list');
});

Route::get('/add_order', function (){
	return view('add_order');
});

Route::get('/manage_order_status','OrderController@manage_order_status');
Route::get('/test','OrderController@test');
Route::get('/send_arrival_emails','CheckSendEmailController@send_arrival_emails');
Route::get('/test2','CheckSendEmailController@test2');

