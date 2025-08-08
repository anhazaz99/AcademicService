<?php

return [
    // Secret dùng để ký JWT. Ưu tiên ENV JWT_SECRET, nếu không có sẽ fallback về APP_KEY
    'secret' => env('JWT_SECRET', env('APP_KEY')),

    // Thời gian sống của token (phút)
    'ttl' => env('JWT_TTL', 60),
];


