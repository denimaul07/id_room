<?php
namespace App\Services\Contact;

use App\Models\ContactMe;


class ContactService
{
    public function list($search = null, $pagging = 10)
    {
        return ContactMe::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        })->paginate($pagging);
    }

}
