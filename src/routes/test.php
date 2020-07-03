<?php
Route::middleware(['api-response-formatter'])->get('api-response-formatter-test', function () {
    return API_RESPONSE_FORMATTER_ASSERT_ARRAY;
});
