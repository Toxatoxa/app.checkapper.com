<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Http\Requests\UnSubscribeRequest;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function subscribe(SubscribeRequest $request)
    {
        Subscription::create($request->all());

        return ['text' => 'You have been successfully subscribed!'];
    }

    public function unSubscribe(UnSubscribeRequest $request)
    {
        $subscription = Subscription::where('id', hashids()->decode($request->get('code')))
            ->where('email', $request->get('email'))
            ->first();

        if (!$subscription) {
            return response()->json(['text' => 'The error has occurred'], 404);
        }

        $subscription->delete();

        return ['text' => 'You have been successfully unsubscribed!'];
    }
}
