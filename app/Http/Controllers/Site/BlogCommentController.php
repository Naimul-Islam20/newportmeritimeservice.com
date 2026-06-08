<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostCommentRequest;
use App\Models\BlogPostComment;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;

class BlogCommentController extends Controller
{
    public function store(StoreBlogPostCommentRequest $request, SubMenu $sub_menu): RedirectResponse
    {
        abort_unless($sub_menu->is_active, 404);
        abort_unless($this->isCommentablePost($sub_menu), 404);

        $comment = BlogPostComment::query()->create([
            'sub_menu_id' => $sub_menu->id,
            'author_name' => $request->validated('author_name'),
            'author_email' => $request->validated('author_email'),
            'body' => $request->validated('body'),
        ]);

        AuditLogger::log('blog.comment.submitted', $comment, [
            'sub_menu_id' => $sub_menu->id,
        ], $request);

        return back()->with('status', 'Thank you! Your comment has been submitted for review.');
    }

    private function isCommentablePost(SubMenu $subMenu): bool
    {
        if ($subMenu->parent_sub_menu_id === null) {
            return false;
        }

        $parent = $subMenu->relationLoaded('parent') ? $subMenu->parent : $subMenu->parent()->first();

        return $parent?->isNavDropdownCategory() === true
            && in_array($parent->blogLayoutType(), ['sidebar_article', 'sidebar_content'], true);
    }
}
