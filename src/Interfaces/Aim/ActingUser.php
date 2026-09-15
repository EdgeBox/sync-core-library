<?php

namespace EdgeBox\SyncCore\Interfaces\Aim;

/**
 * The person a site is acting on behalf of when it calls Sync Core.
 *
 * Holds the scopes the site authorizes for that person and, optionally, the
 * name and address that become the token's user claim. Sync Core validates the
 * claim and answers 401 for a name or address it cannot accept, so an unusable
 * value is dropped here rather than sent.
 */
final class ActingUser
{
    /**
     * The longest address Sync Core accepts on the user claim; a longer one is
     * dropped rather than sent.
     */
    public const MAX_EMAIL_LENGTH = 50;

    /**
     * @var string[]
     */
    private $scopes;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $email;

    /**
     * @param string[]    $scopes members of SyncCore::SITE_AUTHORIZABLE_USER_SCOPES
     * @param null|string $name   the person's display name; omitted from the token when null or empty
     * @param null|string $email  the person's address; omitted when null, empty, not a valid
     *                            address with a TLD, or longer than 50 characters — Sync Core
     *                            validates the claim and answers 401 for any of those, so an
     *                            unusable value is dropped rather than sent
     */
    public function __construct(array $scopes, ?string $name = null, ?string $email = null)
    {
        $this->scopes = array_values($scopes);
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return string[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @return null|array ['name' => string, 'email' => string] with either key absent when unusable; null when both are
     */
    public function toUserClaim()
    {
        $claim = [];

        if (null !== $this->name && '' !== $this->name) {
            $claim['name'] = $this->name;
        }

        if ($this->isUsableEmail($this->email)) {
            $claim['email'] = $this->email;
        }

        return $claim ?: null;
    }

    /**
     * A value Sync Core accepts on the user claim: a valid address with a
     * top-level domain, at most MAX_EMAIL_LENGTH characters long.
     */
    private function isUsableEmail(?string $email): bool
    {
        if (null === $email || '' === $email) {
            return false;
        }

        if (strlen($email) > self::MAX_EMAIL_LENGTH) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // A top-level domain: the host after the @ carries at least one dot.
        $host = substr($email, strrpos($email, '@') + 1);

        return false !== strpos($host, '.');
    }
}
