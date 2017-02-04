<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\Employee;
use Payroll\Tests\TestCase;
use Faker\Factory;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Transaction\AddEmployee;

abstract class AbstractAddEmployeeTestCase extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Employee
     */
    protected $employee = Null;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->data = [
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ];
    }

    /**
     * @return void
     */
    abstract protected function setEmployee();

    /**
     * @return void
     */
    abstract protected function assertTypeSpecificData();

    /**
     * @covers AddEmployee::execute()
     */
    public function testExecute()
    {
        $this->setEmployee();
        $this->assertBaseData();
        $this->assertTypeSpecificData();
    }

    protected function assertBaseData()
    {
        $this->assertEquals($this->data['name'], $this->employee->name);
        $this->assertEquals($this->data['address'], $this->employee->address);
        $this->assertTrue($this->employee->getPaymentMethod() instanceof HoldMethod);
    }
}