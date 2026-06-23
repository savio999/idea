<?php

namespace App\Http\Requests;

use App\IdeaStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! is_array($this->steps)) {
            return;
        }

        $this->merge([
            'steps' => collect($this->steps)
                ->map(function (array|string $step): array {
                    if (is_array($step)) {
                        return [
                            'description' => $step['description'] ?? '',
                            'completed' => (bool) ($step['completed'] ?? false),
                        ];
                    }

                    return [
                        'description' => $step,
                        'completed' => false,
                    ];
                })
                ->values()
                ->all(),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'links' => 'nullable|array',
            'links.*' => 'url|max:255',
            'steps' => 'nullable|array|filled',
            'steps.*.description' => 'required|string|max:255',
            'steps.*.completed' => 'boolean',
            'image' => 'nullable|image|max:5120',
        ];
    }
}
