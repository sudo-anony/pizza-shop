<?php

return [

    /*
    |----------------------------------------------------------------------
    | Validation Language Lines
    |----------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول حقل :attribute.',
    'active_url' => 'حقل :attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون حقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'حقل :attribute يجب أن يحتوي فقط على أحرف.',
    'alpha_dash' => 'حقل :attribute يجب أن يحتوي فقط على أحرف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num' => 'حقل :attribute يجب أن يحتوي فقط على أحرف وأرقام.',
    'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
    'before' => 'يجب أن يكون حقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن يكون حقل :attribute بين :min و :max.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون بين :min و :max كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون بين :min و :max حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على بين :min و :max عنصرًا.',
    ],
    'boolean' => 'حقل :attribute يجب أن يكون true أو false.',
    'confirmed' => 'تأكيد حقل :attribute غير مطابق.',
    'date' => 'حقل :attribute ليس تاريخًا صالحًا.',
    'date_equals' => 'حقل :attribute يجب أن يكون تاريخًا مساوٍ لـ :date.',
    'date_format' => 'تنسيق حقل :attribute غير مطابق للتنسيق :format.',
    'different' => 'يجب أن يكون حقل :attribute و :other مختلفين.',
    'digits' => 'يجب أن يحتوي حقل :attribute على :digits أرقام.',
    'digits_between' => 'يجب أن يحتوي حقل :attribute على بين :min و :max أرقام.',
    'dimensions' => 'صورة حقل :attribute لها أبعاد غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'حقل :attribute يجب أن يكون عنوان بريد إلكتروني صالحًا.',
    'ends_with' => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values.',
    'exists' => 'القيمة المحددة لـ :attribute غير صالحة.',
    'file' => 'حقل :attribute يجب أن يكون ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون حقل :attribute أكبر من :value.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أكبر من :value كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون أكبر من :value حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على أكثر من :value عنصرًا.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون حقل :attribute أكبر من أو يساوي :value.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أكبر من أو يساوي :value كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون أكبر من أو يساوي :value حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على :value عنصرًا أو أكثر.',
    ],
    'image' => 'حقل :attribute يجب أن يكون صورة.',
    'in' => 'القيمة المحددة لـ :attribute غير صالحة.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => 'حقل :attribute يجب أن يكون عددًا صحيحًا.',
    'ip' => 'حقل :attribute يجب أن يكون عنوان IP صالحًا.',
    'ipv4' => 'حقل :attribute يجب أن يكون عنوان IPv4 صالحًا.',
    'ipv6' => 'حقل :attribute يجب أن يكون عنوان IPv6 صالحًا.',
    'json' => 'حقل :attribute يجب أن يكون سلسلة JSON صالحة.',
    'lt' => [
        'numeric' => 'يجب أن يكون حقل :attribute أصغر من :value.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أصغر من :value كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون أصغر من :value حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على أقل من :value عنصرًا.',
    ],
    'lte' => [
        'numeric' => 'يجب أن يكون حقل :attribute أصغر من أو يساوي :value.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أصغر من أو يساوي :value كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون أصغر من أو يساوي :value حرفًا.',
        'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :value عنصرًا.',
    ],
    'max' => [
        'numeric' => 'حقل :attribute يجب ألا يكون أكبر من :max.',
        'file' => 'حجم الملف في حقل :attribute يجب ألا يكون أكبر من :max كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب ألا يكون أكبر من :max حرفًا.',
        'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :max عنصرًا.',
    ],
    'mimes' => 'حقل :attribute يجب أن يكون ملفًا من النوع: :values.',
    'mimetypes' => 'حقل :attribute يجب أن يكون ملفًا من النوع: :values.',
    'min' => [
        'numeric' => 'حقل :attribute يجب أن يكون على الأقل :min.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون على الأقل :min كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون على الأقل :min حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على الأقل :min عنصرًا.',
    ],
    'multiple_of' => 'حقل :attribute يجب أن يكون من مضاعفات :value.',
    'not_in' => 'القيمة المحددة لـ :attribute غير صالحة.',
    'not_regex' => 'تنسيق حقل :attribute غير صالح.',
    'numeric' => 'حقل :attribute يجب أن يكون عددًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'حقل :attribute يجب أن يكون موجودًا.',
    'regex' => 'تنسيق حقل :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other موجودًا في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all' => 'حقل :attribute مطلوب عندما تكون جميع القيم في :values موجودة.',
    'required_without' => 'حقل :attribute مطلوب عندما لا يكون :values موجودًا.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من القيم في :values موجودة.',
    'same' => 'حقل :attribute و :other يجب أن يتطابقا.',
    'size' => [
        'numeric' => 'حقل :attribute يجب أن يكون :size.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون :size كيلو بايت.',
        'string' => 'طول النص في حقل :attribute يجب أن يكون :size حرفًا.',
        'array' => 'حقل :attribute يجب أن يحتوي على :size عنصرًا.',
    ],
    'starts_with' => 'حقل :attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => 'حقل :attribute يجب أن يكون نصًا.',
    'timezone' => 'حقل :attribute يجب أن يكون منطقة زمنية صالحة.',
    'unique' => 'حقل :attribute قد تم أخذه بالفعل.',
    'uploaded' => 'فشل تحميل حقل :attribute.',
    'url' => 'تنسيق حقل :attribute غير صالح.',
    'uuid' => 'حقل :attribute يجب أن يكون UUID صالحًا.',


    /*
    |----------------------------------------------------------------------
    | Custom Validation Language Lines
    |----------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'order_price' => [
            'min' => 'الحد الأدنى لقيمة الطلب هو :min. يرجى إضافة مزيد من العناصر إلى السلة.',
        ],
        'address_id' => [
            'required' => 'يرجى اختيار عنوانك.',
        ],
        'stripe_payment_error_action' => [
            'required' => 'فشل محاولة الدفع لأن هناك إجراءات إضافية مطلوبة قبل إتمامها.',
        ],
        'stripe_payment_failure' => [
            'required' => 'فشل محاولة الدفع بسبب أسباب أخرى، مثل عدم وجود رصيد كافٍ. يرجى التحقق من البيانات المقدمة.',
        ],
        'paypal_payment_error_action' => [
            'required' => 'فشل محاولة الدفع لأن هناك إجراءات إضافية مطلوبة قبل إتمامها.',
        ],
        'general_payment_error_action' => [
            'required' => 'فشلت محاولة الدفع. إذا كنت مسؤول النظام، تحقق من المشكلة مع مزود الدفع.',
        ],
        'link_payment_error_action' => [
            'required' => 'لم يتم العثور على طريقة الدفع عبر الرابط.',
        ],
        'paypal_payment_approval_missing' => [
            'required' => 'لم نتمكن من الحصول على موافقة للدفع عبر PayPal.',
        ],
        'mollie_error_action' => [
            'required' => 'فشل في الحصول على رابط الدفع.',
        ],
        'paystack_error_action' => [
            'required' => 'فشل في الاتصال بـ PayStack.',
        ],
        'dinein_table_id' => [
            'required' => 'يرجى اختيار طاولتك.',
        ],
    ],


    /*
    |----------------------------------------------------------------------
    | Custom Validation Attributes
    |----------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'confirm_password' => 'تأكيد كلمة المرور',
        'first_name' => 'الاسم الأول',
        'last_name' => 'الاسم الأخير',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'country' => 'البلد',
        'phone' => 'الهاتف',
        'postal_code' => 'الرمز البريدي',
        'country_id' => 'الدولة',
        'language' => 'اللغة',
        'order_total' => 'إجمالي الطلب',
    ],
];
