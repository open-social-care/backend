<?php

namespace Tests\Unit\Support;

use App\Support\DocumentValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function testValidateCnpjWithRightInformation()
    {
        $this->assertTrue(DocumentValidator::validateCnpj(fake()->cnpj));
    }

    public function testValidateCnpjWithWrongInformation()
    {
        $cnpj = '12.357.988/0001-11';
        $this->assertFalse(DocumentValidator::validateCnpj($cnpj));
    }

    public function testValidateCpfWithRightInformation()
    {
        $this->assertTrue(DocumentValidator::validateCpf(fake()->cpf));
    }

    public function testValidateCpfWithWrongInformation()
    {
        $cpf = '123.456.789-00';
        $this->assertFalse(DocumentValidator::validateCpf($cpf));
    }
}
