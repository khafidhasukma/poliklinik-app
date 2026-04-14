<?php

use Illuminate\Support\Facades\Broadcast;

// Public channel - no auth needed
Broadcast::channel('antrian', function () {
    return true;
});
