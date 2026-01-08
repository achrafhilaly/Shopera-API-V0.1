<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PaperProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Paper Categories
        $copyPaperCategory = Category::create([
            'name' => 'Copy Paper',
            'slug' => 'copy-paper',
            'is_active' => true,
        ]);

        $cardstockCategory = Category::create([
            'name' => 'Cardstock',
            'slug' => 'cardstock',
            'is_active' => true,
        ]);

        $specialtyPaperCategory = Category::create([
            'name' => 'Specialty Paper',
            'slug' => 'specialty-paper',
            'is_active' => true,
        ]);

        $envelopesCategory = Category::create([
            'name' => 'Envelopes',
            'slug' => 'envelopes',
            'is_active' => true,
        ]);

        // Create 20 Paper Products
        $products = [
            // Copy Paper Products (1-8)
            [
                'name' => 'A4 White Copy Paper - 80gsm (1 Ream)',
                'sku' => 'A4-WHITE-80-1R',
                'description' => 'Premium quality A4 white copy paper, 80gsm weight. Perfect for everyday printing and copying. 500 sheets per ream.',
                'status' => 'active',
                'stock_quantity' => 500,
                'price' => ['base' => 4.99, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 80,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                ]
            ],
            [
                'name' => 'A4 White Copy Paper - 80gsm (5 Reams)',
                'sku' => 'A4-WHITE-80-5R',
                'description' => 'Bulk pack of premium A4 white copy paper. 5 reams (2500 sheets total). Great value for high-volume printing.',
                'status' => 'active',
                'stock_quantity' => 200,
                'price' => ['base' => 22.99, 'discount' => 19.99],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 80,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 2500,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                    'pack_size' => '5 Reams',
                ]
            ],
            [
                'name' => 'A3 White Copy Paper - 80gsm (1 Ream)',
                'sku' => 'A3-WHITE-80-1R',
                'description' => 'A3 size white copy paper for larger format printing. 80gsm, 500 sheets per ream.',
                'status' => 'active',
                'stock_quantity' => 150,
                'price' => ['base' => 9.99, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 80,
                    'size' => 'A3',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '297mm x 420mm',
                    'finish' => 'Matte',
                ]
            ],
            [
                'name' => 'Letter Size White Copy Paper - 20lb (1 Ream)',
                'sku' => 'LTR-WHITE-20-1R',
                'description' => 'Standard US Letter size white copy paper. 20lb weight (75gsm). 500 sheets per ream.',
                'status' => 'active',
                'stock_quantity' => 400,
                'price' => ['base' => 5.49, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 75,
                    'size' => 'Letter',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '8.5" x 11"',
                    'finish' => 'Matte',
                    'weight_lb' => 20,
                ]
            ],
            [
                'name' => 'A4 Recycled Copy Paper - 80gsm (1 Ream)',
                'sku' => 'A4-RECYCLED-80-1R',
                'description' => '100% recycled A4 copy paper. Environmentally friendly choice without compromising quality. 500 sheets.',
                'status' => 'active',
                'stock_quantity' => 300,
                'price' => ['base' => 5.99, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 80,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                    'recycled' => true,
                ]
            ],
            [
                'name' => 'A4 Colored Copy Paper - Assorted (1 Ream)',
                'sku' => 'A4-COLOR-80-1R',
                'description' => 'Vibrant colored copy paper in assorted colors. Perfect for creative projects and presentations. 500 sheets.',
                'status' => 'active',
                'stock_quantity' => 250,
                'price' => ['base' => 6.99, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 80,
                    'size' => 'A4',
                    'color' => 'Assorted',
                    'sheets_per_pack' => 500,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                ]
            ],
            [
                'name' => 'A4 Premium Copy Paper - 100gsm (1 Ream)',
                'sku' => 'A4-PREMIUM-100-1R',
                'description' => 'High-quality premium copy paper with 100gsm weight. Superior opacity and feel. 500 sheets.',
                'status' => 'active',
                'stock_quantity' => 180,
                'price' => ['base' => 7.99, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 100,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                    'premium' => true,
                ]
            ],
            [
                'name' => 'Legal Size Copy Paper - 20lb (1 Ream)',
                'sku' => 'LEGAL-WHITE-20-1R',
                'description' => 'Legal size white copy paper (8.5" x 14"). Perfect for legal documents and contracts. 500 sheets.',
                'status' => 'active',
                'stock_quantity' => 120,
                'price' => ['base' => 6.49, 'discount' => null],
                'category_id' => $copyPaperCategory->id,
                'metadata' => [
                    'gsm' => 75,
                    'size' => 'Legal',
                    'color' => 'White',
                    'sheets_per_pack' => 500,
                    'dimensions' => '8.5" x 14"',
                    'finish' => 'Matte',
                    'weight_lb' => 20,
                ]
            ],

            // Cardstock Products (9-12)
            [
                'name' => 'A4 White Cardstock - 200gsm (100 sheets)',
                'sku' => 'A4-CARDSTOCK-200-WHITE',
                'description' => 'Heavy-duty white cardstock, 200gsm. Perfect for business cards, invitations, and crafts. 100 sheets.',
                'status' => 'active',
                'stock_quantity' => 200,
                'price' => ['base' => 12.99, 'discount' => null],
                'category_id' => $cardstockCategory->id,
                'metadata' => [
                    'gsm' => 200,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 100,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Smooth',
                ]
            ],
            [
                'name' => 'A4 Colored Cardstock - 250gsm (50 sheets)',
                'sku' => 'A4-CARDSTOCK-250-COLOR',
                'description' => 'Premium colored cardstock in assorted vibrant colors. 250gsm weight. 50 sheets per pack.',
                'status' => 'active',
                'stock_quantity' => 150,
                'price' => ['base' => 14.99, 'discount' => null],
                'category_id' => $cardstockCategory->id,
                'metadata' => [
                    'gsm' => 250,
                    'size' => 'A4',
                    'color' => 'Assorted',
                    'sheets_per_pack' => 50,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Smooth',
                ]
            ],
            [
                'name' => 'Letter Size Cardstock - 110lb (100 sheets)',
                'sku' => 'LTR-CARDSTOCK-110-WHITE',
                'description' => 'Heavy letter size cardstock, 110lb weight (300gsm). Professional quality for presentations.',
                'status' => 'active',
                'stock_quantity' => 100,
                'price' => ['base' => 16.99, 'discount' => null],
                'category_id' => $cardstockCategory->id,
                'metadata' => [
                    'gsm' => 300,
                    'size' => 'Letter',
                    'color' => 'White',
                    'sheets_per_pack' => 100,
                    'dimensions' => '8.5" x 11"',
                    'finish' => 'Smooth',
                    'weight_lb' => 110,
                ]
            ],
            [
                'name' => 'A5 Kraft Cardstock - 300gsm (100 sheets)',
                'sku' => 'A5-CARDSTOCK-300-KRAFT',
                'description' => 'Natural kraft cardstock with rustic appeal. 300gsm weight, A5 size. Perfect for DIY projects.',
                'status' => 'active',
                'stock_quantity' => 130,
                'price' => ['base' => 11.99, 'discount' => null],
                'category_id' => $cardstockCategory->id,
                'metadata' => [
                    'gsm' => 300,
                    'size' => 'A5',
                    'color' => 'Kraft',
                    'sheets_per_pack' => 100,
                    'dimensions' => '148mm x 210mm',
                    'finish' => 'Natural',
                ]
            ],

            // Specialty Paper Products (13-16)
            [
                'name' => 'A4 Glossy Photo Paper - 260gsm (20 sheets)',
                'sku' => 'A4-PHOTO-260-GLOSSY',
                'description' => 'Professional glossy photo paper. Vibrant colors and sharp details. 260gsm. 20 sheets.',
                'status' => 'active',
                'stock_quantity' => 180,
                'price' => ['base' => 8.99, 'discount' => null],
                'category_id' => $specialtyPaperCategory->id,
                'metadata' => [
                    'gsm' => 260,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 20,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Glossy',
                    'photo_paper' => true,
                ]
            ],
            [
                'name' => 'A4 Matte Photo Paper - 230gsm (50 sheets)',
                'sku' => 'A4-PHOTO-230-MATTE',
                'description' => 'Premium matte finish photo paper. No glare, perfect for framing. 230gsm. 50 sheets.',
                'status' => 'active',
                'stock_quantity' => 160,
                'price' => ['base' => 15.99, 'discount' => null],
                'category_id' => $specialtyPaperCategory->id,
                'metadata' => [
                    'gsm' => 230,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 50,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Matte',
                    'photo_paper' => true,
                ]
            ],
            [
                'name' => 'A4 Watercolor Paper - 300gsm (25 sheets)',
                'sku' => 'A4-WATERCOLOR-300',
                'description' => 'Professional cold-pressed watercolor paper. 300gsm weight. Acid-free and archival quality.',
                'status' => 'active',
                'stock_quantity' => 90,
                'price' => ['base' => 18.99, 'discount' => null],
                'category_id' => $specialtyPaperCategory->id,
                'metadata' => [
                    'gsm' => 300,
                    'size' => 'A4',
                    'color' => 'White',
                    'sheets_per_pack' => 25,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Cold-pressed',
                    'acid_free' => true,
                ]
            ],
            [
                'name' => 'A4 Vellum Paper - 100gsm (100 sheets)',
                'sku' => 'A4-VELLUM-100',
                'description' => 'Translucent vellum paper for overlays and artistic projects. 100gsm. 100 sheets.',
                'status' => 'active',
                'stock_quantity' => 70,
                'price' => ['base' => 13.99, 'discount' => null],
                'category_id' => $specialtyPaperCategory->id,
                'metadata' => [
                    'gsm' => 100,
                    'size' => 'A4',
                    'color' => 'Translucent',
                    'sheets_per_pack' => 100,
                    'dimensions' => '210mm x 297mm',
                    'finish' => 'Vellum',
                ]
            ],

            // Envelope Products (17-20)
            [
                'name' => 'DL White Envelopes - Self Seal (100 pack)',
                'sku' => 'ENV-DL-WHITE-SS-100',
                'description' => 'Standard DL white envelopes with self-seal closure. Perfect for letters and invoices. 100 pack.',
                'status' => 'active',
                'stock_quantity' => 300,
                'price' => ['base' => 7.99, 'discount' => null],
                'category_id' => $envelopesCategory->id,
                'metadata' => [
                    'size' => 'DL (110mm x 220mm)',
                    'color' => 'White',
                    'quantity' => 100,
                    'seal_type' => 'Self-seal',
                    'window' => false,
                ]
            ],
            [
                'name' => 'C5 Window Envelopes - Self Seal (50 pack)',
                'sku' => 'ENV-C5-WINDOW-SS-50',
                'description' => 'C5 white envelopes with window. Self-seal closure. Perfect for business correspondence.',
                'status' => 'active',
                'stock_quantity' => 200,
                'price' => ['base' => 6.99, 'discount' => null],
                'category_id' => $envelopesCategory->id,
                'metadata' => [
                    'size' => 'C5 (162mm x 229mm)',
                    'color' => 'White',
                    'quantity' => 50,
                    'seal_type' => 'Self-seal',
                    'window' => true,
                ]
            ],
            [
                'name' => 'A4 Board Back Envelopes - Peel & Seal (25 pack)',
                'sku' => 'ENV-A4-BOARD-PS-25',
                'description' => 'Heavy-duty A4 board back envelopes. Do not bend protection. Peel & seal closure.',
                'status' => 'active',
                'stock_quantity' => 150,
                'price' => ['base' => 12.99, 'discount' => null],
                'category_id' => $envelopesCategory->id,
                'metadata' => [
                    'size' => 'C4 (229mm x 324mm)',
                    'color' => 'White',
                    'quantity' => 25,
                    'seal_type' => 'Peel & Seal',
                    'board_back' => true,
                ]
            ],
            [
                'name' => 'Colored Gift Envelopes - Assorted (50 pack)',
                'sku' => 'ENV-GIFT-COLOR-50',
                'description' => 'Vibrant colored envelopes for gift cards and special occasions. Assorted colors. 50 pack.',
                'status' => 'active',
                'stock_quantity' => 180,
                'price' => ['base' => 8.99, 'discount' => null],
                'category_id' => $envelopesCategory->id,
                'metadata' => [
                    'size' => 'Small (114mm x 162mm)',
                    'color' => 'Assorted',
                    'quantity' => 50,
                    'seal_type' => 'Gummed',
                    'window' => false,
                ]
            ],
        ];

        // Insert all products
        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('✓ Created 20 paper products across 4 categories');
        $this->command->info('  - Copy Paper: 8 products');
        $this->command->info('  - Cardstock: 4 products');
        $this->command->info('  - Specialty Paper: 4 products');
        $this->command->info('  - Envelopes: 4 products');
    }
}

