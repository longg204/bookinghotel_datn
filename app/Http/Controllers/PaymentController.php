<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function vn_payment(Request $request)
    {
        $vnp_TmnCode = "J1TZEB1U"; //Mã website tại VNPAY
        $vnp_HashSecret = "7H0R8NR113SB9ROB4TOT9A3YINJ646GW"; //Chuỗi bí mật
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/cart";
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn phí dich vụ";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 20000 * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

//        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
//            $inputData['vnp_BankCode'] = $vnp_BankCode;
//        }
//        ksort($inputData);
//        $query = "";
//        $i = 0;
//        $hashdata = "";
//        foreach ($inputData as $key => $value) {
//            if ($i == 1) {
//                $hashdata .= '&' . $key . "=" . $value;
//            } else {
//                $hashdata .= $key . "=" . $value;
//                $i = 1;
//            }
//            $query .= urlencode($key) . "=" . urlencode($value) . '&';
//        }
//
//        $vnp_Url = $vnp_Url . "?" . $query;
//        if (isset($vnp_HashSecret)) {
//            // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
//            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
//            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
//        }
//        return redirect($vnp_Url);




        ksort($inputData); // Sắp xếp tham số theo thứ tự ASCII
        $query = http_build_query($inputData); // Tạo chuỗi query cho URL
        $hashdata = urldecode($query); // Tạo chuỗi để ký

        // Tạo chữ ký
        $vnpSecureHash = hash_hmac('sha256', $hashdata, $vnp_HashSecret);

        // Gắn chữ ký vào URL
        $vnp_Url = $vnp_Url . "?" . $query . '&vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;

        // Redirect đến URL thanh toán của VNPAY
        return redirect($vnp_Url);
    }

}
