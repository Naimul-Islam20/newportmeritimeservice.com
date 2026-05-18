<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Max image upload size (kilobytes)
    |--------------------------------------------------------------------------
    |
    | Laravel validation uses this value. Effective limit is the lower of this
    | setting and PHP's upload_max_filesize.
    |
    */
    'max_image_kb' => (int) env('UPLOAD_MAX_IMAGE_KB', 5120),

];
