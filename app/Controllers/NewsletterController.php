<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Subscriber;

class NewsletterController extends Controller
{
    /** AJAX newsletter subscription. */
    public function subscribe(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
            $this->json(['success' => false, 'message' => __('newsletter.invalid')], 422);
        }

        $status = Subscriber::subscribe(mb_strtolower($email));
        if ($status === 'already') {
            $this->json(['success' => true, 'message' => __('newsletter.already'), 'already' => true]);
        }

        $this->json(['success' => true, 'message' => __('newsletter.success')]);
    }
}
