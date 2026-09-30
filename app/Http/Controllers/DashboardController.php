<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Models\Training;
use App\Models\User;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
use App\Models\WeeklyAttempt;
use App\Models\WeeklyTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $statuses = ProspectStatus::orderBy('urutan')->get();

        if ($user->isAdmin()) {
            $totalProspects = Prospect::count();
            $totalSales = User::where('peran', 'sales')->where('status_aktif', true)->count();
            $totalPhone = ProspectActivity::where('jenis_aktivitas', 'phone')->count();
            $totalVisit = ProspectActivity::where('jenis_aktivitas', 'visit')->count();

            // Count prospects needing follow-up (dormant >= 3 days, not in final status)
            $overdueCount = Prospect::whereHas('status', fn($q) => $q->where('status_akhir', false))
                ->where(function ($q) {
                    $q->whereDoesntHave('activities', fn($actQ) => $actQ->where('waktu_aktivitas', '>=', now()->subDays(3)))
                      ->where('created_at', '<=', now()->subDays(3));
                })->count();

            $statusCounts = [];
            foreach ($statuses as $st) {
                $statusCounts[$st->kode] = [
                    'model' => $st,
                    'count' => Prospect::where('status_prospek_id', $st->id)->count(),
                ];
            }

            $recentActivities = ProspectActivity::with(['prospect', 'user', 'status'])
                ->orderBy('waktu_aktivitas', 'desc')
                ->take(8)
                ->get();

            $recentProspects = Prospect::with(['user', 'motorcycle', 'status', 'source', 'latestActivity'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $totalTrainingCount = Training::where('status', 'published')->count();
            $totalTrainingCompletions = UserTrainingProgress::where('status', 'selesai')->count();

            return view('dashboard.admin', compact(
                'totalProspects',
                'totalSales',
                'totalPhone',
                'totalVisit',
                'overdueCount',
                'statuses',
                'statusCounts',
                'recentActivities',
                'recentProspects',
                'totalTrainingCount',
                'totalTrainingCompletions'
            ));
        }

        // Sales Dashboard
        $myProspectsQuery = Prospect::where('user_id', $user->id);
        $totalMyProspects = (clone $myProspectsQuery)->count();

        // Count sales prospects needing follow-up (dormant >= 3 days)
        $myOverdueCount = (clone $myProspectsQuery)
            ->whereHas('status', fn($q) => $q->where('status_akhir', false))
            ->where(function ($q) {
                $q->whereDoesntHave('activities', fn($actQ) => $actQ->where('waktu_aktivitas', '>=', now()->subDays(3)))
                  ->where('created_at', '<=', now()->subDays(3));
            })->count();

        $statusCounts = [];
        foreach ($statuses as $st) {
            $statusCounts[$st->kode] = [
                'model' => $st,
                'count' => (clone $myProspectsQuery)->where('status_prospek_id', $st->id)->count(),
            ];
        }

        $myPhoneCount = ProspectActivity::where('user_id', $user->id)->where('jenis_aktivitas', 'phone')->count();
        $myVisitCount = ProspectActivity::where('user_id', $user->id)->where('jenis_aktivitas', 'visit')->count();

        $recentActivities = ProspectActivity::with(['prospect', 'status'])
            ->where('user_id', $user->id)
            ->orderBy('waktu_aktivitas', 'desc')
            ->take(6)
            ->get();

        $myRecentProspects = Prospect::with(['motorcycle', 'status', 'source', 'latestActivity'])
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();


        // Supporting Training Summary for Sales Dashboard
        $totalTrainingCount = Training::where('status', 'published')->count();
        $myTrainingProgresses = UserTrainingProgress::where('user_id', $user->id)->get();
        $totalTrainingCompleted = $myTrainingProgresses->where('status', 'selesai')->count();
        $totalTrainingInProgress = $myTrainingProgresses->where('status', 'sedang_berjalan')->count();
        $latestQuizResult = UserQuizResult::where('user_id', $user->id)->latest('waktu_selesai')->first();

        // Supporting Weekly Test Summary for Sales Dashboard
        $now = now();
        $activeWeeklyTests = WeeklyTest::where('status', 'published')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->get();

        $myCompletedTestIds = WeeklyAttempt::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'waktu_habis'])
            ->pluck('weekly_test_id')
            ->toArray();

        $activeWeeklyTestsCount = $activeWeeklyTests->count();
        $pendingWeeklyTestsCount = $activeWeeklyTests->whereNotIn('id', $myCompletedTestIds)->count();
        $latestWeeklyTestAttempt = WeeklyAttempt::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'waktu_habis'])
            ->latest('waktu_selesai')
            ->first();
        $nextActiveTestId = $activeWeeklyTests->whereNotIn('id', $myCompletedTestIds)->first()?->id;

        return view('dashboard.sales', compact(
            'totalMyProspects',
            'myPhoneCount',
            'myVisitCount',
            'myOverdueCount',
            'statuses',
            'statusCounts',
            'recentActivities',
            'myRecentProspects',
            'totalTrainingCount',
            'totalTrainingCompleted',
            'totalTrainingInProgress',
            'latestQuizResult',
            'activeWeeklyTestsCount',
            'pendingWeeklyTestsCount',
            'latestWeeklyTestAttempt',
            'nextActiveTestId'
        ));
    }
}
