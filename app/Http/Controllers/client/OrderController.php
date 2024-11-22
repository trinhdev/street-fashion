<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\Order;
use App\Models\Status;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|digits_between:9,15',
                'email' => 'required|email',
                'address' => 'required|string|max:500',
                'payment_method' => 'required|string|in:COD,BANK',
            ]);


            $fullAddress = $validatedData['address'] . ', ' . $request['ward'] . ', ' . $request['district'] . ', ' . $request['province'];

            // Lấy thông tin giỏ hàng từ session
            $userId = auth()->id();
            $cart = session()->get('cart.' . $userId, []);

            if (empty($cart)) {
                return redirect()->back()->withErrors(['message' => 'Giỏ hàng của bạn đang trống.']);
            }

            $totalAmount = 0; // Biến để tính tổng giá trị đơn hàng
            $orderCode = 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('YmdHis');
            // Lưu đơn hàng
            $order = new Order();
            $order->user_id = $userId;
            $order->address = $fullAddress;
            $order->order_status_id = 9;
            $order->order_code = $orderCode;
            
            $order->save();


            
            // Lưu chi tiết sản phẩm
            foreach ($cart as $item) {
                $totalPrice = $item['price'] * $item['quantity']; // Tính tổng giá trị cho từng sản phẩm

                $orderDetail = $order->orderDetails()->create([
                    'product_id' => $item['id'], // ID sản phẩm
                    'quantity' => $item['quantity'], // Số lượng
                    'price' => $item['price'], // Giá sản phẩm
                    'total_price' => $totalPrice, // Tổng giá trị cho sản phẩm
                ]);

                // Tính tổng giá trị đơn hàng
                $totalAmount += $totalPrice;
            }
            $order->update(['total_amount' => $totalAmount]);
            // Cộng thêm phí vận chuyển vào tổng tiền
            $shippingFee = 0;
            $totalAmount += $shippingFee;

            // Xóa giỏ hàng sau khi đặt hàng
            session()->forget('cart.' . $userId);


            if ($request->payment_method === 'COD') {
                // Nếu phương thức thanh toán là COD
                $payment = Payment::create([
                    'order_id' => $order->id,

                    'amount' => $totalAmount, // Tổng tiền đơn hàng
                    'payment_method' => $request->payment_method,
                    'payment_status' => payment_status('pending')
                ]);
            } else {

                $accountNo = '1527161408'; // Số tài khoản
                $acqId = '970422'; // Mã ngân hàng
                $accountName = urlencode('HUYNH HAN CONG TUAN'); // Tên tài khoản
                $amount = $totalAmount; // Tổng tiền
                $addInfo = $order->order_code; // Nội dung (mã đơn hàng)

                // Tạo URL QR code
                $qrUrl = "https://api.vietqr.io/image/{$acqId}-{$accountNo}-5mDSHQa.jpg?accountName={$accountName}&amount={$amount}&addInfo={$addInfo}";

                // Lưu thông tin thanh toán
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'order_code' => $orderCode, // Lưu mã đơn hàng
                    'payment_status' => payment_status('pending'), // Trạng thái mặc định là 'pending'

                ]);
                $order->update(['payment_id' => $payment->id]);

                // Trả về view với mã QR
                return view('client.cart.qr_code', compact('qrUrl', 'order'));
            }

            // Cập nhật thông tin thanh toán vào đơn hàng
            $order->update(['payment_id' => $payment->id]);

            return view('client.cart.order_successfully', compact('order'));
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác thực.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function generateQR(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)->first();
        // Lấy thông tin từ request
        $accountNo = '1527161408'; // Số tài khoản
        $acqId = '970422'; // Mã ngân hàng
        $accountName = urlencode('HUYNH HAN CONG TUAN'); // Tên tài khoản (URL encode để đảm bảo an toàn)
        $amount = $order->total_amount;

        $addInfo = $order->order_code; // Nội dung mô tả (order_code)

        // Tạo URL dựa trên các tham số đã cung cấp
        $qrUrl = "https://api.vietqr.io/image/{$acqId}-{$accountNo}-5mDSHQa.jpg?accountName={$accountName}&amount={$amount}&addInfo={$addInfo}";

        return response()->json([
            'status' => 'success',
            'qrUrl' => $qrUrl,
        ]);
    }

    public function qr(Request $request)
    {
        // Lấy thông tin từ request
        $accountNo = '1527161408'; // Số tài khoản
        $acqId = '970422'; // Mã ngân hàng
        $accountName = urlencode('HUYNH HAN CONG TUAN'); // Tên tài khoản (URL encode để đảm bảo an toàn)
        $amount = '2000';

        $addInfo = 'ORD-JGHDFG-728734'; // Nội dung mô tả (order_code)

        // Tạo URL dựa trên các tham số đã cung cấp
        $qrUrl = "https://api.vietqr.io/image/{$acqId}-{$accountNo}-5mDSHQa.jpg?accountName={$accountName}&amount={$amount}&addInfo={$addInfo}";

       
        return view("client.cart.qr_code", compact("qrUrl"));
    }

    public function thueapi_hooks(Request $request)
    {
        if (preg_match('/ORD-[A-Z0-9]+-[0-9]+/', $request->content, $matches)) {
            $orderCode = $matches[0]; // Mã order_code được trích xuất
        } else {
            return;
        }
        // Tìm đơn hàng theo mã
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        // Kiểm tra số tiền
        if ($request->money === $order->amount) {
            $payment = Payment::where('id', $order->payment_id)->first();

            // Kiểm tra trạng thái thanh toán trước khi cập nhật
            if ($payment->payment_status !== payment_status('completed')) {
                $payment->update(['payment_status' => payment_status('completed')]);
            }

            return;
        }
        return;
    }

    public function checkPayment(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)->first();
        if($order->payment->payment_status === payment_status('completed')) {
            return true;
        }
    
        return false;
    }

    public function applyVoucher(Request $request)
    {
        try {
            // Lấy mã voucher từ yêu cầu của người dùng
            $voucherCode = $request->input('voucher_code');
            $totalAmount = $request->input('total_amount'); // Giả sử bạn truyền vào tổng số tiền đơn hàng
    
            // Tìm voucher trong cơ sở dữ liệu
            $voucher = Voucher::where('code', $voucherCode)
                ->whereDate('start_date', '<=', now()) // Kiểm tra ngày bắt đầu
                ->whereDate('end_date', '>=', now()) // Kiểm tra ngày hết hạn
                ->first();
    
            // Kiểm tra nếu voucher hợp lệ
            if ($voucher) {
                // Tính toán giảm giá (giả sử voucher có trường 'discount' là số tiền giảm trực tiếp)
                $discount = $voucher->discount; // Số tiền giảm từ voucher
    
                // Nếu số tiền giảm lớn hơn tổng tiền đơn hàng, thì giới hạn giảm giá bằng tổng tiền đơn hàng
                if ($discount > $totalAmount) {
                    $discount = $totalAmount;
                }
    
                // Trả về phản hồi với thông tin giảm giá
                return response()->json([
                    'success' => true,
                    'discount' => $discount, // Trả về giá trị giảm giá
                    'message' => 'Voucher áp dụng thành công!'
                ]);
            }
    
            // Nếu không tìm thấy voucher hoặc voucher đã hết hạn
            return response()->json([
                'success' => false,
                'message' => 'Voucher không hợp lệ hoặc đã hết hạn.'
            ]);
        } catch (Exception $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

