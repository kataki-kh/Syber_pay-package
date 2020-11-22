<?php

namespace Kataki\Syber_pay\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kataki\Syber_pay\Models\Payment;
use Illuminate\Support\Facades\DB;

//use App\env('order_model');
class PaymentController extends Controller
{
   
    public function SyberPay(Request $request)
    {
        
        $headers = array( 'Content-Type: application/json');
        $order_model=config('Syber_pay.order_model');
        $order_price_column=config('Syber_pay.order_price_column');
        $customer_name_column=config('Syber_pay.customer_name_column');
        $order_payment_status_column=config('Syber_pay.order_payment_status_column');
        $syberpayURL = config('Syber_pay.syberpayURL') . 'getUrl';

        $applicationId = config('Syber_pay.applicationId');
        $serviceId = config('Syber_pay.serviceId');
        $key = config('Syber_pay.key');
        $salt= config('Syber_pay.salt');
//return $syberpayURL;
        $currencyDesc = 'SDG';
        // $orderId = mt_rand(1,10000);
        $orderId = $request->order_id;
//return $orderId;
      //return   DB::table('tickets')->get();
     
         $get_order=DB::table($order_model.'s')->where('id',$orderId)->first();
         if(!$get_order){
            return response()->json([
                
                "message" => $order_model."    Not Found ",
                "status" => false,

                "code" => 404
            ]);
         }
     //return $get_order->id;
        // $totalAmount = mt_rand(1 , 500);
         $customerName =null;
         $customer_id=null;
         
        $totalAmount = $get_order->$order_price_column;
        if(auth()->check()){
            $customer_id=auth()->user()->id;
             if(isset(auth()->user()->$customer_name_column)){
                $customerName=auth()->user()->$customer_name_column;

                }
    }
        
        $HashedData =  hash('sha256', $key . '|' .$applicationId .'|' .$serviceId .'|' .$totalAmount .'|' .$currencyDesc .'|' .$orderId .'|' . $salt);

        //  Payment info here
        $paymentInfo = array('orderNo' => $orderId , 'customerName' => $customerName );

        // PHP Array contain all request body parameters
        $jsonDataArray = array(
            'applicationId' =>  $applicationId , 
            'serviceId' => $serviceId , 
            'customerRef' => $orderId , 
            'amount' => $totalAmount , 
            'currency' => $currencyDesc , 
            'paymentInfo' => $paymentInfo, 
            'hash' => $HashedData
        );

        // Convert PHP array into JSON object 
        $jsonData = json_encode( $jsonDataArray );

        // Using CURL to send post request 

        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $syberpayURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        // Execute post
        $result = curl_exec($ch);
        curl_close($ch);

        $result_array = json_decode($result, true);
        

        if ( !isset($result_array['paymentUrl']) ) {
            abort(404);
        }
        ///save transaction to the payment table
        
        $Payment=new Payment();
        $Payment->transaction_id=$result_array['transactionId'];
        $Payment->order_id=$orderId;
        $Payment->amount=$totalAmount;
        $Payment->customer_id=$customer_id;
        $Payment->hash=$result_array['hash'];
        $Payment->note='pending payment';
        $Payment->payment_method='syber';
        $Payment->status=0;
        $Payment->save();





        ////

        return $paymentUrl = $result_array['paymentUrl'];
        // return $result_array;

        //return redirect($paymentUrl);

    }
    public function notify(Request $request)
    {


        $headers = array( 'Content-Type: application/json');
        $order_model=config('Syber_pay.order_model');
        $order_price_column=config('Syber_pay.order_price_column');
        $customer_name_column=config('Syber_pay.customer_name_column');
        $order_payment_status_column=config('Syber_pay.order_payment_status_column');
        $syberpayURL = config('Syber_pay.syberpayURL') . 'getUrl';

        $applicationId = config('Syber_pay.applicationId');
        $serviceId = config('Syber_pay.serviceId');
        $key = config('Syber_pay.key');
        $salt= config('Syber_pay.salt');
        

        
        
        
        
        if ($request->transactionId) {
            $transactionId = $request->transactionId;

            $headers = array('Content-Type: application/json');
            $hashData = hash('sha256', $key . '|' . $applicationId . '|' . $transactionId . '|' . $salt);
            $jsonData = '{"applicationId":"' . $applicationId . '","transactionId":"' . $transactionId . '","hash":"' . $hashData . '"}';
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $syberpayURL . 'payment_status');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            // Execute post
            $result = curl_exec($ch);
            curl_close($ch);
            $result_array = json_decode($result, true);

//$result_array['payment']['status'] == "Successful" or
            if ($result_array['payment']['status'] == "Successful") {


                // PAID RESERVATION ..
                $Payment = Payment::where('transaction_id', $request->transactionId)->first();
                $Payment->payment_method = 1;
                $Payment->status = 1;


                $Payment_save = $Payment->save();
                $check_id = $Payment->order_id;
                $Order = DB::table($order_model.'s')->where('id',$check_id)->first();


            }
        }
        if ($Order) {
            //$Order->$order_payment_status_column = 2;//payed
            DB::update('update '.$order_model.'s'.' set '.$order_payment_status_column.' = ? where id = ?',[2,$check_id]);
           // $Order = DB::table($order_model.'s')->where('id',$check_id)->first();
           // return $Order;
            //$Order_save = $Order->update();
            
                $response = APIHelpers::creatAPIResponse(false, '200', 'added to orders', null);
                return response()->json($response, 200);

            
        }
        
        
        


    }
    
    ///return function
    public function return(Request $request)
    {
        $customerRef = $request->customerRef;
        $Payment = Payment::where('order_id', $customerRef)->first();

        if ($Payment) {
            if ($Payment->status == 1) {
                $response = APIHelpers::creatAPIResponse(false, '200', 'payment done please go back', null);
                ///change the returned view
                 $url=  url('')."/vendor/syber-pay/9912-payment-success.mp4";
                $message=config('Syber_pay.success_message');
                return view('syber-pay::success',[
            'url'=>$url,
            'message'=>$message,


        ]);
               /// return  $response;
               /// return view('payment::payment.success');
            } else {
                $response = APIHelpers::creatAPIResponse(true, '400', 'payment error please try again', null);
                ///change the returned view
                $url=  url('')."/vendor/syber-pay/9913-payment-failed.mp4";
                $message=config('Syber_pay.error_message');
                return view('syber-pay::cancel',[
            'url'=>$url,
            'message'=>$message,


        ]);
               // return  $response;
               // return view('payment::payment.failed');
            }
        }

    }
///
///cancel function
    public function cancel(Request $request)
    {
        $url=  url('')."/vendor/syber-pay/9913-payment-failed.mp4";
        $message=config('Syber_pay.cancel_message');
        
        return view('syber-pay::cancel',[
            'url'=>$url,
            'message'=>$message,


        ]);


    }
}
