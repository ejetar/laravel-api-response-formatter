<?php
Route::middleware(['response_formatter'])->get('test', function () {
    return API_RESPONSE_FORMATTER_ASSERT_ARRAY;
});