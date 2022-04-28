<?php

use Project\Services\User\DefaultGroups\DefaultGroupsService;
use Project\Services\User\Friends\FriendsService;
use Project\Services\User\Groups\GroupsService;
use Project\Services\User\Posts\PostCommentsService;
use Project\Services\User\Posts\PostLikesService;
use Project\Services\User\Posts\PostsService;

if (!function_exists('activeNavItem')) {
    /**
     * Check when menu item is active.
     *
     * @param string $routeName
     * @param string $activeClass
     * @return string
     */
    function activeNavItem(string $routeName, string $activeClass = 'active-menu-item'): string
    {
        return stripos(getCurrentRouteName(), $routeName) !== false ? $activeClass : '';
    }
}

if (!function_exists('isoFormat')) {
    /**
     * Date to iso format.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function isoFormat($date, string $format = 'D MMM YY'): string
    {
        return \Simpler\Components\DateTime::isoFormat($date, $format);
    }
}

if (!function_exists('ownerPost')) {
    /**
     * Check user is owner post.
     *
     * @param int $postId
     * @return string
     */
    function ownerPost(int $postId): string
    {
        return PostsService::owner($postId);
    }
}

if (!function_exists('userPost')) {
    /**
     * Get user post.
     *
     * @param int $userId
     * @return mixed
     */
    function userPost(int $userId)
    {
        return PostsService::user($userId);
    }
}

if (!function_exists('isFriends')) {
    /**
     * Check users is friends.
     *
     * @param int $friendId
     * @return bool
     */
    function isFriends(int $friendId): bool
    {
        return FriendsService::isFriends($friendId);
    }
}

if (!function_exists('userJoinedGroup')) {
    /**
     * Checks if the user has joined the group.
     *
     * @param int $groupId
     * @param bool $default
     * @return bool
     */
    function userJoinedGroup(int $groupId, bool $default = false): bool
    {
        return $default ? DefaultGroupsService::joinedGroup($groupId) : GroupsService::joinedGroup($groupId);
    }
}

if (!function_exists('qntyComments')) {
    /**
     * Get qnty post comments.
     *
     * @param int $postId
     * @return int
     */
    function qntyComments(int $postId): int
    {
        return PostCommentsService::qntyComments($postId);
    }
}

if (!function_exists('qntyLikes')) {
    /**
     * Get qnty post likes.
     *
     * @param int $postId
     * @return int
     */
    function qntyLikes(int $postId): int
    {
        return PostLikesService::qntyLikes($postId);
    }
}

if (!function_exists('comments')) {
    /**
     * Get all comments from post.
     *
     * @param int $postId
     * @return array
     */
    function comments(int $postId): array
    {
        return PostsService::comments($postId);
    }
}

if (!function_exists('ownerCommentLike')) {
    /**
     * Get check owner comment or like.
     *
     * @param int $id
     * @param bool $comment
     * @return bool
     */
    function ownerCommentLike(int $id, bool $comment = true): bool
    {
        return $comment ? PostCommentsService::owner($id) : PostLikesService::owner($id);
    }
}
