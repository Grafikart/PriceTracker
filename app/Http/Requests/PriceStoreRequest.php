<?php

namespace App\Http\Requests;

use App\Domain\Trackers\PriceTrackerFactory;
use App\Domain\Trackers\PriceTrackerInterface;
use Illuminate\Foundation\Http\FormRequest;

class PriceStoreRequest extends FormRequest
{
    public PriceTrackerInterface $tracker;

    public function __construct(private readonly PriceTrackerFactory $factory) {}

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => [
                'required',
                $this->checkURL(...),
            ],
        ];
    }

    public function checkURL(string $attribute, mixed $value, \Closure $fail)
    {
        $trackers = $this->factory->all();
        foreach ($trackers as $tracker) {
            if ($tracker->checkUrl($value)) {
                $this->tracker = $tracker;

                return;
            }
        }
        $fail(__('This link cannot be handled by the platform'));
    }
}
