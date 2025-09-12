<?php

namespace App\Http\Controllers;

use App\Models\Admin\TravelPackage;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;

class TiersController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display the packages index.
     */
    public function index()
    {
        // Get active travel packages ordered by sort_order
        $travelPackages = TravelPackage::where('status', 'active')
            ->orderBy('sort_order')
            ->get();
        
        $faqCategories = Faq::active()
            ->ordered()
            ->get()
            ->groupBy('category');
        // Pass the $travelPackages variable to the view
        return view('tiers.index', compact('travelPackages','faqCategories'));
    }

    /**
     * Display the specified package.
     */
    public function show($type)
    {
        // Find the package by type
        $package = TravelPackage::where('type', $type)
            ->where('status', 'active')
            ->firstOrFail();
            
        $packageType = $type;
        $packageName = $package->name;
        $packageDescription = $package->short_description ?? 'Premium vacation package';
        $packagePrice = $package->price;
        
        return view('tiers.show', compact(
            'packageType',
            'packageName',
            'packageDescription',
            'packagePrice',
            'package'
        ));
    }

    /**
     * Process the booking form.
     */
    public function book(Request $request)
    {
        // Validate booking data
        $validated = $request->validate([
            'package_type' => 'required|string',
            'package_price' => 'required|numeric',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'card_number' => 'required|string|max:19',
            'cardholder_name' => 'required|string|max:255',
            'expiration_month' => 'required|numeric|min:1|max:12',
            'expiration_year' => 'required|numeric|min:2023',
            'cvv' => 'required|string|max:4',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:10',
            'consent' => 'required',
            'quantity' => 'required|numeric|min:1|max:4',
        ]);
        
        // Find the package by type
        $package = TravelPackage::where('type', $validated['package_type'])->firstOrFail();
        
        // Calculate total price based on quantity
        $quantity = (int)$validated['quantity'];
        $totalPrice = $package->price * $quantity;
        
        // Prepare card data for payment processing
        $cardData = [
            'card_number' => $validated['card_number'],
            'exp_month' => $validated['expiration_month'],
            'exp_year' => $validated['expiration_year'],
            'cvc' => $validated['cvv'],
        ];
        
        // Prepare customer info for payment processing
        $customerInfo = [
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'country' => 'US', // Default to US
        ];
        
        try {
            // Process the payment
            $paymentResult = $this->paymentService->processCardPayment(
                $cardData,
                $totalPrice,
                'USD', // Assuming USD as currency
                $customerInfo,
                'Booking payment for ' . $package->name . ' (Qty: ' . $quantity . ')',
                'stripe' // Explicitly use Stripe
            );
            
            if (!$paymentResult['success']) {
                // Payment failed
                return redirect()->back()->with('error', $paymentResult['message'] ?? 'Payment processing failed. Please try again.');
            }
            
            // Create a new booking in the database
            $booking = Booking::create([
                'user_id' => Auth::id(), // Will be null if user is not logged in
                'package_type' => $validated['package_type'],
                'package_name' => $package->name,
                'package_price' => $totalPrice, // Use the calculated total price
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip' => $validated['zip'],
                'payment_method' => 'credit_card',
                'card_last_four' => substr($validated['card_number'], -4), // Store only last 4 digits for security
                'status' => 'completed', // Mark as completed since payment was successful
                'transaction_id' => $paymentResult['transaction_id'], // Store the transaction ID
                'quantity' => $quantity, // Store the quantity
            ]);
            
            // For the thank you page, we prepare a booking array
            $bookingData = [
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'package' => $package->name,
                'price' => $totalPrice, // Use the calculated total price
                'quantity' => $quantity,
                'transaction_id' => $paymentResult['transaction_id'],
            ];
            
            return redirect()->route('tiers.thankyou')->with('booking', $bookingData);
            
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Payment processing failed: ' . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the thank you page after booking.
     */
    public function thankYou()
    {
        if (!session('booking')) {
            return redirect()->route('tiers.index');
        }

        $booking = session('booking');
        
        return view('tiers.thankyou', compact('booking'));
    }
}