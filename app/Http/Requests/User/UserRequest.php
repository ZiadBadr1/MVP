<?php

namespace App\Http\Requests\User;

use App\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $authUser = auth()->user();
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => $this->isMethod('post')
                ? 'required|string|min:6'
                : 'nullable|string|min:6',
            'role' => [
                $this->isMethod('post') ? 'nullable' : 'required',
                'exists:roles,name',
            ],
            'tenant_id' => [$authUser->isSuperAdmin() ?   'required' : 'nullable',
                'exists:tenants,id'],
        ];
    }
}
