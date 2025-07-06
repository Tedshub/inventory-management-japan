<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BulkDeleteBarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_bulk_delete_barang()
    {
        $user = User::factory()->create();
        $barangs = Barang::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->post('/barang/bulk-delete', [
                'ids' => $barangs->pluck('id')->toArray()
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        foreach ($barangs as $barang) {
            $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);
        }
    }

    public function test_bulk_delete_validates_ids()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/barang/bulk-delete', [
                'ids' => []
            ]);

        $response->assertSessionHasErrors(['ids']);
    }

    public function test_bulk_delete_validates_existing_ids()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/barang/bulk-delete', [
                'ids' => [99999] // ID yang tidak ada
            ]);

        $response->assertSessionHasErrors(['ids.0']);
    }
}
