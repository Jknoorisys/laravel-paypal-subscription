<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Plans;
use App\Models\Subscriptions;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    private $provider;

    public function __construct() {
        // Multiligual
        $lang = (isset($_POST['language']) && !empty($_POST['language'])) ? $_POST['language'] : 'en';
        App::setlocale($lang);

        // Set the API credentials
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $paypalToken = $this->provider->getAccessToken();
    }

    public function subscribe(Request $request)
    {
        // Input validation
        $validator = Validator::make($request->all(), [
            'language' => [
                'required' ,
                Rule::in(['en','hi','ur','bn','ar','in','ms','tr','fa','fr','de','es']),
            ],
            'user_id'   => 'required||numeric',
            'plan_id' =>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.Validation Failed!'),
                'errors'    => $validator->errors()
            ],400);
        }

        try {
            // set return and cancel urls
            $return_url = url('api/success');
            $cancel_url = url('api/cancel');

            // Check if the user exists in the database, if not throw error
            $user = User::where('id', '=', $request->user_id)->first();
            if (empty($user)) {
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.User Not Found!'),
                ],400);
            }

            // Check if the plan exists, if not throw error
            $plan_id = $request->plan_id;
            $plan = Plans::where('plan_id', '=', $plan_id)->first();
            if (empty($plan)) {
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.Invalid Plan!'),
                ],400);
            }

            // Call the provider and add the product, daily plan and setup subscription
            $response = $this->provider->addBillingPlanById($request->plan_id)
                                        ->setReturnAndCancelUrl($return_url, $cancel_url)
                                        ->setupSubscription($user->name, $user->email, Carbon::now()->addMinutes(5));
            
            // Check the response and redirect to the approval page if successful
            if ($response && !empty($response)) {
                $data = [
                    'user_id'   => $request->user_id,
                    'paypal_url' => $response['links'][0]['href'],
                    'subscription_id' => $response['id'],
                    'plan_id'   => $plan->plan_id,
                    'plan_name' => $plan->name,
                    'currency'  => $plan->currency,
                    'price'     => $plan->price,
                    'status'    => $response['status'],
                    'created_at' => Carbon::now()	
                ];

                // insert subscription details in subscription table
                $insert = Subscriptions::insert($data);

                if ($insert) {
                    return response()->json([
                        'status'    => 'success',
                        'message'   => trans('msg.Subscription Created!'),
                        'data'      => $response
                    ],200);
                } else {
                    return response()->json([
                        'status'    => 'failed',
                        'message'   => trans('msg.error'),
                    ],400);
                }
            }else{
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.Unable to Subscribe, Please Try again...'),
                ],400);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.error'),
                'error'     => $e->getMessage()
            ],500);
        }
    }

    public function success(Request $request)
    {
        // Input validation
        $validator = Validator::make($request->all(), [
            'language' => [
                'required' ,
                Rule::in(['en','hi','ur','bn','ar','in','ms','tr','fa','fr','de','es']),
            ],
            'subscription_id'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.Validation Failed!'),
                'errors'    => $validator->errors()
            ],400);
        }

        try {
             
            $subscription_id = $request->subscription_id;

            // retrive subscription details
            $response = $this->provider->showSubscriptionDetails($subscription_id);

            if (!empty($response) && $response['status'] == 'ACTIVE') {

                // upadate subscription details in table
                $data = [
                    'status' => $response['status'],
                    'start_date' => $response['start_time'],
                    'end_date'   => $response['billing_info']['next_billing_time']
                ];

                $update = Subscriptions::where('subscription_id', '=', $subscription_id)->update($data);
                return response()->json([
                    'status'    => 'success',
                    'message'   => trans('msg.Subscription Succesful!'),
                ],200);
                
            } else {
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.error'),
                    'data'      => $response['links']
                ],400);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.error'),
                'error'     => $e->getMessage()
            ],500);
        }
    }

    public function cancel(Request $request)
    {
        // Input validation
        $validator = Validator::make($request->all(), [
            'language' => [
                'required' ,
                Rule::in(['en','hi','ur','bn','ar','in','ms','tr','fa','fr','de','es']),
            ],
            'subscription_id'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.Validation Failed!'),
                'errors'    => $validator->errors()
            ],400);
        }

        try {
            
            $subscription_id = $request->subscription_id;

            // retrive subscription details
            $response = $this->provider->showSubscriptionDetails($subscription_id);

            if (!empty($response) && $response['status'] == 'APPROVAL_PENDING') {

                // cancel subscription
                // $cancel = $this->provider->cancelSubscription($subscription_id, 'cancel');
                // return $cancel;

                // upadate subscription details in table
                $data = [
                    'status' => 'CANCELED',
                ];

                $update = Subscriptions::where('subscription_id', '=', $subscription_id)->update($data);

                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.Subscription Canceled'),
                ],400);
                
            } elseif(!empty($response) && $response['status'] == 'ACTIVE') {
                // if subscription is already activated, throw error
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.Already Subscribed'),
                ],400);
            }else{
                return response()->json([
                    'status'    => 'failed',
                    'message'   => trans('msg.error'),
                ],400);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'    => 'failed',
                'message'   => trans('msg.error'),
                'error'     => $e->getMessage()
            ],500);
        }
    }
}
