# TE Pay

**Author:** B_es
**GitHub:** [https://github.com/B-es](https://github.com/B-es)
**Plugin Repository:** [https://github.com/B-es/tutor-epoint](https://github.com/B-es/tutor-epoint)

TE Pay integrates Epoint.az with Tutor LMS. This plugin enables one-time course payments through the Epoint payment gateway.

## Features

- ✅ One-time payments for course purchases
- ✅ AZN currency support
- ✅ Signature-based request verification (SHA1 + Base64)
- ✅ Webhook (callback) processing with signature validation
- ✅ Secure payment processing
- ✅ Automatic student enrollment after successful payment
- ✅ Sandbox and Live environment support
- ✅ Internationalization (i18n) support

## Minimum Requirements

- WordPress 5.3 or higher
- PHP 7.4 or higher
- Tutor LMS (Free version)
- Epoint.az merchant account

## Installation

1. Upload the plugin folder to `/wp-content/plugins`
2. Activate the plugin through WordPress admin
3. Ensure Tutor LMS is activated
4. Configure settings in Tutor LMS > Settings > Payments

## Configuration

### Step 1: Get Epoint Credentials

1. Register as a merchant at [Epoint.az](https://epoint.az)
2. Provide the following information to Epoint:
   - Your website URL
   - Success page URL (`success_url`)
   - Error page URL (`error_url`)
   - Callback URL (`result_url`)
3. Receive your access keys:
   - `public_key` — merchant identifier (e.g., `i000000001`)
   - `private_key` — secret key for API signatures

### Step 2: Configure Plugin

1. Go to **Tutor LMS > Settings > Payments**
2. Find **Epoint** in the payment gateways list
3. Enable and configure:
   - **Public Key**: Enter your Epoint Public Key
   - **Private Key**: Enter your Epoint Private Key
   - **Result URL**: Copy this URL and add it to your Epoint merchant panel

### Step 3: Configure Epoint Merchant Panel

1. Login to your Epoint merchant panel
2. Add the Result URL from step 2
3. Save settings

## Testing

### Test Transaction Flow

1. Create a test course in your LMS
2. Set a price for the course
3. Add course to cart and proceed to checkout
4. Select Epoint as payment method
5. Complete payment on the Epoint payment page
6. Verify order status in Tutor LMS

## How It Works

### Payment Flow

```
Student clicks "Purchase"
    ↓
Plugin prepares payment data (order_id, amount, currency)
    ↓
Plugin generates signature: base64(sha1(private_key + data + private_key))
    ↓
POST request to https://epoint.az/api/1/checkout
    ↓
Student redirected to bank payment page
    ↓
Student enters card details and confirms payment
    ↓
Student redirected to success_url or error_url
    ↓
Epoint sends POST callback to result_url (webhook)
    ↓
Plugin verifies signature
    ↓
Plugin updates order status (paid / failed)
    ↓
Student gets access to course (if successful)
```

### Security Features

1. **Signature Verification**: Every request and callback is signed using `base64(sha1(private_key + data + private_key))`
2. **Idempotency**: Orders are marked as processed to prevent duplicate webhook handling
3. **HTTPS Communication**: All API calls use HTTPS

## Supported Currency

- AZN (Azerbaijani Manat) — the only currency supported by Epoint

## API Integration Details

### Payment Initiation (Checkout)
- **Endpoint**: `https://epoint.az/api/1/checkout`
- **Method**: POST
- **Parameters**: `data` (base64-encoded JSON), `signature`
- **Response**: `{ status, redirect_url }`

### Payment Status Check
- **Endpoint**: `https://epoint.az/api/1/get-status`
- **Method**: POST
- **Parameters**: `data`, `signature`
- **Response**: `{ status, transaction, code, message, ... }`

### IPN Callback (Webhook)
- Epoint sends POST to `result_url` with `data` and `signature`
- Plugin verifies signature and decodes `data`
- Order is updated, student is enrolled

### Signature Formula

```
data      = base64_encode(json_string)
signature = base64_encode(sha1(private_key + data + private_key, true))
```

## Payment Statuses

| Epoint Status | Plugin Status |
|---------------|--------------|
| `success`     | `paid`       |
| `new`         | `pending`    |
| `returned`    | `refunded`   |
| `error`       | `failed`     |

## Troubleshooting

### Payment Not Processing

1. **Check Credentials**: Ensure Public Key and Private Key are correct
2. **Result URL**: Verify Result URL is correctly configured in Epoint panel
3. **SSL Certificate**: Ensure your site has a valid SSL certificate

### Transaction Validation Failed

1. Check if Result URL is accessible (not blocked by firewall)
2. Enable debug logging in WordPress (`WP_DEBUG`)
3. Check error logs for detailed messages

### Order Status Not Updating

1. Verify webhook (Result URL) is configured correctly
2. Check signature verification is passing
3. Check webhook response in server logs

## Known Limitations

1. **No Subscription Support**: Epoint doesn't provide native recurring payment functionality
2. **Refunds**: Manual refund processing through Epoint merchant panel required

## Changelog

### Version 1.0.7
- **Improvement**: Code cleanup and optimization
- **Fix**: Corrected currency to AZN (per Epoint documentation)
- **Fix**: Fixed API response field mapping (`redirect_url` instead of `url`)
- **Fix**: Corrected webhook status checking (`success` instead of `paid`)
- **Fix**: Removed non-existent fields from payment payload

### Version 1.0.6
- **Feature**: Added complete internationalization (i18n) support
- **Improvement**: Updated plugin constants and code structure

### Version 1.0.5
- Minor fixes and improvements

### Version 1.0.4
- Minor fixes and improvements

### Version 1.0.3
- **Improvement**: Replaced cURL with WordPress HTTP API
- **Improvement**: Enhanced error handling and JSON validation

### Version 1.0.2
- **Security**: Fixed fatal errors in IPN handling
- **Security**: Improved validation for webhook requests

### Version 1.0.1
- **Fix**: Corrected payment amount sending
- **Fix**: Updated to use correct Tutor LMS field names

### Version 1.0.0
- Initial release
- One-time payment support
- Webhook integration
- Transaction validation

## Support

For issues related to:
- **Plugin functionality**: Create issue on [GitHub](https://github.com/B-es/tutor-epoint)
- **Epoint API**: Contact Epoint support via [epoint.az](https://epoint.az)
- **Tutor LMS**: Contact Themeum support

## License

This plugin is licensed under GPLv2 or later.

## Credits

- Developed for Tutor LMS
- Epoint.az API integration
- Based on Tutor LMS Payment Gateway framework

## Additional Resources

- [Epoint.az Website](https://epoint.az)
- [Tutor LMS Documentation](https://docs.themeum.com/tutor-lms/)
