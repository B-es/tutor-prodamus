<?php

namespace TPPay;

use Payments\Prodamus\Prodamus;
use Tutor\PaymentGateways\GatewayBase;

class ProdamusGateway extends GatewayBase {

	public function get_root_dir_name(): string {
		return 'Prodamus';
	}

	public function get_payment_class(): string {
		return Prodamus::class;
	}

	public function get_config_class(): string {
		return ProdamusConfig::class;
	}

	public static function get_autoload_file(): string {
		return '';
	}
}