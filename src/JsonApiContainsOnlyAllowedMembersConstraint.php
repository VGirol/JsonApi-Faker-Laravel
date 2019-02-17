<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Constraint\Constraint;

class JsonApiContainsOnlyAllowedMembersConstraint extends Constraint
{
    /**
     * @var array
     */
    private $members;

    public function __construct(array $members)
    {
        parent::__construct();

        $this->members = $members;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return \sprintf(
            'contains only elements of "%s"',
            \implode(', ', $this->members)
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        if (!is_array($other)) {
            return false;
        }

        foreach ($other as $key => $value) {
            if (!in_array($key, $this->members)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     */
    protected function failureDescription($other): string
    {
        return \sprintf(
            '%s contains only elements of "%s"',
            $this->exporter->shortenedExport($other),
            \implode(', ', $this->members)
        );
    }
}
