<?php

use App\Enums\Permission;
use App\Enums\UserRole;

return [

    /*
    |--------------------------------------------------------------------------
    | Role → permission matrix
    |--------------------------------------------------------------------------
    |
    | Writers see only their editorial workspace. Editors run the newsroom.
    | Route middleware and @can checks both read from this matrix.
    |
    */

    UserRole::Writer->value => [
        Permission::ViewDashboard->value,
        Permission::ViewArticles->value,
        Permission::CreateArticles->value,
        Permission::SubmitArticles->value,
    ],

    UserRole::Editor->value => [
        Permission::ViewDashboard->value,
        Permission::ViewArticles->value,
        Permission::CreateArticles->value,
        Permission::DeleteArticles->value,
        Permission::SubmitArticles->value,
        Permission::ViewReviewQueue->value,
        Permission::ReviewArticles->value,
        Permission::PublishArticles->value,
        Permission::UnpublishArticles->value,
        Permission::ManageCategories->value,
        Permission::ManageTags->value,
        Permission::ManageGallery->value,
        Permission::ManageMedia->value,
        Permission::ManageStaff->value,
        Permission::ManageLayout->value,
        Permission::ViewPermissionsMatrix->value,
    ],

];
