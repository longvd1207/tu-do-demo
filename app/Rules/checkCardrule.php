<?php

namespace App\Rules;

use App\Repositories\Staff\StaffRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class checkCardrule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $staffRepo;
    protected $customValue;

    public function __construct(StaffRepositoryInterface $staffRepo, $customValue = null)
    {
        $this->staffRepo = $staffRepo;
        $this->customValue = $customValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) == 10) {
            $value = dechex((int)$value);
        }
        $this->customValue = $value;
        return $this->staffRepo->checkExitCardId((string)$value) != 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Thẻ nhân viên đã tồn tại ' . $this->customValue . '!';
    }
}
