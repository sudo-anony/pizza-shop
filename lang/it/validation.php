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

    'accepted' => 'Il campo :attribute deve essere accettato.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva a :date.',
    'after_or_equal' => 'Il campo :attribute deve essere una data successiva o uguale a :date.',
    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, trattini e underscore.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array' => 'Il campo :attribute deve essere un array.',
    'before' => 'Il campo :attribute deve essere una data precedente a :date.',
    'before_or_equal' => 'Il campo :attribute deve essere una data precedente o uguale a :date.',
    'between' => [
        'numeric' => 'Il campo :attribute deve essere compreso tra :min e :max.',
        'file' => 'La dimensione del file nel campo :attribute deve essere tra :min e :max kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere tra :min e :max caratteri.',
        'array' => 'Il campo :attribute deve contenere tra :min e :max elementi.',
    ],
    'boolean' => 'Il campo :attribute deve essere true o false.',
    'confirmed' => 'La conferma del campo :attribute non corrisponde.',
    'date' => 'Il campo :attribute non è una data valida.',
    'date_equals' => 'Il campo :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il formato del campo :attribute non corrisponde al formato :format.',
    'different' => 'I campi :attribute e :other devono essere diversi.',
    'digits' => 'Il campo :attribute deve contenere :digits cifre.',
    'digits_between' => 'Il campo :attribute deve contenere tra :min e :max cifre.',
    'dimensions' => 'L\'immagine nel campo :attribute ha dimensioni non valide.',
    'distinct' => 'Il campo :attribute ha un valore duplicato.',
    'email' => 'Il campo :attribute deve essere un\'email valida.',
    'ends_with' => 'Il campo :attribute deve terminare con uno dei seguenti valori: :values.',
    'exists' => 'Il valore selezionato per :attribute non è valido.',
    'file' => 'Il campo :attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve contenere un valore.',
    'gt' => [
        'numeric' => 'Il campo :attribute deve essere maggiore di :value.',
        'file' => 'La dimensione del file nel campo :attribute deve essere maggiore di :value kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere maggiore di :value caratteri.',
        'array' => 'Il campo :attribute deve contenere più di :value elementi.',
    ],
    'gte' => [
        'numeric' => 'Il campo :attribute deve essere maggiore o uguale a :value.',
        'file' => 'La dimensione del file nel campo :attribute deve essere maggiore o uguale a :value kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere maggiore o uguale a :value caratteri.',
        'array' => 'Il campo :attribute deve contenere :value o più elementi.',
    ],
    'image' => 'Il campo :attribute deve essere un\'immagine.',
    'in' => 'Il valore selezionato per :attribute non è valido.',
    'in_array' => 'Il campo :attribute non esiste in :other.',
    'integer' => 'Il campo :attribute deve essere un numero intero.',
    'ip' => 'Il campo :attribute deve essere un\'indirizzo IP valido.',
    'ipv4' => 'Il campo :attribute deve essere un\'indirizzo IPv4 valido.',
    'ipv6' => 'Il campo :attribute deve essere un\'indirizzo IPv6 valido.',
    'json' => 'Il campo :attribute deve essere una stringa JSON valida.',
    'lt' => [
        'numeric' => 'Il campo :attribute deve essere minore di :value.',
        'file' => 'La dimensione del file nel campo :attribute deve essere minore di :value kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere minore di :value caratteri.',
        'array' => 'Il campo :attribute deve contenere meno di :value elementi.',
    ],
    'lte' => [
        'numeric' => 'Il campo :attribute deve essere minore o uguale a :value.',
        'file' => 'La dimensione del file nel campo :attribute deve essere minore o uguale a :value kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere minore o uguale a :value caratteri.',
        'array' => 'Il campo :attribute non deve contenere più di :value elementi.',
    ],

    'max' => [
        'numeric' => 'Il campo :attribute non può essere maggiore di :max.',
        'file' => 'La dimensione del file nel campo :attribute non può essere maggiore di :max kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute non può essere maggiore di :max caratteri.',
        'array' => 'Il campo :attribute non può contenere più di :max elementi.',
    ],
    'mimes' => 'Il campo :attribute deve essere un file del tipo: :values.',
    'mimetypes' => 'Il campo :attribute deve essere un file del tipo: :values.',
    'min' => [
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'file' => 'La dimensione del file nel campo :attribute deve essere almeno :min kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere almeno :min caratteri.',
        'array' => 'Il campo :attribute deve contenere almeno :min elementi.',
    ],
    'multiple_of' => 'Il campo :attribute deve essere un multiplo di :value',
    'not_in' => 'Il valore selezionato per :attribute non è valido.',
    'not_regex' => 'Il formato per :attribute non è valido.',
    'numeric' => 'Il campo :attribute deve essere un numero.',
    'password' => 'La password è errata.',
    'present' => 'Il campo :attribute deve essere presente.',
    'regex' => 'Il formato per :attribute non è valido.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_if' => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless' => 'Il campo :attribute è obbligatorio a meno che :other non sia in :values.',
    'required_with' => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è obbligatorio quando tutti i :values sono presenti.',
    'required_without' => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno dei :values è presente.',
    'same' => 'Il campo :attribute e :other devono corrispondere.',
    'size' => [
        'numeric' => 'Il campo :attribute deve essere :size.',
        'file' => 'La dimensione del file nel campo :attribute deve essere di :size kilobyte.',
        'string' => 'La lunghezza del testo nel campo :attribute deve essere di :size caratteri.',
        'array' => 'Il campo :attribute deve contenere :size elementi.',
    ],
    'starts_with' => 'Il campo :attribute deve iniziare con uno dei seguenti valori: :values.',
    'string' => 'Il campo :attribute deve essere una stringa.',
    'timezone' => 'Il campo :attribute deve essere una zona oraria valida.',
    'unique' => 'Il campo :attribute è già stato preso.',
    'uploaded' => 'Il caricamento del :attribute è fallito.',
    'url' => 'Il formato per :attribute non è valido.',
    'uuid' => 'Il campo :attribute deve essere una UUID valida.',


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
        'order_price' => [
            'min' => 'Il valore minimo dell\'ordine è :min. Aggiungi altri articoli al carrello.',
        ],
        'address_id' => [
            'required' => 'Seleziona il tuo indirizzo.',
        ],
        'stripe_payment_error_action' => [
            'required' => 'Il tentativo di pagamento è fallito perché sono necessarie azioni aggiuntive per completarlo.',
        ],
        'stripe_payment_failure' => [
            'required' => 'Il tentativo di pagamento è fallito per vari altri motivi, come fondi insufficienti. Verifica i dati inseriti.',
        ],
        'paypal_payment_error_action' => [
            'required' => 'Il tentativo di pagamento è fallito perché sono necessarie azioni aggiuntive per completarlo.',
        ],
        'general_payment_error_action' => [
            'required' => 'Il tentativo di pagamento è fallito. Se sei un amministratore di sistema, verifica il problema con il fornitore di pagamento.',
        ],
        'link_payment_error_action' => [
            'required' => 'Metodo di pagamento basato su link non trovato.',
        ],
        'paypal_payment_approval_missing' => [
            'required' => 'Non siamo riusciti ad ottenere l\'approvazione per il pagamento PayPal.',
        ],
        'mollie_error_action' => [
            'required' => 'Errore nel recuperare il link di pagamento.',
        ],
        'paystack_error_action' => [
            'required' => 'Errore nella comunicazione con PayStack.',
        ],
        'dinein_table_id' => [
            'required' => 'Seleziona il tuo tavolo.',
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
