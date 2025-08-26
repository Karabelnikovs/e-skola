<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\PendingUser;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Mail;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = [
                'name' => $row['vards'] ?? null,
                'email' => $row['e_pasts'] ?? null,
                'language' => $row['valoda'] ?? null,
            ];

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|unique:pending_users,email',
                'language' => 'required|in:lv,en,ru,ua',
            ]);

            if ($validator->fails()) {
                Log::warning('User import validation failed', [
                    'data' => $data,
                    'errors' => $validator->errors()->all()
                ]);
                continue;
            }

            $token = Str::random(32);
            $expires_at = now()->addDays(7);

            $pending = PendingUser::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'language' => $data['language'],
                'token' => $token,
                'expires_at' => $expires_at,
            ]);

            Mail::to($pending->email)->send(new InvitationEmail($pending));
        }
    }
}