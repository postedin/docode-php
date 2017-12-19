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

        $this->assertEquals($profileData['regime']['words'], $profile->regime->words);
        $this->assertEquals($profileData['regime']['files'], $profile->regime->files);
        $this->assertEquals($profileData['regime']['is_expired'], $profile->regime->isExpired);
    }
}
