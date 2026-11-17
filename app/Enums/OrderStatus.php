<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAYMENT_UNDER_REVIEW = 'payment_under_review';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}