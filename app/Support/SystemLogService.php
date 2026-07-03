<?php

namespace App\Support;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SystemLogService
{
    public static function record(
        ?Request $request,
        string $module,
        string $action,
        string $description,
        Model|array|string|int|null $subject = null,
        array $properties = []
    ): void {
        self::recordForUser(
            $request,
            $request?->user() ?? auth()->user(),
            $module,
            $action,
            $description,
            $subject,
            $properties
        );
    }

    public static function recordForUser(
        ?Request $request,
        ?User $user,
        string $module,
        string $action,
        string $description,
        Model|array|string|int|null $subject = null,
        array $properties = []
    ): void {
        [$subjectType, $subjectId] = self::resolveSubject($subject);

        try {
            SystemLog::create([
                'user_id' => $user?->id,
                'module' => $module,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => $description,
                'properties' => empty($properties) ? null : $properties,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private static function resolveSubject(Model|array|string|int|null $subject): array
    {
        if ($subject instanceof Model) {
            return [class_basename($subject), (string) $subject->getKey()];
        }

        if (is_array($subject)) {
            return [
                $subject['type'] ?? null,
                isset($subject['id']) ? (string) $subject['id'] : null,
            ];
        }

        if ($subject !== null) {
            return [null, (string) $subject];
        }

        return [null, null];
    }
}
