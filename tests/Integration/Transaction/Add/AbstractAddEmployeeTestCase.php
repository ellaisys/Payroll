<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\Tests\TestCase;
use Payroll\PaymentMethod\HoldMethod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class AbstractAddEmployeeTestCase extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Employee
     */
    protected $employee = null;

    public function __construct()
    {
        parent::__construct();
        $this->data = [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
        ];
    }

    abstract protected function setEmployee();

    abstract protected function assertTypeSpecificData();

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
        $this->assertTrue($this->employee->getPaymentClassification()->getEmployee() != null);
        $this->assertDatabaseHas('employees', $this->employee->toArray());
    }
}
