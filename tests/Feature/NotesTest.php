<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotesTest extends TestCase
{
    public function testNotesCanBeAccesed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/notes");

        $response->assertStatus(200);
    }

    public function testNotesAreStored(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post("/notes", [
            "text" => Str::random(256),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function testUserCannotDeleteOtherUserNote(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);

        $note = Note::factory()->create(["user_id" => $user2->id]);

        $response = $this->delete("/notes/" . $note->getAttribute("id"));

        $response->assertStatus(302); 
        $response->assertForbidden();
        $this->assertDatabaseHas("notes", ["id" => $note->getAttribute("id")]);
    }

    public function testUserCannotCreateEmptyNote(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post("/notes", [
            "text" => "",
        ]);

        $response->assertSessionHasErrors("text");
        $this->assertDatabaseCount("notes", 0);
    }

    public function testUserCanDeleteHisNote(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note = Note::factory()->create(["user_id" => $user->id]);

        $response = $this->delete("/notes/" . $note->id);

        $response->assertRedirect();
        $response->assertSessionHas("success", "Note has been deleted");
        $this->assertDatabaseMissing("notes", ["id" => $note->id]);
    }

    public function testUserCanSeeHisNotes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note1 = Note::factory()->create(["user_id" => $user->id]);
        $note2 = Note::factory()->create(["user_id" => $user->id]);

        $response = $this->get("/notes");

        $response->assertStatus(200);
        $response->assertSee($note1->text);
        $response->assertSee($note2->text);
    }
}
