<?php

namespace Database\Seeders;

use App\Models\MasterItem;
use App\Models\MasterItemCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class MasterItemsSeeder extends Seeder
{
    private const BARGE_CATEGORIES = ['Barge up to 330 ft', 'Barge above 330 ft'];

    /**
     * Seed the master item catalog into both Barge categories.
     */
    public function run(): void
    {
        $tenant = Tenant::find('docking');

        if (! $tenant) {
            return;
        }

        $tenant->run(function (): void {
            foreach (self::BARGE_CATEGORIES as $categoryName) {
                $category = MasterItemCategory::where('name', $categoryName)->first();

                if (! $category) {
                    continue;
                }

                $alreadySeeded = MasterItem::query()
                    ->where('category_id', $category->id)
                    ->whereNull('parent_id')
                    ->exists();

                if ($alreadySeeded) {
                    continue;
                }

                $this->insertNodes($this->data(), null, $category->id);
            }
        });
    }

    private function insertNodes(array $nodes, ?int $parentId, int $categoryId): void
    {
        foreach ($nodes as $index => $node) {
            $item = MasterItem::create([
                'category_id' => $categoryId,
                'parent_id' => $parentId,
                'name' => $node['name'],
                'qty' => null,
                'unit' => null,
                'unit_price' => 0,
                'sort_order' => $index + 1,
            ]);

            if (! empty($node['children'])) {
                $this->insertNodes($node['children'], $item->id, $categoryId);
            }
        }
    }

    private function data(): array
    {
        return [
            [
                'name' => 'GENERAL SERVICE / AIRBAG SYSTEM',
                'children' => [
                    [
                        'name' => 'Penanganan naik / turun diatas dock untuk perbaikan meliputi :',
                        'children' => [
                            ['name' => 'Jasa naik dan turun kapal (include persiapan dan pemasangan ganjal blok)'],
                            ['name' => 'Dock Master Service'],
                            ['name' => 'Pelayanan tali temali pada saat kapal masuk dan keluar dock'],
                            ['name' => 'Assistensi shifting olah gerak (mooring boat assist)'],
                        ],
                    ],
                    [
                        'name' => 'Jasa Galangan - Kapal dalam dock meliputi :',
                        'children' => [
                            ['name' => 'Biaya harian kapal di atas dock'],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman'],
                            ['name' => 'Diberikan fasilitas keamanan'],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal'],
                            ['name' => 'Diberikan fasilitas tempat sampah selama perbaikan (termasuk buang)'],
                        ],
                    ],
                    [
                        'name' => 'Biaya sandar sebelum/sesudah dock',
                        'children' => [
                            ['name' => 'Biaya harian kapal selama kapal sandar'],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman'],
                            ['name' => 'Diberikan fasilitas keamanan selama sandar'],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal'],
                            ['name' => 'Diberikan fasilitas tempat sampah selama sandar (termasuk buang)'],
                        ],
                    ],
                    ['name' => 'Gas Free Tangki'],
                    ['name' => 'Fasilitas alat berat untuk angkat peralatan owner dari darat ke kapal'],
                    ['name' => 'Dilakukan pembersihan deck area pada saat kapal sudah selesai repair'],
                ],
            ],
            [
                'name' => 'PERAWATAN LAMBUNG DAN DECK',
                'children' => [
                    ['name' => 'Scrapping lambung kapal dari keel sampai topside untuk menghilangkan tritip'],
                    ['name' => 'Waterjet lambung kapal dari keel sampai topside untuk membersihkan air laut untuk mengurangi proses korosi'],
                    [
                        'name' => 'Blasting lambung kapal dari keel sampai topside',
                        'children' => [
                            [
                                'name' => 'Underwater area',
                                'children' => [
                                    ['name' => 'Full blasting'],
                                ],
                            ],
                            [
                                'name' => 'Top side',
                                'children' => [
                                    ['name' => 'Sweep blasting'],
                                    ['name' => 'Spot blasting'],
                                ],
                            ],
                            [
                                'name' => 'Bullwark external side',
                                'children' => [
                                    ['name' => 'Sweep blasting'],
                                    ['name' => 'Spot blasting'],
                                ],
                            ],
                            [
                                'name' => 'Walkway / Main deck eks.',
                                'children' => [
                                    ['name' => 'Sweep blasting'],
                                ],
                            ],
                        ],
                    ],
                    ['name' => 'Pekerjaan pengecatan'],
                    ['name' => 'Jasa perawatan plimsol mark, draft mark, port registry dan nama kapal kemudian dicat putih'],
                    [
                        'name' => 'Zinc/Aluminium Anode',
                        'children' => [
                            ['name' => 'Bongkar pasang ganti baru zinc/alumunium anode @ 8 kg'],
                            ['name' => 'Dilakukan proteksi pada zinc anode agar tidak terkena cat'],
                            ['name' => 'Bongkar pasang kaki-kaki zinc anode'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'STEEL WORK',
                'children' => [
                    [
                        'name' => 'Replating',
                        'children' => [
                            ['name' => 'Common Area'],
                            ['name' => 'Internal Frame area'],
                        ],
                    ],
                    [
                        'name' => 'Asistensi pemasangan scaffolding',
                        'children' => [
                            ['name' => 'Common Area'],
                            ['name' => 'Internal Frame area'],
                        ],
                    ],
                    ['name' => 'Pengelasan Ulang ( Rewelding )'],
                ],
            ],
            [
                'name' => 'ANCHOR, ANCHOR CHAIN & CHAIN LOCKER',
                'children' => [
                    [
                        'name' => 'Anchor & Anchor Chain',
                        'children' => [
                            ['name' => 'Penurunan jangkar dan rantai jangkar digelar untuk dilakukan inspeksi'],
                            ['name' => 'Kalibrasi jangkar dan rantai jangkar dan dibuatkan laporannya'],
                            ['name' => 'Bongkar pasang jangkar dan rantai jangkar'],
                            [
                                'name' => 'Perawatan jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping'],
                                    ['name' => 'Painting'],
                                ],
                            ],
                            [
                                'name' => 'Perawatan rantai jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping'],
                                    ['name' => 'Painting'],
                                ],
                            ],
                            ['name' => 'Dibuatkan tanda persegelnya dan kemudian dipasang kembali'],
                        ],
                    ],
                    [
                        'name' => 'Chain Locker',
                        'children' => [
                            ['name' => 'Cleaning bak rantai jangkar'],
                            ['name' => 'Dilakukan pengecatan bak rantai jangkar'],
                            ['name' => 'Pembuangan lumpur'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'OUTFITTINGS',
                'children' => [
                    ['name' => 'Bongkar pasang pad eye yang rusak'],
                    ['name' => 'Bongkar pasang ban daprah'],
                    ['name' => 'Bongkar pasang ganti baru rantai dan shackle ban dapra'],
                    ['name' => 'Bongkar dan penyemenan ulang tutup manhole'],
                    ['name' => 'Bongkar dan pasang tutup manhole'],
                    ['name' => 'Pasang dan penggantian baru packing manhole'],
                    ['name' => 'Pengganian B/N Manhole'],
                ],
            ],
            [
                'name' => 'INSPECTION & TEST',
                'children' => [
                    ['name' => 'Air Pressure test tangki untuk pengetesan kebocoran tangki setelah replating'],
                    ['name' => 'Vacum test tangki untuk pengetesan kebocoran tangki setelah replating'],
                    ['name' => 'UT test untuk mengukur ketebalan plate'],
                ],
            ],
            [
                'name' => 'OTHER',
                'children' => [
                    ['name' => 'Dibuatkan gambar bukaan kulit untuk area replating'],
                    ['name' => 'Docking report'],
                ],
            ],
        ];
    }
}
