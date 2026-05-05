<?php

namespace App\Policies;

use App\Models\Drs\DailyRunSheetItem;
use App\Models\User;

class DailyRunSheetItemPolicy
{
    /** SuperAdmin can do anything. */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
        return null; // fall through to individual methods
    }

    /**
     * Customer may update an item only when its parent run sheet belongs
     * to one of the Customer's assigned functional areas and cuff time has not passed.
     */
    public function update(User $user, DailyRunSheetItem $item): bool
    {
        return $this->ownsFunctionalArea($user, $item) && !$this->isSheetLocked($item);
    }

    /**
     * Customer may delete an item only when its parent run sheet belongs
     * to one of the Customer's assigned functional areas and cuff time has not passed.
     */
    public function delete(User $user, DailyRunSheetItem $item): bool
    {
        return $this->ownsFunctionalArea($user, $item) && !$this->isSheetLocked($item);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function ownsFunctionalArea(User $user, DailyRunSheetItem $item): bool
    {
        $sheetFaId = $item->runSheet?->functional_area_id;

        if (!$sheetFaId) {
            return false;
        }

        return $user->fa()->where('functional_areas.id', $sheetFaId)->exists();
    }

    private function isSheetLocked(DailyRunSheetItem $item): bool
    {
        $sheetType = $item->runSheet?->sheetType;
        
        if (!$sheetType || !$sheetType->cuff_date_time) {
            return false;
        }

        return \Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($sheetType->cuff_date_time));
    }
}
