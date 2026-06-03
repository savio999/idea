<?php

namespace Tests\Unit;

use App\Models\Idea;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IdeaTest extends TestCase
{
    #[Test]
    public function idea_user_relationship_is_defined(): void
    {
        $idea = new Idea(); 

        $relation = $idea->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(User::class, $relation->getRelated()::class);
        $this->assertSame('user_id', $relation->getForeignKeyName());
    }

    #[Test]
    public function idea_just_created_has_no_steps(): void
    {
        $idea = new Idea();
        $this->assertEmpty($idea->steps);

        $idea = new Idea();
        $this->assertEmpty($idea->steps);
    }
}