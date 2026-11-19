<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserContentRequestController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:photo,video,hero,post'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);
        $filters['search'] = trim((string) ($filters['search'] ?? ''));

        $requestRows = DB::table('gallery_photos')
            ->whereNotNull('user_id')
            ->selectRaw(
                "id, title, 'photo' AS request_type, 'Photo' AS type_label,
                CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END AS status,
                user_id, created_at"
            );
        $requestRows->unionAll(
            DB::table('gallery_videos')
                ->whereNotNull('user_id')
                ->selectRaw(
                    "id, title, 'video' AS request_type, 'Video' AS type_label,
                    CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END AS status,
                    user_id, created_at"
                )
        );
        $requestRows->unionAll(
            DB::table('hero_slides')
                ->whereNotNull('user_id')
                ->selectRaw(
                    "id, alt_text AS title, 'hero' AS request_type, 'Hero image' AS type_label,
                    CASE
                        WHEN rejected_at IS NOT NULL THEN 'rejected'
                        WHEN is_approved = 1 THEN 'approved'
                        ELSE 'pending'
                    END AS status,
                    user_id, created_at"
                )
        );
        $requestRows->unionAll(
            DB::table('posts')
                ->whereNotNull('user_id')
                ->whereIn('category', ['News', 'Event'])
                ->selectRaw(
                    "id, title, 'post' AS request_type, 'News & Event' AS type_label,
                    CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END AS status,
                    user_id, created_at"
                )
        );

        $summary = DB::query()
            ->fromSub(clone $requestRows, 'user_requests')
            ->selectRaw(
                "COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) AS pending,
                COALESCE(SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END), 0) AS approved,
                COALESCE(SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END), 0) AS rejected"
            )
            ->first();

        $requestsQuery = DB::query()
            ->fromSub($requestRows, 'user_requests')
            ->join('users', 'users.id', '=', 'user_requests.user_id')
            ->select('user_requests.*', 'users.name as user_name', 'users.email as user_email');

        if (filled($filters['search'])) {
            $search = $filters['search'];
            $requestsQuery->where(function (Builder $query) use ($search): void {
                $query->where('user_requests.title', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if (filled($filters['type'] ?? null)) {
            $requestsQuery->where('user_requests.request_type', $filters['type']);
        }

        if (filled($filters['status'] ?? null)) {
            $requestsQuery->where('user_requests.status', $filters['status']);
        }

        return view('admin.user-content-requests', [
            'requests' => $requestsQuery
                ->orderByDesc('user_requests.created_at')
                ->orderBy('user_requests.request_type')
                ->orderByDesc('user_requests.id')
                ->paginate(20)
                ->withQueryString(),
            'summary' => $summary,
            'filters' => $filters,
            'managementRoutes' => [
                'photo' => route('admin.gallery.photos.index'),
                'video' => route('admin.gallery.videos.index'),
                'hero' => route('admin.hero.manage'),
                'post' => route('admin.posts.index'),
            ],
        ]);
    }
}
