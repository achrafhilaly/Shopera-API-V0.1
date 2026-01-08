#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

BASE_URL="http://127.0.0.1:8000/api"

# Login as admin
echo -e "${BLUE}Logging in as admin...${NC}"
TOKEN=$(curl -s -X POST "${BASE_URL}/login" \
    -H "Content-Type: application/json" \
    -d '{"email":"achraf.hilaly.admin@gmail.com","password":"password"}' \
    | python3 -c 'import sys,json; print(json.load(sys.stdin)["token"])')

echo -e "${GREEN}✓ Logged in${NC}"
echo ""

# Create Categories
echo -e "${BLUE}Creating categories...${NC}"

COPY_PAPER=$(curl -s -X POST "${BASE_URL}/categories" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"name":"Copy Paper","slug":"copy-paper","is_active":true}' \
    | python3 -c 'import sys,json; print(json.load(sys.stdin)["data"]["id"])')

CARDSTOCK=$(curl -s -X POST "${BASE_URL}/categories" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"name":"Cardstock","slug":"cardstock","is_active":true}' \
    | python3 -c 'import sys,json; print(json.load(sys.stdin)["data"]["id"])')

SPECIALTY=$(curl -s -X POST "${BASE_URL}/categories" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"name":"Specialty Paper","slug":"specialty-paper","is_active":true}' \
    | python3 -c 'import sys,json; print(json.load(sys.stdin)["data"]["id"])')

ENVELOPES=$(curl -s -X POST "${BASE_URL}/categories" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"name":"Envelopes","slug":"envelopes","is_active":true}' \
    | python3 -c 'import sys,json; print(json.load(sys.stdin)["data"]["id"])')

echo -e "${GREEN}✓ Created 4 categories${NC}"
echo ""

# Function to create product
create_product() {
    local name="$1"
    local sku="$2"
    local desc="$3"
    local price="$4"
    local discount="$5"
    local stock="$6"
    local category_id="$7"
    
    curl -s -X POST "${BASE_URL}/products" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Content-Type: application/json" \
        -d '{
            "name": "'"$name"'",
            "sku": "'"$sku"'",
            "description": "'"$desc"'",
            "status": "active",
            "stock_quantity": '"$stock"',
            "price": {"base": '"$price"', "discount": '"$discount"'},
            "category_id": "'"$category_id"'"
        }' > /dev/null
    
    echo -e "${GREEN}✓${NC} $name"
}

echo -e "${BLUE}Creating Copy Paper products (8)...${NC}"

create_product \
    "A4 White Copy Paper - 80gsm (1 Ream)" \
    "A4-WHITE-80-1R" \
    "Premium quality A4 white copy paper, 80gsm weight. Perfect for everyday printing and copying. 500 sheets per ream." \
    "4.99" "null" "500" "$COPY_PAPER"

create_product \
    "A4 White Copy Paper - 80gsm (5 Reams)" \
    "A4-WHITE-80-5R" \
    "Bulk pack of premium A4 white copy paper. 5 reams (2500 sheets total). Great value for high-volume printing." \
    "22.99" "19.99" "200" "$COPY_PAPER"

create_product \
    "A3 White Copy Paper - 80gsm (1 Ream)" \
    "A3-WHITE-80-1R" \
    "A3 size white copy paper for larger format printing. 80gsm, 500 sheets per ream." \
    "9.99" "null" "150" "$COPY_PAPER"

create_product \
    "Letter Size White Copy Paper - 20lb (1 Ream)" \
    "LTR-WHITE-20-1R" \
    "Standard US Letter size white copy paper. 20lb weight (75gsm). 500 sheets per ream." \
    "5.49" "null" "400" "$COPY_PAPER"

create_product \
    "A4 Recycled Copy Paper - 80gsm (1 Ream)" \
    "A4-RECYCLED-80-1R" \
    "100% recycled A4 copy paper. Environmentally friendly choice without compromising quality. 500 sheets." \
    "5.99" "null" "300" "$COPY_PAPER"

create_product \
    "A4 Colored Copy Paper - Assorted (1 Ream)" \
    "A4-COLOR-80-1R" \
    "Vibrant colored copy paper in assorted colors. Perfect for creative projects and presentations. 500 sheets." \
    "6.99" "null" "250" "$COPY_PAPER"

create_product \
    "A4 Premium Copy Paper - 100gsm (1 Ream)" \
    "A4-PREMIUM-100-1R" \
    "High-quality premium copy paper with 100gsm weight. Superior opacity and feel. 500 sheets." \
    "7.99" "null" "180" "$COPY_PAPER"

create_product \
    "Legal Size Copy Paper - 20lb (1 Ream)" \
    "LEGAL-WHITE-20-1R" \
    "Legal size white copy paper (8.5 x 14). Perfect for legal documents and contracts. 500 sheets." \
    "6.49" "null" "120" "$COPY_PAPER"

echo ""
echo -e "${BLUE}Creating Cardstock products (4)...${NC}"

create_product \
    "A4 White Cardstock - 200gsm (100 sheets)" \
    "A4-CARDSTOCK-200-WHITE" \
    "Heavy-duty white cardstock, 200gsm. Perfect for business cards, invitations, and crafts. 100 sheets." \
    "12.99" "null" "200" "$CARDSTOCK"

create_product \
    "A4 Colored Cardstock - 250gsm (50 sheets)" \
    "A4-CARDSTOCK-250-COLOR" \
    "Premium colored cardstock in assorted vibrant colors. 250gsm weight. 50 sheets per pack." \
    "14.99" "null" "150" "$CARDSTOCK"

create_product \
    "Letter Size Cardstock - 110lb (100 sheets)" \
    "LTR-CARDSTOCK-110-WHITE" \
    "Heavy letter size cardstock, 110lb weight (300gsm). Professional quality for presentations." \
    "16.99" "null" "100" "$CARDSTOCK"

create_product \
    "A5 Kraft Cardstock - 300gsm (100 sheets)" \
    "A5-CARDSTOCK-300-KRAFT" \
    "Natural kraft cardstock with rustic appeal. 300gsm weight, A5 size. Perfect for DIY projects." \
    "11.99" "null" "130" "$CARDSTOCK"

echo ""
echo -e "${BLUE}Creating Specialty Paper products (4)...${NC}"

create_product \
    "A4 Glossy Photo Paper - 260gsm (20 sheets)" \
    "A4-PHOTO-260-GLOSSY" \
    "Professional glossy photo paper. Vibrant colors and sharp details. 260gsm. 20 sheets." \
    "8.99" "null" "180" "$SPECIALTY"

create_product \
    "A4 Matte Photo Paper - 230gsm (50 sheets)" \
    "A4-PHOTO-230-MATTE" \
    "Premium matte finish photo paper. No glare, perfect for framing. 230gsm. 50 sheets." \
    "15.99" "null" "160" "$SPECIALTY"

create_product \
    "A4 Watercolor Paper - 300gsm (25 sheets)" \
    "A4-WATERCOLOR-300" \
    "Professional cold-pressed watercolor paper. 300gsm weight. Acid-free and archival quality." \
    "18.99" "null" "90" "$SPECIALTY"

create_product \
    "A4 Vellum Paper - 100gsm (100 sheets)" \
    "A4-VELLUM-100" \
    "Translucent vellum paper for overlays and artistic projects. 100gsm. 100 sheets." \
    "13.99" "null" "70" "$SPECIALTY"

echo ""
echo -e "${BLUE}Creating Envelope products (4)...${NC}"

create_product \
    "DL White Envelopes - Self Seal (100 pack)" \
    "ENV-DL-WHITE-SS-100" \
    "Standard DL white envelopes with self-seal closure. Perfect for letters and invoices. 100 pack." \
    "7.99" "null" "300" "$ENVELOPES"

create_product \
    "C5 Window Envelopes - Self Seal (50 pack)" \
    "ENV-C5-WINDOW-SS-50" \
    "C5 white envelopes with window. Self-seal closure. Perfect for business correspondence." \
    "6.99" "null" "200" "$ENVELOPES"

create_product \
    "A4 Board Back Envelopes - Peel & Seal (25 pack)" \
    "ENV-A4-BOARD-PS-25" \
    "Heavy-duty A4 board back envelopes. Do not bend protection. Peel & seal closure." \
    "12.99" "null" "150" "$ENVELOPES"

create_product \
    "Colored Gift Envelopes - Assorted (50 pack)" \
    "ENV-GIFT-COLOR-50" \
    "Vibrant colored envelopes for gift cards and special occasions. Assorted colors. 50 pack." \
    "8.99" "null" "180" "$ENVELOPES"

echo ""
echo -e "${GREEN}═══════════════════════════════════════${NC}"
echo -e "${GREEN}✓ Successfully created 20 paper products!${NC}"
echo -e "${GREEN}  - Copy Paper: 8 products${NC}"
echo -e "${GREEN}  - Cardstock: 4 products${NC}"
echo -e "${GREEN}  - Specialty Paper: 4 products${NC}"
echo -e "${GREEN}  - Envelopes: 4 products${NC}"
echo -e "${GREEN}═══════════════════════════════════════${NC}"

