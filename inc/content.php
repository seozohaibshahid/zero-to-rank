<?php
/**
 * Landing page content.
 *
 * All copy lives here so sections stay presentational. Change anything via:
 *
 *     add_filter( 'ztr_content', function ( $c ) {
 *         $c['hero']['title'] = '…';
 *         return $c;
 *     } );
 *
 * Content rule: no fabricated testimonials, client logos, rankings or
 * performance statistics. Case studies in `$c['results']['cases']` use real
 * client reports, with the screenshots shipped as evidence.
 *
 * @package ZeroToRank
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the full content model (cached per request).
 *
 * @param string|null $key Optional top-level key.
 * @return array
 */
function ztr_content( $key = null ) {
	static $content = null;

	if ( null === $content ) {
		$content = apply_filters( 'ztr_content', ztr_default_content() );
	}

	if ( null === $key ) {
		return $content;
	}

	return isset( $content[ $key ] ) ? $content[ $key ] : array();
}

/**
 * Default content model.
 *
 * @return array
 */
function ztr_default_content() {
	return array(

		/* 01 — Navigation (used when no menu is assigned to the "Landing page" location). */
		'nav'        => array(
			array( 'label' => __( 'Results', 'zero-to-rank' ), 'url' => '#results' ),
			array( 'label' => __( 'Services', 'zero-to-rank' ), 'url' => '#services' ),
			array( 'label' => __( 'Process', 'zero-to-rank' ), 'url' => '#process' ),
			array( 'label' => __( 'Who we help', 'zero-to-rank' ), 'url' => '#industries' ),
			array( 'label' => __( 'FAQs', 'zero-to-rank' ), 'url' => '#faq' ),
		),

		/* 02 — Hero. */
		'hero'       => array(
			'eyebrow'       => __( 'Search visibility, redefined', 'zero-to-rank' ),
			/* Three-word headline (one per channel). 'accent' highlights a word. */
			'words'         => array(
				array( 'word' => __( 'Found.', 'zero-to-rank' ), 'caption' => __( 'SEO — rank where your buyers search', 'zero-to-rank' ) ),
				array( 'word' => __( 'Answered.', 'zero-to-rank' ), 'caption' => __( 'AEO — become the direct answer', 'zero-to-rank' ), 'accent' => true ),
				array( 'word' => __( 'Cited.', 'zero-to-rank' ), 'caption' => __( 'GEO — get recommended by AI assistants', 'zero-to-rank' ) ),
			),
			'title_lines'   => array(
				__( 'Be the <em>answer.</em>', 'zero-to-rank' ),
				__( 'Not just another search result.', 'zero-to-rank' ),
			),
			'lead_strong'   => __( 'From zero to everywhere.', 'zero-to-rank' ),
			'lead'          => __( 'We help ambitious businesses grow through SEO, Answer Engine Optimization and Generative Engine Optimization.', 'zero-to-rank' ),
			'primary_cta'   => __( 'Get your free visibility audit', 'zero-to-rank' ),
			'secondary_cta' => __( 'See client results', 'zero-to-rank' ),
			/* Real results shown under the hero; each links to its case study. */
			'proof'         => array(
				array( 'value' => '+144%', 'label' => __( 'search clicks in 6 months', 'zero-to-rank' ), 'who' => __( 'International B2B', 'zero-to-rank' ), 'href' => '#ztr-case-international' ),
				array( 'value' => '16.8K', 'label' => __( 'clicks from a near-standing start', 'zero-to-rank' ), 'who' => __( 'Industrial manufacturer', 'zero-to-rank' ), 'href' => '#ztr-case-industrial' ),
				array( 'value' => '322K', 'label' => __( 'impressions in Google AI features', 'zero-to-rank' ), 'who' => __( 'Real estate · Canada', 'zero-to-rank' ), 'href' => '#ztr-case-realestate' ),
			),
		),

		/* 03 — Trust bar: channels covered + verifiable working principles (no client logos). */
		'trust'      => array(
			'label'      => __( 'We build visibility where your customers now search', 'zero-to-rank' ),
			'platforms'  => array( 'Google Search', 'Google AI Overviews', 'Google Maps', 'Bing', 'ChatGPT', 'Perplexity', 'Gemini', 'Microsoft Copilot', 'YouTube' ),
			'principles' => array(
				array( 'icon' => 'shield', 'text' => __( 'Search-guideline-led methods', 'zero-to-rank' ) ),
				array( 'icon' => 'chart', 'text' => __( 'Transparent, outcome-based reporting', 'zero-to-rank' ) ),
				array( 'icon' => 'layers', 'text' => __( 'One roadmap across SEO, AEO & GEO', 'zero-to-rank' ) ),
			),
		),

		/*
		 * Statement: a point-of-view line that fills in word by word on scroll.
		 * Wrap the phrase to emphasise in <em>. Opinion, not a claim — no figures.
		 */
		'statement'  => array(
			'eyebrow' => __( 'Our point of view', 'zero-to-rank' ),
			'text'    => __( 'Your buyers don’t search once any more. They ask Google, check the map, then ask an AI assistant to compare. At every one of those moments there is a single answer — <em>we make sure it is you.</em>', 'zero-to-rank' ),
		),

		/*
		 * 04 — Results: real case studies, taken from the clients' own Google
		 * Search Console and GA4 reports. Every figure below is shown in the
		 * attached screenshot ('evidence'). Clients are described, not named.
		 * Images live in assets/images/cases/ (or pass full URLs).
		 */
		'results'    => array(
			'eyebrow' => __( 'Case studies', 'zero-to-rank' ),
			'title'   => __( 'Real results, <em>straight from the reports.</em>', 'zero-to-rank' ),
			'intro'   => __( 'Three live engagements. Every number on this page comes from the client’s own Google Search Console or GA4 — the screenshots are right there to check.', 'zero-to-rank' ),
			'note'    => __( 'Client names are withheld. Figures are as reported by Google Search Console and Google Analytics 4 for the periods shown; past results do not guarantee future rankings.', 'zero-to-rank' ),
			'cases'   => array(
				array(
					'key'      => 'international',
					'tab'      => __( 'International B2B', 'zero-to-rank' ),
					'sector'   => __( 'B2B services brand · multiple countries', 'zero-to-rank' ),
					'period'   => __( 'Last 6 months vs the previous 6 months', 'zero-to-rank' ),
					'headline' => __( 'Clicks up 144% in six months, while impressions in Google’s AI features keep climbing.', 'zero-to-rank' ),
					'metrics'  => array(
						array( 'value' => '+144%', 'label' => __( 'Search clicks', 'zero-to-rank' ), 'detail' => __( '4.47K vs 1.83K', 'zero-to-rank' ) ),
						array( 'value' => '+249%', 'label' => __( 'Search impressions', 'zero-to-rank' ), 'detail' => __( '219K vs 62.8K', 'zero-to-rank' ) ),
						array( 'value' => '30.4K', 'label' => __( 'Impressions in Google AI features', 'zero-to-rank' ), 'detail' => __( 'Last 6 months', 'zero-to-rank' ) ),
						array( 'value' => '10.5', 'label' => __( 'Average position', 'zero-to-rank' ), 'detail' => __( 'Up from 11.2', 'zero-to-rank' ) ),
					),
					'sources'  => array(),
					'footnote' => __( 'CTR moved from 2.9% to 2.0% because impressions grew faster than clicks.', 'zero-to-rank' ),
					'evidence' => array(
						array( 'src' => 'images/cases/intl-search-compare.webp', 'w' => 1460, 'h' => 545, 'caption' => __( 'Search Console · Performance, last 6 months vs previous 6 months', 'zero-to-rank' ) ),
						array( 'src' => 'images/cases/intl-ai-impressions.webp', 'w' => 1410, 'h' => 520, 'caption' => __( 'Search Console · Generative AI, last 6 months', 'zero-to-rank' ) ),
					),
				),
				array(
					'key'      => 'industrial',
					'tab'      => __( 'Industrial manufacturer', 'zero-to-rank' ),
					'sector'   => __( 'Industrial air treatment systems', 'zero-to-rank' ),
					'period'   => __( '16 months of Search Console data', 'zero-to-rank' ),
					'headline' => __( 'From a near-standing start to 16.8K clicks — and visitors now arrive from AI assistants.', 'zero-to-rank' ),
					'metrics'  => array(
						array( 'value' => '16.8K', 'label' => __( 'Search clicks', 'zero-to-rank' ), 'detail' => __( '16 months · 3.8% CTR', 'zero-to-rank' ) ),
						array( 'value' => '446K', 'label' => __( 'Search impressions', 'zero-to-rank' ), 'detail' => __( '16 months', 'zero-to-rank' ) ),
						array( 'value' => '22K', 'label' => __( 'Impressions in Google AI features', 'zero-to-rank' ), 'detail' => __( 'Last 6 months', 'zero-to-rank' ) ),
						array( 'value' => '180', 'label' => __( 'Sessions from AI assistants', 'zero-to-rank' ), 'detail' => __( 'GA4 · 65% engaged', 'zero-to-rank' ) ),
					),
					'sources'  => array(
						array( 'name' => 'Gemini', 'value' => 63 ),
						array( 'name' => 'ChatGPT', 'value' => 46 ),
						array( 'name' => 'Claude', 'value' => 45 ),
						array( 'name' => 'Copilot', 'value' => 11 ),
						array( 'name' => 'Perplexity', 'value' => 5 ),
					),
					'footnote' => __( 'The highlighted area marks the engagement period.', 'zero-to-rank' ),
					'evidence' => array(
						array( 'src' => 'images/cases/industrial-search-16m.webp', 'w' => 1465, 'h' => 490, 'caption' => __( 'Search Console · Performance, 16 months', 'zero-to-rank' ) ),
						array( 'src' => 'images/cases/industrial-ai-impressions.webp', 'w' => 1455, 'h' => 500, 'caption' => __( 'Search Console · Generative AI, last 6 months', 'zero-to-rank' ) ),
						array( 'src' => 'images/cases/industrial-ai-traffic.webp', 'w' => 1485, 'h' => 605, 'caption' => __( 'GA4 · AI-assistant traffic by source / medium', 'zero-to-rank' ) ),
					),
				),
				array(
					'key'      => 'realestate',
					'tab'      => __( 'Real estate · Canada', 'zero-to-rank' ),
					'sector'   => __( 'Real estate · Canada', 'zero-to-rank' ),
					'period'   => __( '16 months of Search Console data', 'zero-to-rank' ),
					'headline' => __( '3.28M search impressions, and AI-assistant visitors who take action.', 'zero-to-rank' ),
					'metrics'  => array(
						array( 'value' => '3.28M', 'label' => __( 'Search impressions', 'zero-to-rank' ), 'detail' => __( '16 months · 6.57K clicks', 'zero-to-rank' ) ),
						array( 'value' => '322K', 'label' => __( 'Impressions in Google AI features', 'zero-to-rank' ), 'detail' => __( 'Last 6 months', 'zero-to-rank' ) ),
						array( 'value' => '514', 'label' => __( 'Sessions from AI assistants', 'zero-to-rank' ), 'detail' => __( 'GA4 · 82.9% engaged', 'zero-to-rank' ) ),
						array( 'value' => '1,797', 'label' => __( 'Key events from those sessions', 'zero-to-rank' ), 'detail' => __( '85.6% session key event rate', 'zero-to-rank' ) ),
					),
					'sources'  => array(
						array( 'name' => 'ChatGPT', 'value' => 403 ),
						array( 'name' => 'Gemini', 'value' => 54 ),
						array( 'name' => 'Claude', 'value' => 21 ),
						array( 'name' => 'Perplexity', 'value' => 17 ),
						array( 'name' => 'Copilot', 'value' => 6 ),
					),
					'footnote' => '',
					'evidence' => array(
						array( 'src' => 'images/cases/realestate-search-16m.webp', 'w' => 1460, 'h' => 485, 'caption' => __( 'Search Console · Performance, 16 months', 'zero-to-rank' ) ),
						array( 'src' => 'images/cases/realestate-ai-impressions.webp', 'w' => 1460, 'h' => 490, 'caption' => __( 'Search Console · Generative AI, last 6 months', 'zero-to-rank' ) ),
						array( 'src' => 'images/cases/realestate-ai-traffic.webp', 'w' => 1495, 'h' => 670, 'caption' => __( 'GA4 · AI-assistant traffic by source / medium', 'zero-to-rank' ) ),
					),
				),
			),
		),

		/* 05 — The visibility shift. */
		'shift'      => array(
			'eyebrow' => __( 'The visibility shift', 'zero-to-rank' ),
			'title'   => __( 'Search didn’t disappear. <em>It split.</em>', 'zero-to-rank' ),
			'intro'   => __( 'Buyers still search — but more of their questions are now answered before they ever click. Visibility means being found in the results and being the source the answer is built from.', 'zero-to-rank' ),
			'modes'   => array(
				'traditional' => array(
					'label'   => __( 'Traditional search', 'zero-to-rank' ),
					'query'   => __( 'best project management software', 'zero-to-rank' ),
					'steps'   => array( __( 'Types keywords', 'zero-to-rank' ), __( 'Scans ten links', 'zero-to-rank' ), __( 'Opens several tabs', 'zero-to-rank' ), __( 'Compares and decides', 'zero-to-rank' ) ),
					'wins'    => array( __( 'Rankings & position', 'zero-to-rank' ), __( 'Click-through rate', 'zero-to-rank' ), __( 'Keyword-matched pages', 'zero-to-rank' ), __( 'Backlink authority', 'zero-to-rank' ) ),
					'summary' => __( 'You compete for a click. The buyer does the research.', 'zero-to-rank' ),
				),
				'ai'          => array(
					'label'   => __( 'AI-driven discovery', 'zero-to-rank' ),
					'query'   => __( 'Which project management tool suits a 12-person agency?', 'zero-to-rank' ),
					'steps'   => array( __( 'Asks a full question', 'zero-to-rank' ), __( 'Gets one synthesised answer', 'zero-to-rank' ), __( 'Sees a shortlist with sources', 'zero-to-rank' ), __( 'Acts on the recommendation', 'zero-to-rank' ) ),
					'wins'    => array( __( 'Clear, citable answers', 'zero-to-rank' ), __( 'Entity & brand consistency', 'zero-to-rank' ), __( 'Structured data', 'zero-to-rank' ), __( 'Mentions on trusted sources', 'zero-to-rank' ) ),
					'summary' => __( 'You compete to be included. The AI does the research.', 'zero-to-rank' ),
				),
			),
		),

		/* 06 — Services. */
		'services'   => array(
			'eyebrow' => __( 'Services', 'zero-to-rank' ),
			'title'   => __( 'Three channels. <em>One visibility strategy.</em>', 'zero-to-rank' ),
			'intro'   => __( 'Each discipline answers a different question your buyers ask. Together they make sure you show up however the question is asked.', 'zero-to-rank' ),
			'items'   => array(
				array(
					'key'          => 'seo',
					'abbr'         => 'SEO',
					'name'         => __( 'Search Engine Optimization', 'zero-to-rank' ),
					'question'     => __( 'Can buyers find you?', 'zero-to-rank' ),
					'summary'      => __( 'Technical foundations, content and authority that earn durable rankings for the searches that drive revenue.', 'zero-to-rank' ),
					'deliverables' => array( __( 'Technical SEO & Core Web Vitals', 'zero-to-rank' ), __( 'Keyword & intent mapping', 'zero-to-rank' ), __( 'Service & location page strategy', 'zero-to-rank' ), __( 'Local SEO & Google Business Profile', 'zero-to-rank' ), __( 'Digital PR & link earning', 'zero-to-rank' ) ),
				),
				array(
					'key'          => 'aeo',
					'abbr'         => 'AEO',
					'name'         => __( 'Answer Engine Optimization', 'zero-to-rank' ),
					'question'     => __( 'Are you the answer?', 'zero-to-rank' ),
					'summary'      => __( 'Question-led content and structure that gets you selected for featured snippets, People Also Ask and voice results.', 'zero-to-rank' ),
					'deliverables' => array( __( 'Question & conversation research', 'zero-to-rank' ), __( 'Answer-first content design', 'zero-to-rank' ), __( 'FAQ, HowTo & entity schema', 'zero-to-rank' ), __( 'Featured snippet targeting', 'zero-to-rank' ), __( 'Voice search readiness', 'zero-to-rank' ) ),
				),
				array(
					'key'          => 'geo',
					'abbr'         => 'GEO',
					'name'         => __( 'Generative Engine Optimization', 'zero-to-rank' ),
					'question'     => __( 'Does AI recommend you?', 'zero-to-rank' ),
					'summary'      => __( 'Brand, entity and source signals that help AI assistants understand, trust and cite your business.', 'zero-to-rank' ),
					'deliverables' => array( __( 'AI visibility & citation audit', 'zero-to-rank' ), __( 'Entity & knowledge graph alignment', 'zero-to-rank' ), __( 'Citable, evidence-rich content', 'zero-to-rank' ), __( 'Third-party mention strategy', 'zero-to-rank' ), __( 'AI share-of-voice monitoring', 'zero-to-rank' ) ),
				),
			),
		),

		/* 07 — Why Zero to Rank. Metrics describe how we work; confirm before launch. */
		'why'        => array(
			'eyebrow'         => __( 'Why Zero to Rank', 'zero-to-rank' ),
			'title'           => __( 'Built for how people <em>search now.</em>', 'zero-to-rank' ),
			'intro'           => __( 'Most agencies bolt “AI” onto an old SEO playbook. We start from how decisions are actually made today and work back to the actions that move them.', 'zero-to-rank' ),
			'metrics'         => array(
				array( 'value' => 3, 'suffix' => '', 'label' => __( 'visibility channels, planned as one roadmap', 'zero-to-rank' ) ),
				array( 'value' => 5, 'suffix' => '', 'label' => __( 'stages from audit to compounding growth', 'zero-to-rank' ) ),
				array( 'value' => 90, 'suffix' => '-day', 'label' => __( 'priority roadmaps, reviewed every cycle', 'zero-to-rank' ) ),
			),
			'differentiators' => array(
				array( 'icon' => 'layers', 'title' => __( 'Unified, not siloed', 'zero-to-rank' ), 'text' => __( 'SEO, AEO and GEO share one research base and one roadmap, so every page works across all three channels.', 'zero-to-rank' ) ),
				array( 'icon' => 'target', 'title' => __( 'Commercial intent first', 'zero-to-rank' ), 'text' => __( 'We prioritise the searches and questions closest to a buying decision before chasing volume.', 'zero-to-rank' ) ),
				array( 'icon' => 'chart', 'title' => __( 'Evidence over opinion', 'zero-to-rank' ), 'text' => __( 'Recommendations are backed by your data, live SERP and AI-answer checks — and labelled when they are a test.', 'zero-to-rank' ) ),
				array( 'icon' => 'shield', 'title' => __( 'No shortcuts', 'zero-to-rank' ), 'text' => __( 'Guideline-led work that protects your domain. We will never promise guaranteed rankings.', 'zero-to-rank' ) ),
			),
		),

		/* 08 — Process. */
		'process'    => array(
			'eyebrow' => __( 'Our process', 'zero-to-rank' ),
			'title'   => __( 'From zero to <em>everywhere,</em> step by step.', 'zero-to-rank' ),
			'intro'   => __( 'A clear sequence, so you always know what is happening, why, and what comes next.', 'zero-to-rank' ),
			'steps'   => array(
				array( 'title' => __( 'Visibility audit', 'zero-to-rank' ), 'text' => __( 'We benchmark where you appear today across search results, answer features and AI assistants — and where competitors are winning instead.', 'zero-to-rank' ), 'output' => __( 'Visibility baseline', 'zero-to-rank' ) ),
				array( 'title' => __( 'Strategy & roadmap', 'zero-to-rank' ), 'text' => __( 'We map buyer questions to pages, prioritise by commercial impact and effort, and agree the KPIs we will report on.', 'zero-to-rank' ), 'output' => __( 'Prioritised roadmap', 'zero-to-rank' ) ),
				array( 'title' => __( 'Foundations', 'zero-to-rank' ), 'text' => __( 'Technical fixes, site structure, structured data and entity consistency — so search engines and AI can read and trust you.', 'zero-to-rank' ), 'output' => __( 'Crawlable, understood site', 'zero-to-rank' ) ),
				array( 'title' => __( 'Content & authority', 'zero-to-rank' ), 'text' => __( 'Answer-first pages, expert content and earned mentions that make you the most citable source in your category.', 'zero-to-rank' ), 'output' => __( 'Citable content library', 'zero-to-rank' ) ),
				array( 'title' => __( 'Measure & compound', 'zero-to-rank' ), 'text' => __( 'Monthly reporting on rankings, answer share, AI citations and leads — then we double down on what works.', 'zero-to-rank' ), 'output' => __( 'Compounding growth', 'zero-to-rank' ) ),
			),
		),

		/* 09 — Who we help. */
		'industries' => array(
			'eyebrow' => __( 'Who we help', 'zero-to-rank' ),
			'title'   => __( 'Visibility plans shaped <em>around your market.</em>', 'zero-to-rank' ),
			'items'   => array(
				array(
					'key'       => 'local',
					'name'      => __( 'Local & multi-location', 'zero-to-rank' ),
					'challenge' => __( 'Buyers ask maps and AI assistants for “the best near me” — and act on the first few names they hear.', 'zero-to-rank' ),
					'approach'  => array( __( 'Google Business Profile and local pack optimization', 'zero-to-rank' ), __( 'Location and service-area pages that answer real questions', 'zero-to-rank' ), __( 'Review, citation and NAP consistency across the web', 'zero-to-rank' ) ),
					'channels'  => array( 'seo', 'aeo', 'geo' ),
				),
				array(
					'key'       => 'professional',
					'name'      => __( 'Professional services', 'zero-to-rank' ),
					'challenge' => __( 'High-trust decisions start with research questions. Expertise has to be visible before the first call.', 'zero-to-rank' ),
					'approach'  => array( __( 'Expert-led content that demonstrates experience', 'zero-to-rank' ), __( 'Author, organization and service entity markup', 'zero-to-rank' ), __( 'Answer-first pages for every pre-sales question', 'zero-to-rank' ) ),
					'channels'  => array( 'seo', 'aeo', 'geo' ),
				),
				array(
					'key'       => 'ecommerce',
					'name'      => __( 'eCommerce', 'zero-to-rank' ),
					'challenge' => __( 'Shoppers compare in AI assistants and shopping results long before they land on a product page.', 'zero-to-rank' ),
					'approach'  => array( __( 'Category and product page architecture', 'zero-to-rank' ), __( 'Product, review and merchant structured data', 'zero-to-rank' ), __( 'Comparison and buying-guide content AI can cite', 'zero-to-rank' ) ),
					'channels'  => array( 'seo', 'geo' ),
				),
				array(
					'key'       => 'b2b',
					'name'      => __( 'B2B & SaaS', 'zero-to-rank' ),
					'challenge' => __( 'Long, committee-led journeys where shortlists are increasingly built by asking AI.', 'zero-to-rank' ),
					'approach'  => array( __( 'Problem, solution and comparison page strategy', 'zero-to-rank' ), __( 'Consistent product entity signals across the web', 'zero-to-rank' ), __( 'Pipeline attribution from organic and AI referrals', 'zero-to-rank' ) ),
					'channels'  => array( 'seo', 'aeo', 'geo' ),
				),
				array(
					'key'       => 'health',
					'name'      => __( 'Healthcare & clinics', 'zero-to-rank' ),
					'challenge' => __( 'Health queries are held to the highest quality bar. Accuracy and credentials decide who gets shown.', 'zero-to-rank' ),
					'approach'  => array( __( 'Clinician-reviewed, clearly sourced content', 'zero-to-rank' ), __( 'Practitioner and medical organization markup', 'zero-to-rank' ), __( 'Local visibility for every clinic and treatment', 'zero-to-rank' ) ),
					'channels'  => array( 'seo', 'aeo' ),
				),
			),
		),

		/* 10 — Conversion. */
		'audit'      => array(
			'eyebrow'  => __( 'Free visibility audit', 'zero-to-rank' ),
			'title'    => __( 'See where you’re <em>invisible</em> — before your competitors do.', 'zero-to-rank' ),
			'intro'    => __( 'Tell us about your business and we will review your visibility across search, answer engines and AI assistants, then send back the priorities we would tackle first.', 'zero-to-rank' ),
			'includes' => array(
				__( 'Technical and on-page SEO health check', 'zero-to-rank' ),
				__( 'Answer-readiness review of your key pages', 'zero-to-rank' ),
				__( 'AI assistant mention and citation spot-check', 'zero-to-rank' ),
				__( 'A short, prioritised action list', 'zero-to-rank' ),
			),
			'reassure' => __( 'No obligation. No automated report dump. Reviewed by a person.', 'zero-to-rank' ),
			'button'   => __( 'Request my free audit', 'zero-to-rank' ),
			'success'  => __( 'Thank you — your audit request is in. We will be in touch at the email you provided.', 'zero-to-rank' ),
		),

		/* 11 — FAQs (also output as FAQPage structured data). */
		'faq'        => array(
			'eyebrow' => __( 'FAQs', 'zero-to-rank' ),
			'title'   => __( 'Straight answers to <em>fair questions.</em>', 'zero-to-rank' ),
			'items'   => array(
				array(
					'q' => __( 'What is the difference between SEO, AEO and GEO?', 'zero-to-rank' ),
					'a' => __( 'SEO helps your pages rank in traditional search results. AEO (Answer Engine Optimization) structures your content so it is chosen as the direct answer — featured snippets, People Also Ask and voice results. GEO (Generative Engine Optimization) helps AI assistants such as ChatGPT, Gemini, Perplexity and Google’s AI features understand, trust and cite your brand. They overlap, so we plan them as one strategy.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'Do I still need SEO if people are using AI assistants?', 'zero-to-rank' ),
					'a' => __( 'Yes. AI assistants and AI search features draw heavily on content that is already crawlable, well structured and trusted in search. Strong SEO foundations are what make AEO and GEO work — they are an extension of search, not a replacement for it.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'How long does it take to see results?', 'zero-to-rank' ),
					'a' => __( 'Technical fixes and quick wins can show movement within weeks. Competitive rankings and consistent AI citations usually build over several months. We set expectations in your roadmap based on your starting point and competition, and report progress monthly.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'Can you guarantee first-page rankings or AI mentions?', 'zero-to-rank' ),
					'a' => __( 'No — and you should be cautious of anyone who does. Search engines and AI systems decide what they show. What we guarantee is a transparent strategy, guideline-led work and honest reporting on the metrics that matter to your business.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'How do you measure AI visibility?', 'zero-to-rank' ),
					'a' => __( 'We track a defined set of buyer questions across AI assistants and AI search features, recording whether your brand is mentioned, recommended or cited — and how that compares with competitors over time. Alongside this we report on AI referral traffic and conversions in your analytics.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'What is included in the free visibility audit?', 'zero-to-rank' ),
					'a' => __( 'A person on our team reviews your technical and on-page SEO health, how answer-ready your key pages are, and whether AI assistants currently mention or cite you. You receive a short, prioritised list of what we would fix first. There is no obligation to work with us.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'Will you need access to my website and analytics?', 'zero-to-rank' ),
					'a' => __( 'Not for the free audit — it is based on public information. For an ongoing engagement we ask for read access to Google Search Console and your analytics, plus a way to make or request site changes, so we can measure accurately and implement quickly.', 'zero-to-rank' ),
				),
				array(
					'q' => __( 'How is pricing structured?', 'zero-to-rank' ),
					'a' => __( 'Pricing depends on your goals, market and how much of the work we implement for you. After the audit we recommend a scope and give you a clear monthly or project price before anything starts.', 'zero-to-rank' ),
				),
			),
		),

		/* 12 — Footer. */
		'footer'     => array(
			'cta_title' => __( 'Ready to go from <em>zero to everywhere?</em>', 'zero-to-rank' ),
			'cta_text'  => __( 'Start with a free visibility audit. We will show you exactly where you stand.', 'zero-to-rank' ),
			'blurb'     => __( 'Zero to Rank is a search visibility agency helping ambitious businesses get found, chosen and cited across search engines, answer engines and AI assistants.', 'zero-to-rank' ),
		),
	);
}
