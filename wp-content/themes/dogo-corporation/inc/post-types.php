<?php
/**
 * Custom post types: Careers (dogo_job).
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the dogo_job CPT.
 */
add_action( 'init', 'dogo_register_jobs_cpt', 5 );
function dogo_register_jobs_cpt() {
	register_post_type( 'dogo_job', array(
		'labels' => array(
			'name'          => __( 'Careers', 'dogo-corporation' ),
			'singular_name' => __( 'Job opening', 'dogo-corporation' ),
			'menu_name'     => __( 'Careers', 'dogo-corporation' ),
			'add_new_item'  => __( 'Add new job', 'dogo-corporation' ),
		),
		'public'             => true,
		'has_archive'        => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-businessman',
		'rewrite'            => array( 'slug' => 'careers', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'publicly_queryable' => true,
	) );
}

/**
 * Seed sample jobs once. Idempotent — keyed by slug + theme version.
 */
add_action( 'init', 'dogo_seed_jobs', 20 );
function dogo_seed_jobs() {
	$marker = 'dogo_jobs_seeded_' . DOGO_THEME_VERSION;
	if ( get_option( $marker ) ) {
		return;
	}
	foreach ( dogo_job_seeds() as $job ) {
		if ( get_page_by_path( $job['slug'], OBJECT, 'dogo_job' ) ) {
			continue;
		}
		wp_insert_post( array(
			'post_type'    => 'dogo_job',
			'post_status'  => 'publish',
			'post_title'   => $job['title']['ja'],
			'post_name'    => $job['slug'],
			'post_excerpt' => $job['intro']['ja'],
			'post_date'    => $job['posted'] . ' 09:00:00',
		) );
	}
	update_option( $marker, time(), false );
	flush_rewrite_rules( false );
}

/**
 * Helper: look up a seed entry by slug. Defaults to the current single post.
 */
function dogo_job( $slug = null ) {
	if ( ! $slug ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
	}
	if ( ! $slug ) {
		return null;
	}
	foreach ( dogo_job_seeds() as $j ) {
		if ( $j['slug'] === $slug ) {
			return $j;
		}
	}
	return null;
}

/**
 * Pick the right localised value from a seed field.
 * Falls back to JA → EN → first available.
 */
function dogo_jl( $field ) {
	$lang = function_exists( 'dogo_current_lang' ) ? dogo_current_lang() : 'ja';
	if ( is_array( $field ) ) {
		return $field[ $lang ] ?? $field['ja'] ?? $field['en'] ?? reset( $field );
	}
	return (string) $field;
}

/**
 * Job openings — 3-language native content.
 * Edit this array to add / remove / re-write postings.
 *
 * Salary is a multilang array — single string would force one locale on all viewers.
 */
function dogo_job_seeds() {
	return apply_filters( 'dogo_job_seeds', array(

		array(
			'slug'   => 'packing-staff',
			'posted' => '2026-05-03',
			'phone'  => '070-8486-3086',
			'salary' => array(
				'ja' => '時給 ¥1,057〜（慣れたら 3 か月以内に昇給）',
				'en' => '¥1,057 / hour to start · raise within 3 months once experienced',
				'vi' => '¥1.057/giờ · tăng lương trong 3 tháng khi đã quen việc',
			),
			'department' => array( 'ja' => '物流オペレーション', 'en' => 'Logistics Operations', 'vi' => 'Vận hành Logistics' ),
			'location'   => array( 'ja' => '福岡本社 ／ 倉庫', 'en' => 'Fukuoka HQ · warehouse', 'vi' => 'Trụ sở Fukuoka · kho' ),
			'type'       => array( 'ja' => 'パート ／ アルバイト', 'en' => 'Part-time', 'vi' => 'Bán thời gian' ),
			'level'      => array( 'ja' => '未経験 OK', 'en' => 'Entry friendly — no experience needed', 'vi' => 'Không yêu cầu kinh nghiệm' ),
			'title' => array(
				'ja' => '梱包スタッフ（Packing Staff）',
				'en' => 'Packing Staff — Fukuoka Warehouse',
				'vi' => 'Nhân viên Đóng gói — Kho Fukuoka',
			),
			'intro' => array(
				'ja' => '福岡本社の倉庫で、世界中のお客様へ本物の日本製品を丁寧にお届けする梱包スタッフを募集しています。未経験 OK、9:00–18:00 のうち週 3 日・1 日 4 時間からシフト調整可能です。',
				'en' => 'Join our Fukuoka warehouse as packing staff — help us ship authentic Japanese products to customers worldwide. No experience needed, flexible shifts (3 days / week, 4 hrs / day minimum within 9:00–18:00).',
				'vi' => 'Tham gia kho Fukuoka với vai trò nhân viên đóng gói — giúp chúng tôi gửi hàng Nhật chính hãng đến khách hàng khắp thế giới. Không cần kinh nghiệm, ca làm linh hoạt (từ 3 ngày/tuần, 4 giờ/ngày, trong khung 9:00–18:00).',
			),
			'responsibilities' => array(
				'ja' => array(
					'注文に応じた商品のピッキングと検品',
					'丁寧な梱包と配送ラベルの貼付',
					'倉庫内在庫の整理整頓',
					'出荷状況の記録とチームとの連携',
				),
				'en' => array(
					'Pick and verify items against customer orders',
					'Careful packing and applying shipping labels',
					'Keep warehouse stock organized',
					'Log outbound shipments and coordinate with the team',
				),
				'vi' => array(
					'Lấy hàng và kiểm tra theo đơn của khách',
					'Đóng gói cẩn thận và dán nhãn vận chuyển',
					'Sắp xếp tồn kho gọn gàng',
					'Ghi nhận tình trạng xuất hàng và phối hợp với team',
				),
			),
			'requirements' => array(
				'ja' => array(
					'体を動かす作業が苦にならない方',
					'細かい作業を丁寧にこなせる方',
					'登録したシフトに沿って出勤できる方',
				),
				'en' => array(
					'Comfortable with light physical work',
					'Attentive to detail and quality',
					'Reliable attendance for the shifts you commit to',
				),
				'vi' => array(
					'Sức khỏe tốt, làm được công việc tay chân nhẹ',
					'Cẩn thận, tỉ mỉ trong từng chi tiết',
					'Đi làm đúng theo ca đã đăng ký',
				),
			),
			'nice' => array(
				'ja' => array(
					'EC ・物流業務の経験',
					'梱包作業の経験',
					'軽い荷物の取り扱いに慣れている方',
				),
				'en' => array(
					'Experience in eCommerce or logistics',
					'Packing work experience',
					'Comfortable lifting light boxes',
				),
				'vi' => array(
					'Kinh nghiệm eCommerce hoặc logistics',
					'Kinh nghiệm làm việc đóng gói',
					'Quen với việc nâng đồ nhẹ',
				),
			),
			'benefits' => array(
				'ja' => array(
					'時給 ¥1,057〜、慣れたら 3 か月以内に昇給',
					'シフト柔軟：9:00–18:00 のうち週 3 日・1 日 4 時間〜 OK',
				),
				'en' => array(
					'¥1,057 / hour to start, raise within 3 months once experienced',
					'Flexible shifts: 9:00–18:00, 3 days/week, 4 hrs/day minimum',
				),
				'vi' => array(
					'¥1.057/giờ khởi điểm, tăng lương trong 3 tháng khi đã quen việc',
					'Ca linh hoạt: 9:00–18:00, từ 3 ngày/tuần, 4 giờ/ngày trở lên',
				),
			),
		),
		/* === Legacy seeds (5 roles) removed 2026-05-03 — replaced by the single packing role above.
		 * Restore from git history (commit 304411c or earlier) if you want them back.
		 */
		/* removed legacy block start
		array(
			'slug'   => 'buyer-figurines-archived',
			'posted' => '2026-04-22',
			'salary' => '¥4,000,000 – ¥6,000,000 / yr',
			'department' => array( 'ja' => '仕入・MD', 'en' => 'Merchandising', 'vi' => 'Quản lý hàng hóa' ),
			'location'   => array( 'ja' => '福岡本社', 'en' => 'Fukuoka HQ', 'vi' => 'Trụ sở Fukuoka' ),
			'type'       => array( 'ja' => '正社員', 'en' => 'Full-time', 'vi' => 'Toàn thời gian' ),
			'level'      => array( 'ja' => 'ミドル(2〜5年)', 'en' => 'Mid-level (2–5 yrs)', 'vi' => 'Trung cấp (2–5 năm)' ),
			'title' => array(
				'ja' => 'バイヤー / フィギュア・コレクティブル部門',
				'en' => 'EC Buyer — Figurines & Collectibles',
				'vi' => 'Chuyên viên Mua hàng — Figurine & Sản phẩm sưu tầm',
			),
			'intro' => array(
				'ja' => '日本のメーカー・正規ディーラーから本物のフィギュアやコレクティブルを調達し、世界のコレクターへお届けする中核ポジションです。',
				'en' => 'Source authentic figurines and collectibles from Japanese makers and licensed dealers, and deliver them to collectors worldwide.',
				'vi' => 'Tìm nguồn figurine và sản phẩm sưu tầm chính hãng từ các nhà sản xuất và đại lý uỷ quyền tại Nhật Bản, đưa đến tay khách sưu tầm toàn cầu.',
			),
			'responsibilities' => array(
				'ja' => array(
					'メーカー・正規ディーラーとの新規開拓および交渉',
					'限定品・コラボ商品の発売情報のリサーチと先行予約',
					'仕入価格・在庫水準・販売予測の管理',
					'真贋鑑定基準の整備および品質管理チームとの連携',
					'マーケットプレイス出品データの整備とパフォーマンス分析',
				),
				'en' => array(
					'Identify and negotiate with new makers and licensed dealers',
					'Research limited and collab releases, secure pre-orders',
					'Own COGS, inventory levels and sell-through forecasts',
					'Maintain authentication standards with the QA team',
					'Curate marketplace listings and analyse performance',
				),
				'vi' => array(
					'Tìm kiếm và đàm phán với nhà sản xuất và đại lý uỷ quyền mới',
					'Nghiên cứu các bản giới hạn / collab, đặt trước hàng',
					'Quản lý giá vốn, tồn kho và dự báo bán ra',
					'Phối hợp đội QA, duy trì chuẩn xác thực hàng',
					'Hoàn thiện listing trên các sàn, phân tích hiệu quả',
				),
			),
			'requirements' => array(
				'ja' => array(
					'日本語ネイティブまたはビジネス上級レベル',
					'バイヤー、MD、または小売仕入の経験 2 年以上',
					'フィギュア、トレーディングカード、ゲーム等の業界知識',
					'数値管理(エクセル / Google スプレッドシート)が得意な方',
					'メーカーとの長期的な信頼関係を築けるコミュニケーション力',
				),
				'en' => array(
					'Native or business-level Japanese',
					'2+ years buyer / MD / retail sourcing experience',
					'Domain knowledge of figurines, TCG, video games',
					'Comfortable with spreadsheets and KPI ownership',
					'Strong communicator who can build long-term vendor trust',
				),
				'vi' => array(
					'Tiếng Nhật bản ngữ hoặc trình độ business',
					'Tối thiểu 2 năm kinh nghiệm buyer / MD / mua hàng bán lẻ',
					'Hiểu biết về figurine, thẻ bài (TCG), game',
					'Thành thạo bảng tính, làm việc theo KPI',
					'Kỹ năng giao tiếp tốt, xây dựng quan hệ dài hạn với nhà cung cấp',
				),
			),
			'nice' => array(
				'ja' => array(
					'英語または中国語でのコミュニケーション',
					'eBay、Amazon、メルカリ等での販売経験',
					'コレクター文化への深い理解',
				),
				'en' => array(
					'English or Mandarin communication skills',
					'Experience selling on eBay / Amazon / Mercari',
					'Personal passion for collector culture',
				),
				'vi' => array(
					'Kỹ năng tiếng Anh hoặc tiếng Trung',
					'Kinh nghiệm bán trên eBay / Amazon / Mercari',
					'Đam mê văn hoá sưu tầm',
				),
			),
			'benefits' => array(
				'ja' => array(
					'昇給・賞与年 2 回',
					'交通費全額支給、リモート併用可',
					'社員割引(全カテゴリ)',
					'年次有給休暇 + 夏季・冬季休暇',
					'書籍・カンファレンス補助',
				),
				'en' => array(
					'Annual review + bi-annual bonus',
					'Full commuting allowance, hybrid friendly',
					'Employee discount across all categories',
					'PTO + summer & winter holidays',
					'Books & conference budget',
				),
				'vi' => array(
					'Đánh giá tăng lương hàng năm + thưởng 2 lần/năm',
					'Trợ cấp đi lại đầy đủ, có thể làm việc hybrid',
					'Giảm giá nhân viên trên toàn bộ danh mục',
					'Nghỉ phép có lương + nghỉ hè và nghỉ đông',
					'Ngân sách sách và hội thảo',
				),
			),
		),

		array(
			'slug'   => 'customer-success-bilingual',
			'posted' => '2026-04-20',
			'salary' => '¥3,800,000 – ¥5,200,000 / yr',
			'department' => array( 'ja' => 'カスタマーサクセス', 'en' => 'Customer Success', 'vi' => 'Customer Success' ),
			'location'   => array( 'ja' => 'ハイブリッド(福岡 / 在宅)', 'en' => 'Hybrid (Fukuoka / remote)', 'vi' => 'Hybrid (Fukuoka / từ xa)' ),
			'type'       => array( 'ja' => '正社員', 'en' => 'Full-time', 'vi' => 'Toàn thời gian' ),
			'level'      => array( 'ja' => 'ジュニア〜ミドル', 'en' => 'Junior – Mid-level', 'vi' => 'Mới ra trường – Trung cấp' ),
			'title' => array(
				'ja' => 'カスタマーサクセス担当(英語 / 日本語バイリンガル)',
				'en' => 'Customer Success Specialist (EN / JA bilingual)',
				'vi' => 'Chuyên viên Customer Success (song ngữ Anh – Nhật)',
			),
			'intro' => array(
				'ja' => '世界中のお客様一人ひとりに、母国語で安心していただける体験をお届けするチームの中心メンバーを募集します。',
				'en' => 'Be the human voice of Dogo for international customers — answering, advising, and resolving in their own language.',
				'vi' => 'Trở thành "tiếng nói" của Dogo cho khách hàng quốc tế — tư vấn, giải đáp, xử lý bằng chính ngôn ngữ của khách.',
			),
			'responsibilities' => array(
				'ja' => array(
					'メール・チャットでの一次対応(英 / 日)',
					'注文・配送・通関に関する問い合わせ対応',
					'返品・返金プロセスの調整',
					'よくある質問・ナレッジベースの運用と更新',
					'プロダクト / オペレーションチームへのフィードバック共有',
				),
				'en' => array(
					'Front-line email and chat support in EN / JA',
					'Order, shipping and customs query resolution',
					'Manage returns and refund workflows',
					'Maintain FAQ and internal knowledge base',
					'Surface customer signal back to product / ops',
				),
				'vi' => array(
					'Hỗ trợ tuyến đầu qua email và chat (Anh / Nhật)',
					'Xử lý các thắc mắc về đơn hàng, vận chuyển, thông quan',
					'Điều phối quy trình đổi trả và hoàn tiền',
					'Duy trì FAQ và knowledge base nội bộ',
					'Phản hồi insight khách hàng về team product / ops',
				),
			),
			'requirements' => array(
				'ja' => array(
					'英語(ビジネスレベル)+ 日本語(ビジネス〜ネイティブ)',
					'カスタマーサポートまたは EC 業務経験 1 年以上',
					'丁寧なライティングと共感力',
					'マルチタスクと優先順位付けのスキル',
				),
				'en' => array(
					'Business-level English + business / native Japanese',
					'1+ year customer support or eCommerce experience',
					'Strong written tone and empathy',
					'Comfortable juggling priorities',
				),
				'vi' => array(
					'Tiếng Anh business + tiếng Nhật business / bản ngữ',
					'Tối thiểu 1 năm kinh nghiệm CS hoặc eCommerce',
					'Khả năng viết tốt, sự đồng cảm cao',
					'Quản lý đa nhiệm và sắp xếp ưu tiên tốt',
				),
			),
			'nice' => array(
				'ja' => array(
					'Zendesk / Intercom 等の利用経験',
					'第 3 言語(中国語 / ベトナム語等)',
					'国際物流・通関の知識',
				),
				'en' => array(
					'Experience with Zendesk / Intercom',
					'Third language (Chinese / Vietnamese / etc.)',
					'Knowledge of international logistics and customs',
				),
				'vi' => array(
					'Kinh nghiệm sử dụng Zendesk / Intercom',
					'Thêm ngôn ngữ thứ 3 (Trung / Việt / khác)',
					'Hiểu biết logistics quốc tế và thông quan',
				),
			),
			'benefits' => array(
				'ja' => array(
					'フレックス勤務・週 2-3 日リモート可',
					'月 1 のチームランチ補助',
					'語学学習補助(日本語・英語)',
					'昇給・賞与年 2 回',
					'年次有給休暇 + 夏季・冬季休暇',
				),
				'en' => array(
					'Flex hours · 2–3 remote days a week',
					'Monthly team lunch budget',
					'Language-learning stipend (JA / EN)',
					'Annual review + bi-annual bonus',
					'PTO + summer & winter holidays',
				),
				'vi' => array(
					'Giờ linh hoạt · 2–3 ngày làm từ xa mỗi tuần',
					'Phụ cấp ăn trưa team hàng tháng',
					'Hỗ trợ học ngôn ngữ (Nhật / Anh)',
					'Đánh giá tăng lương hàng năm + thưởng 2 lần/năm',
					'Phép có lương + nghỉ hè và nghỉ đông',
				),
			),
		),

		array(
			'slug'   => 'frontend-engineer',
			'posted' => '2026-04-15',
			'salary' => '¥6,000,000 – ¥9,500,000 / yr',
			'department' => array( 'ja' => 'プロダクト', 'en' => 'Product Engineering', 'vi' => 'Product Engineering' ),
			'location'   => array( 'ja' => 'リモート(アジア太平洋)', 'en' => 'Remote (Asia–Pacific)', 'vi' => 'Từ xa (Châu Á – TBD)' ),
			'type'       => array( 'ja' => '正社員', 'en' => 'Full-time', 'vi' => 'Toàn thời gian' ),
			'level'      => array( 'ja' => 'ミドル〜シニア', 'en' => 'Mid – Senior', 'vi' => 'Trung cấp – Senior' ),
			'title' => array(
				'ja' => 'フロントエンドエンジニア(React / TypeScript)',
				'en' => 'Frontend Engineer (React / TypeScript)',
				'vi' => 'Frontend Engineer (React / TypeScript)',
			),
			'intro' => array(
				'ja' => 'Dogo の越境 EC 体験を、3 言語のお客様にとってシンプルで気持ち良いものにしていく、UI を主導するエンジニアを募集します。',
				'en' => 'Lead the UI of Dogo\'s cross-border eCommerce experience — making it simple and delightful for customers in 3 languages.',
				'vi' => 'Dẫn dắt UI cho trải nghiệm eCommerce xuyên biên giới của Dogo — đơn giản và thoải mái cho khách hàng ở 3 ngôn ngữ.',
			),
			'responsibilities' => array(
				'ja' => array(
					'マーケティングサイト・ストアフロントの実装',
					'デザイナー・バックエンドとの密接な連携',
					'多言語(EN / JA / VI)対応の UI 設計',
					'パフォーマンス・アクセシビリティの継続改善',
					'コンポーネントライブラリの保守・拡張',
				),
				'en' => array(
					'Build the marketing site and storefront UIs',
					'Pair closely with design and backend',
					'Architect for tri-lingual (EN / JA / VI) experience',
					'Continuously improve performance and a11y',
					'Maintain and grow the component library',
				),
				'vi' => array(
					'Xây UI cho trang marketing và storefront',
					'Phối hợp chặt với design và backend',
					'Thiết kế UI đa ngôn ngữ (EN / JA / VI)',
					'Liên tục cải thiện performance và accessibility',
					'Duy trì và mở rộng component library',
				),
			),
			'requirements' => array(
				'ja' => array(
					'React / TypeScript の実務経験 3 年以上',
					'CSS 設計(BEM, CSS-in-JS など)に明るい',
					'Web パフォーマンス、Core Web Vitals の最適化経験',
					'i18n の実装経験',
					'英語または日本語でのチーム共同作業',
				),
				'en' => array(
					'3+ years production React / TypeScript',
					'Solid CSS architecture (BEM, CSS-in-JS, etc.)',
					'Hands-on with web performance and Core Web Vitals',
					'i18n implementation experience',
					'Working English or Japanese',
				),
				'vi' => array(
					'Tối thiểu 3 năm React / TypeScript thực tế',
					'Vững CSS architecture (BEM, CSS-in-JS, …)',
					'Kinh nghiệm tối ưu performance và Core Web Vitals',
					'Đã làm i18n trong production',
					'Tiếng Anh hoặc Nhật giao tiếp công việc',
				),
			),
			'nice' => array(
				'ja' => array(
					'Next.js / Remix 等のメタフレームワーク',
					'WordPress / ヘッドレス CMS の経験',
					'デザインシステム構築経験',
				),
				'en' => array(
					'Next.js / Remix or similar meta-framework',
					'WordPress / headless CMS experience',
					'Design-system building experience',
				),
				'vi' => array(
					'Next.js / Remix hoặc meta-framework tương tự',
					'Kinh nghiệm WordPress / headless CMS',
					'Đã xây design system',
				),
			),
			'benefits' => array(
				'ja' => array(
					'フルリモート・任意の時間帯(コア時間 11-15 時 JST)',
					'機材補助(PC / モニター / 椅子)',
					'カンファレンス・学習補助 年 ¥150,000',
					'年 1 回の福岡オフサイト(渡航費負担)',
					'昇給・賞与年 2 回',
				),
				'en' => array(
					'Fully remote · flex hours (core 11–15 JST)',
					'Equipment stipend (laptop / monitor / chair)',
					'¥150,000 / yr conference & learning budget',
					'Annual Fukuoka offsite (travel covered)',
					'Annual review + bi-annual bonus',
				),
				'vi' => array(
					'Làm từ xa hoàn toàn · giờ linh hoạt (core 11–15 JST)',
					'Phụ cấp thiết bị (laptop / màn hình / ghế)',
					'Ngân sách học và hội thảo ¥150.000/năm',
					'Offsite Fukuoka 1 lần/năm (công ty trả vé)',
					'Đánh giá tăng lương hàng năm + thưởng 2 lần/năm',
				),
			),
		),

		array(
			'slug'   => 'beauty-buyer',
			'posted' => '2026-04-10',
			'salary' => '¥4,200,000 – ¥6,500,000 / yr',
			'department' => array( 'ja' => '仕入・MD', 'en' => 'Merchandising', 'vi' => 'Quản lý hàng hóa' ),
			'location'   => array( 'ja' => '福岡本社', 'en' => 'Fukuoka HQ', 'vi' => 'Trụ sở Fukuoka' ),
			'type'       => array( 'ja' => '正社員', 'en' => 'Full-time', 'vi' => 'Toàn thời gian' ),
			'level'      => array( 'ja' => 'ミドル', 'en' => 'Mid-level', 'vi' => 'Trung cấp' ),
			'title' => array(
				'ja' => 'ビューティーバイヤー(スキンケア・コスメ部門)',
				'en' => 'Beauty Buyer (Skincare & Cosmetics)',
				'vi' => 'Chuyên viên Mua hàng — Làm đẹp (Chăm sóc da & Mỹ phẩm)',
			),
			'intro' => array(
				'ja' => '日本ブランドの正規取引契約を拡大し、世界の美容愛好家へ本物を届けるバイヤーポジションです。',
				'en' => 'Expand authorised supply agreements with Japanese brands, bringing the real thing to global beauty enthusiasts.',
				'vi' => 'Mở rộng hợp đồng phân phối chính hãng với các brand Nhật, đưa hàng thật đến tín đồ làm đẹp toàn cầu.',
			),
			'responsibilities' => array(
				'ja' => array(
					'スキンケア・コスメブランドとの正規取引契約交渉',
					'年間プロモーションプランの策定',
					'仕入計画・在庫水準・粗利率の管理',
					'規制(EU / 米国 / 中国等)対応の確認',
				),
				'en' => array(
					'Negotiate authorised supply agreements',
					'Build the annual promo calendar',
					'Manage purchasing, inventory and gross margin',
					'Track destination-market compliance (EU / US / CN, etc.)',
				),
				'vi' => array(
					'Đàm phán hợp đồng phân phối chính hãng với brand',
					'Lập kế hoạch khuyến mãi hàng năm',
					'Quản lý mua hàng, tồn kho, biên lợi nhuận gộp',
					'Theo dõi tuân thủ theo thị trường (EU / Mỹ / TQ, …)',
				),
			),
			'requirements' => array(
				'ja' => array(
					'バイヤー / MD 経験 3 年以上(化粧品・健康食品歓迎)',
					'日本語ネイティブまたはビジネス上級',
					'契約交渉・社内承認プロセスの実務経験',
					'数値感度の高さ',
				),
				'en' => array(
					'3+ years buyer / MD experience (beauty preferred)',
					'Native / business-level Japanese',
					'Comfortable owning contract negotiation',
					'Numerate and KPI-driven',
				),
				'vi' => array(
					'Tối thiểu 3 năm buyer / MD (ưu tiên làm đẹp)',
					'Tiếng Nhật bản ngữ hoặc business',
					'Đàm phán hợp đồng và quy trình phê duyệt nội bộ',
					'Tư duy số hoá, làm việc theo KPI',
				),
			),
			'nice' => array(
				'ja' => array(
					'化粧品の輸出入規制に関する知識',
					'英語または中国語でのコミュニケーション',
				),
				'en' => array(
					'Knowledge of cosmetics export / import regulations',
					'English or Mandarin communication skills',
				),
				'vi' => array(
					'Hiểu quy định xuất nhập khẩu mỹ phẩm',
					'Tiếng Anh hoặc Trung giao tiếp',
				),
			),
			'benefits' => array(
				'ja' => array(
					'昇給・賞与年 2 回',
					'交通費全額支給',
					'社員割引(全カテゴリ)',
					'年次有給休暇 + 夏季・冬季休暇',
				),
				'en' => array(
					'Annual review + bi-annual bonus',
					'Full commuting allowance',
					'Employee discount across all categories',
					'PTO + summer & winter holidays',
				),
				'vi' => array(
					'Đánh giá tăng lương hàng năm + thưởng 2 lần/năm',
					'Trợ cấp đi lại đầy đủ',
					'Giảm giá nhân viên trên toàn bộ danh mục',
					'Phép có lương + nghỉ hè và nghỉ đông',
				),
			),
		),

		array(
			'slug'   => 'multilingual-support-vietnamese',
			'posted' => '2026-04-05',
			'salary' => '¥2,800,000 – ¥3,800,000 / yr',
			'department' => array( 'ja' => 'カスタマーサクセス', 'en' => 'Customer Success', 'vi' => 'Customer Success' ),
			'location'   => array( 'ja' => 'リモート', 'en' => 'Remote', 'vi' => 'Từ xa' ),
			'type'       => array( 'ja' => '業務委託 / パートタイム', 'en' => 'Contract / Part-time', 'vi' => 'Hợp đồng / Bán thời gian' ),
			'level'      => array( 'ja' => '未経験可', 'en' => 'Entry friendly', 'vi' => 'Mở cho mới ra trường' ),
			'title' => array(
				'ja' => '多言語サポート担当(ベトナム語ネイティブ)',
				'en' => 'Multilingual Customer Support — Vietnamese Native',
				'vi' => 'Hỗ trợ khách hàng đa ngôn ngữ — Bản ngữ tiếng Việt',
			),
			'intro' => array(
				'ja' => 'ベトナム市場の急成長に伴い、ベトナム語ネイティブのサポートメンバーを募集します。日本語または英語でのチーム連携が可能な方歓迎。',
				'en' => 'Vietnam is one of our fastest-growing markets — join us as a native Vietnamese support specialist who collaborates in JA or EN.',
				'vi' => 'Vietnam là một trong những thị trường tăng trưởng nhanh nhất của Dogo — chúng tôi đang tìm bạn bản ngữ tiếng Việt, có thể trao đổi nội bộ bằng tiếng Anh hoặc Nhật.',
			),
			'responsibilities' => array(
				'ja' => array(
					'ベトナム語でのお客様対応(メール / チャット / SNS)',
					'お客様の声の社内共有・翻訳',
					'地域マーケティングのコピー監修サポート',
				),
				'en' => array(
					'Front-line Vietnamese customer support (email / chat / social)',
					'Translate and surface local customer signal internally',
					'Review and refine Vietnamese marketing copy',
				),
				'vi' => array(
					'Hỗ trợ khách hàng tiếng Việt (email / chat / mạng xã hội)',
					'Dịch và truyền đạt phản hồi của khách về team nội bộ',
					'Hỗ trợ rà soát và biên tập copy marketing tiếng Việt',
				),
			),
			'requirements' => array(
				'ja' => array(
					'ベトナム語ネイティブ',
					'日本語または英語でのビジネスコミュニケーション',
					'丁寧な顧客対応のマインドセット',
				),
				'en' => array(
					'Native Vietnamese',
					'Business-level Japanese or English',
					'Customer-first mindset',
				),
				'vi' => array(
					'Bản ngữ tiếng Việt',
					'Tiếng Nhật hoặc Anh giao tiếp công việc',
					'Tinh thần lấy khách hàng làm trung tâm',
				),
			),
			'nice' => array(
				'ja' => array(
					'CS ツール(Zendesk / Help Scout 等)経験',
					'EC 業界経験',
				),
				'en' => array(
					'Experience with CS tools (Zendesk / Help Scout / etc.)',
					'eCommerce industry exposure',
				),
				'vi' => array(
					'Kinh nghiệm dùng tool CS (Zendesk / Help Scout / …)',
					'Kinh nghiệm trong ngành eCommerce',
				),
			),
			'benefits' => array(
				'ja' => array(
					'完全リモート、時間帯選択可',
					'実績に応じたインセンティブ',
					'長期契約への登用機会',
				),
				'en' => array(
					'Fully remote · flexible hours',
					'Performance-linked incentives',
					'Path to long-term engagement',
				),
				'vi' => array(
					'Làm từ xa hoàn toàn · giờ linh hoạt',
					'Thưởng theo hiệu suất',
					'Lộ trình lên hợp đồng dài hạn',
				),
			),
		),
		end legacy block */

	) );
}
