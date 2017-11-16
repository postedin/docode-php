<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Exceptions\InvalidTokenException;
use Postedin\Docode\Tests\UnitTest;

class InvalidTokenTest extends UnitTest
{
    public function test_invalid_token()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(403, ['detail' => 'Las credenciales de autenticación no se proveyeron.']),
        ]));

        $this->expectException(InvalidTokenException::class);

        $api->getProfile();
    }
}
