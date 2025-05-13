<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BadgeController extends Controller
{
    /**
     * Display a listing of all badges.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $badges = Badge::withCount('users')->get();
        
        return response()->json([
            'badges' => $badges
        ]);
    }

    /**
     * Display badges earned by the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userBadges(Request $request)
    {
        $userBadges = $request->user()
            ->badges()
            ->withPivot('earned_at')
            ->get()
            ->map(function ($badge) {
                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon' => $badge->icon,
                    'earned_at' => $badge->pivot->earned_at,
                ];
            });
        
        return response()->json([
            'badges' => $userBadges
        ]);
    }

    /**
     * Store a newly created badge (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:badges',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'criteria' => 'required|string',
            'threshold' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $badge = Badge::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'criteria' => $request->criteria,
            'threshold' => $request->threshold,
        ]);

        return response()->json([
            'message' => 'Badge created successfully',
            'badge' => $badge
        ], 201);
    }

    /**
     * Update an existing badge (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:badges,name,' . $id,
            'description' => 'sometimes|required|string',
            'icon' => 'nullable|string',
            'criteria' => 'sometimes|required|string',
            'threshold' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $badge = Badge::findOrFail($id);
        $badge->fill($request->only([
            'name',
            'description',
            'icon',
            'criteria',
            'threshold',
        ]));
        
        $badge->save();

        return response()->json([
            'message' => 'Badge updated successfully',
            'badge' => $badge
        ]);
    }

    /**
     * Remove a badge (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        
        // Remove all user associations first
        DB::table('user_badges')->where('badge_id', $id)->delete();
        
        // Delete the badge
        $badge->delete();

        return response()->json([
            'message' => 'Badge deleted successfully'
        ]);
    }

    /**
     * Assign a badge to a user (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignBadge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'badge_id' => 'required|exists:badges,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $badge = Badge::findOrFail($request->badge_id);
        
        // Check if the user already has this badge
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return response()->json([
                'message' => 'User already has this badge'
            ], 400);
        }
        
        // Assign the badge
        $user->badges()->attach($badge->id, ['earned_at' => now()]);

        return response()->json([
            'message' => 'Badge assigned successfully'
        ]);
    }
}
