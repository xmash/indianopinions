<?php

namespace App\Enums;

enum Permission: string
{
    case ViewDashboard = 'view_dashboard';
    case ViewArticles = 'view_articles';
    case CreateArticles = 'create_articles';
    case DeleteArticles = 'delete_articles';
    case SubmitArticles = 'submit_articles';
    case ViewReviewQueue = 'view_review_queue';
    case ReviewArticles = 'review_articles';
    case PublishArticles = 'publish_articles';
    case UnpublishArticles = 'unpublish_articles';
    case ManageCategories = 'manage_categories';
    case ManageTags = 'manage_tags';
    case ManageGallery = 'manage_gallery';
    case ManageMedia = 'manage_media';
    case ManageStaff = 'manage_staff';
    case ManageLayout = 'manage_layout';
    case ViewPermissionsMatrix = 'view_permissions_matrix';

    public function label(): string
    {
        return match ($this) {
            self::ViewDashboard => 'View dashboard',
            self::ViewArticles => 'View articles',
            self::CreateArticles => 'Create articles',
            self::DeleteArticles => 'Delete articles',
            self::SubmitArticles => 'Submit for review',
            self::ViewReviewQueue => 'View review queue',
            self::ReviewArticles => 'Review & request changes',
            self::PublishArticles => 'Approve & publish',
            self::UnpublishArticles => 'Unpublish articles',
            self::ManageCategories => 'Manage categories',
            self::ManageTags => 'Manage tags',
            self::ManageGallery => 'Manage gallery',
            self::ManageMedia => 'Manage media videos',
            self::ManageStaff => 'Manage staff',
            self::ManageLayout => 'Orchestrate homepage & hub pages',
            self::ViewPermissionsMatrix => 'View permissions matrix',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
