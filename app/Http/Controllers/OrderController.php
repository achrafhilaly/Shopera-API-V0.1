<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\MealPlan;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Order::query()->orderByDesc('created_at');
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by client email if provided
        if ($request->filled('client_email')) {
            $query->where('client_info.email', $request->client_email);
        }
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $orders = $query->paginate($perPage);
        
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $taxRate = 0.20;
        
        // Initialize order data
        $orderData = [
            'client_info' => [
                'name' => $data['contact_name'],
                'email' => $data['contact_email'],
                'phone' => $data['contact_phone'],
            ],
            'status' => 'Confirmed',
            'order_number' => Str::upper(Str::random(10)),
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
            $orderData['tax'] = round($orderData['subtotal'] * $taxRate, 2);
            $orderData['total'] = round($orderData['subtotal'] + $orderData['tax'], 2);
        }

        // Process Meal Plan Order
        if (isset($data['meal_plan_id'])) {
            $mealPlan = MealPlan::findOrFail($data['meal_plan_id']);
            $deliveryDays = $data['delivery_days'];
            $menuSelections = $data['menu_selections_array'];
            $preferences = $data['preferences'];

            $orderData['order_type'] = 'meal_plan';
            $orderData['meal_plan_id'] = $mealPlan->id;
            $orderData['delivery_days'] = $deliveryDays;
            $orderData['menu_selections'] = $menuSelections;
            $orderData['preferences'] = $preferences;
            $orderData['delivery_address'] = $data['delivery_address'];
            $orderData['total'] = $data['total_price'];
        }

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
     * Update the order status.
     */
    public function updateStatus(Order $order, UpdateOrderStatusRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $newStatus = $validated['Status'];
        $comment = $validated['Comment'];
        $oldStatus = $order->status;
        
        // Get status history or initialize empty array
        $statusHistory = $order->status_history ?? [];
        
        // Add new history entry
        $statusHistory[] = [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'comment' => $comment,
            'updated_by_user_id' => Auth::id(),
            'updated_at' => now()->toISOString(),
        ];
        
        // Update order with new status and history
        $order->update([
            'status' => $newStatus,
            'status_history' => $statusHistory,
        ]);
        
        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => new OrderResource($order->refresh())
        ], 200);
    }
}

