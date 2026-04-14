<?php

namespace app\model;

use think\Model;

class Session extends Model
{
    protected $table = 'sessions';

    /**
     * Create a new session for a user.
     * Token expires in 24 hours by default.
     */
    public static function createForUser(int $userId, int $ttlSeconds = 86400): self
    {
        $session = new self();
        $session->user_id = $userId;
        $session->token = bin2hex(random_bytes(32));
        $session->expires_at = date('Y-m-d H:i:s', time() + $ttlSeconds);
        $session->save();
        return $session;
    }

    /**
     * Find a valid (non-expired) session by token.
     */
    public static function findValidByToken(string $token): ?self
    {
        return self::where('token', $token)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->find();
    }

    /**
     * Invalidate (delete) this session.
     */
    public function invalidate(): void
    {
        $this->delete();
    }

    /**
     * Clean up all expired sessions.
     */
    public static function cleanExpired(): int
    {
        return self::where('expires_at', '<=', date('Y-m-d H:i:s'))->delete();
    }
}
