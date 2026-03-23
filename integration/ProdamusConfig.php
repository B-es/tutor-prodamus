<?php

namespace TPPay;

use Tutor\Ecommerce\Settings;
use Ollyo\PaymentHub\Core\Payment\BaseConfig;
use Tutor\PaymentGateways\Configs\PaymentUrlsTrait;
use Ollyo\PaymentHub\Contracts\Payment\ConfigContract;

class ProdamusConfig extends BaseConfig implements ConfigContract {

	private const CONFIG_KEYS = [
		'environment' => 'select',
		'store_id' => 'text',
		'store_password' => 'secret_key',
	];

	use PaymentUrlsTrait;

	private $environment;
	private $store_id;
	private $store_password;

	protected $name = 'prodamus';

	public function __construct() {
		parent::__construct();

		$settings = Settings::get_payment_gateway_settings('prodamus');

		if (!is_array($settings)) {
			throw new \RuntimeException(__('Unable to load Prodamus gateway settings', 'tppay'));
		}

		$config_keys = array_keys(self::CONFIG_KEYS);

		foreach ($config_keys as $key) {
			if ('webhook_url' !== $key) {
				$this->$key = $this->get_field_value($settings, $key);
			}
		}
	}

	public function getMode(): string {
		return $this->environment;
	}

	public function getStoreId(): string {
		return $this->store_id;
	}

	public function getStorePassword(): string {
		return $this->store_password;
	}

	public function getApiDomain(): string {
		return $this->environment === 'sandbox'
			? 'https://sandbox.prodamus.com'
			: 'https://securepay.prodamus.com';
	}

	public function is_configured(): bool {
		return !empty($this->store_id) && !empty($this->store_password);
	}

	public function createConfig(): void {
		parent::createConfig();

		$config = [
			'store_id' => $this->getStoreId(),
			'store_password' => $this->getStorePassword(),
			'api_domain' => $this->getApiDomain(),
		];

		$this->updateConfig($config);
	}
}