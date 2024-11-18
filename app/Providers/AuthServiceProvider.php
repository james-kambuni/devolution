<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


// Policies
use App\Policies\CommentPolicy;
use App\Policies\LoanPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\UserPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\PaymentPolicy;

// Models
use App\Models\Comment;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Payment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Comment::class => CommentPolicy::class,
        Loan::class => LoanPolicy::class,
        Notification::class => NotificationPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Payment::class => PaymentPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
