<?php

namespace App\Http\Requests\Api\Campaign;

use App\Http\Requests\Api\BaseRequest;

class SkipDeleteLeadRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'lead_id' => 'required',
        ];

        return $rules;
    }
}
