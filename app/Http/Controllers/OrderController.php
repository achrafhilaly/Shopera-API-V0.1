<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\ValidateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\MealPlan;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Initialize order data
        $orderData = [
            'client_info' => [
                'name' => $data['client_name'],
                'email' => $data['client_email'],
                'phone' => $data['client_phone'],
            ],
            'status' => 'Pending',
            'line_items' => [],
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
        ];

        // Process Products Order
        if (isset($data['products']) && !empty($data['products'])) {
            $lineItems = [];
            $subtotal = 0;

            foreach ($data['products'] as $productItem) {
                $product = Product::findOrFail($productItem['product_id']);
                
                // Get unit price from product's base price
                $unitPrice = $product->price['base'] ?? 0;
                $quantity = $productItem['quantity'];
                $lineSubtotal = $unitPrice * $quantity;

                $lineItems[] = [
                    'type' => 'product',
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineSubtotal,
                ];

                $subtotal += $lineSubtotal;
            }

            $orderData['line_items'] = $lineItems;
            $orderData['subtotal'] = round($subtotal, 2);
            $orderData['order_type'] = 'products';
        }

        // Process Meal Plan Order
        if (isset($data['meal_plan_id']) && isset($data['days'])) {
            $mealPlan = MealPlan::findOrFail($data['meal_plan_id']);
            $days = $data['days'];

            // Calculate daily price (breakfast + lunch + dinner)
            $breakfastPrice = $mealPlan->breakfast_price_per_day ?? 0;
            $lunchPrice = $mealPlan->lunch_price_per_day ?? 0;
            $dinnerPrice = $mealPlan->dinner_price_per_day ?? 0;
            
            $dailyPrice = $breakfastPrice + $lunchPrice + $dinnerPrice;
            $subtotal = $dailyPrice * $days;

            $lineItems = [
                [
                    'type' => 'meal_plan',
                    'meal_plan_id' => $mealPlan->id,
                    'meal_plan_name' => $mealPlan->name,
                    'meal_plan_sku' => $mealPlan->sku,
                    'days' => $days,
                    'daily_price' => round($dailyPrice, 2),
                    'price_breakdown' => [
                        'breakfast' => $breakfastPrice,
                        'lunch' => $lunchPrice,
                        'dinner' => $dinnerPrice,
                    ],
                    'subtotal' => round($subtotal, 2),
                ],
            ];

            $orderData['line_items'] = $lineItems;
            $orderData['subtotal'] = round($subtotal, 2);
            $orderData['meal_plan_id'] = $mealPlan->id;
            $orderData['order_type'] = 'meal_plan';
        }

        // Calculate tax (assuming 10% tax rate - you can make this configurable)
        $taxRate = 0.20;
        $orderData['tax'] = round($orderData['subtotal'] * $taxRate, 2);
        $orderData['total'] = round($orderData['subtotal'] + $orderData['tax'], 2);

        // Create order
        $order = Order::create($orderData);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => new OrderResource($order)
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    /**
     * Validate a pending order.
     * Only admins can validate orders.
     */
    public function validate(ValidateOrderRequest $request, Order $order): JsonResponse
    {
        // Check if order is in Pending status
        if ($order->status !== 'Pending') {
            return response()->json([
                'message' => 'Only pending orders can be validated.',
                'current_status' => $order->status
            ], 422);
        }

        $data = $request->validated();
        
        // Get the authenticated admin user from the Bearer token
        /** @var User $user */
        $user = auth()->user();

        // Update order with validation info
        $order->update([
            'status' => 'Validated',
            'validated_at' => now(),
            'validated_by' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ],
            'validation_comment' => $data['comment'],
        ]);

        return response()->json([
            'message' => 'Order validated successfully',
            'data' => new OrderResource($order->fresh())
        ], 200);
    }
}

