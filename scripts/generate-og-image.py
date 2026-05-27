#!/usr/bin/env python3
"""
Generate the Open Graph image for Dogo Corporation.

Output: wp-content/themes/dogo-corporation/assets/images/og-image.png (1200x630)

Design echoes the homepage hero:
  - Warm-dark background (#0f0e0b) with soft orange + gold radial glows
  - Brand logomark top-left
  - Big Japanese headline; the highlight phrase has an orange → cream gradient
  - Brand line + URL bottom-left
  - Lang chip top-right

Bundles Noto Sans JP (in assets/fonts/) so output is identical across machines.
"""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont, ImageFilter

ROOT  = Path(__file__).resolve().parent.parent
THEME = ROOT / "wp-content/themes/dogo-corporation"
IMGS  = THEME / "assets/images"
FONTS = THEME / "assets/fonts"
OUT   = IMGS / "og-image.png"
LOGO  = IMGS / "logo-light.png"

JP_BLACK = str(FONTS / "NotoSansJP-Black.otf")
JP_BOLD  = str(FONTS / "NotoSansJP-Bold.otf")
SANS     = "/System/Library/Fonts/HelveticaNeue.ttc"

W, H = 1200, 630

CREAM   = (235, 234, 229)
CREAM_T = (235, 234, 229, 175)   # cream w/ alpha for sub-text
ORANGE  = (245,  78,   0)
PEACH   = (255, 188, 130)        # gradient end (warmer cream)
GOLD    = (192, 133,  50)
BG      = ( 15,  14,  11)


def text_size(font, text):
    """Return (width, height) of rendered text."""
    bbox = font.getbbox(text)
    return bbox[2] - bbox[0], bbox[3] - bbox[1]


def draw_gradient_text(canvas, text, font, position, c1, c2):
    """Draw `text` at `position` filled with a horizontal gradient c1 → c2."""
    bbox = font.getbbox(text)
    pad = 12
    tw = bbox[2] + pad * 2
    th = bbox[3] + pad * 2

    # 1. Render text into an alpha mask (high-quality)
    mask = Image.new("L", (tw, th), 0)
    md = ImageDraw.Draw(mask)
    md.text((pad, pad), text, font=font, fill=255)

    # 2. Build the gradient strip the same size as the mask
    grad = Image.new("RGB", (tw, th), c1)
    px = grad.load()
    for x in range(tw):
        t = x / max(tw - 1, 1)
        r = int(c1[0] * (1 - t) + c2[0] * t)
        g = int(c1[1] * (1 - t) + c2[1] * t)
        b = int(c1[2] * (1 - t) + c2[2] * t)
        for y in range(th):
            px[x, y] = (r, g, b)

    # 3. Paste gradient using mask as alpha — only the text shape becomes coloured
    canvas.paste(grad, (position[0] - pad, position[1] - pad), mask)


def main():
    img = Image.new("RGB", (W, H), BG)

    # --- Soft radial glow background ---------------------------------------
    glow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    g = ImageDraw.Draw(glow)
    g.ellipse((-260, -300, 720, 660), fill=(*ORANGE, 95))   # upper-left orange
    g.ellipse(( 600, -240, 1500, 580), fill=(*GOLD,   72))   # upper-right gold
    g.ellipse(( 200,  380, 1100, 900), fill=(*ORANGE, 30))   # very subtle bottom orange
    glow = glow.filter(ImageFilter.GaussianBlur(radius=160))
    img = Image.alpha_composite(img.convert("RGBA"), glow).convert("RGB")

    d = ImageDraw.Draw(img, "RGBA")

    # --- Logo top-left -----------------------------------------------------
    if LOGO.exists():
        logo = Image.open(LOGO).convert("RGBA")
        logo.thumbnail((220, 80), Image.LANCZOS)
        img.paste(logo, (72, 64), logo)

    # --- Top-right language chip ------------------------------------------
    chip_font = ImageFont.truetype(SANS, 22)
    chip_text = "JA · EN · VI"
    cw, ch = text_size(chip_font, chip_text)
    cpad_x, cpad_y = 18, 10
    chip_w = cw + cpad_x * 2
    chip_h = ch + cpad_y * 2 + 4
    cx = W - chip_w - 72
    cy = 80
    d.rounded_rectangle((cx, cy, cx + chip_w, cy + chip_h),
                        radius=chip_h // 2,
                        fill=(255, 255, 255, 28),
                        outline=(255, 255, 255, 60), width=1)
    d.text((cx + cpad_x, cy + cpad_y - 4), chip_text, font=chip_font, fill=CREAM_T)

    # --- Headlines ---------------------------------------------------------
    head_font = ImageFont.truetype(JP_BLACK, 116)
    base_x = 72
    line1 = "越境 EC の卓越性を、"
    line2 = "かたちに。"

    # Line 1 — gradient orange → peach
    draw_gradient_text(img, line1, head_font, (base_x, 200), c1=ORANGE, c2=PEACH)

    # Line 2 — solid cream
    d2 = ImageDraw.Draw(img)
    d2.text((base_x, 350), line2, font=head_font, fill=CREAM)

    # --- Brand bottom block ------------------------------------------------
    brand_font = ImageFont.truetype(JP_BOLD, 38)
    sub_font   = ImageFont.truetype(JP_BOLD, 24)

    d2.text((base_x, 525), "Dogo Corporation", font=brand_font, fill=CREAM)
    d2.text((base_x, 580), "本物の日本製品を、世界へ。 dogo-trading.com",
             font=sub_font, fill=(*CREAM, 165) if False else (170, 168, 162))

    img.save(OUT, "PNG", optimize=True)
    print(f"  wrote {OUT.relative_to(ROOT)}  ({OUT.stat().st_size/1024:.1f} KB)")


if __name__ == "__main__":
    main()
