<?php

namespace MarkWalet\Changelog\Concerns;

use Illuminate\Support\Facades\Artisan;

trait CallsAddCommand
{
    protected function callAdd($type, $message)
    {
        Artisan::call('changelog:add',
                    [
                        '--type' => $type,
                        '--message' => $message,
                    ]
        );
    }
}
