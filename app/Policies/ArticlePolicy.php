<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

//    public function before(User $user)
//    {
//        if ($user->tokenCan('articles:create')){
//            return true;
//        }
//    }

    public function create(User $user, $request)
    {
        return $user->tokenCan('articles:create') &&
            $user->id === $request->json('data.relationships.authors.data.id');

    }

    public function update(User $user, $article)
    {
        return $user->tokenCan('articles:update') &&
            $article->user->is($user);
    }


    public function delete(User $user, $article)
    {
        return $user->tokenCan('articles:delete') &&
            $article->user->is($user);
    }


    public function modifyCategories(User $user,$article,$request)
    {
        return $user->tokenCan('articles:modify-categories') &&
            $article->user->is($user);
    }
}
