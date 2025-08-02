<?php

view()->composer(
    [
        'base::admin._partials.header',
        'front.*.menu',
        'front.reader'
    ],
    'Modules\Notification\Composers\NotificationViewComposer'
);
