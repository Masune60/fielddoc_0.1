<?php

namespace App\Http\Controllers;

use App\Models\FotoDokumentasi;
use App\Models\Pk;
use App\Models\TahapPenagihan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $canViewPk = $user->can('view pk');
        $canViewTahap = $user->can('view tahap');
        $canViewPhotos = $user->can('view pk details');

        $pkCount = $canViewPk ? Pk::count() : null;
        $inProgressCount = $canViewPk ? Pk::where('status_konstruksi', 'PROSES')->count() : null;
        $completedCount = $canViewPk ? Pk::where('status_konstruksi', 'SELESAI_100')->count() : null;
        $stageCounts = $canViewTahap
            ? TahapPenagihan::query()
                ->selectRaw('status_penagihan, COUNT(*) as total')
                ->groupBy('status_penagihan')
                ->pluck('total', 'status_penagihan')
            : collect();

        $photoCounts = $canViewPhotos
            ? FotoDokumentasi::query()
                ->selectRaw('status_verifikasi, COUNT(*) as total')
                ->groupBy('status_verifikasi')
                ->pluck('total', 'status_verifikasi')
            : collect();

        $today = Carbon::today();
        $startDate = $today->copy()->subDays(6);
        $activityDates = collect();
        $recentActivity = collect();

        if ($canViewPk) {
            $pks = Pk::query()->latest()->limit(8)->get();
            $recentActivity = $recentActivity->merge($pks->map(fn (Pk $pk) => [
                'title' => $pk->nomor_pk,
                'description' => $pk->nama_pekerjaan,
                'status' => $pk->status_konstruksi === 'SELESAI_100' ? 'Selesai 100%' : 'Dalam proses',
                'status_class' => $pk->status_konstruksi === 'SELESAI_100' ? 'success' : 'pending',
                'created_at' => $pk->created_at,
                'href' => $user->can('view pk details') ? route('pk.show', $pk) : route('pk.index'),
                'type' => 'PK / SPK',
                'icon' => 'briefcase',
            ]));
            $activityDates = $activityDates->merge(
                Pk::whereBetween('created_at', [$startDate, $today->copy()->endOfDay()])
                    ->pluck('created_at')
            );
        }

        if ($canViewTahap) {
            $stages = TahapPenagihan::query()->latest()->limit(8)->get();
            $recentActivity = $recentActivity->merge($stages->map(fn (TahapPenagihan $stage) => [
                'title' => $stage->nomor_tahap,
                'description' => 'Tahap penagihan',
                'status' => match ($stage->status_penagihan) {
                    'DICAIRKAN' => 'Dicairkan',
                    'DIAJUKAN' => 'Diajukan',
                    default => 'Draft',
                },
                'status_class' => match ($stage->status_penagihan) {
                    'DICAIRKAN' => 'success',
                    'DIAJUKAN' => 'info',
                    default => 'pending',
                },
                'created_at' => $stage->created_at,
                'href' => route('tahap.show', $stage),
                'type' => 'Penagihan',
                'icon' => 'receipt',
            ]));
            $activityDates = $activityDates->merge(
                TahapPenagihan::whereBetween('created_at', [$startDate, $today->copy()->endOfDay()])
                    ->pluck('created_at')
            );
        }

        if ($canViewPhotos) {
            $photos = FotoDokumentasi::query()->latest()->limit(8)->get();
            $recentActivity = $recentActivity->merge($photos->map(fn (FotoDokumentasi $photo) => [
                'title' => 'Dokumentasi foto',
                'description' => 'Unggahan untuk PK #' . $photo->pk_id,
                'status' => match ($photo->status_verifikasi) {
                    'VALID' => 'Terverifikasi',
                    'INVALID' => 'Perlu diperbaiki',
                    default => 'Menunggu verifikasi',
                },
                'status_class' => match ($photo->status_verifikasi) {
                    'VALID' => 'success',
                    'INVALID' => 'danger',
                    default => 'pending',
                },
                'created_at' => $photo->created_at,
                'href' => route('pk.show', $photo->pk_id),
                'type' => 'Dokumentasi',
                'icon' => 'image',
            ]));
            $activityDates = $activityDates->merge(
                FotoDokumentasi::whereBetween('created_at', [$startDate, $today->copy()->endOfDay()])
                    ->pluck('created_at')
            );
        }

        $dailyActivity = collect(range(6, 0))->map(function (int $daysAgo) use ($today, $activityDates) {
            $date = $today->copy()->subDays($daysAgo);

            return [
                'label' => $date->locale('id')->translatedFormat('D'),
                'date' => $date->locale('id')->translatedFormat('d M'),
                'count' => $activityDates->filter(fn ($createdAt) => Carbon::parse($createdAt)->isSameDay($date))->count(),
            ];
        });

        if ($canViewTahap) {
            $distribution = collect([
                ['label' => 'Draft', 'value' => (int) ($stageCounts['DRAFT'] ?? 0), 'color' => '#f59e0b'],
                ['label' => 'Diajukan', 'value' => (int) ($stageCounts['DIAJUKAN'] ?? 0), 'color' => '#3b82f6'],
                ['label' => 'Dicairkan', 'value' => (int) ($stageCounts['DICAIRKAN'] ?? 0), 'color' => '#10b981'],
            ]);
            $distributionTitle = 'Status penagihan';
        } elseif ($canViewPk) {
            $distribution = collect([
                ['label' => 'Dalam proses', 'value' => $inProgressCount, 'color' => '#f59e0b'],
                ['label' => 'Selesai 100%', 'value' => $completedCount, 'color' => '#10b981'],
            ]);
            $distributionTitle = 'Status pekerjaan';
        } else {
            $distribution = collect();
            $distributionTitle = 'Status data';
        }

        $distributionTotal = $distribution->sum('value');
        $distributionOffset = 0;
        $distribution->transform(function (array $item) use (&$distributionOffset, $distributionTotal) {
            $item['start'] = $distributionOffset;
            $item['percentage'] = $distributionTotal > 0 ? ($item['value'] / $distributionTotal) * 100 : 0;
            $distributionOffset += $item['percentage'];

            return $item;
        });
        $distributionGradient = $distributionTotal > 0
            ? 'conic-gradient(' . $distribution->map(
                fn (array $item) => "{$item['color']} {$item['start']}% " . ($item['start'] + $item['percentage']) . '%'
            )->implode(', ') . ')'
            : 'conic-gradient(#e5e7eb 0% 100%)';

        $recentActivity = $recentActivity
            ->sortByDesc('created_at')
            ->take(8)
            ->values()
            ->map(function (array $item) {
                $item['date_label'] = $item['created_at']->locale('id')->diffForHumans();
                $item['date_full'] = $item['created_at']->locale('id')->translatedFormat('d M Y, H:i');

                return $item;
            });

        return view('dashboard', [
            'user' => $user,
            'canViewPk' => $canViewPk,
            'canViewTahap' => $canViewTahap,
            'canViewPhotos' => $canViewPhotos,
            'canCreatePk' => $user->can('create pk'),
            'canManageTahap' => $user->can('manage tahap'),
            'canManageKhs' => $user->can('manage khs'),
            'canViewKhs' => $user->can('view khs'),
            'canManageUsers' => $user->can('manage users'),
            'canManageProfile' => $user->can('manage profile'),
            'pkCount' => $pkCount,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'stageCounts' => $stageCounts,
            'photoCounts' => $photoCounts,
            'dailyActivity' => $dailyActivity,
            'maxDailyActivity' => max(1, $dailyActivity->max('count')),
            'distribution' => $distribution,
            'distributionTotal' => $distributionTotal,
            'distributionGradient' => $distributionGradient,
            'distributionTitle' => $distributionTitle,
            'recentActivity' => $recentActivity,
            'activeStages' => (int) ($stageCounts['DRAFT'] ?? 0) + (int) ($stageCounts['DIAJUKAN'] ?? 0),
            'pendingPhotos' => (int) ($photoCounts['PENDING'] ?? 0),
        ]);
    }
}
