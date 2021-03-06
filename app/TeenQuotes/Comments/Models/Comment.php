<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeenQuotes\Comments\Models;

use Auth;
use Laracasts\Presenter\PresentableTrait;
use TeenQuotes\Comments\Models\Relations\CommentTrait as CommentRelationsTrait;
use TeenQuotes\Comments\Models\Scopes\CommentTrait as CommentScopesTrait;
use TeenQuotes\Users\Models\User;
use Toloquent;

class Comment extends Toloquent
{
    use CommentRelationsTrait, CommentScopesTrait, PresentableTrait;
    protected $presenter = 'TeenQuotes\Comments\Presenters\CommentPresenter';

    protected $fillable = [];

    protected $hidden = ['deleted_at', 'updated_at'];

    public function isPostedBySelf()
    {
        if (Auth::check()) {
            return $this->user_id == Auth::id();
        }

        return false;
    }

    public function isPostedByUser(User $u)
    {
        return $this->user_id == $u->id;
    }
}
