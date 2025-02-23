<?php

return [

    /*
    |----------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |----------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, doğrulayıcı sınıfı tarafından kullanılan varsayılan hata mesajlarını içerir.
    | Bazı kuralların birden fazla versiyonu vardır, örneğin boyut kuralları. Bu mesajları burada özelleştirebilirsiniz.
    |
    */

    'accepted' => ':attribute alanı kabul edilmelidir.',
    'active_url' => ':attribute alanı geçerli bir URL değil.',
    'after' => ':attribute alanı :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute alanı :date tarihinden sonra veya aynı tarihte bir tarih olmalıdır.',
    'alpha' => ':attribute alanı yalnızca harf içerebilir.',
    'alpha_dash' => ':attribute alanı yalnızca harfler, sayılar, tireler ve alt çizgiler içerebilir.',
    'alpha_num' => ':attribute alanı yalnızca harfler ve sayılar içerebilir.',
    'array' => ':attribute alanı bir dizi olmalıdır.',
    'before' => ':attribute alanı :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute alanı :date tarihinden önce veya aynı tarihte bir tarih olmalıdır.',
    'between' => [
        'numeric' => ':attribute alanı :min ile :max arasında bir değer olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :min ile :max kilobayt arasında olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :min ile :max karakter arasında olmalıdır.',
        'array' => ':attribute alanı :min ile :max arasında öğe içermelidir.',
    ],
    'boolean' => ':attribute alanı true veya false olmalıdır.',
    'confirmed' => ':attribute alanının onayı uyuşmuyor.',
    'date' => ':attribute alanı geçerli bir tarih değil.',
    'date_equals' => ':attribute alanı, :date ile aynı tarihte olmalıdır.',
    'date_format' => ':attribute alanının formatı :format formatıyla uyuşmuyor.',
    'different' => ':attribute ve :other alanları farklı olmalıdır.',
    'digits' => ':attribute alanı :digits basamaktan oluşmalıdır.',
    'digits_between' => ':attribute alanı :min ile :max basamak arasında olmalıdır.',
    'dimensions' => ':attribute alanındaki görsel geçersiz boyutlara sahip.',
    'distinct' => ':attribute alanı birden fazla aynı değere sahip.',
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute alanı şu değerlerden biriyle bitmelidir: :values.',
    'exists' => ':attribute için seçilen değer geçersiz.',
    'file' => ':attribute alanı bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değer içermelidir.',
    'gt' => [
        'numeric' => ':attribute alanı :value değerinden büyük olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :value kilobayttan büyük olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :value karakterden uzun olmalıdır.',
        'array' => ':attribute alanı :value öğeden fazla öğe içermelidir.',
    ],
    'gte' => [
        'numeric' => ':attribute alanı :value değerine eşit veya büyük olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :value kilobayttan eşit veya büyük olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :value karakterden eşit veya uzun olmalıdır.',
        'array' => ':attribute alanı en az :value öğe içermelidir.',
    ],
    'image' => ':attribute alanı bir resim olmalıdır.',
    'in' => ':attribute için seçilen değer geçersiz.',
    'in_array' => ':attribute alanı :other içinde bulunmamaktadır.',
    'integer' => ':attribute alanı bir tam sayı olmalıdır.',
    'ip' => ':attribute alanı geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute alanı geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute alanı geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute alanı geçerli bir JSON dizesi olmalıdır.',
    'lt' => [
        'numeric' => ':attribute alanı :value değerinden küçük olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :value kilobayttan küçük olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :value karakterden kısa olmalıdır.',
        'array' => ':attribute alanı :value öğeden az öğe içermelidir.',
    ],
    'lte' => [
        'numeric' => ':attribute alanı :value değerine eşit veya küçük olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :value kilobayttan eşit veya küçük olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :value karakterden eşit veya kısa olmalıdır.',
        'array' => ':attribute alanı en fazla :value öğe içermelidir.',
    ],

    'max' => [
        'numeric' => ':attribute alanı :max değerinden büyük olamaz.',
        'file' => ':attribute alanındaki dosya boyutu :max kilobayttan büyük olamaz.',
        'string' => ':attribute alanındaki metin uzunluğu :max karakterden büyük olamaz.',
        'array' => ':attribute alanı :max öğeden fazla öğe içeremez.',
    ],
    'mimes' => ':attribute alanı şu türde bir dosya olmalıdır: :values.',
    'mimetypes' => ':attribute alanı şu türde bir dosya olmalıdır: :values.',
    'min' => [
        'numeric' => ':attribute alanı en az :min olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu en az :min kilobayt olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu en az :min karakter olmalıdır.',
        'array' => ':attribute alanı en az :min öğe içermelidir.',
    ],
    'multiple_of' => ':attribute alanı :value sayısının katı olmalıdır.',
    'not_in' => ':attribute için seçilen değer geçersiz.',
    'not_regex' => ':attribute formatı geçersiz.',
    'numeric' => ':attribute alanı bir sayı olmalıdır.',
    'password' => 'Şifre yanlış.',
    'present' => ':attribute alanı mevcut olmalıdır.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_if' => ':attribute alanı, :other :value değerine sahip olduğunda gereklidir.',
    'required_unless' => ':attribute alanı, :other :values içinde olmadığı sürece gereklidir.',
    'required_with' => ':attribute alanı, :values mevcut olduğunda gereklidir.',
    'required_with_all' => ':attribute alanı, :values tamamı mevcut olduğunda gereklidir.',
    'required_without' => ':attribute alanı, :values mevcut olmadığında gereklidir.',
    'required_without_all' => ':attribute alanı, :values hiçbirisi mevcut olmadığında gereklidir.',
    'same' => ':attribute ve :other alanları aynı olmalıdır.',
    'size' => [
        'numeric' => ':attribute alanı :size olmalıdır.',
        'file' => ':attribute alanındaki dosya boyutu :size kilobayt olmalıdır.',
        'string' => ':attribute alanındaki metin uzunluğu :size karakter olmalıdır.',
        'array' => ':attribute alanı :size öğe içermelidir.',
    ],
    'starts_with' => ':attribute alanı şu değerlerden biriyle başlamalıdır: :values.',
    'string' => ':attribute alanı bir metin olmalıdır.',
    'timezone' => ':attribute alanı geçerli bir zaman dilimi olmalıdır.',
    'unique' => ':attribute alanı zaten alınmış.',
    'uploaded' => ':attribute yükleme başarısız oldu.',
    'url' => ':attribute formatı geçersiz.',
    'uuid' => ':attribute alanı geçerli bir UUID olmalıdır.',

    /*
    |----------------------------------------------------------------------
    | Özel Doğrulama Dil Satırları
    |----------------------------------------------------------------------
    |
    | Burada, belirli bir özellik kuralı için özel doğrulama mesajları
    | belirleyebilirsiniz. Bu, belirli bir özellik kuralı için hızlıca
    | özel bir dil satırı belirtmeyi sağlar.
    |
    */
    'custom' => [
        'order_price' => [
            'min' => 'Minimum sipariş tutarı :min. Lütfen sepetinize daha fazla ürün ekleyin.',
        ],
        'address_id' => [
            'required' => 'Lütfen adresinizi seçin.',
        ],
        'stripe_payment_error_action' => [
            'required' => 'Ödeme girişimi tamamlanmadan önce ek işlemler yapılması gerekiyor.',
        ],
        'stripe_payment_failure' => [
            'required' => 'Ödeme girişimi, yetersiz bakiye gibi farklı sebeplerden dolayı başarısız oldu. Lütfen bilgilerinizi kontrol edin.',
        ],
        'paypal_payment_error_action' => [
            'required' => 'Ödeme girişimi tamamlanmadan önce ek işlemler yapılması gerekiyor.',
        ],
        'general_payment_error_action' => [
            'required' => 'Ödeme girişimi başarısız oldu. Sistem yöneticisiyseniz, ödeme sağlayıcısında bir sorun olup olmadığını kontrol edin.',
        ],
        'link_payment_error_action' => [
            'required' => 'Link tabanlı ödeme yöntemi bulunamadı.',
        ],
        'paypal_payment_approval_missing' => [
            'required' => 'PayPal ödemesi için onay alınamadı.',
        ],
        'mollie_error_action' => [
            'required' => 'Ödeme bağlantısı alınırken bir hata oluştu.',
        ],
        'paystack_error_action' => [
            'required' => 'PayStack ile iletişimde bir hata oluştu.',
        ],
        'dinein_table_id' => [
            'required' => 'Lütfen masanızı seçin.',
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Özel Doğrulama Özellik Adları
    |----------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, özellik yer tutucumuzu daha okunabilir
    | hale getirecek şekilde değiştirir, örneğin "E-posta Adresi" yerine
    | "email" yerine. Bu, mesajımızı daha anlaşılır hale getirir.
    |
    */
    'attributes' => [],
];
