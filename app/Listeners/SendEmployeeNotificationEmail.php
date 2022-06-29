<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\EmployeeNotification;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;

class SendEmployeeNotificationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $employee = Employee::find($event->employee_id)->first();
        Mail::send('emails.event_new_employee_email', ['employee'=> $employee], function($message) use ($employee){
            $message->to($employee->email);
            $message->subject('Welcome to the company');
            $message->from('info@ayhoo.com', 'Company Task');
        });
    }
}
