<?php

use App\Models\User;
use App\Models\Testimony;

/** Index */
$app->get('/', function ()  {

    return redirect('/register');
});


/** Registration page */
$app->get('/register', function()  {

    return view('register');
});


/** Handle registration form */
$app->post('/register', function()  {

    /** Get the name and number */
    $name   = $_POST["name"];
    $number = $_POST["number"];

    /** Check if number is already registered */
    $already = User::where('number', $number)->first();
    if ($already) {
        return view('register',[
            "message" => "Whoa, this number appears to already be registered."
        ]);
    }

    /** Check if number is valid by sending SMS */
    /*try {
        sendSMS($number, "Doomsday Buddy registration confirmed!");
    } catch(Exception $e) {
        return view('register', [
            "message" => "That number looks wrong - try again maybe?"
        ]);
    }*/

    /** Create a user row */
    User::create([
        'name'      => $name,
        'number'    => $number,
        'informed'  => 0
    ]);

    /** Get the ID of this user */
    $sender_id = User::where('number', $number)->first()->getKey();

    /** Get all user instances */
    $users = User::all();

    /** Registration successful - redirect to testimony page */
    return view('testimony', [
        "sender_id" => $sender_id,
        "users"     => $users
    ]);

});


/** Test route for testimonies */
$app->get('/testimony', function() {

    /** Get all user instances */
    $users = User::all();

    /** Registration successful - redirect to testimony page */
    return view('testimony', [
        "users"     => $users,
        "sender_id" => 1
    ]);
});


/** Testimony form route */
$app->post('/testimony', function() {

    /** Save testimony */
    Testimony::create([
        "sender_id"     =>  $_POST["sender_id"],
        "recipient_id"  =>  $_POST["recipient_id"],
        "message"       =>  $_POST["message"]
    ]);

    /** Get all user instances  */
    $users = User::all();

    /** Go back to testimony page */
    return view('testimony', [
        "sender_id" => $_POST["sender_id"],
        "users"     => $users,
        "message"   => "Testimony saved successfully"
    ]);
});


/** Update existing number with testimonies */
$app->post('/update', function() {

    /** Find the ID by phone number */
    $user = User::query()->where("number", $_POST["number"])->first();
    if (!$user) return view("register", ["message"=>"Uh oh, couldn't find that number, you sure you are registered?"]);
    $id = $user->getKey();

    /** Get all user instances */
    $users = User::all();

    /** Go to testimony page */
    return view('testimony', [
        "users"     => $users,
        "sender_id" => $id
    ]);

});


/** DOOMSDAY: inform everyone registered */
$app->get('/doomsday/{temp}', function($temp) use ($app) {

    /** Loop through each user */
    $users = User::all();
    foreach ($users as $user) {

        /** If user has already been informed - don't bother him again */
        if ($user->informed) continue;

        /** Send SMS */
        $message = "DOOMSDAY! Global temperatures have risen to ".$temp." Celcius. Human extinction imminent.";
        try {
            sendSMS($user->number, $message);

        } catch (Exception $e) {

        }


        /** Send testimonies from other users */
        sendTestimonies($user->getKey());

        /** Update user informed field */
        $user->informed = 1;
        $user->save();

    }

    return 'Messages sent - may God be with us';
});


/** Test route */
$app->get('/test', function() use ($app) {

    sendTestimonies(1);

});


function sendTestimonies($recipient_id) {

    /** Get the recipient's number */
    $recipient_number = User::query()->where("id", $recipient_id)->first()->number;

    /** Get all testimonies for the doomed recipient */
    $testimonies = Testimony::query()->where("recipient_id", $recipient_id)->get();

    foreach ($testimonies as $testimony) {

        /** Get the sender's name and message */
        $sender = User::query()->where("id", $testimony->sender_id)->first();
        $sender_name = $sender->name;
        $message = "Final message from ".$sender_name. ": ".$testimony["message"];

        /** Send SMS */
        try {
            sendSMS($recipient_number, $message);

        } catch(Exception $e) {

        }

    }

}


function sendSMS($number, $message) {

    $account_sid = 'AC9568cb173d658bf5b8240a97556ac60a';
    $auth_token = '50e3325230062dd59f682828926b8ac1';
    $client = new Services_Twilio($account_sid, $auth_token);

    $client->account->messages->create(array(
        'To' => $number,
        'From' => "+441572460377",
        'Body' => $message,
    ));

};