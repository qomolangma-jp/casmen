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

    'accepted' => ':attributeを承認してください。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal' => ':attributeには、:date以降の日付を指定してください。',
    'alpha' => ':attributeには、アルファベットのみ使用できます。',
    'alpha_dash' => ':attributeには、アルファベット、数字、ダッシュ、アンダースコアのみ使用できます。',
    'alpha_num' => ':attributeには、アルファベットと数字のみ使用できます。',
    'array' => ':attributeには、配列を指定してください。',
    'ascii' => ':attributeには、英数字と記号のみ使用できます。',
    'before' => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeには、:date以前の日付を指定してください。',
    'between' => [
        'array' => ':attributeの項目は、:min個から:max個にしてください。',
        'file' => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'numeric' => ':attributeには、:minから:maxまでの数字を指定してください。',
        'string' => ':attributeは、:min文字から:max文字にしてください。',
    ],
    'boolean' => ':attributeには、trueかfalseを指定してください。',
    'can' => ':attributeフィールドには、不正な値が含まれています。',
    'confirmed' => ':attributeが一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attributeは、正しい日付ではありません。',
    'date_equals' => ':attributeは:dateと同じ日付を入力してください。',
    'date_format' => ':attributeの形式は、:formatと一致していません。',
    'decimal' => ':attributeは、小数点以下:decimal桁の数字にしてください。',
    'declined' => ':attributeを拒否してください。',
    'declined_if' => ':otherが:valueの場合、:attributeを拒否してください。',
    'different' => ':attributeと:otherには、異なるものを指定してください。',
    'digits' => ':attributeは、:digits桁にしてください。',
    'digits_between' => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions' => ':attributeの画像サイズが無効です',
    'distinct' => ':attributeの値が重複しています。',
    'doesnt_end_with' => ':attributeは、次のいずれかで終わってはいけません: :values',
    'doesnt_start_with' => ':attributeは、次のいずれかで始まってはいけません: :values',
    'email' => ':attributeは、有効なメールアドレス形式で指定してください。',
    'ends_with' => ':attributeは、次のいずれかで終わらなければなりません: :values',
    'enum' => '選択された:attributeは、無効です。',
    'exists' => '選択された:attributeは、無効です。',
    'extensions' => ':attributeには、次の拡張子のいずれかを指定してください: :values',
    'file' => ':attributeには、ファイルを指定してください。',
    'filled' => ':attributeは、必須です。',
    'gt' => [
        'array' => ':attributeの項目は、:value個より多くしてください。',
        'file' => ':attributeは、:value KBより大きくしてください。',
        'numeric' => ':attributeは、:valueより大きくしてください。',
        'string' => ':attributeは、:value文字より多くしてください。',
    ],
    'gte' => [
        'array' => ':attributeの項目は、:value個以上にしてください。',
        'file' => ':attributeは、:value KB以上にしてください。',
        'numeric' => ':attributeは、:value以上にしてください。',
        'string' => ':attributeは、:value文字以上にしてください。',
    ],
    'hex_color' => ':attributeは、有効な16進数カラーコードを指定してください。',
    'image' => ':attributeには、画像を指定してください。',
    'in' => '選択された:attributeは、無効です。',
    'in_array' => ':attributeが:otherに存在しません。',
    'integer' => ':attributeには、整数を指定してください。',
    'ip' => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4' => ':attributeには、有効なIPv4アドレスを指定してください。',
    'ipv6' => ':attributeには、有効なIPv6アドレスを指定してください。',
    'json' => ':attributeには、有効なJSON文字列を指定してください。',
    'lowercase' => ':attributeは、小文字で入力してください。',
    'lt' => [
        'array' => ':attributeの項目は、:value個より少なくしてください。',
        'file' => ':attributeは、:value KBより小さくしてください。',
        'numeric' => ':attributeは、:valueより小さくしてください。',
        'string' => ':attributeは、:value文字より少なくしてください。',
    ],
    'lte' => [
        'array' => ':attributeの項目は、:value個以下にしてください。',
        'file' => ':attributeは、:value KB以下にしてください。',
        'numeric' => ':attributeは、:value以下にしてください。',
        'string' => ':attributeは、:value文字以下にしてください。',
    ],
    'mac_address' => ':attributeは、有効なMACアドレスを指定してください。',
    'max' => [
        'array' => ':attributeの項目は、:max個以下にしてください。',
        'file' => ':attributeは、:max KB以下にしてください。',
        'numeric' => ':attributeは、:max以下にしてください。',
        'string' => ':attributeは、:max文字以下にしてください。',
    ],
    'max_digits' => ':attributeは、:max桁以下にしてください。',
    'mimes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min' => [
        'array' => ':attributeの項目は、:min個以上にしてください。',
        'file' => ':attributeは、:min KB以上にしてください。',
        'numeric' => ':attributeは、:min以上にしてください。',
        'string' => ':attributeは、:min文字以上にしてください。',
    ],
    'min_digits' => ':attributeは、:min桁以上にしてください。',
    'missing' => ':attributeフィールドは、存在してはいけません。',
    'missing_if' => ':otherが:valueの場合、:attributeフィールドは存在してはいけません。',
    'missing_unless' => ':otherが:valueでない限り、:attributeフィールドは存在してはいけません。',
    'missing_with' => ':valuesが存在する場合、:attributeフィールドは存在してはいけません。',
    'missing_with_all' => ':valuesが存在する場合、:attributeフィールドは存在してはいけません。',
    'multiple_of' => ':attributeは:valueの倍数でなければなりません',
    'not_in' => '選択された:attributeは、無効です。',
    'not_regex' => ':attributeの形式が無効です。',
    'numeric' => ':attributeには、数字を指定してください。',
    'password' => [
        'letters' => ':attributeは少なくとも1つの文字を含める必要があります。',
        'mixed' => ':attributeは少なくとも1つの大文字と1つの小文字を含める必要があります。',
        'numbers' => ':attributeは少なくとも1つの数字を含める必要があります。',
        'symbols' => ':attributeは少なくとも1つの記号を含める必要があります。',
        'uncompromised' => '指定された:attributeは、データ漏洩で既知のものです。別の:attributeを選択してください。',
    ],
    'present' => ':attributeが存在している必要があります。',
    'present_if' => ':otherが:valueの場合、:attributeフィールドが存在する必要があります。',
    'present_unless' => ':otherが:valueでない限り、:attributeフィールドが存在する必要があります。',
    'present_with' => ':valuesが存在する場合、:attributeフィールドが存在する必要があります。',
    'present_with_all' => ':valuesが存在する場合、:attributeフィールドが存在する必要があります。',
    'prohibited' => ':attributeフィールドは禁止されています。',
    'prohibited_if' => ':otherが:valueの場合、:attributeフィールドは禁止されています。',
    'prohibited_unless' => ':otherが:valuesでない限り、:attributeフィールドは禁止されています。',
    'prohibits' => ':attributeフィールドは、:otherが存在することを禁止します。',
    'regex' => ':attributeには、有効な正規表現を指定してください。',
    'required' => ':attributeは、必須項目です。',
    'required_array_keys' => ':attributeフィールドには、次のエントリを含める必要があります: :values',
    'required_if' => ':otherが:valueの場合、:attributeを指定してください。',
    'required_if_accepted' => ':otherが承認されている場合、:attributeフィールドは必須です。',
    'required_unless' => ':otherが:values以外の場合、:attributeを指定してください。',
    'required_with' => ':valuesが指定されている場合、:attributeを指定してください。',
    'required_with_all' => ':valuesが全て指定されている場合、:attributeを指定してください。',
    'required_without' => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same' => ':attributeと:otherが一致しません。',
    'size' => [
        'array' => ':attributeの項目は、:size個にしてください。',
        'file' => ':attributeは、:size KBにしてください。',
        'numeric' => ':attributeは、:sizeにしてください。',
        'string' => ':attributeは、:size文字にしてください。',
    ],
    'starts_with' => ':attributeは、次のいずれかで始まる必要があります: :values',
    'string' => ':attributeには、文字を指定してください。',
    'timezone' => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique' => '指定の:attributeは既に使用されています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'uppercase' => ':attributeは、大文字で入力してください。',
    'url' => ':attributeは、有効なURL形式で指定してください。',
    'ulid' => ':attributeは、有効なULIDである必要があります。',
    'uuid' => ':attributeは、有効なUUIDである必要があります。',

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

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'token' => 'トークン',
        'name' => '名前',
        'phone' => '電話番号',
        'video' => '動画',
    ],

];
