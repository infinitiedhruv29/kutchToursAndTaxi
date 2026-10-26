<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function faqData(array $overrides = []): array
    {
        return array_replace(['question' => 'How do I enquire?', 'answer' => '<p>Contact our taxi team.</p>', 'sort_order' => 0], $overrides);
    }

    private function destinationData(array $overrides = []): array
    {
        return array_replace(['name' => 'White Rann', 'sort_order' => 0], $overrides);
    }

    public function test_both_modules_require_authentication_and_add_no_public_or_detail_routes(): void
    {
        $faq = Faq::create($this->faqData());
        $destination = Destination::create($this->destinationData());
        foreach ([['faqs', $faq], ['destinations', $destination]] as [$module, $record]) {
            foreach (['', '/data', '/create', '/'.$record->id.'/edit'] as $suffix) {
                $this->get('/admin/'.$module.$suffix)->assertRedirect('/admin/login');
            }
            $this->postJson('/admin/'.$module, [])->assertUnauthorized();
            $this->putJson('/admin/'.$module.'/'.$record->id, [])->assertUnauthorized();
            $this->deleteJson('/admin/'.$module.'/'.$record->id)->assertUnauthorized();
            $this->get('/'.$module)->assertNotFound();
        }
        $this->actingAs(User::factory()->create());
        $this->get('/admin/faqs/'.$faq->id)->assertStatus(405);
        $this->get('/admin/destinations/'.$destination->id)->assertStatus(405);
        $this->get('/destinations/white-rann')->assertNotFound();
    }

    public function test_empty_lists_forms_and_existing_shared_controls_render(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['faqs' => 'No FAQs found', 'destinations' => 'No destinations found'] as $module => $message) {
            $this->get('/admin/'.$module)->assertOk()->assertSee($message)->assertSee('data-admin-datatable', false)->assertSee('data-delete-feedback', false);
            $this->getJson('/admin/'.$module.'/data?draw=7')->assertExactJson(['draw' => 7, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            $this->get('/admin/'.$module.'/create')->assertOk()->assertSee('data-admin-form', false);
        }
        $this->get('/admin/faqs/create')->assertSee('data-rich-editor', false)->assertSee('Answer formatting');
        $this->get('/admin/destinations/create')->assertSee('Destination image preview')->assertDontSee('Vehicle image preview');
        $this->assertDatabaseCount('faqs', 0);
        $this->assertDatabaseCount('destinations', 0);
    }

    public function test_faq_crud_persistence_duplicates_and_unexpected_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/faqs', $this->faqData(['id' => 900, 'route_id' => 4, 'category_id' => 2]))->assertOk()->assertJsonPath('redirect_url', route('admin.faqs.index'))->assertSessionMissing('status');
        $faq = Faq::firstOrFail();
        $this->assertNotSame(900, $faq->id);
        $this->assertFalse($faq->is_published);
        $this->post('/admin/faqs', $this->faqData())->assertRedirect('/admin/faqs')->assertSessionHas('status');
        $this->assertDatabaseCount('faqs', 2);
        $this->putJson('/admin/faqs/'.$faq->id, $this->faqData(['is_published' => true, 'sort_order' => 8]))->assertOk();
        $this->assertTrue($faq->fresh()->is_published);
        $this->assertSame(8, $faq->fresh()->sort_order);
        $this->get('/admin/faqs/'.$faq->id.'/edit')->assertOk();
        $this->putJson('/admin/faqs/'.$faq->id, $this->faqData())->assertOk();
        $this->assertFalse($faq->fresh()->is_published);
        $this->deleteJson('/admin/faqs/'.$faq->id)->assertOk();
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
        $this->delete('/admin/faqs/'.Faq::firstOrFail()->id)->assertRedirect('/admin/faqs');
    }

    public function test_faq_required_fields_lengths_boolean_and_order_validation(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/faqs', [])->assertUnprocessable()->assertJsonValidationErrors(['question', 'answer', 'sort_order']);
        foreach ([['question' => str_repeat('q', 1001)], ['question' => ['bad']], ['answer' => str_repeat('a', 100001)], ['answer' => ['bad']], ['is_published' => 'bad'], ['sort_order' => -1], ['sort_order' => 4294967296]] as $invalid) {
            $this->postJson('/admin/faqs', $this->faqData($invalid))->assertUnprocessable();
        }
        $this->postJson('/admin/faqs', $this->faqData(['question' => str_repeat('q', 1000), 'answer' => '0']))->assertOk();
    }

    public function test_faq_answers_are_sanitized_and_formatting_survives_round_trips(): void
    {
        $this->actingAs(User::factory()->create());
        $supported = '<h2>Travel</h2><h3>Questions</h3><p><strong>Taxi</strong> <em>service</em><br>Available</p><ul><li>First</li></ul><ol><li>Second</li></ol><p><a href="https://example.com">Details</a></p>';
        $unsafe = '<script>alert(1)</script><iframe src="https://example.com">frame</iframe><p style="color:red" onclick="alert(1)">Safe <a href="javascript:alert(1)">bad</a><a href="data:text/html,bad">data</a><a href="/relative">relative</a><img src=x onerror="bad"></p>';
        $this->postJson('/admin/faqs', $this->faqData(['answer' => $supported.$unsafe]))->assertOk();
        $faq = Faq::firstOrFail();
        foreach (['<h2>Travel</h2>', '<h3>Questions</h3>', '<strong>Taxi</strong>', '<em>service</em>', '<ul>', '<ol>', 'href="https://example.com"'] as $expected) {
            $this->assertStringContainsString($expected, $faq->answer);
        }
        foreach (['<script', '<iframe', '<img', 'onclick', 'style=', 'javascript:', 'data:', 'href="/relative"'] as $unexpected) {
            $this->assertStringNotContainsString($unexpected, $faq->answer);
        }
        $this->get('/admin/faqs/'.$faq->id.'/edit')->assertOk()->assertSee('&lt;h2&gt;Travel&lt;/h2&gt;', false)->assertDontSee('<h2>Travel</h2>', false);
        $this->putJson('/admin/faqs/'.$faq->id, $this->faqData(['answer' => $faq->answer]))->assertOk();
        $this->assertSame($faq->answer, $faq->fresh()->answer);
        $direct = Faq::create($this->faqData(['answer' => '<p>Safe</p><script>bad</script>']));
        $this->assertStringNotContainsString('script', $direct->answer);
    }

    public function test_faq_rejects_content_that_is_empty_after_sanitization_even_when_published(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['<p><br></p>', '<p>&nbsp; </p>', '<script>Only unsafe text</script>', '<iframe>Unsupported</iframe>', '<img src=x>', '<p>&#8203;&#8204;</p>', '<ul><li> </li></ul>'] as $answer) {
            $this->postJson('/admin/faqs', $this->faqData(['answer' => $answer, 'is_published' => true]))->assertUnprocessable()->assertJsonValidationErrors('answer');
        }
        $this->assertDatabaseCount('faqs', 0);
    }

    public function test_native_validation_recovery_retains_escaped_rich_text(): void
    {
        $this->actingAs(User::factory()->create());
        $this->from('/admin/faqs/create')->post('/admin/faqs', $this->faqData(['question' => '', 'answer' => '<p><strong>Keep my answer</strong></p>']))->assertSessionHasErrors('question');
        $this->withCookie(config('session.cookie'), session()->getId())->get('/admin/faqs/create')->assertOk()->assertSee('&lt;p&gt;&lt;strong&gt;Keep my answer&lt;/strong&gt;&lt;/p&gt;', false);
    }

    public function test_faq_search_filters_stable_order_pagination_and_escaped_excerpts(): void
    {
        $this->actingAs(User::factory()->create());
        for ($index = 0; $index < 18; $index++) {
            Faq::create($this->faqData(['question' => sprintf('Question %02d', $index), 'is_published' => true, 'sort_order' => 2]));
        }
        $priority = Faq::create($this->faqData(['question' => 'Priority', 'is_published' => true]));
        $draft = Faq::create($this->faqData(['question' => '<script>Draft 0</script>', 'answer' => '<p>&lt;img src=x&gt; Literal</p>']));
        $this->getJson('/admin/faqs/data?status=published')->assertOk()->assertJsonPath('recordsTotal', 20)->assertJsonPath('recordsFiltered', 19)->assertJsonCount(15, 'data')->assertJsonPath('data.0.id', $priority->id)->assertJsonPath('data.1.id', 18);
        $this->get('/admin/faqs?status=published&page=2')->assertOk()->assertViewHas('faqs', fn ($rows): bool => $rows->count() === 4);
        $response = $this->getJson('/admin/faqs/data?q=Draft&status=draft')->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonPath('data.0.id', $draft->id);
        $this->assertStringNotContainsString('<script>', $response->json('data.0.question'));
        $this->assertStringContainsString('&lt;script&gt;', $response->json('data.0.question'));
        $this->assertStringContainsString('&lt;img src=x&gt;', $response->json('data.0.answer'));
        $this->assertStringNotContainsString('<img', $response->json('data.0.answer'));
        $this->getJson('/admin/faqs/data?q=Contact%20our%20taxi')->assertJsonPath('recordsFiltered', 0);
        $this->getJson('/admin/faqs/data?q=Question&sort=question&direction=asc')->assertJsonPath('data.0.id', 1);
        $this->getJson('/admin/faqs/data?q=Question&sort=question&direction=desc')->assertJsonPath('data.0.id', 18);
        $this->getJson('/admin/faqs/data?q=0')->assertJsonPath('recordsFiltered', 12);
    }

    public function test_destination_crud_optional_fields_featured_drafts_duplicates_and_whitelist(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/destinations', $this->destinationData(['id' => 900, 'image_path' => 'forged.webp', 'slug' => 'ignored', 'route_id' => 1]))->assertOk()->assertJsonPath('redirect_url', route('admin.destinations.index'));
        $destination = Destination::firstOrFail();
        $this->assertFalse($destination->is_published);
        $this->assertFalse($destination->is_featured);
        $this->assertNull($destination->image_path);
        $this->assertNull($destination->short_description);
        $this->assertNotSame(900, $destination->id);
        $this->post('/admin/destinations', $this->destinationData(['is_published' => true]))->assertRedirect('/admin/destinations');
        $this->assertDatabaseCount('destinations', 2);
        $this->putJson('/admin/destinations/'.$destination->id, $this->destinationData(['is_featured' => true, 'sort_order' => 4]))->assertOk();
        $this->assertTrue($destination->fresh()->is_featured);
        $this->assertFalse($destination->fresh()->is_published);
        $this->assertSame(4, $destination->fresh()->sort_order);
        $this->get('/admin/destinations/'.$destination->id.'/edit')->assertOk();
        $this->putJson('/admin/destinations/'.$destination->id, $this->destinationData())->assertOk();
        $this->assertFalse($destination->fresh()->is_featured);
        $this->delete('/admin/destinations/'.$destination->id)->assertRedirect('/admin/destinations');
    }

    public function test_destination_validation_limits_and_plain_text_are_preserved(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson('/admin/destinations', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'sort_order']);
        foreach ([['name' => str_repeat('n', 256)], ['image_alt' => str_repeat('a', 256)], ['is_featured' => 'bad'], ['sort_order' => -1], ['sort_order' => 4294967296]] as $invalid) {
            $this->postJson('/admin/destinations', $this->destinationData($invalid))->assertUnprocessable();
        }
        $this->postJson('/admin/destinations', $this->destinationData(['image_alt' => '<strong>Plain text</strong>']))->assertOk();
        $destination = Destination::firstOrFail();
        $this->assertSame('<strong>Plain text</strong>', $destination->image_alt);
        $this->get('/admin/destinations/'.$destination->id.'/edit')->assertSee('&lt;strong&gt;Plain text&lt;/strong&gt;', false);
    }

    public function test_destination_search_combined_filters_and_escaped_cells(): void
    {
        $this->actingAs(User::factory()->create());
        for ($index = 0; $index < 18; $index++) {
            Destination::create($this->destinationData(['name' => sprintf('Destination %02d', $index), 'is_published' => true, 'is_featured' => true, 'sort_order' => 2]));
        }
        $priority = Destination::create($this->destinationData(['name' => 'Priority', 'is_published' => true, 'is_featured' => true]));
        $draft = Destination::create($this->destinationData(['name' => '<script>Draft Sand dunes</script>']));
        $this->getJson('/admin/destinations/data?status=published&featured=yes')->assertOk()->assertJsonPath('recordsFiltered', 19)->assertJsonCount(15, 'data')->assertJsonPath('data.0.id', $priority->id)->assertJsonPath('data.1.id', 18);
        $this->get('/admin/destinations?status=published&featured=yes&page=2')->assertOk()->assertViewHas('destinations', fn ($rows): bool => $rows->count() === 4);
        $response = $this->getJson('/admin/destinations/data?q=Sand&status=draft&featured=no')->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonPath('data.0.id', $draft->id);
        $this->assertStringContainsString('&lt;script&gt;', $response->json('data.0.destination'));
        $this->assertStringNotContainsString('<script>', $response->json('data.0.destination'));
        $this->getJson('/admin/destinations/data?q=Destination&sort=name&direction=desc')->assertJsonPath('data.0.id', 18);
        $this->getJson('/admin/destinations/data?order[0][column]=2&order[0][dir]=asc')->assertOk();
    }

    public function test_both_table_endpoints_reject_hostile_sorting_filters_and_limits(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['faqs', 'destinations'] as $module) {
            foreach (['sort=answer', 'direction=sideways', 'length=-1', 'start=-1', 'status=unknown', 'order[0][column]=1&order[0][dir]=asc', 'search[regex]=true'] as $query) {
                $this->getJson('/admin/'.$module.'/data?'.$query)->assertUnprocessable();
            }
        }
    }

    public function test_module_listing_return_state_is_preserved_and_hostile_destinations_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['faqs' => $this->faqData(), 'destinations' => $this->destinationData()] as $module => $data) {
            $destination = url('/admin/'.$module).'?q=0&status=draft'.($module === 'destinations' ? '&featured=yes' : '').'&sort=sort_order&direction=desc&page=2&per_page=25';
            $this->get('/admin/'.$module.'/create?'.http_build_query(['return_to' => $destination]))->assertOk()->assertViewHas('returnTo', $destination);
            $this->postJson('/admin/'.$module, $data + ['return_to' => $destination])->assertOk()->assertJsonPath('redirect_url', $destination);
            $id = $module === 'faqs' ? Faq::firstOrFail()->id : Destination::firstOrFail()->id;
            $this->get('/admin/'.$module.'/'.$id.'/edit?'.http_build_query(['return_to' => $destination]))->assertOk()->assertViewHas('returnTo', $destination);
            $this->put('/admin/'.$module.'/'.$id, $data + ['return_to' => $destination])->assertRedirect($destination);
            foreach (['https://evil.test/admin/'.$module, '/admin/vehicles', '/admin/'.$module.'?sort=password'] as $hostile) {
                $this->putJson('/admin/'.$module.'/'.$id, $data + ['return_to' => $hostile])->assertOk()->assertJsonPath('redirect_url', url('/admin/'.$module));
            }
            $this->get('/admin/'.$module.'/999/edit')->assertNotFound();
            $this->putJson('/admin/'.$module.'/999', $data)->assertNotFound();
            $this->deleteJson('/admin/'.$module.'/999')->assertNotFound();
        }
    }

    public function test_destination_upload_replacement_removal_and_same_origin_urls(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/admin/destinations', $this->destinationData(['image' => UploadedFile::fake()->image('rann.webp'), 'image_alt' => 'Salt desert']))->assertRedirect('/admin/destinations');
        $destination = Destination::firstOrFail();
        $first = $destination->image_path;
        Storage::disk('public')->assertExists($first);
        $this->get('/admin/destinations/'.$destination->id.'/edit')->assertOk()->assertSee('src="/storage/'.$first.'"', false);
        $this->assertStringContainsString('src="/storage/'.$first.'"', $this->getJson('/admin/destinations/data')->json('data.0.destination'));
        $this->put('/admin/destinations/'.$destination->id, $this->destinationData(['image' => UploadedFile::fake()->image('new.jpg'), 'remove_image' => true]))->assertRedirect('/admin/destinations');
        $second = $destination->fresh()->image_path;
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);
        $this->putJson('/admin/destinations/'.$destination->id, $this->destinationData(['remove_image' => true]))->assertOk();
        Storage::disk('public')->assertMissing($second);
        $this->assertNull($destination->fresh()->image_path);
    }

    public function test_destination_rejects_unsupported_and_oversized_images(): void
    {
        $this->actingAs(User::factory()->create());
        foreach ([UploadedFile::fake()->create('unsafe.svg', 1, 'image/svg+xml'), UploadedFile::fake()->image('large.png')->size(2049)] as $image) {
            $this->post('/admin/destinations', $this->destinationData(['image' => $image]))->assertSessionHasErrors('image');
        }
        $this->assertDatabaseCount('destinations', 0);
    }

    public function test_destination_failed_save_cleans_upload_and_preserves_old_record_and_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('old.png')->store('destinations', 'public');
        $destination = Destination::create($this->destinationData(['image_path' => $path]));
        Destination::saving(fn () => throw new RuntimeException('Save failed'));
        $this->withoutExceptionHandling();
        try {
            $this->put('/admin/destinations/'.$destination->id, $this->destinationData(['name' => 'Must not save', 'image' => UploadedFile::fake()->image('new.png')]));
            $this->fail('Expected save failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Save failed', $exception->getMessage());
        }
        $this->assertSame('White Rann', $destination->fresh()->name);
        $this->assertSame([$path], Storage::disk('public')->allFiles('destinations'));
    }

    public function test_successful_destination_deletion_cleans_only_its_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('delete.png')->store('destinations', 'public');
        $other = UploadedFile::fake()->image('other.png')->store('destinations', 'public');
        $destination = Destination::create($this->destinationData(['image_path' => $path]));
        $this->deleteJson('/admin/destinations/'.$destination->id)->assertOk();
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($other);
        $this->assertDatabaseCount('destinations', 0);
    }

    public function test_failed_deletion_preserves_content_and_destination_image(): void
    {
        $this->actingAs(User::factory()->create());
        $path = UploadedFile::fake()->image('keep.png')->store('destinations', 'public');
        $destination = Destination::create($this->destinationData(['image_path' => $path]));
        Destination::deleting(fn (): bool => false);
        $this->withoutExceptionHandling();
        try {
            $this->delete('/admin/destinations/'.$destination->id);
            $this->fail('Expected delete failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Unable to delete the destination.', $exception->getMessage());
        }
        $this->assertNotNull($destination->fresh());
        Storage::disk('public')->assertExists($path);
    }
}
