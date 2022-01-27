<?php
/**
 * Create QR code after order payment completed
 */
add_action( 'woocommerce_order_status_processing', 'famcare_generate_qr_code', 10, 1 );
function famcare_generate_qr_code( $order_id ) {
	// Do nothing if its already created.
	$_qr_code_image_id = get_post_meta($order_id,'_qr_code_image_id',true);
	if($_qr_code_image_id != '')
		return;

	// Do nothing if image already exist.
	$_qr_code_image_url = wp_get_attachment_url($_qr_code_image_id);
	if($_qr_code_image_url)
		return;

	// Load TLV QR generator
	require(untrailingslashit(dirname(__FILE__)) . '/Famcare_TLV.php');

	/*
	 * Get Order info.
	 */
	$order = wc_get_order($order_id);
	$order_total = $order->get_total();
	$order_tax = $order->get_total_tax();
	$order_date = $order->get_date_created()->date('Y-m-d\TH:i:s');

	(new Famcare_TLV_QR)->generateTLVImage($order_id,$order_date,$order_total,$order_tax);
}