<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/TestRunner.php';

$tests = new TestRunner();

function createBooking(
    string $customerType = 'standard',
    string $passType = 'day',
    float $price = 50.0,
    int $quantity = 1,
    ?string $phone = '0600000000'
): Booking {
    $customer = new Customer(1, 'test@example.com', $phone, $customerType);
    $ticket = new Ticket('TEST', 'Ticket test', $price);
    $booking = new Booking(1, $customer, $passType);
    $booking->addItem(new BookingItem($ticket, $quantity));
    return $booking;
}

ob_start();

$service = new BookingService();
$service->addObserver(new EmailObserver(new EmailService()));
$service->addObserver(new SmsObserver(new SmsClient()));
$service->addObserver(new LoyaltyObserver(new LoyaltyService()));
$service->addObserver(new AnalyticsObserver(new AnalyticsClient()));

$standard = createBooking('standard', 'day', 50.0, 2);
$standardTotal = $service->confirm($standard, 'stripe');
$tests->near(100.0, $standardTotal, 'standard customer keeps initial total');
$tests->same('confirmed', $standard->status, 'booking becomes confirmed');

$vip = createBooking('vip', 'day', 50.0, 2);
$vipTotal = $service->confirm($vip, 'stripe');
$tests->near(90.0, $vipTotal, 'legacy VIP rule gives 10 percent discount');

$vipAndThreeDays = createBooking('vip', '3days', 60.0, 2);
$vipAndThreeDaysTotal = $service->confirm($vipAndThreeDays, 'stripe');
$tests->near(88.0, $vipAndThreeDaysTotal, 'VIP and 3 days discounts are cumulative');

$threeDays = createBooking('standard', '3days', 60.0, 2);
$threeDaysTotal = $service->confirm($threeDays, 'stripe');
$tests->near(100.0, $threeDaysTotal, 'new three day pass discount is 20 euros');

$largeQuantity = createBooking('standard', 'day', 15.50, 4);
$largeQuantityTotal = $service->confirm($largeQuantity, 'stripe');
$tests->near(62.0, $largeQuantityTotal, 'standard customer with 4 items calculates correct tot');

ob_end_clean();

$tests->summary();