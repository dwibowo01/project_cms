<?php

namespace Database\Seeders;

use App\Models\MasterItem;
use App\Models\MasterItemCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class MasterItemsSeeder extends Seeder
{
    private const BARGE_CATEGORY_DATA = [
        'Barge up to 330 ft' => 'dataBargeUpTo330Ft',
        'Barge above 330 ft' => 'dataBargeAbove330Ft',
    ];

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
            foreach (self::BARGE_CATEGORY_DATA as $categoryName => $dataMethod) {
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

                $this->insertNodes($this->{$dataMethod}(), null, $category->id);
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
                'qty' => $node['qty'] ?? null,
                'unit' => $node['unit'] ?? null,
                'unit_price' => $node['unit_price'] ?? 0,
                'sort_order' => $index + 1,
            ]);

            if (! empty($node['children'])) {
                $this->insertNodes($node['children'], $item->id, $categoryId);
            }
        }
    }

    /**
     * Item tree for "Barge up to 330 ft", with qty/unit/unit_price sourced from
     * public/Masterlist Item/PENAWARAN - DOCKING BARGE LOA up to 330 FT.xlsx.
     */
    private function dataBargeUpTo330Ft(): array
    {
        return [
            [
                'name' => 'GENERAL SERVICE / AIRBAG SYSTEM',
                'children' => [
                    [
                        'name' => 'Penanganan naik / turun diatas dock untuk perbaikan meliputi :',
                        'children' => [
                            ['name' => 'Jasa naik dan turun kapal (include persiapan dan pemasangan ganjal blok)', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 75000000],
                            ['name' => 'Dock Master Service', 'qty' => 2, 'unit' => 'ops', 'unit_price' => 1500000],
                            ['name' => 'Pelayanan tali temali pada saat kapal masuk dan keluar dock', 'qty' => 2, 'unit' => 'ops', 'unit_price' => 500000],
                            ['name' => 'Assistensi shifting olah gerak (mooring boat assist)', 'qty' => 2, 'unit' => 'lot', 'unit_price' => 11000000],
                        ],
                    ],
                    [
                        'name' => 'Jasa Galangan - Kapal dalam dock meliputi :',
                        'children' => [
                            ['name' => 'Biaya harian kapal di atas dock', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 2500000],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 200000],
                            ['name' => 'Diberikan fasilitas keamanan', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 150000],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 100000],
                            ['name' => 'Diberikan fasilitas tempat sampah selama perbaikan (termasuk buang)', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 50000],
                        ],
                    ],
                    [
                        'name' => 'Biaya sandar sebelum/sesudah dock',
                        'children' => [
                            ['name' => 'Biaya harian kapal selama kapal sandar', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 750000],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 200000],
                            ['name' => 'Diberikan fasilitas keamanan selama sandar', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 150000],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 100000],
                            ['name' => 'Diberikan fasilitas tempat sampah selama sandar (termasuk buang)', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 50000],
                        ],
                    ],
                    ['name' => 'Gas Free Tangki', 'qty' => 10, 'unit' => 'tangki', 'unit_price' => 250000],
                    ['name' => 'Fasilitas alat berat untuk angkat peralatan owner dari darat ke kapal', 'qty' => 1, 'unit' => 'jam', 'unit_price' => 1500000],
                    ['name' => 'Dilakukan pembersihan deck area pada saat kapal sudah selesai repair', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 7500000],
                ],
            ],
            [
                'name' => 'PERAWATAN LAMBUNG DAN DECK',
                'children' => [
                    ['name' => 'Scrapping lambung kapal dari keel sampai topside untuk menghilangkan tritip', 'qty' => 2100, 'unit' => 'm2', 'unit_price' => 20000],
                    ['name' => 'Waterjet lambung kapal dari keel sampai topside untuk membersihkan air laut untuk mengurangi proses korosi', 'qty' => 3300, 'unit' => 'm2', 'unit_price' => 20000],
                    [
                        'name' => 'Blasting lambung kapal dari keel sampai topside',
                        'children' => [
                            [
                                'name' => 'Underwater area',
                                'children' => [
                                    ['name' => 'Full blasting', 'qty' => 2100, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Top side',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 840, 'unit' => 'm2', 'unit_price' => 68000],
                                    ['name' => 'Spot blasting', 'qty' => 360, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Bullwark external side',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 105, 'unit' => 'm2', 'unit_price' => 68000],
                                    ['name' => 'Spot blasting', 'qty' => 45, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Walkway / Main deck eks.',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 315, 'unit' => 'm2', 'unit_price' => 68000],
                                ],
                            ],
                        ],
                    ],
                    ['name' => 'Pekerjaan pengecatan'],
                    ['name' => 'Jasa perawatan plimsol mark, draft mark, port registry dan nama kapal kemudian dicat putih', 'qty' => 1, 'unit' => 'ship', 'unit_price' => 4500000],
                    [
                        'name' => 'Zinc/Aluminium Anode',
                        'children' => [
                            ['name' => 'Bongkar pasang ganti baru zinc/alumunium anode @ 8 kg', 'qty' => 46, 'unit' => 'pcs', 'unit_price' => 150000],
                            ['name' => 'Dilakukan proteksi pada zinc anode agar tidak terkena cat', 'qty' => 46, 'unit' => 'pcs', 'unit_price' => 50000],
                            ['name' => 'Bongkar pasang kaki-kaki zinc anode', 'qty' => 92, 'unit' => 'pcs', 'unit_price' => 100000],
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
                            ['name' => 'Common Area', 'qty' => 1, 'unit' => 'kg', 'unit_price' => 35000],
                            ['name' => 'Internal Frame area', 'qty' => 1, 'unit' => 'kg', 'unit_price' => 37000],
                        ],
                    ],
                    [
                        'name' => 'Asistensi pemasangan scaffolding',
                        'children' => [
                            ['name' => 'Common Area', 'qty' => 1, 'unit' => 'm3', 'unit_price' => 65000],
                            ['name' => 'Internal Frame area', 'qty' => 1, 'unit' => 'm3', 'unit_price' => 85000],
                        ],
                    ],
                    ['name' => 'Pengelasan Ulang ( Rewelding )', 'qty' => 1, 'unit' => 'm', 'unit_price' => 150000],
                ],
            ],
            [
                'name' => 'ANCHOR, ANCHOR CHAIN & CHAIN LOCKER',
                'children' => [
                    [
                        'name' => 'Anchor & Anchor Chain',
                        'children' => [
                            ['name' => 'Penurunan jangkar dan rantai jangkar digelar untuk dilakukan inspeksi', 'qty' => 2, 'unit' => 'set', 'unit_price' => 3500000],
                            ['name' => 'Kalibrasi jangkar dan rantai jangkar dan dibuatkan laporannya', 'qty' => 2, 'unit' => 'set', 'unit_price' => 5000000],
                            ['name' => 'Bongkar pasang jangkar dan rantai jangkar', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                            [
                                'name' => 'Perawatan jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1000000],
                                    ['name' => 'Painting', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                                ],
                            ],
                            [
                                'name' => 'Perawatan rantai jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1500000],
                                    ['name' => 'Painting', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1000000],
                                ],
                            ],
                            ['name' => 'Dibuatkan tanda persegelnya dan kemudian dipasang kembali', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                        ],
                    ],
                    [
                        'name' => 'Chain Locker',
                        'children' => [
                            ['name' => 'Cleaning bak rantai jangkar', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 4000000],
                            ['name' => 'Dilakukan pengecatan bak rantai jangkar', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 2000000],
                            ['name' => 'Pembuangan lumpur', 'qty' => 1, 'unit' => 'ton', 'unit_price' => 1000000],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'OUTFITTINGS',
                'children' => [
                    ['name' => 'Bongkar pasang pad eye yang rusak', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 350000],
                    ['name' => 'Bongkar pasang ban daprah', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 200000],
                    ['name' => 'Bongkar pasang ganti baru rantai dan shackle ban dapra', 'qty' => 10, 'unit' => 'set', 'unit_price' => 450000],
                    ['name' => 'Bongkar dan penyemenan ulang tutup manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 350000],
                    ['name' => 'Bongkar dan pasang tutup manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 350000],
                    ['name' => 'Pasang dan penggantian baru packing manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 175000],
                    ['name' => 'Pengganian B/N Manhole', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 150000],
                ],
            ],
            [
                'name' => 'INSPECTION & TEST',
                'children' => [
                    ['name' => 'Air Pressure test tangki untuk pengetesan kebocoran tangki setelah replating', 'qty' => 1, 'unit' => 'tanks', 'unit_price' => 2750000],
                    ['name' => 'Vacum test tangki untuk pengetesan kebocoran tangki setelah replating', 'qty' => 1, 'unit' => 'tanks', 'unit_price' => 2750000],
                    ['name' => 'UT test untuk mengukur ketebalan plate', 'qty' => 300, 'unit' => 'spot', 'unit_price' => 45000],
                ],
            ],
            [
                'name' => 'OTHER',
                'children' => [
                    ['name' => 'Dibuatkan gambar bukaan kulit untuk area replating', 'qty' => 1, 'unit' => 'lot', 'unit_price' => 1500000],
                    ['name' => 'Docking report', 'qty' => 1, 'unit' => 'lot', 'unit_price' => 2000000],
                ],
            ],
        ];
    }

    /**
     * Item tree for "Barge above 330 ft", with qty/unit/unit_price sourced from
     * public/Masterlist Item/PENAWARAN - DOCKING BARGE LOA above 330 FT.xlsx.
     * Fractional m2 quantities from the source (e.g. 0.7/0.3 blasting splits) are
     * rounded to whole numbers since master_items.qty is an unsigned integer column.
     */
    private function dataBargeAbove330Ft(): array
    {
        return [
            [
                'name' => 'GENERAL SERVICE / AIRBAG SYSTEM',
                'children' => [
                    [
                        'name' => 'Penanganan naik / turun diatas dock untuk perbaikan meliputi :',
                        'children' => [
                            ['name' => 'Jasa naik dan turun kapal (include persiapan dan pemasangan ganjal blok)', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 90000000],
                            ['name' => 'Dock Master Service', 'qty' => 2, 'unit' => 'ops', 'unit_price' => 1500000],
                            ['name' => 'Pelayanan tali temali pada saat kapal masuk dan keluar dock', 'qty' => 2, 'unit' => 'ops', 'unit_price' => 500000],
                            ['name' => 'Assistensi shifting olah gerak (mooring boat assist)', 'qty' => 2, 'unit' => 'lot', 'unit_price' => 11000000],
                        ],
                    ],
                    [
                        'name' => 'Jasa Galangan - Kapal dalam dock meliputi :',
                        'children' => [
                            ['name' => 'Biaya harian kapal di atas dock', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 3500000],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 200000],
                            ['name' => 'Diberikan fasilitas keamanan', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 150000],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 100000],
                            ['name' => 'Diberikan fasilitas tempat sampah selama perbaikan (termasuk buang)', 'qty' => 14, 'unit' => 'hari', 'unit_price' => 50000],
                        ],
                    ],
                    [
                        'name' => 'Biaya sandar sebelum/sesudah dock',
                        'children' => [
                            ['name' => 'Biaya harian kapal selama kapal sandar', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 750000],
                            ['name' => 'Diberikan fasilitas pemadam kebakaran dan watchman', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 200000],
                            ['name' => 'Diberikan fasilitas keamanan selama sandar', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 150000],
                            ['name' => 'Diberikan fasilitas tangga akses ke atas kapal', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 100000],
                            ['name' => 'Diberikan fasilitas tempat sampah selama sandar (termasuk buang)', 'qty' => 3, 'unit' => 'hari', 'unit_price' => 50000],
                        ],
                    ],
                    ['name' => 'Gas Free Tangki', 'qty' => 10, 'unit' => 'tangki', 'unit_price' => 250000],
                    ['name' => 'Fasilitas alat berat untuk angkat peralatan owner dari darat ke kapal', 'qty' => 1, 'unit' => 'jam', 'unit_price' => 1500000],
                    ['name' => 'Dilakukan pembersihan deck area pada saat kapal sudah selesai repair', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 7500000],
                ],
            ],
            [
                'name' => 'PERAWATAN LAMBUNG DAN DECK',
                'children' => [
                    ['name' => 'Scrapping lambung kapal dari keel sampai topside untuk menghilangkan tritip', 'qty' => 2875, 'unit' => 'm2', 'unit_price' => 20000],
                    ['name' => 'Waterjet lambung kapal dari keel sampai topside untuk membersihkan air laut untuk mengurangi proses korosi', 'qty' => 4311, 'unit' => 'm2', 'unit_price' => 20000],
                    [
                        'name' => 'Blasting lambung kapal dari keel sampai topside',
                        'children' => [
                            [
                                'name' => 'Underwater area',
                                'children' => [
                                    ['name' => 'Full blasting', 'qty' => 2875, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Top side',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 1005, 'unit' => 'm2', 'unit_price' => 68000],
                                    ['name' => 'Spot blasting', 'qty' => 431, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Bullwark external side',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 119, 'unit' => 'm2', 'unit_price' => 68000],
                                    ['name' => 'Spot blasting', 'qty' => 51, 'unit' => 'm2', 'unit_price' => 70000],
                                ],
                            ],
                            [
                                'name' => 'Walkway / Main deck eks.',
                                'children' => [
                                    ['name' => 'Sweep blasting', 'qty' => 500, 'unit' => 'm2', 'unit_price' => 68000],
                                ],
                            ],
                        ],
                    ],
                    ['name' => 'Pekerjaan pengecatan'],
                    ['name' => 'Jasa perawatan plimsol mark, draft mark, port registry dan nama kapal kemudian dicat putih', 'qty' => 1, 'unit' => 'ship', 'unit_price' => 4500000],
                    [
                        'name' => 'Zinc/Aluminium Anode',
                        'children' => [
                            ['name' => 'Bongkar pasang ganti baru zinc/alumunium anode @ 8 kg', 'qty' => 46, 'unit' => 'pcs', 'unit_price' => 150000],
                            ['name' => 'Dilakukan proteksi pada zinc anode agar tidak terkena cat', 'qty' => 46, 'unit' => 'pcs', 'unit_price' => 50000],
                            ['name' => 'Bongkar pasang kaki-kaki zinc anode', 'qty' => 92, 'unit' => 'pcs', 'unit_price' => 100000],
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
                            ['name' => 'Common Area', 'qty' => 1, 'unit' => 'kg', 'unit_price' => 35000],
                            ['name' => 'Internal Frame area', 'qty' => 1, 'unit' => 'kg', 'unit_price' => 37000],
                        ],
                    ],
                    [
                        'name' => 'Asistensi pemasangan scaffolding',
                        'children' => [
                            ['name' => 'Common Area', 'qty' => 1, 'unit' => 'm3', 'unit_price' => 65000],
                            ['name' => 'Internal Frame area', 'qty' => 1, 'unit' => 'm3', 'unit_price' => 85000],
                        ],
                    ],
                    ['name' => 'Pengelasan Ulang ( Rewelding )', 'qty' => 1, 'unit' => 'm', 'unit_price' => 150000],
                ],
            ],
            [
                'name' => 'ANCHOR, ANCHOR CHAIN & CHAIN LOCKER',
                'children' => [
                    [
                        'name' => 'Anchor & Anchor Chain',
                        'children' => [
                            ['name' => 'Penurunan jangkar dan rantai jangkar digelar untuk dilakukan inspeksi', 'qty' => 2, 'unit' => 'set', 'unit_price' => 3500000],
                            ['name' => 'Kalibrasi jangkar dan rantai jangkar dan dibuatkan laporannya', 'qty' => 2, 'unit' => 'set', 'unit_price' => 5000000],
                            ['name' => 'Bongkar pasang jangkar dan rantai jangkar', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                            [
                                'name' => 'Perawatan jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1000000],
                                    ['name' => 'Painting', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                                ],
                            ],
                            [
                                'name' => 'Perawatan rantai jangkar',
                                'children' => [
                                    ['name' => 'Blasting dan chipping', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1500000],
                                    ['name' => 'Painting', 'qty' => 2, 'unit' => 'set', 'unit_price' => 1000000],
                                ],
                            ],
                            ['name' => 'Dibuatkan tanda persegelnya dan kemudian dipasang kembali', 'qty' => 2, 'unit' => 'set', 'unit_price' => 500000],
                        ],
                    ],
                    [
                        'name' => 'Chain Locker',
                        'children' => [
                            ['name' => 'Cleaning bak rantai jangkar', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 4000000],
                            ['name' => 'Dilakukan pengecatan bak rantai jangkar', 'qty' => 1, 'unit' => 'ls', 'unit_price' => 2000000],
                            ['name' => 'Pembuangan lumpur', 'qty' => 1, 'unit' => 'ton', 'unit_price' => 1000000],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'OUTFITTINGS',
                'children' => [
                    ['name' => 'Bongkar pasang pad eye yang rusak', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 350000],
                    ['name' => 'Bongkar pasang ban daprah', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 200000],
                    ['name' => 'Bongkar pasang ganti baru rantai dan shackle ban dapra', 'qty' => 10, 'unit' => 'set', 'unit_price' => 450000],
                    ['name' => 'Bongkar dan penyemenan ulang tutup manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 350000],
                    ['name' => 'Bongkar dan pasang tutup manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 350000],
                    ['name' => 'Pasang dan penggantian baru packing manhole', 'qty' => 2, 'unit' => 'unit', 'unit_price' => 175000],
                    ['name' => 'Pengganian B/N Manhole', 'qty' => 10, 'unit' => 'pcs', 'unit_price' => 150000],
                ],
            ],
            [
                'name' => 'INSPECTION & TEST',
                'children' => [
                    ['name' => 'Air Pressure test tangki untuk pengetesan kebocoran tangki setelah replating', 'qty' => 1, 'unit' => 'tanks', 'unit_price' => 2750000],
                    ['name' => 'Vacum test tangki untuk pengetesan kebocoran tangki setelah replating', 'qty' => 1, 'unit' => 'tanks', 'unit_price' => 2750000],
                    ['name' => 'UT test untuk mengukur ketebalan plate', 'qty' => 300, 'unit' => 'spot', 'unit_price' => 45000],
                ],
            ],
            [
                'name' => 'OTHER',
                'children' => [
                    ['name' => 'Dibuatkan gambar bukaan kulit untuk area replating', 'qty' => 1, 'unit' => 'lot', 'unit_price' => 1500000],
                    ['name' => 'Docking report', 'qty' => 1, 'unit' => 'lot', 'unit_price' => 2000000],
                ],
            ],
        ];
    }
}
