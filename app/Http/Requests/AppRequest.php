<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AppRequest
{
    /**
     * AuthController: Register validation rules
     */
    public static function authRegister(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * AuthController: Login validation rules
     */
    public static function authLogin(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * SessionController: Store validation rules
     */
    public static function sessionStore(): array
    {
        return [
            'subject' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'session_type' => 'required|in:pomodoro,custom',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * ChallengeController: Update progress validation rules
     */
    public static function challengeUpdateProgress(): array
    {
        return [
            'progress' => 'required|integer|min:0',
        ];
    }

    /**
     * TaskController: Store validation rules
     */
    public static function taskStore(): array
    {
        return [
            'title' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * TaskController: Update validation rules
     */
    public static function taskUpdate(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'color' => 'nullable|string|max:50',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * UserController: Update profile validation rules
     */
    public static function userUpdate($userId): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'avatar' => 'nullable|string',
        ];
    }

    /**
     * ChallengeInviteController: Store validation rules
     */
    public static function inviteStore(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
