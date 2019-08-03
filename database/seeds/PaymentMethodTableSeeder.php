<?php

use App\Models\PaymentMethodCategory;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!PaymentMethodCategory::count()) {
            // Gift Cards
            $category = PaymentMethodCategory::create([
                'name' => 'Gift Cards'
            ]);

            $payment_methods = [
                'iTunes Gift Card', 'Walmart Gift Card', 'Starbucks Gift Card', 'Nike Gift Card',
                'Amazon Gift Card', 'Playstation Gift Card', 'eBay Gift Card', 'Ali Express Gift Card',
                'Steam Wallet Gift Card', 'Vanilla Gift Card', 'Skype Credits', 'Microsoft Gift Card'
            ];

            foreach ($payment_methods as $method) {
                $category->payment_methods()->create([
                    'name' => $method
                ]);
            }

            // Debit/Credit Cards
            $category = PaymentMethodCategory::create([
                'name' => 'Debit/Credit Cards'
            ]);

            $payment_methods = [
                'My Vanilla Prepaid Debit Card', 'Walmart Money Card', 'Prepaid Debit Card',
                'VISA Debit/Credit Card', 'American Express', 'Apple Pay', 'Square Cash',
            ];

            foreach ($payment_methods as $method) {
                $category->payment_methods()->create([
                    'name' => $method
                ]);
            }

            // Cash Deposits
            $category = PaymentMethodCategory::create([
                'name' => 'Cash Deposits'
            ]);

            $payment_methods = [
                'Bitcoin ATM', 'Money Gram', 'Western Union', 'Cardless Cash', 'Cash Deposit To Banks',
            ];

            foreach ($payment_methods as $method) {
                $category->payment_methods()->create([
                    'name' => $method
                ]);
            }

            // Online Transfer
            $category = PaymentMethodCategory::create([
                'name' => 'Online Transfers'
            ]);

            $payment_methods = [
                'Mobile Recharge', 'Internet Wire Transfer (SWIFT)', 'Bank Transfers', 'Bill Payment',
                'Domestic Wire Transfer', 'GoFundMe.com', 'Skrill', 'PayPal', 'Google Pay', 'Payoneer',
                'Perfect Money', 'AliPay', 'Stripe', 'Payza', 'Paytm Online Wallet', 'Facebook Pay'
            ];

            foreach ($payment_methods as $method) {
                $category->payment_methods()->create([
                    'name' => $method
                ]);
            }

        }
    }
}
