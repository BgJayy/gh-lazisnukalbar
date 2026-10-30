<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Support\ArticleCover;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCoverTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_covers_are_resized_to_the_social_preview_size(): void
    {
        Storage::fake('public');

        $this->writeJpeg('library/wide.jpg', 2400, 400);
        $this->writeJpeg('articles/uploads/small.jpg', 300, 200);
        $original = Storage::disk('public')->get('library/wide.jpg');

        $wide = Article::query()->create([
            'title' => 'Berita lebar',
            'slug' => 'berita-lebar',
            'body' => '<p>Isi.</p>',
            'cover_path' => 'library/wide.jpg',
            'published_at' => now(),
        ]);
        $small = Article::query()->create([
            'title' => 'Berita kecil',
            'slug' => 'berita-kecil',
            'body' => '<p>Isi.</p>',
            'cover_path' => 'articles/uploads/small.jpg',
            'published_at' => now(),
        ]);

        $wide->refresh();
        $small->refresh();

        $this->assertSame('articles/covers/'.$wide->id.'.jpg', $wide->cover_path);
        $this->assertSame('articles/covers/'.$small->id.'.jpg', $small->cover_path);
        $this->assertSame($original, Storage::disk('public')->get('library/wide.jpg'));
        $this->assertFalse(Storage::disk('public')->exists('articles/uploads/small.jpg'));

        foreach ([$wide, $small] as $article) {
            $size = getimagesize(Storage::disk('public')->path($article->cover_path));
            $this->assertSame(ArticleCover::WIDTH, $size[0]);
            $this->assertSame(ArticleCover::HEIGHT, $size[1]);
        }

        $this->get('/berita/'.$wide->slug)
            ->assertOk()
            ->assertSee('property="og:image"', false)
            ->assertSee('content="1200"', false)
            ->assertSee('content="630"', false)
            ->assertSee('articles/covers/'.$wide->id.'.jpg', false);
    }

    public function test_a_missing_upload_path_keeps_the_saved_cover(): void
    {
        Storage::fake('public');
        $this->writeJpeg('articles/uploads/awal.jpg', 1600, 900);

        $article = Article::query()->create([
            'title' => 'Berita sampul',
            'slug' => 'berita-sampul',
            'body' => '<p>Isi.</p>',
            'cover_path' => 'articles/uploads/awal.jpg',
            'published_at' => now(),
        ]);

        $standard = 'articles/covers/'.$article->id.'.jpg';
        $this->assertTrue(ArticleCover::isStandard($standard), 'standard missing before the broken save');

        $article->cover_path = 'articles/uploads/hilang.jpg';
        $article->save();
        $article->refresh();

        $this->assertSame('articles/covers/'.$article->id.'.jpg', $article->cover_path);
        $this->get('/berita/'.$article->slug)
            ->assertOk()
            ->assertSee('articles/covers/'.$article->id.'.jpg', false);
    }

    private function writeJpeg(string $path, int $width, int $height): void
    {
        $image = imagecreatetruecolor($width, $height);
        $absolute = Storage::disk('public')->path($path);
        $directory = dirname($absolute);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        imagejpeg($image, $absolute, 90);
        imagedestroy($image);
    }
}
