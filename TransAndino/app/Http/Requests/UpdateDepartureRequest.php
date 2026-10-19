<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'route_id' => ['required', 'integer', 'exists:routes,id'],
            'bus_id' => ['required', 'integer', 'exists:buses,id'],
            'chofer' => ['required', 'string', 'max:255'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (ValidatorContract $validator) {
                if (! $this->filled('fecha') || ! $this->filled('hora')) {
                    return;
                }

                $inicio = Carbon::parse(
                    $this->input('fecha').' '.$this->input('hora'),
                    config('app.timezone')
                );

                if ($inicio->lessThanOrEqualTo(Carbon::now())) {
                    $validator->errors()->add(
                        'fecha',
                        'La salida debe programarse en una fecha y hora futuras.'
                    );
                }
            },
        ];
    }
}
