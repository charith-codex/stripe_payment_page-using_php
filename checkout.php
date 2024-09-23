<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the Stripe secret key from .env
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];

// Use it in your Stripe initialization
\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    // Create a new Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'payment_method_types' => ['card'], // Ensure card is the payment method
        'line_items' => [
            [
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'lkr',
                    'unit_amount' => 250000,
                    'product_data' => [
                        'name' => 'T-shirt'
                    ]
                ]
            ],
            [
                'quantity' => 2,
                'price_data' => [
                    'currency' => 'lkr',
                    'unit_amount' => 30000,
                    'product_data' => [
                        'name' => 'Hat'
                    ]
                ]
            ],
        ],
        'success_url' => 'http://localhost/projects/stripe_php/success.php',
        'cancel_url' => 'http://localhost/projects/stripe_php/index.php',
    ]);

    // Redirect to Stripe Checkout URL
    http_response_code(303);
    header('Location: ' . $checkout_session->url);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle API error (optional)
    echo 'Error creating Checkout Session: ' . $e->getMessage();
}
