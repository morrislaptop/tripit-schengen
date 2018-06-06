<?php

namespace SocialiteProviders\TripIt;

use SocialiteProviders\Manager\SocialiteWasCalled;

class TripItExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'tripit',
            __NAMESPACE__.'\Provider',
            __NAMESPACE__.'\Server'
        );
    }
}
