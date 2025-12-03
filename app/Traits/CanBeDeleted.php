<?php

namespace App\Traits;

trait CanBeDeleted
{
    /**
     * Check if the model can be safely deleted
     */
    public function canBeDeleted(): bool
    {
        // Check relationships that would prevent deletion
        $relationships = $this->getDeletionBlockingRelationships();

        foreach ($relationships as $relationship) {
            if ($this->$relationship()->exists()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get relationships that block deletion
     */
    protected function getDeletionBlockingRelationships(): array
    {
        // Override in child classes to specify blocking relationships
        return [];
    }

    /**
     * Get deletion error message
     */
    public function getDeletionErrorMessage(): string
    {
        return 'This record cannot be deleted because it has related records.';
    }
}