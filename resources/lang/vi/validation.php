<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The <b>:attribute</b> must be accepted.',
    'active_url' => 'The <b>:attribute</b> is not a valid URL.',
    'after' => 'The <b>:attribute</b> must be a date after :date.',
    'after_or_equal' => 'The <b>:attribute</b> must be a date after or equal to :date.',
    'alpha' => 'The <b>:attribute</b> may only contain letters.',
    'alpha_dash' => 'The <b>:attribute</b> may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The <b>:attribute</b> may only contain letters and numbers.',
    'array' => 'The <b>:attribute</b> must be an array.',
    'before' => 'The <b>:attribute</b> must be a date before :date.',
    'before_or_equal' => 'The <b>:attribute</b> must be a date before or equal to :date.',
    'between' => [
        'numeric' => '<b>:attribute</b> phải nằm khoảng :min và :max.',
        'file' => 'The <b>:attribute</b> must be between :min and :max kilobytes.',
        'string' => 'The <b>:attribute</b> must be between :min and :max characters.',
        'array' => 'The <b>:attribute</b> must have between :min and :max items.',
    ],
    'boolean' => 'The <b>:attribute</b> field must be true or false.',
    'confirmed' => 'The <b>:attribute</b> confirmation does not match.',
    'date' => '<b>:attribute</b> không được để trống.',
    'date_equals' => 'The <b>:attribute</b> must be a date equal to :date.',
    'date_format' => '<b>:attribute</b> phải đúng định dạng :format.',
    'different' => 'The <b>:attribute</b> and <b>:other</b> must be different.',
    'digits' => 'The <b>:attribute</b> must be :digits digits.',
    'digits_between' => 'The <b>:attribute</b> must be between :min and :max digits.',
    'dimensions' => 'The <b>:attribute</b> has invalid image dimensions.',
    'distinct' => 'The <b>:attribute</b> field has a duplicate value.',
    'email' => '<b>:attribute</b> phải đúng định dạng email.',
    'ends_with' => 'The <b>:attribute</b> must end with one of the following: <b>:values</b>',
    'exists' => 'The selected <b>:attribute</b> is invalid.',
    'file' => '<b>:attribute</b> phải là kiểu file.',
    'filled' => 'The <b>:attribute</b> field must have a value.',
    'gt' => [
        'numeric' => 'The <b>:attribute</b> must be greater than :value.',
        'file' => 'The <b>:attribute</b> must be greater than <b>:value</b> kilobytes.',
        'string' => 'The <b>:attribute</b> must be greater than <b>:value</b> characters.',
        'array' => 'The <b>:attribute</b> must have more than <b>:value</b> items.',
    ],
    'gte' => [
        'numeric' => 'The <b>:attribute</b> must be greater than or equal :value.',
        'file' => 'The <b>:attribute</b> must be greater than or equal <b>:value</b> kilobytes.',
        'string' => 'The <b>:attribute</b> must be greater than or equal <b>:value</b> characters.',
        'array' => 'The <b>:attribute</b> must have <b>:value</b> items or more.',
    ],
    'image' => '<b>:attribute</b> phải là hình ảnh.',
    'in' => '<b>:attribute</b> được chọn không có giá trị.',
    'in_array' => 'The <b>:attribute</b> field does not exist in <b>:other</b>.',
    'integer' => '<b>:attribute</b> phải là kiểu số nguyên.',
    'ip' => 'The <b>:attribute</b> must be a valid IP address.',
    'ipv4' => 'The <b>:attribute</b> must be a valid IPv4 address.',
    'ipv6' => 'The <b>:attribute</b> must be a valid IPv6 address.',
    'json' => 'The <b>:attribute</b> must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The <b>:attribute</b> must be less than :value.',
        'file' => 'The <b>:attribute</b> must be less than <b>:value</b> kilobytes.',
        'string' => 'The <b>:attribute</b> must be less than <b>:value</b> characters.',
        'array' => 'The <b>:attribute</b> must have less than <b>:value</b> items.',
    ],
    'lte' => [
        'numeric' => 'The <b>:attribute</b> must be less than or equal :value.',
        'file' => 'The <b>:attribute</b> must be less than or equal <b>:value</b> kilobytes.',
        'string' => 'The <b>:attribute</b> must be less than or equal <b>:value</b> characters.',
        'array' => 'The <b>:attribute</b> must not have more than <b>:value</b> items.',
    ],
    'max' => [
        'numeric' => '<b>:attribute</b> phải nhỏ hơn :max.',
        'file' => '<b>:attribute</b> phải nhỏ hơn :max KB.',
        'string' => '<b>:attribute</b> phải nhỏ hơn :max kí tự.',
        'array' => '<b>:attribute</b> phải nhỏ hơn :max phần tử.',
    ],
    'mimes' => 'file phải có định dạng là: :values.',
    'mimetypes' => 'The <b>:attribute</b> must be a file of type: <b>:values</b>.',
    'min' => [
        'numeric' => '<b>:attribute</b> phải lớn hơn :min.',
        'file' => '<b>:attribute</b> phải lớn hơn :min KB.',
        'string' => '<b>:attribute</b> phải lớn hơn :min kí tự.',
        'array' => '<b>:attribute</b> phải lớn hơn :min phần tử.',
    ],
    'not_in' => 'The selected <b>:attribute</b> is invalid.',
    'not_regex' => 'The <b>:attribute</b> format is invalid.',
    'numeric' => '<b>:attribute</b> phải là kiểu số.',
    'present' => 'The <b>:attribute</b> field must be present.',
    'regex' => '<b>:attribute</b> không đúng định dạng.',
    'required' => '<b>:attribute</b> không được để trống.',
    'required_if' => '<b>:attribute</b> không được để trống.',
    'required_unless' => 'The <b>:attribute</b> field is required unless <b>:other</b> is in <b>:values</b>.',
    'required_with' => 'The <b>:attribute</b> field is required when <b>:values</b> is present.',
    'required_with_all' => 'The <b>:attribute</b> field is required when <b>:values</b> are present.',
    'required_without' => 'The <b>:attribute</b> field is required when <b>:values</b> is not present.',
    'required_without_all' => 'The <b>:attribute</b> field is required when none of <b>:values</b> are present.',
    'same' => '<b>:attribute</b> và <b>:other</b> không giống nhau.',
    'size' => [
        'numeric' => 'The <b>:attribute</b> must be :size.',
        'file' => 'The <b>:attribute</b> must be :size kilobytes.',
        'string' => 'The <b>:attribute</b> must be :size characters.',
        'array' => 'The <b>:attribute</b> must contain :size items.',
    ],
    'starts_with' => 'The <b>:attribute</b> must start with one of the following: <b>:values</b>',
    'string' => 'The <b>:attribute</b> must be a string.',
    'timezone' => 'The <b>:attribute</b> must be a valid zone.',
    'unique' => '<b>:attribute</b> đã tồn tại.',
    'uploaded' => '<b>:attribute</b> không thể upload.',
    'url' => 'The <b>:attribute</b> format is invalid.',
    'uuid' => 'The <b>:attribute</b> must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
