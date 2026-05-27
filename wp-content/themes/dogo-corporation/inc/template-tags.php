<?php
/**
 * Reusable template helpers.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print SVG icon by name. Inline so we can style with CSS.
 */
function dogo_icon( $name, $class = 'icon' ) {
	$icons = array(
		'arrow-right' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'arrow-up'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 17L17 7M17 7H8M17 7V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'check'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'globe'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18" stroke="currentColor" stroke-width="1.5"/></svg>',
		'sparkle'     => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4L12 2z" fill="currentColor"/></svg>',
		'menu'        => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		'close'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		'sun'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'moon'        => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" fill="currentColor" fill-opacity="0.12"/></svg>',
		'cake'        => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v3M9 4l3 2 3-2M5 11h14v9H5zM5 11a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3M5 15c2 1 4-1 7 0s4-1 7 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'ghost'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 11a7 7 0 0 1 14 0v9l-2-2-2 2-2-2-2 2-2-2-2 2-2-2v-7z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" fill="currentColor" fill-opacity="0.08"/><circle cx="9.5" cy="11" r="1" fill="currentColor"/><circle cx="14.5" cy="11" r="1" fill="currentColor"/></svg>',
		'box'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 7l9-4 9 4v10l-9 4-9-4V7zM3 7l9 4 9-4M12 11v10" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'bowl'        => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11h18a8 8 0 0 1-16 0M2 18h20M9 7c0-1.5 1-3 3-3s3 1.5 3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'book'        => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-2V5zM4 19a2 2 0 0 1 2-2h12" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'heart'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" fill="currentColor" fill-opacity="0.10"/></svg>',
		'hands'       => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M3 20c1-3 3.5-5 6-5s5 2 6 5M14 20c.5-2 2-3.5 4-3.5s3.5 1.5 4 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'confetti'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 19l4-12 8 8-12 4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" fill="currentColor" fill-opacity="0.08"/><path d="M14 4l1 2M19 7l2 1M16 11l3-1M21 14l-2 1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'calendar'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M3 10h18M8 3v4M16 3v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
	);
	$svg = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
	if ( ! $svg ) {
		return;
	}
	echo '<span class="' . esc_attr( $class ) . '" aria-hidden="true">' . $svg . '</span>';
}

/**
 * Image URL helper for theme assets.
 */
function dogo_img( $file ) {
	return DOGO_THEME_URI . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * Default services data. Overridable via 'dogo_services' filter once a CMS panel is added.
 */
function dogo_services() {
	$services = array(
		array(
			'key'   => 'jf',
			'tag'   => 'JF',
			'title' => __( 'Collectibles & Figurines', 'dogo-corporation' ),
			'desc'  => __( 'From trading cards to figurines and plush toys — every collectible is verified authentic, reflecting the soul of Japanese craftsmanship.', 'dogo-corporation' ),
			'image' => 'service-jf.jpg',
			'features' => array(
				__( 'Authentic figurines & art books', 'dogo-corporation' ),
				__( 'Trading cards and video games', 'dogo-corporation' ),
				__( 'Worldwide collector network', 'dogo-corporation' ),
			),
		),
		array(
			'key'   => 'jwl',
			'tag'   => 'JWL',
			'title' => __( 'Beauty & Personal Care', 'dogo-corporation' ),
			'desc'  => __( 'Authorized distributor of leading Japanese beauty brands — skincare, cosmetics, body care and supplements that meet Japan\'s rigorous standards.', 'dogo-corporation' ),
			'image' => 'service-jwl.jpg',
			'features' => array(
				__( 'Skincare, haircare & cosmetics', 'dogo-corporation' ),
				__( 'Body care & supplements', 'dogo-corporation' ),
				__( 'Official brand partnerships', 'dogo-corporation' ),
			),
		),
		array(
			'key'   => 'kitchen',
			'tag'   => 'Kitchen',
			'title' => __( 'Kitchenware', 'dogo-corporation' ),
			'desc'  => __( 'A curated kitchenware collection bringing Japanese culinary heritage to global tables — from precision knives to time-honored cookware.', 'dogo-corporation' ),
			'image' => 'service-kitchen.jpg',
			'features' => array(
				__( 'Hand-forged knives & cutlery', 'dogo-corporation' ),
				__( 'Cookware, sinks & food storage', 'dogo-corporation' ),
				__( 'Heritage culinary tools', 'dogo-corporation' ),
			),
		),
		array(
			'key'   => 'proxy',
			'tag'   => 'Proxy',
			'title' => __( 'Purchase Agency', 'dogo-corporation' ),
			'desc'  => __( 'Your bridge to Japan\'s domestic marketplace. We source hard-to-find products from Amazon JP, Yahoo Auctions, Rakuma and Mercari.', 'dogo-corporation' ),
			'image' => 'service-proxy.jpg',
			'features' => array(
				__( 'Amazon JP, Yahoo Auctions, Mercari, Rakuma', 'dogo-corporation' ),
				__( 'Authentication & secure shipping', 'dogo-corporation' ),
				__( 'Multi-language concierge support', 'dogo-corporation' ),
			),
		),
	);
	return apply_filters( 'dogo_services', $services );
}

/**
 * Company profile (会社概要).
 *
 * Single source of truth for the Company page.
 * Fields marked TODO: fill in real values then redeploy.
 */
function dogo_company_info() {
	return apply_filters( 'dogo_company_info', array(
		'name_ja'        => '株式会社 DOGO',
		'name_en'        => 'Dogo Co., Ltd.',
		'name_legal'     => 'Dogo Corporation',
		'founded'        => '2020年12月02日',
		'capital'        => '3,000 万円',
		'reg_code'       => '4290001091213',
		'representative' => '代表取締役 ヴォタイ ズォン',
		'license'        => '古物商許可証 : 福岡県公安委員会 第 901072110037 号',
		'postal'         => '〒812-0006',
		'address_ja'     => '福岡県福岡市博多区豊 2-2-26',
		'address_en'     => '2-2-26 Yutaka, Hakata-ku, Fukuoka 812-0006, Japan',
		'email'          => 'info@dogo-trading.com',
		'website'        => 'https://dogo-trading.com',
		'hours'          => __( 'Weekdays 9:00 – 18:00 JST (closed weekends and Japanese national holidays)', 'dogo-corporation' ),
		'business_lines' => array(
			'ja' => array(
				'購入代行事業',
				'クロスボーダー EC 事業（越境 EC）',
				'各種貿易関連業務',
				'海外向け商品の企画・開発・製造・販売',
			),
			'en' => array(
				'Purchase agency services',
				'Cross-border eCommerce',
				'Various trade-related operations',
				'Planning, development, manufacturing and sale of products for overseas markets',
			),
			'vi' => array(
				'Dịch vụ mua hộ',
				'Thương mại điện tử xuyên biên giới',
				'Các hoạt động liên quan đến thương mại',
				'Lên ý tưởng, phát triển, sản xuất và phân phối sản phẩm cho thị trường nước ngoài',
			),
		),
		'banks'          => array(
			'福岡銀行（Fukuoka Bank）',
			'PayPay 銀行（PayPay Bank）',
			'GMO あおぞらネット銀行（GMO Aozora Net Bank）',
		),
	) );
}

/**
 * "Life at DOGO" categories — used by the masonry grid and the section list.
 *
 * Each entry can carry a real `image` URL (drop into assets/images/life/).
 * If `image` is empty/missing, the page renders a beautiful gradient placeholder
 * keyed by `palette` (peach / lavender / sage / blue / cream / orange).
 */
function dogo_life_categories() {
	return apply_filters( 'dogo_life_categories', array(
		array(
			'key' => 'party', 'icon' => 'confetti', 'palette' => 'peach', 'image' => dogo_img( 'life/party.jpg' ),
			'title' => array( 'ja' => '社内パーティー', 'en' => 'Company parties', 'vi' => 'Tiệc công ty' ),
			'desc'  => array(
				'ja' => '締めの打ち上げから新年会まで、節目をしっかり祝います。',
				'en' => 'From quarter-end celebrations to the annual New Year party — milestones get the toast they deserve.',
				'vi' => 'Từ tiệc cuối quý đến tiệc tất niên — mọi cột mốc đều được nâng ly.',
			),
		),
		array(
			'key' => 'halloween', 'icon' => 'ghost', 'palette' => 'lavender', 'image' => dogo_img( 'life/halloween.jpg' ),
			'title' => array( 'ja' => 'ハロウィン', 'en' => 'Halloween', 'vi' => 'Halloween' ),
			'desc'  => array(
				'ja' => '本気のコスプレ大会。バイヤー陣のクオリティが毎年ヤバいです。',
				'en' => 'Costume contest run with the same passion as our buying. Yes, the buying team always wins.',
				'vi' => 'Cuộc thi hoá trang nghiêm túc — team buyer luôn thắng vì… đam mê.',
			),
		),
		array(
			'key' => 'birthday', 'icon' => 'cake', 'palette' => 'cream', 'image' => dogo_img( 'life/birthday.jpg' ),
			'title' => array( 'ja' => '誕生日', 'en' => 'Birthdays', 'vi' => 'Sinh nhật nhân viên' ),
			'desc'  => array(
				'ja' => '毎月、誕生日メンバーをみんなでお祝い。ケーキの担当は持ち回りです。',
				'en' => 'Monthly birthday round-up — cake duty rotates, the singing is mandatory.',
				'vi' => 'Mỗi tháng tổng kết sinh nhật — bánh xoay vòng, hát bài chúc mừng là bắt buộc.',
			),
		),
		array(
			'key' => 'teambuilding', 'icon' => 'hands', 'palette' => 'blue', 'image' => dogo_img( 'life/teambuilding.jpg' ),
			'title' => array( 'ja' => 'チームビルディング', 'en' => 'Team building', 'vi' => 'Team building' ),
			'desc'  => array(
				'ja' => '年 2 回のオフサイト。糸島ビーチや温泉、ボードゲームナイトなど。',
				'en' => 'Twice-yearly offsites — Itoshima beach, onsen weekends, and board-game nights.',
				'vi' => 'Offsite 2 lần/năm — biển Itoshima, cuối tuần onsen, board game night.',
			),
		),
		array(
			'key' => 'food', 'icon' => 'bowl', 'palette' => 'sage', 'image' => dogo_img( 'life/food.jpg' ),
			'title' => array( 'ja' => 'みんなでごはん', 'en' => 'Eating together', 'vi' => 'Ăn uống cùng nhau' ),
			'desc'  => array(
				'ja' => '金曜のチームランチ、出張帰りのお土産シェア、博多の名店巡り。',
				'en' => 'Friday team lunches, omiyage sharing after business trips, weekend Hakata food crawls.',
				'vi' => 'Ăn trưa thứ 6, chia quà Nhật sau công tác, lê la quán ngon Hakata cuối tuần.',
			),
		),
		array(
			'key' => 'office', 'icon' => 'box', 'palette' => 'orange', 'image' => dogo_img( 'life/office.jpg' ),
			'title' => array( 'ja' => 'オフィス・倉庫・パッキング', 'en' => 'Office, warehouse, packing', 'vi' => 'Văn phòng, kho, packing' ),
			'desc'  => array(
				'ja' => '博多区の本社オフィスから、丁寧に梱包されて世界へ旅立つ商品たち。',
				'en' => 'From our Hakata HQ to the packing line — every order leaves with care.',
				'vi' => 'Từ trụ sở Hakata đến dây chuyền đóng gói — mỗi đơn hàng đi ra đều được chăm chút.',
			),
		),
		array(
			'key' => 'workshop', 'icon' => 'book', 'palette' => 'sage', 'image' => dogo_img( 'life/workshop.jpg' ),
			'title' => array( 'ja' => 'ワークショップ・研修', 'en' => 'Workshops & training', 'vi' => 'Workshop & đào tạo' ),
			'desc'  => array(
				'ja' => '商品知識、英語・ベトナム語クラス、社内ナレッジ共有会。',
				'en' => 'Product deep-dives, English / Vietnamese classes, internal lunch-and-learns.',
				'vi' => 'Đào tạo sản phẩm, lớp tiếng Anh / Việt, lunch-and-learn nội bộ.',
			),
		),
		array(
			'key' => 'event', 'icon' => 'calendar', 'palette' => 'lavender', 'image' => dogo_img( 'life/event.jpg' ),
			'title' => array( 'ja' => '社内イベント', 'en' => 'Internal events', 'vi' => 'Sự kiện nội bộ' ),
			'desc'  => array(
				'ja' => '創業記念日、入社式、All-hands、感謝祭。チームの節目を大切に。',
				'en' => 'Founding day, onboarding ceremonies, all-hands, gratitude day — moments that bind us.',
				'vi' => 'Ngày thành lập, lễ chào tân thành viên, all-hands, ngày tri ân — những khoảnh khắc gắn kết.',
			),
		),
		array(
			'key' => 'daily', 'icon' => 'heart', 'palette' => 'peach', 'image' => dogo_img( 'life/daily.jpg' ),
			'title' => array( 'ja' => 'チームの日常', 'en' => 'Everyday joy', 'vi' => 'Đời thường vui vẻ' ),
			'desc'  => array(
				'ja' => '休憩時間のおやつ、犬の写真共有、Slack の絵文字対決。',
				'en' => 'Snack time, dog photo threads, Slack emoji battles — the small stuff that makes a team.',
				'vi' => 'Giờ giải lao, thread ảnh chó của team, đấu emoji trên Slack — những điều nhỏ làm nên một team.',
			),
		),
	) );
}

/**
 * Sample team voices for the Life page.
 */
function dogo_life_quotes() {
	return apply_filters( 'dogo_life_quotes', array(
		array(
			'name'  => array( 'ja' => '田中 美咲', 'en' => 'Misaki Tanaka', 'vi' => 'Misaki Tanaka' ),
			'role'  => array( 'ja' => 'シニアバイヤー · ビューティー部門', 'en' => 'Senior Buyer · Beauty', 'vi' => 'Senior Buyer · Mảng Làm đẹp' ),
			'since' => '2021',
			'quote' => array(
				'ja' => '「日本ブランドの担当者と直接話して、世界中のお客様にちゃんと届ける」 ── これが毎日できる職場って、想像していたより少ないんです。',
				'en' => '"Talking to brand owners in Japan, then watching their products land with customers in 60+ countries — places where you get to do that every day are rare."',
				'vi' => '"Trò chuyện trực tiếp với chủ thương hiệu Nhật, rồi nhìn sản phẩm của họ đến tay khách ở hơn 60 nước — không nhiều nơi cho bạn làm điều đó mỗi ngày."',
			),
		),
		array(
			'name'  => array( 'ja' => 'グエン・ミン', 'en' => 'Minh Nguyen', 'vi' => 'Nguyễn Minh' ),
			'role'  => array( 'ja' => 'カスタマーサクセス · ベトナム語担当', 'en' => 'Customer Success · Vietnamese', 'vi' => 'Customer Success · Tiếng Việt' ),
			'since' => '2023',
			'quote' => array(
				'ja' => '「英語と日本語が飛び交う中で、ベトナムのお客様にちゃんと寄り添える。チームが本当に多文化で、毎日勉強です。」',
				'en' => '"In a Slack full of English and Japanese, I get to be the bridge for Vietnamese customers. Working with this many cultures, you learn something new every day."',
				'vi' => '"Trong Slack toàn tiếng Anh và Nhật, mình là cầu nối với khách Việt. Làm việc với nhiều nền văn hoá thế này, ngày nào cũng học được điều mới."',
			),
		),
		array(
			'name'  => array( 'ja' => '佐藤 健', 'en' => 'Ken Sato', 'vi' => 'Ken Sato' ),
			'role'  => array( 'ja' => '物流オペレーション · パッキングリード', 'en' => 'Logistics Ops · Packing Lead', 'vi' => 'Logistics Ops · Trưởng nhóm Packing' ),
			'since' => '2022',
			'quote' => array(
				'ja' => '「フィギュアもコスメも、全部"ちゃんと"届けたい。チーム全員が同じ気持ちで包んでます。」',
				'en' => '"Whether it\'s a figurine or a serum, we wrap each one like it\'s a gift to a friend. The whole team feels the same way."',
				'vi' => '"Dù là figurine hay serum, mình gói như đang gửi quà cho bạn. Cả team đều có cùng suy nghĩ đó."',
			),
		),
		array(
			'name'  => array( 'ja' => 'リー · チェン', 'en' => 'Li Chen', 'vi' => 'Li Chen' ),
			'role'  => array( 'ja' => 'プロダクトエンジニア', 'en' => 'Product Engineer', 'vi' => 'Product Engineer' ),
			'since' => '2024',
			'quote' => array(
				'ja' => '「3 言語のお客様が同じ瞬間に同じ商品を見ている、それを支える UI を作っているのが面白い。」',
				'en' => '"At any moment customers in 3 languages are looking at the same product. Building the UI that makes that work is genuinely fun."',
				'vi' => '"Bất cứ lúc nào cũng có khách 3 ngôn ngữ đang xem cùng một sản phẩm. Build UI cho điều đó hoạt động thật sự rất vui."',
			),
		),
	) );
}

/**
 * Culture stats for Life at Dogo page.
 */
function dogo_life_stats() {
	return apply_filters( 'dogo_life_stats', array(
		array( 'value' => '4',  'label' => array( 'ja' => '国籍', 'en' => 'Nationalities', 'vi' => 'Quốc tịch' ) ),
		array( 'value' => '5',  'label' => array( 'ja' => '日常で飛び交う言語', 'en' => 'Languages on Slack', 'vi' => 'Ngôn ngữ trên Slack' ) ),
		array( 'value' => '12', 'label' => array( 'ja' => '年間の社内イベント', 'en' => 'Internal events / year', 'vi' => 'Sự kiện nội bộ / năm' ) ),
		array( 'value' => '0',  'label' => array( 'ja' => '退屈な金曜日', 'en' => 'Boring Fridays', 'vi' => 'Ngày thứ 6 chán' ) ),
	) );
}

/**
 * Stats shown in hero / stats strip.
 */
function dogo_stats() {
	return apply_filters( 'dogo_stats', array(
		array( 'value' => '2020', 'label' => __( 'Founded', 'dogo-corporation' ) ),
		array( 'value' => '1M+',  'label' => __( 'Customers worldwide', 'dogo-corporation' ) ),
		array( 'value' => '3M+',  'label' => __( 'Products shipped', 'dogo-corporation' ) ),
		array( 'value' => '180+', 'label' => __( 'Countries delivered', 'dogo-corporation' ) ),
	) );
}
