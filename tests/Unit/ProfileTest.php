<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Profile;
use Postedin\Docode\Tests\UnitTest;

class ProfileTest extends UnitTest
{
    public function test_get_profile()
    {
        $profileData = $this->parseHjsonFile(__DIR__.'/fake-api/profile.hjson');

        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $profileData),
        ]));

        $profile = $api->getProfile();

        $this->assertInstanceOf(Profile::class, $profile);

        foreach ($profileData as $param => $value) {
            if (! is_array($value)) {
                $this->assertEquals($value, $profile->{camel_case($param)});
            }
        }

        foreach ($profileData['regime'] as $param => $value) {
            $this->assertEquals($value, $profile->plan->{camel_case($param)});
        }
    }
}
