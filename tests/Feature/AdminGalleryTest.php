<?php

namespace Tests\Feature;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    public function test_gallery_routes_require_authentication(): void
    {
        $this->get('/admin/gallery-categories')->assertRedirect('/admin/login');
        $this->get('/admin/gallery-images')->assertRedirect('/admin/login');
        $this->get('/admin/gallery-images/create')->assertRedirect('/admin/login');
        $this->get('/admin/gallery-images/data')->assertRedirect('/admin/login');
    }

    public function test_can_create_update_and_delete_gallery_category(): void
    {
        $user = User::factory()->create();

        // Create
        $response = $this->actingAs($user)->post('/admin/gallery-categories', [
            'name' => 'White Desert',
            'sort_order' => 1,
            'is_published' => true,
        ]);
        $response->assertRedirect(route('admin.gallery-categories.index'));
        $this->assertDatabaseHas('gallery_categories', [
            'name' => 'White Desert',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $category = GalleryCategory::first();

        // Update
        $updateResponse = $this->actingAs($user)->put('/admin/gallery-categories/' . $category->id, [
            'name' => 'White Desert Updated',
            'sort_order' => 5,
            'is_published' => false,
        ]);
        $updateResponse->assertRedirect(route('admin.gallery-categories.index'));
        $this->assertDatabaseHas('gallery_categories', [
            'id' => $category->id,
            'name' => 'White Desert Updated',
            'sort_order' => 5,
            'is_published' => false,
        ]);

        // Reorder
        $category2 = GalleryCategory::create(['name' => 'Handicrafts', 'sort_order' => 2]);
        $reorderResponse = $this->actingAs($user)->postJson('/admin/gallery-categories/reorder', [
            'order' => [$category2->id, $category->id],
        ]);
        $reorderResponse->assertOk();
        $this->assertEquals(1, $category2->fresh()->sort_order);
        $this->assertEquals(2, $category->fresh()->sort_order);

        // Delete
        $deleteResponse = $this->actingAs($user)->delete('/admin/gallery-categories/' . $category2->id);
        $deleteResponse->assertRedirect(route('admin.gallery-categories.index'));
        $this->assertDatabaseMissing('gallery_categories', ['id' => $category2->id]);
    }

    public function test_cannot_delete_category_with_photos(): void
    {
        $user = User::factory()->create();
        $category = GalleryCategory::create(['name' => 'Monuments', 'sort_order' => 1]);
        $image = GalleryImage::create([
            'gallery_category_id' => $category->id,
            'image_path' => 'gallery/test.jpg',
            'caption' => 'Aina Mahal',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $response = $this->actingAs($user)->delete('/admin/gallery-categories/' . $category->id);
        $response->assertSessionHasErrors('category');
        $this->assertDatabaseHas('gallery_categories', ['id' => $category->id]);
    }

    public function test_can_upload_update_and_delete_gallery_image(): void
    {
        $user = User::factory()->create();
        $category = GalleryCategory::create(['name' => 'Culture', 'sort_order' => 1]);
        $file = UploadedFile::fake()->image('kutch-camel.jpg', 800, 600);

        // Upload / Store
        $response = $this->actingAs($user)->post('/admin/gallery-images', [
            'gallery_category_id' => $category->id,
            'image' => $file,
            'caption' => 'Sunset with camel',
            'image_alt' => 'Camel in white rann during sunset',
            'is_published' => 1,
            'sort_order' => 3,
        ]);

        $response->assertRedirect(route('admin.gallery-images.index'));
        $this->assertDatabaseHas('gallery_images', [
            'gallery_category_id' => $category->id,
            'caption' => 'Sunset with camel',
            'image_alt' => 'Camel in white rann during sunset',
            'is_published' => true,
            'sort_order' => 3,
        ]);

        $image = GalleryImage::first();
        Storage::disk('public')->assertExists($image->image_path);

        // Edit View
        $editResponse = $this->actingAs($user)->get('/admin/gallery-images/' . $image->id . '/edit');
        $editResponse->assertOk();
        $editResponse->assertSee('Sunset with camel');

        // Update with new image
        $newFile = UploadedFile::fake()->image('kutch-camel-updated.jpg', 900, 700);
        $oldPath = $image->image_path;

        $updateResponse = $this->actingAs($user)->put('/admin/gallery-images/' . $image->id, [
            'gallery_category_id' => $category->id,
            'image' => $newFile,
            'caption' => 'Sunset with camel - Updated',
            'image_alt' => 'New alt text',
            'is_published' => 1,
            'sort_order' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.gallery-images.index'));
        $image->refresh();
        $this->assertEquals('Sunset with camel - Updated', $image->caption);
        $this->assertNotEquals($oldPath, $image->image_path);
        Storage::disk('public')->assertExists($image->image_path);
        Storage::disk('public')->assertMissing($oldPath);

        // DataTables JSON feed
        $dataResponse = $this->actingAs($user)->getJson('/admin/gallery-images/data');
        $dataResponse->assertOk();
        $dataResponse->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        // Delete
        $deletePath = $image->image_path;
        $deleteResponse = $this->actingAs($user)->delete('/admin/gallery-images/' . $image->id);
        $deleteResponse->assertRedirect(route('admin.gallery-images.index'));
        $this->assertDatabaseMissing('gallery_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($deletePath);
    }
}
