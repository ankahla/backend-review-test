<?php

namespace App\Dto;

final class GhEvent
{
    public const COMMIT_COMMENT_EVENT = 'CommitCommentEvent';
    public const ISSUE_COMMENT_EVENT = 'IssueCommentEvent';
    public const PULL_REQUEST_REVIEW_COMMENT_EVENT = 'PullRequestReviewCommentEvent';
    public const PULL_REQUEST_EVENT = 'PullRequestEvent';
    public const PUSH_EVENT = 'PushEvent';
    public const TYPES = [
        self::COMMIT_COMMENT_EVENT,
        self::ISSUE_COMMENT_EVENT,
        self::PULL_REQUEST_REVIEW_COMMENT_EVENT,
        self::PULL_REQUEST_EVENT,
        self::PUSH_EVENT,
    ];

    public string $id;
    public string $type;
    public GhActor $actor;
    public GhRepo $repo;
    public array $payload;
    public \DateTimeImmutable $createdAt;
}
