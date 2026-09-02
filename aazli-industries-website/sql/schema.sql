-- AAZLI INDUSTRIES — full database schema
-- In phpMyAdmin: open the "SQL" tab on any page, paste this entire file, then click Go.
-- This creates the database, the inquiries table (contact/quote form submissions),
-- the products table (managed from the admin panel), and seeds 26 example products
-- across all six categories so you can preview the full catalog immediately.

CREATE DATABASE IF NOT EXISTS aazli_industries CHARACTER SET utf8mb4;
USE aazli_industries;

-- ---------- Contact & quote form submissions ----------
CREATE TABLE IF NOT EXISTS inquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  form_type VARCHAR(20) NOT NULL,        -- 'contact' or 'quote'
  full_name VARCHAR(150),
  company_name VARCHAR(150),
  email VARCHAR(150),
  phone VARCHAR(50),
  country VARCHAR(100),
  product_category VARCHAR(100),
  product_name VARCHAR(150),
  quantity VARCHAR(100),
  timeline VARCHAR(100),
  requirements TEXT,
  message TEXT,
  status VARCHAR(20) NOT NULL DEFAULT 'new',   -- 'new' or 'reviewed'
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------- Product catalog (editable from /admin) ----------
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) UNIQUE NOT NULL,
  name VARCHAR(150) NOT NULL,
  category VARCHAR(100) NOT NULL,
  category_slug VARCHAR(50) NOT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  swatch_from VARCHAR(20) NOT NULL DEFAULT '#F0721F',
  swatch_to VARCHAR(20) NOT NULL DEFAULT '#DE1E74',
  short_desc VARCHAR(300),
  description TEXT,
  material VARCHAR(200),
  sizes VARCHAR(100),
  moq VARCHAR(100),
  lead_time VARCHAR(100),
  features TEXT,          -- one feature per line
  customization TEXT,     -- comma-separated list
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------- Example seed data so the catalog isn't empty on first run ----------
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('classic-crew-tshirt', 'Classic Crew Neck T-Shirt', 'T-Shirts', 'tshirts', 1, '#F0721F', '#DE1E74', 'A wardrobe staple built for private-label programs — clean fit, durable stitching.', 'A versatile everyday tee designed as a base canvas for your brand. Built on a mid-weight cotton platform that holds print and embroidery well, with a true-to-size crew fit suited to both basics lines and graphic drops.', '100% combed cotton, 180 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Reinforced shoulder and neck seams
Pre-shrunk fabric option available
Consistent fit across size run
Print- and embroidery-ready surface', 'Fabric weight, Neck style, Screen print, Embroidery, Woven label, Hang tag & packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('oversized-tshirt', 'Oversized Drop-Shoulder Tee', 'T-Shirts', 'tshirts', 1, '#DE1E74', '#F5A82A', 'Streetwear-driven silhouette with a dropped shoulder and boxy body.', 'An oversized, drop-shoulder tee for streetwear and fashion-led private label brands. The boxy block and extended shoulder line are pattern-adjustable to match your house fit.', '100% cotton, 220 GSM heavyweight jersey', 'S – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Adjustable drop-shoulder pattern
Boxy, relaxed body block
Ribbed or raw-edge neckline options
Suited for oversized graphic placement', 'Silhouette adjustment, Fabric selection, All-over print, Custom labeling, Retail-ready packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('longsleeve-tshirt', 'Long Sleeve Tee', 'T-Shirts', 'tshirts', 0, '#5B2A8C', '#DE1E74', 'A transitional-season layering piece for basics and streetwear ranges alike.', 'A long-sleeve tee built to extend a T-shirt program into cooler seasons. Works as a standalone piece or a base layer within a streetwear or activewear collection.', '95% cotton / 5% elastane, 200 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Ribbed cuffs for shape retention
Layer-friendly fit
Consistent sizing across the tee family', 'Cuff style, Fabric weight, Print & embroidery, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('polo-tshirt', 'Pique Polo Shirt', 'T-Shirts', 'tshirts', 0, '#F5A82A', '#5B2A8C', 'Smart-casual polo for corporate, workwear and uniform private-label orders.', 'A pique-knit polo suited to corporate uniform programs, promotional merchandise, and smart-casual private-label collections.', '100% cotton pique, 220 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Structured collar
Reinforced placket and buttons
Suited for embroidered logo placement', 'Collar & cuff style, Button color, Embroidery, Corporate branding packages');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('pullover-hoodie', 'Pullover Hoodie', 'Hoodies & Sweatshirts', 'hoodies', 1, '#5B2A8C', '#19141F', 'Heavyweight fleece hoodie, the anchor piece of most private-label ranges.', 'A heavyweight pullover hoodie built as the anchor product for streetwear, activewear and lifestyle private-label ranges. Fleece weight, hood construction and fit are all adjustable to your spec.', '80% cotton / 20% polyester fleece, 320 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Adjustable fleece weight
Double-layered hood
Kangaroo pocket
Ribbed cuffs and hem', 'Hood lining, Drawcord color, Embroidery & puff print, Woven neck label, Branded packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('zip-hoodie', 'Full-Zip Hoodie', 'Hoodies & Sweatshirts', 'hoodies', 1, '#19141F', '#DE1E74', 'Layer-friendly zip-through hoodie for activewear and streetwear lines.', 'A zip-through hoodie designed for layering across activewear and streetwear ranges. Zipper hardware, pocket configuration and hood shape are all specifiable.', '80% cotton / 20% polyester fleece, 300 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Full-length zip with custom pull tab
Side seam or kangaroo pocket options
Adjustable drawcord hood', 'Zipper color & branding, Pocket style, Fabric weight, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('crewneck-sweatshirt', 'Crewneck Sweatshirt', 'Hoodies & Sweatshirts', 'hoodies', 0, '#F0721F', '#19141F', 'A clean, hood-free alternative for minimal streetwear and basics drops.', 'A crewneck sweatshirt for brands that want the same fleece feel as a hoodie without the hood — popular in minimal streetwear and collegiate-inspired collections.', '80% cotton / 20% polyester fleece, 280 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Ribbed crew collar, cuffs and hem
Adjustable fleece weight
Print- and embroidery-ready chest and back', 'Fabric weight, Rib color-blocking, Print & embroidery, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('performance-tee', 'Performance Training Tee', 'Activewear & Sportswear', 'activewear', 1, '#F5A82A', '#F0721F', 'Moisture-wicking training tee for activewear and team-sport private label.', 'A performance tee engineered for movement — built on moisture-wicking, stretch-capable fabric for training, athleisure and team-sport private-label programs.', '100% polyester interlock, moisture-wicking finish', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Moisture-wicking fabric option
4-way stretch capability
Flatlock seams to reduce chafing
Breathable mesh panel option', 'Fabric technology, Panel construction, Sublimation print, Team/brand labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('training-jacket', 'Training Track Jacket', 'Activewear & Sportswear', 'activewear', 0, '#F0721F', '#F5A82A', 'Lightweight track jacket for athleisure and sports-brand private label.', 'A lightweight training jacket suited to athleisure and sports-brand ranges, with a fit and finish tailored for movement.', '92% polyester / 8% spandex stretch-woven', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Stand collar with zip closure
Zippered side pockets
Stretch-woven or knit fabric options', 'Colorblocking, Zipper hardware, Sublimation & embroidery, Team kits');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('sports-bra', 'Compression Sports Top', 'Activewear & Sportswear', 'activewear', 0, '#DE1E74', '#F5A82A', 'Support-focused compression top for women''s activewear ranges.', 'A compression sports top designed as an entry point into a women''s activewear private-label range, built on supportive, stretch-capable fabric.', '88% nylon / 12% spandex compression knit', 'XS – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Medium-to-high support construction
4-way stretch fabric
Flatlock seaming', 'Support level, Strap style, Print & panel color, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('boxy-hoodie-streetwear', 'Boxy Streetwear Hoodie', 'Streetwear Essentials', 'streetwear', 1, '#19141F', '#5B2A8C', 'Heavyweight, boxy-fit hoodie built for limited-drop streetwear labels.', 'A heavyweight, boxy-fit hoodie built for limited-drop streetwear labels that need a distinctive silhouette and premium hand-feel at low minimums.', '100% cotton brushed fleece, 400 GSM', 'S – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Boxy, dropped-shoulder block
Heavyweight brushed fleece option
Oversized kangaroo pocket', 'Silhouette & block, Fleece weight, Puff print, embroidery, patches, Premium packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('utility-cargo', 'Utility Cargo Pant', 'Streetwear Essentials', 'streetwear', 0, '#5B2A8C', '#F0721F', 'Multi-pocket cargo pant for streetwear and utility-driven collections.', 'A multi-pocket cargo pant designed for streetwear and utility-inspired private-label collections, with adjustable pocket count and placement.', '98% cotton / 2% spandex twill', '28 – 40', '100 pieces per style/color', '15–20 working days after sample approval', 'Adjustable pocket configuration
Reinforced stitching at stress points
Adjustable waist and hem options', 'Pocket layout, Fabric & wash, Hardware branding, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('joggers', 'Fleece Joggers', 'Joggers & Bottoms', 'bottoms', 1, '#F0721F', '#5B2A8C', 'Tapered fleece jogger — the standard bottoms pairing for hoodie sets.', 'A tapered fleece jogger designed to pair with the hoodie and sweatshirt range as a matching-set offer, with an elastic-and-drawcord waistband.', '80% cotton / 20% polyester fleece, 300 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Elastic waistband with drawcord
Tapered leg with ribbed cuff
Side seam pockets', 'Fabric weight, Cuff style, Set matching with hoodies, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('lounge-pants', 'Loungewear Pants', 'Joggers & Bottoms', 'bottoms', 0, '#F5A82A', '#5B2A8C', 'Soft-hand loungewear pant for comfort-led private-label ranges.', 'A soft-hand loungewear pant for brands building a comfort-focused, at-home category alongside their core apparel range.', '95% cotton / 5% elastane brushed jersey', 'XS – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Relaxed, straight-leg fit
Soft-brushed interior option
Elastic waistband', 'Fabric hand-feel, Fit adjustment, Print & embroidery, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('kids-tshirt-set', 'Kids'' T-Shirt & Jogger Set', 'Kidswear', 'kids', 0, '#F5A82A', '#DE1E74', 'Matching kids'' tee and jogger set for family and kidswear private label.', 'A matching tee-and-jogger set sized for kidswear private-label programs, built with the same customization and branding options as the adult range.', '100% combed cotton, 180 GSM', '2T – 14', '100 pieces per style/color', '15–20 working days after sample approval', 'Soft, skin-friendly fabric option
Reinforced seams for durability
Matching set sizing', 'Fabric selection, Print & embroidery, Packaging as a set');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('henley-tshirt', 'Henley Neck Tee', 'T-Shirts', 'tshirts', 0, '#F5A82A', '#DE1E74', 'A button-placket alternative to the crew tee for elevated basics lines.', 'A henley-neck tee that gives a basics range a more elevated, textured alternative to a standard crew neck — popular with premium streetwear and lifestyle labels.', '100% cotton slub jersey, 200 GSM', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', '3-button placket
Garment-dyed fabric option
Reinforced collar band', 'Button color, Placket length, Fabric wash, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('oversized-hoodie', 'Oversized Fleece Hoodie', 'Hoodies & Sweatshirts', 'hoodies', 0, '#F0721F', '#19141F', 'Extended-length, dropped-shoulder hoodie for fashion-led ranges.', 'An oversized hoodie with an extended body length and dropped shoulder, built for brands wanting a distinct fashion silhouette rather than a standard athletic fit.', '70% cotton / 30% polyester fleece, 340 GSM', 'S – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Extended body length
Dropped, extended shoulder seam
Oversized hood with adjustable drawcord', 'Body length, Fleece weight, Embroidery & puff print, Premium packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('performance-leggings', 'Performance Leggings', 'Activewear & Sportswear', 'activewear', 1, '#DE1E74', '#5B2A8C', 'High-waist, four-way stretch leggings for training and athleisure ranges.', 'High-waist performance leggings built on a four-way stretch, squat-proof fabric — a core piece for any women''s activewear or athleisure private-label range.', '78% nylon / 22% spandex, brushed-back knit', 'XS – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'High-rise, squat-proof waistband
Four-way stretch fabric
Flatlock seaming
Optional side pocket', 'Waistband height, Panel colorblocking, Sublimation print, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('windbreaker-jacket', 'Lightweight Windbreaker', 'Activewear & Sportswear', 'activewear', 0, '#F5A82A', '#5B2A8C', 'Packable, water-resistant jacket for running and training lines.', 'A lightweight, packable windbreaker with a water-resistant finish, suited to running and training-focused activewear ranges as a light outer layer.', '100% polyester ripstop, DWR coating', 'XS – 2XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Water-resistant DWR coating
Packable into own pocket
Reflective trim option', 'Colorblocking, Reflective branding, Zipper hardware, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('utility-vest', 'Sleeveless Utility Vest', 'Streetwear Essentials', 'streetwear', 1, '#19141F', '#F0721F', 'Multi-pocket utility vest, a statement layering piece for streetwear drops.', 'A sleeveless utility vest with a multi-pocket layout, designed as a statement layering piece for streetwear brands building out a fuller collection beyond tees and hoodies.', '100% cotton canvas, 280 GSM', 'S – 2XL', '100 pieces per style/color', '18–25 working days after sample approval', 'Multi-pocket utility layout
Adjustable side straps
Reinforced bar-tack stitching', 'Pocket configuration, Hardware finish, Fabric & wash, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('puffer-jacket', 'Quilted Puffer Jacket', 'Streetwear Essentials', 'streetwear', 0, '#5B2A8C', '#DE1E74', 'Insulated puffer for cold-season streetwear and outerwear drops.', 'A quilted, insulated puffer jacket for brands extending into cold-season outerwear, with a boxy or fitted silhouette available depending on your spec.', 'Polyester shell with synthetic fill insulation', 'S – 2XL', '80 pieces per style/color', '20–28 working days after sample approval', 'Quilted panel construction
Insulated fill for warmth
Storm cuffs and adjustable hem', 'Silhouette & fit, Fill weight, Lining color, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('track-pants', 'Track Pants with Side Stripe', 'Joggers & Bottoms', 'bottoms', 0, '#DE1E74', '#19141F', 'Classic side-stripe track pant for sport-inspired streetwear ranges.', 'A classic track pant with a side-stripe detail, popular across sport-inspired streetwear and retro athleisure collections.', '100% polyester tricot', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Contrast side-stripe detail
Elastic waistband with drawcord
Ankle zip option', 'Stripe color & width, Fabric finish, Ankle style, Custom labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('training-shorts', 'Training Shorts', 'Joggers & Bottoms', 'bottoms', 0, '#F5A82A', '#F0721F', 'Lightweight training shorts for activewear and team-sport programs.', 'Lightweight training shorts built for movement, suited to activewear ranges, team-sport kits, and summer streetwear drops alike.', '100% polyester interlock, moisture-wicking finish', 'XS – 3XL', '100 pieces per style/color', '15–20 working days after sample approval', 'Moisture-wicking fabric
Elastic waistband with internal drawcord
Side seam pockets', 'Inseam length, Fabric technology, Sublimation print, Team/brand labeling');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('kids-hoodie', 'Kids'' Pullover Hoodie', 'Kidswear', 'kids', 1, '#F0721F', '#F5A82A', 'Scaled-down version of the adult hoodie, sized for kidswear ranges.', 'A pullover hoodie scaled and softened for kidswear private-label programs, using the same customization options as the adult range so sibling or family sets are easy to produce.', '80% cotton / 20% polyester fleece, 280 GSM', '2T – 14', '100 pieces per style/color', '15–20 working days after sample approval', 'Soft, skin-friendly fleece
Reinforced seams for durability
Kangaroo pocket', 'Fabric weight, Print & embroidery, Matching adult/kids sets');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('kids-shorts-set', 'Kids'' Tee & Shorts Set', 'Kidswear', 'kids', 0, '#DE1E74', '#F0721F', 'Matching summer set for kidswear private-label ranges.', 'A matching tee-and-shorts set for warmer-season kidswear ranges, packaged and labeled as a set for retail or e-commerce sale.', '100% combed cotton, 180 GSM', '2T – 14', '100 pieces per set', '15–20 working days after sample approval', 'Soft, breathable cotton
Elastic waistband on shorts
Matching set sizing', 'Fabric selection, Print & embroidery, Set packaging');
INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to, short_desc, description, material, sizes, moq, lead_time, features, customization) VALUES ('kids-leggings', 'Kids'' Leggings', 'Kidswear', 'kids', 0, '#5B2A8C', '#F5A82A', 'Stretch leggings for kidswear and family activewear ranges.', 'Stretch leggings sized for a kidswear range, sharing the same stretch-fabric platform as the adult performance leggings for brands offering family activewear sets.', '90% cotton / 10% spandex jersey', '2T – 14', '100 pieces per style/color', '15–20 working days after sample approval', 'Soft stretch waistband
Flat seams for comfort
Durable, wash-resistant fabric', 'Fabric selection, Print patterns, Matching family sets');
