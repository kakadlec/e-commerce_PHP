<?php

namespace Store\Test\Unit;

use PHPUnit\Framework\TestCase;
use Store\Cpf;

class OrderTest extends TestCase {
    public function testShouldNotCreateAnInvalidCpf() {
       $this->expectException(\Exception::class);
       $this->expectExceptionMessage('Invalid CPF');
       new Cpf("010.216.339-178");
    }

    public function testShouldCreateACpf() {
            $cpf = new Cpf("010.216.339-17");
            $this->assertEquals("010.216.339-17", $cpf->value);
    }
}