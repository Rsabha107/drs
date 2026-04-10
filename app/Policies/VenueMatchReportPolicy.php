<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use App\Models\Vapp\VappRequest;
use App\Models\Vms\VenueMatchReport;
use App\Models\Wdr\WorkforceDailyReport;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class VenueMatchReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */


    public function view(User $user, VenueMatchReport $venueMatchReport): bool
    {
        // dd('inside policy WorkforceDailyReportPolicy::view user_id=' . $user->id . ' report_venue_id=' . $workforceDailyReport->venue_id);
        Log::info('inside policy WorkforceDailyReportPolicy::view user_id=' . $user->id . ' report_venue_id=' . $venueMatchReport->venue_id);
        if ($user->hasRole('SuperAdmin')) return true;

        return $user->events()->where('events.id', $venueMatchReport->event_id)->exists()
            && $user->venues()->where('venues.id', $venueMatchReport->venue_id)->exists();
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VenueMatchReport $venueMatchReport): bool
    {
        //
        if (auth()->user()->hasAnyRole(['SuperAdmin'])) {
            appLog('inside policy VenueMatchReportPolicy::update user has role SuperAdmin/Admin');
            return true;
        }
        appLog('inside policy VenueMatchReportPolicy::update use_id=' . $user->id . ' report_created_by=' . $venueMatchReport->created_by);
        return $user->id == $venueMatchReport->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VenueMatchReport $venueMatchReport): bool
    {
        //
        if (auth()->user()->hasAnyRole(['SuperAdmin'])) {
            appLog('inside policy VenueMatchReportPolicy::delete user has role SuperAdmin/Admin');
            return true;
        }
        appLog('inside policy VenueMatchReportPolicy::delete use_id=' . $user->id . ' report_created_by=' . $venueMatchReport->created_by);
        return $user->id == $venueMatchReport->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        //
    }
}
