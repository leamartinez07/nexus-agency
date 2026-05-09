<?php
/*
 * Template Name: Nexus Agency Landing
 * Description: Landing page completa — SEO, accesibilidad WCAG 2.1 AA, formulario funcional, Schema.org, Open Graph, cookie consent.
 */
add_filter( 'show_admin_bar', '__return_false' );

/* ── Formulario de contacto ────────────────────────────────────────────────
   Procesamos ANTES de cualquier salida para poder hacer wp_redirect si fuera
   necesario. Usa nonce de WordPress + sanitización nativa.              */
$nexus_sent  = false;
$nexus_error = false;

if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['nexus_nonce'] ) ) {
    if ( wp_verify_nonce( $_POST['nexus_nonce'], 'nexus_contact_form' ) ) {

        $nombre  = sanitize_text_field( $_POST['nexus_nombre']  ?? '' );
        $wa      = sanitize_text_field( $_POST['nexus_wa']      ?? '' );
        $email   = sanitize_email(      $_POST['nexus_email']   ?? '' );
        $negocio = sanitize_textarea_field( $_POST['nexus_negocio'] ?? '' );

        if ( $nombre && is_email( $email ) ) {
            $admin_email = get_option( 'admin_email' );
            $subject     = 'Nuevo diagnóstico gratuito — Nexus Agency';
            $message     = "Nombre: $nombre\nWhatsApp: $wa\nEmail: $email\nNegocio / consulta:\n$negocio";
            $headers     = [
                'Content-Type: text/plain; charset=UTF-8',
                "Reply-To: $nombre <$email>",
            ];
            $nexus_sent = wp_mail( $admin_email, $subject, $message, $headers );
            if ( ! $nexus_sent ) { $nexus_error = true; }
        } else {
            $nexus_error = true;
        }

    } else {
        $nexus_error = true; // nonce inválido
    }
}

/* ── URLs dinámicas ────────────────────────────────────────────────────── */
$nexus_site_url = home_url( '/' );
$nexus_page_url = get_permalink();
$nexus_og_img   = get_template_directory_uri() . '/assets/og-nexus.jpg'; // reemplazá con tu imagen OG real (1200×630)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- ══ SEO Básico ══════════════════════════════════════════════════════ -->
  <!-- Si instalás Yoast SEO / RankMath, ellos sobreescriben estos tags   -->
  <!-- via wp_head(). Están acá como fallback si no usás ningún plugin.   -->
  <title>Nexus Agency — Marketing Digital que Convierte</title>
  <meta name="description" content="Agencia de marketing digital para PYMEs: SEO, Google Ads, Meta Ads, diseño web y WooCommerce. Diagnóstico gratuito, sin compromiso." />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?php echo esc_url( $nexus_site_url ); ?>" />

  <!-- ══ Open Graph (WhatsApp, LinkedIn, Twitter/X, Facebook) ════════════ -->
  <meta property="og:type"        content="website" />
  <meta property="og:url"         content="<?php echo esc_url( $nexus_site_url ); ?>" />
  <meta property="og:site_name"   content="Nexus Agency" />
  <meta property="og:title"       content="Nexus Agency — Marketing Digital que Convierte" />
  <meta property="og:description" content="SEO, Google Ads, Meta Ads y Diseño Web para PYMEs. Resultados medibles en 30 días. Diagnóstico gratuito." />
  <meta property="og:image"       content="<?php echo esc_url( $nexus_og_img ); ?>" />
  <meta property="og:image:width"  content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:locale"      content="es_AR" />
  <meta name="twitter:card"        content="summary_large_image" />
  <meta name="twitter:title"       content="Nexus Agency — Marketing Digital que Convierte" />
  <meta name="twitter:description" content="SEO, Google Ads, Meta Ads y Diseño Web para PYMEs." />
  <meta name="twitter:image"       content="<?php echo esc_url( $nexus_og_img ); ?>" />

  <!-- ══ Schema.org JSON-LD (evaluado por Google para rich results) ══════ -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "MarketingAgency",
    "name": "Nexus Agency",
    "url": "<?php echo esc_url( $nexus_site_url ); ?>",
    "logo": "<?php echo esc_url( get_template_directory_uri() ); ?>/assets/logo-nexus.png",
    "description": "Agencia de marketing digital para PYMEs: SEO local y nacional, Google Ads, Meta Ads, diseño web y WooCommerce.",
    "email": "hola@nexusagency.com",
    "telephone": "",
    "areaServed": { "@type": "Country", "name": "Argentina" },
    "knowsAbout": ["SEO", "Google Ads", "Meta Ads", "Email Marketing", "WooCommerce", "Diseño Web", "Branding"],
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.9",
      "bestRating": "5",
      "reviewCount": "60"
    },
    "review": [
      {
        "@type": "Review",
        "author": { "@type": "Person", "name": "Martín Rivas" },
        "reviewRating": { "@type": "Rating", "ratingValue": "5" },
        "reviewBody": "Llevábamos 2 años sin crecer. En 4 meses con Nexus triplicamos las consultas online y abrimos una segunda sede."
      },
      {
        "@type": "Review",
        "author": { "@type": "Person", "name": "Laura González" },
        "reviewRating": { "@type": "Rating", "ratingValue": "5" },
        "reviewBody": "Armaron la tienda en 3 semanas y el primer mes ya recuperé la inversión."
      }
    ]
  }
  </script>

  <!-- ══ Performance: Resource Hints ════════════════════════════════════ -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <!-- DNS prefetch para servicios de terceros que uses -->
  <link rel="dns-prefetch" href="//www.google-analytics.com" />
  <link rel="dns-prefetch" href="//www.googletagmanager.com" />

  <!-- ══ Google Fonts ════════════════════════════════════════════════════ -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,700;0,800;1,300&display=swap" rel="stylesheet" />

  <!-- ══ wp_head() — OBLIGATORIO para que funcionen los plugins ══════════
       Yoast SEO, RankMath, WP Rocket, Site Kit, reCAPTCHA, etc.
       inyectan sus scripts/meta tags desde aquí.                        -->
  <?php wp_head(); ?>

  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg:      #f4f2ee;
      --white:   #ffffff;
      --ink:     #131211;
      --ink2:    #2a2825;
      --muted:   #807b74;
      --border:  #dedad2;
      --forest:  #1a5c28;
      --forest2: #236e33;
      --sage-bg: #e8f3eb;
      --gold:    #a86e1e;
      --gold-bg: #fcf3e0;
      --gold-bd: #e8d08a;
    }

    /* ── Override Twenty Twenty-Five theme injected margins ── */
    html { scroll-behavior: smooth; margin-top: 0 !important; scroll-padding-top: 68px; }
    body {
      background: var(--bg);
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
      font-size: 16px;
      line-height: 1.6;
      overflow-x: hidden;
      margin-top: 0 !important;
      padding-top: 0 !important;
    }
    /* Hide anything WordPress/theme might inject before our layout */
    body > .wp-site-blocks,
    body > .entry-content,
    body > header.wp-block-template-part { display: none !important; }

    /* ── Accesibilidad: skip link ──────────────────────────────────────── */
    .skip-link {
      position: absolute; top: -100%; left: 1rem; z-index: 9999;
      background: var(--forest); color: #fff;
      padding: .6rem 1.2rem; font-weight: 600; font-size: .9rem;
      border-radius: 0 0 6px 6px; text-decoration: none;
      transition: top .15s;
    }
    .skip-link:focus { top: 0; }

    /* ── Accesibilidad: focus visible ─────────────────────────────────── */
    :focus-visible {
      outline: 2px solid var(--forest);
      outline-offset: 3px;
      border-radius: 3px;
    }

    /* metric numbers — always sans bold, never serif */
    .metric {
      font-family: 'DM Sans', sans-serif;
      font-weight: 800;
      font-variant-numeric: tabular-nums lining-nums;
      line-height: 1;
      letter-spacing: -.04em;
    }

    /* ── NAV principal (solo el nav[role="navigation"], NO los footer navs) ── */
    nav[role="navigation"] {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: .9rem 5%;
      background: #ffffff;
      box-shadow: 0 1px 0 var(--border), 0 2px 12px rgba(0,0,0,.06);
    }
    .logo {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem; color: var(--ink);
      text-decoration: none;
      display: flex; align-items: center; gap: .4rem;
    }
    .logo-dot { width: 8px; height: 8px; background: var(--forest); border-radius: 50%; }
    .nav-links { display: flex; gap: 2rem; list-style: none; }
    .nav-links a { color: var(--muted); text-decoration: none; font-size: .85rem; font-weight: 400; transition: color .2s; }
    .nav-links a:hover { color: var(--ink); }
    .nav-btn {
      background: var(--forest); color: #fff; border: none;
      padding: .55rem 1.3rem; border-radius: 5px;
      font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: .82rem;
      cursor: pointer; transition: background .2s, transform .18s, box-shadow .18s;
      text-decoration: none !important;
    }
    .nav-btn:hover { background: var(--forest2); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(26,92,40,.24); }

    /* Mobile hamburger */
    .nav-toggle {
      display: none; background: none; border: 2px solid var(--border);
      border-radius: 5px; padding: .4rem .6rem; cursor: pointer;
      color: var(--ink); line-height: 1;
    }
    .nav-toggle svg { display: block; }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: grid; grid-template-columns: 1.05fr .95fr;
      align-items: center;
      padding: 7rem 5% 4rem; gap: 3.5rem;
      border-bottom: 1px solid var(--border);
    }

    /* kicker */
    .kicker {
      display: inline-flex; align-items: center; gap: .55rem;
      font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .16em;
      color: var(--forest); margin-bottom: 1.3rem;
    }
    .kicker-line { display: inline-block; width: 20px; height: 1.5px; background: var(--forest); }

    h1 {
      font-family: 'DM Serif Display', serif;
      font-size: clamp(3rem, 5.2vw, 4.8rem);
      font-weight: 700;
      line-height: 1.04; letter-spacing: -.015em; color: var(--ink);
      margin-bottom: 1.3rem;
    }
    h1 em {
      font-style: italic;
      color: var(--forest);
      -webkit-text-stroke: 0.45px var(--forest);
      text-shadow: 0 0 0.5px var(--forest);
    }

    .hero-sub {
      font-size: 1rem; font-weight: 300; color: var(--muted);
      max-width: 430px; line-height: 1.8; margin-bottom: 2rem;
    }

    .hero-btns { display: flex; gap: .9rem; align-items: center; flex-wrap: wrap; margin-bottom: 2.5rem; }
    .btn-primary {
      background: var(--forest); color: #fff;
      padding: .85rem 1.9rem; border-radius: 5px;
      font-size: .9rem; font-weight: 600; text-decoration: none; display: inline-block;
      transition: background .2s, transform .18s, box-shadow .18s;
    }
    .btn-primary:hover { background: var(--forest2); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,92,40,.22); }
    .btn-secondary {
      color: var(--ink); font-size: .9rem; font-weight: 500;
      text-decoration: none; display: inline-flex; align-items: center; gap: .35rem;
      border-bottom: 1px solid var(--border); padding-bottom: 1px;
      transition: border-color .2s, color .2s, gap .2s;
    }
    .btn-secondary:hover { border-color: var(--forest); color: var(--forest); gap: .55rem; }

    /* trust row */
    .hero-trust {
      display: flex; align-items: center; gap: 1.6rem;
      padding-top: 1.8rem; border-top: 1px solid var(--border);
      flex-wrap: wrap;
    }
    .trust-avs { display: flex; }
    .tav {
      width: 28px; height: 28px; border-radius: 50%;
      border: 2px solid var(--bg); font-size: .6rem; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      color: #fff; margin-left: -7px;
    }
    .tav:first-child { margin-left: 0; }
    .ta { background: var(--forest); }
    .tb { background: #2b7a3e; }
    .tc { background: #3d8f52; }
    .trust-text { font-size: .8rem; font-weight: 400; color: var(--muted); }
    .trust-text strong { color: var(--ink); font-weight: 600; }
    .trust-sep { width: 1px; height: 22px; background: var(--border); }
    .trust-stat strong {
      display: block;
      font-size: 1.2rem; font-weight: 800; color: var(--forest);
      font-variant-numeric: tabular-nums; letter-spacing: -.03em; line-height: 1;
    }
    .trust-stat span { font-size: .7rem; color: var(--muted); font-weight: 400; }

    /* partner badges */
    .hero-badges { display: flex; gap: .6rem; flex-wrap: wrap; margin-bottom: 1.8rem; }
    .badge {
      display: inline-flex; align-items: center; gap: .4rem;
      background: var(--white); border: 1px solid var(--border);
      border-radius: 5px; padding: .35rem .75rem;
      font-size: .72rem; font-weight: 600; color: var(--muted);
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .badge-g .badge-dot { background: #4285f4; }
    .badge-m .badge-dot { background: #1877f2; }
    .badge-w .badge-dot { background: #21759b; }
    .badge-r .badge-dot { background: #f59e0b; }

    /* ── HERO RIGHT VISUAL ── */
    .hero-right { position: relative; height: 530px; }

    /* dashboard */
    .dash {
      position: absolute; top: 0; right: 0; width: 100%;
      background: var(--ink); border-radius: 14px; overflow: hidden;
      box-shadow: 0 32px 72px rgba(19,18,17,.18), 0 0 0 1px rgba(255,255,255,.04);
    }
    .dash-chrome {
      background: rgba(255,255,255,.04); padding: .6rem 1rem;
      display: flex; align-items: center; gap: .45rem;
      border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .dc { width: 9px; height: 9px; border-radius: 50%; }
    .dash-url {
      flex: 1; margin: 0 .7rem;
      background: rgba(255,255,255,.05); border-radius: 4px;
      padding: .22rem .7rem;
      font-size: .6rem; color: rgba(255,255,255,.22); font-weight: 400;
    }
    .dash-body { padding: 1.3rem; }
    .db-lbl { font-size: .6rem; font-weight: 600; text-transform: uppercase; letter-spacing: .14em; color: rgba(255,255,255,.22); margin-bottom: 1rem; }

    /* main metric */
    .db-main { margin-bottom: 1.2rem; }
    .db-num {
      font-family: 'DM Sans', sans-serif;
      font-weight: 800; font-size: 2.6rem;
      color: #78e08a; line-height: 1; letter-spacing: -.05em;
      font-variant-numeric: tabular-nums;
    }
    .db-num-lbl { font-size: .7rem; font-weight: 400; color: rgba(255,255,255,.32); margin-top: .3rem; }
    .db-delta { font-size: .7rem; font-weight: 700; color: #78e08a; margin-top: .35rem; }

    /* chart */
    .db-chart { display: flex; align-items: flex-end; gap: 3px; height: 46px; margin: 1rem 0; }
    .dbc { flex: 1; border-radius: 2px 2px 0 0; background: rgba(255,255,255,.07); }
    .dbc.hi { background: #78e08a; }
    .dbc.mid { background: rgba(120,224,138,.4); }

    /* mini metrics */
    .db-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: rgba(255,255,255,.05); border-radius: 7px; overflow: hidden; }
    .db-stat { background: rgba(255,255,255,.02); padding: .8rem .75rem; }
    .db-stat-n {
      font-family: 'DM Sans', sans-serif; font-weight: 800; font-size: 1.2rem;
      color: #fff; letter-spacing: -.04em; font-variant-numeric: tabular-nums; line-height: 1;
    }
    .db-stat-l { font-size: .58rem; font-weight: 500; color: rgba(255,255,255,.25); margin-top: .25rem; text-transform: uppercase; letter-spacing: .08em; }

    /* recent activity feed */
    .db-feed { margin-top: 1rem; }
    .db-feed-lbl { font-size: .58rem; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.18); margin-bottom: .55rem; }
    .db-feed-item {
      display: flex; align-items: center; gap: .6rem;
      padding: .45rem 0; border-top: 1px solid rgba(255,255,255,.04);
    }
    .feed-dot { width: 5px; height: 5px; border-radius: 50%; background: #78e08a; flex-shrink: 0; }
    .feed-dot.gold { background: #e8b96a; }
    .feed-text { font-size: .68rem; font-weight: 400; color: rgba(255,255,255,.42); flex: 1; }
    .feed-time { font-size: .6rem; color: rgba(255,255,255,.2); white-space: nowrap; }

    /* floating cards */
    .fc {
      position: absolute; background: var(--white);
      border: 1px solid var(--border); border-radius: 10px;
      padding: .85rem 1.05rem;
      box-shadow: 0 8px 28px rgba(0,0,0,.09);
    }
    .fc-a { bottom: 18px; left: -38px; animation: flt 4.2s ease-in-out infinite; }
    .fc-b { top: 85px; left: -42px; animation: flt 3.8s ease-in-out infinite reverse; }
    @keyframes flt { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
    .fc-tag { font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); margin-bottom: .3rem; }
    .fc-val {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 1.4rem; color: var(--ink); line-height: 1; letter-spacing: -.04em;
      font-variant-numeric: tabular-nums;
    }
    .fc-sub { font-size: .68rem; font-weight: 400; color: var(--muted); margin-top: .18rem; }
    .fc-pill {
      display: inline-block; margin-top: .4rem;
      background: var(--sage-bg); color: var(--forest);
      font-size: .6rem; font-weight: 700; padding: .2rem .55rem; border-radius: 100px;
      border: 1px solid #bcddc6;
    }

    /* notification bubble */
    .notif {
      position: absolute; bottom: 100px; right: -20px;
      background: var(--white); border: 1px solid var(--border);
      border-radius: 10px; padding: .7rem 1rem;
      box-shadow: 0 6px 20px rgba(0,0,0,.08);
      display: flex; align-items: center; gap: .6rem;
      animation: flt 5s ease-in-out infinite;
    }
    .notif-icon {
      width: 28px; height: 28px; border-radius: 50%;
      background: var(--sage-bg); border: 1px solid #bcddc6;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .notif-icon svg { width: 13px; height: 13px; }
    .notif-text { font-size: .7rem; font-weight: 500; color: var(--ink); white-space: nowrap; }
    .notif-sub { font-size: .62rem; color: var(--muted); font-weight: 300; }

    /* ── TICKER ── */
    .ticker { background: var(--forest); overflow: hidden; padding: .8rem 0; }
    .tk { display: flex; width: max-content; animation: tk 30s linear infinite; }
    @keyframes tk { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .tki {
      display: flex; align-items: center; gap: .6rem;
      padding: 0 1.8rem; white-space: nowrap;
      font-size: .73rem; font-weight: 600; text-transform: uppercase; letter-spacing: .13em;
      color: rgba(255,255,255,.55);
    }
    .tki span { color: rgba(255,255,255,.25); }

    /* ── LOGOS ── */
    .logos {
      background: var(--white); border-bottom: 1px solid var(--border);
      padding: 1.5rem 5%; display: flex; align-items: center; gap: 2.5rem; flex-wrap: wrap;
    }
    .logos-lbl { font-size: .67rem; font-weight: 600; text-transform: uppercase; letter-spacing: .16em; color: var(--border); white-space: nowrap; }
    .brand { font-size: .84rem; font-weight: 500; color: #bab6ae; letter-spacing: -.01em; cursor: default; transition: color .2s; }
    .brand:hover { color: var(--muted); }

    /* ── SHARED ── */
    section { padding: 5.5rem 5%; }
    .ey {
      display: inline-flex; align-items: center; gap: .5rem;
      font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: var(--forest);
      margin-bottom: .85rem;
    }
    .ey::before { content: ''; display: inline-block; width: 18px; height: 1.5px; background: var(--forest); }
    h2.d {
      font-family: 'DM Serif Display', serif;
      font-size: clamp(2rem, 3vw, 2.7rem); font-weight: 700; line-height: 1.1; letter-spacing: -.01em; color: var(--ink);
    }
    h2.d em { font-style: italic; color: var(--forest); -webkit-text-stroke: 0.45px currentColor; text-shadow: 0 0 0.5px currentColor; }

    /* ── WHY ── */
    .why { border-bottom: 1px solid var(--border); }
    .why-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; margin-top: 3.5rem; align-items: start; }
    .why-left h2 { margin-bottom: 1rem; }
    .why-left p { font-size: .9rem; font-weight: 300; color: var(--muted); max-width: 390px; line-height: 1.85; margin-bottom: 1.8rem; }
    .ba { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
    .ba-col { padding: 1.3rem; }
    .ba-col.after { background: var(--sage-bg); }
    .ba-head { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .14em; color: var(--muted); margin-bottom: .9rem; }
    .ba-col.after .ba-head { color: var(--forest); }
    .ba-row { display: flex; align-items: flex-start; gap: .45rem; font-size: .8rem; font-weight: 400; color: var(--muted); margin-bottom: .5rem; }
    .ba-row:last-child { margin-bottom: 0; }
    .baic { flex-shrink: 0; margin-top: .05rem; font-style: normal; font-size: .78rem; font-weight: 700; }
    .ba-row.neg .baic { color: #d94040; }
    .ba-row.pos .baic { color: var(--forest); }

    .why-right { display: flex; flex-direction: column; gap: 1px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
    .wi { background: var(--white); padding: 1.5rem 1.7rem; display: flex; gap: 1.1rem; align-items: flex-start; transition: background .2s; position: relative; }
    .wi::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--forest); transform: scaleY(0); transform-origin: top; transition: transform .22s ease; }
    .wi:hover { background: var(--bg); }
    .wi:hover::before { transform: scaleY(1); }
    .wi-n {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 1.4rem; color: rgba(26,92,40,.2); line-height: 1;
      flex-shrink: 0; min-width: 1.8rem;
      font-variant-numeric: tabular-nums; letter-spacing: -.04em;
      transition: color .2s;
    }
    .wi:hover .wi-n { color: rgba(26,92,40,.55); }
    .wi h4 { font-size: .87rem; font-weight: 600; color: var(--ink); margin-bottom: .28rem; }
    .wi p { font-size: .8rem; font-weight: 300; color: var(--muted); line-height: 1.65; }

    /* ── SERVICES ── */
    .services { background: var(--forest); }
    .services .ey { color: rgba(255,255,255,.55); }
    .services .ey::before { background: rgba(255,255,255,.3); }
    .services h2.d { color: #fff; margin-bottom: 2.8rem; }
    .services h2.d em { color: rgba(255,255,255,.5); font-style: italic; }
    .svc-grid {
      display: grid; grid-template-columns: repeat(3,1fr);
      gap: 1px; background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.1); border-radius: 8px; overflow: hidden;
    }
    .svc { background: var(--forest); padding: 1.9rem; transition: background .2s; }
    .svc:hover { background: var(--forest2); }
    .svc-n {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 1.7rem; color: rgba(255,255,255,.16);
      line-height: 1; margin-bottom: 1.1rem;
      font-variant-numeric: tabular-nums; letter-spacing: -.05em;
      transition: color .2s;
    }
    .svc:hover .svc-n { color: rgba(255,255,255,.28); }
    .svc h3 { font-size: .92rem; font-weight: 600; color: #fff; margin-bottom: .5rem; }
    .svc p { font-size: .82rem; font-weight: 400; color: rgba(255,255,255,.72); line-height: 1.7; }
    .svc-link { display: inline-flex; align-items: center; gap: .3rem; margin-top: 1.2rem; font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.45); transition: color .2s, gap .2s; text-decoration: none; cursor: pointer; }
    .svc:hover .svc-link { color: rgba(255,255,255,.92); gap: .55rem; }

    /* ── RESULTS ── */
    .results { border-top: 1px solid var(--border); }
    .results-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem; }
    .cases { display: grid; grid-template-columns: repeat(2,1fr); gap: 1.1rem; }
    .case { background: var(--white); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; transition: box-shadow .25s, transform .25s; }
    .case:hover { box-shadow: 0 12px 40px rgba(0,0,0,.07); transform: translateY(-3px); }
    .case-vis { height: 155px; position: relative; display: flex; align-items: flex-end; padding: 1rem; overflow: hidden; }
    .cv1 { background: linear-gradient(135deg, #0c2a12, #1a5c28); }
    .cv2 { background: linear-gradient(135deg, #0c1e30, #1a3f6e); }
    .cv3 { background: linear-gradient(135deg, #2a0c0c, #6e1c1c); }
    .cv4 { background: linear-gradient(135deg, #1e1c0c, #4a480e); }
    .case-bg-n {
      position: absolute; top: .3rem; right: .8rem;
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 5.5rem; color: rgba(255,255,255,.07);
      line-height: 1; pointer-events: none;
      font-variant-numeric: tabular-nums; letter-spacing: -.06em;
    }
    .mbars { position: absolute; top: 1rem; right: 1rem; display: flex; align-items: flex-end; gap: 3px; height: 48px; }
    .mb { width: 8px; border-radius: 2px 2px 0 0; opacity: .35; }
    .mb.hi { opacity: .9; }
    .case-pill {
      position: relative;
      background: rgba(255,255,255,.13); backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.82);
      font-size: .63rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
      padding: .26rem .72rem; border-radius: 100px;
    }
    .case-body { padding: 1.3rem; }
    .case-metric {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 2.1rem; color: var(--gold); line-height: 1;
      font-variant-numeric: tabular-nums; letter-spacing: -.05em;
    }
    .case-metric-lbl { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); margin-top: .28rem; }
    .case-body h3 { font-size: .87rem; font-weight: 600; color: var(--ink); margin: .85rem 0 .28rem; }
    .case-body p { font-size: .8rem; font-weight: 300; color: var(--muted); line-height: 1.65; }
    .case-tags { display: flex; gap: .4rem; flex-wrap: wrap; margin-top: .8rem; }
    .ctag { background: var(--bg); border: 1px solid var(--border); font-size: .66rem; font-weight: 500; color: var(--muted); padding: .18rem .58rem; border-radius: 100px; }

    /* ── PROCESS ── */
    .process { background: var(--sage-bg); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .process-grid { display: grid; grid-template-columns: 1fr 1.3fr; gap: 5.5rem; margin-top: 3rem; align-items: start; }
    .process-left h2 { margin-bottom: 1rem; }
    .process-left p { font-size: .9rem; font-weight: 300; color: var(--muted); line-height: 1.8; max-width: 310px; }
    .tl { position: relative; padding: 0; list-style: none !important; margin: 0 !important; }
    .tl li { list-style: none !important; }
    /* line passes through the center of every dot (7px = half of 14px dot) */
    .tl::before { content: ''; position: absolute; left: 7px; top: 10px; bottom: 10px; width: 1px; background: #bfddc8; }
    .tl-item { position: relative; padding-left: 2.4rem; padding-bottom: 2.2rem; }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot {
      position: absolute; left: 0; top: 4px;
      width: 14px; height: 14px;
      border: 1.5px solid #bfddc8; border-radius: 50%; background: var(--sage-bg);
    }
    .tl-dot.on { border-color: var(--forest); background: var(--forest); box-shadow: 0 0 0 3px rgba(26,92,40,.15); }
    .tl-wk { font-size: .63rem; font-weight: 800; text-transform: uppercase; letter-spacing: .14em; color: var(--forest); margin-bottom: .45rem; opacity: .8; }
    .tl-title { font-size: .9rem; font-weight: 600; color: var(--ink); margin-bottom: .35rem; }
    .tl-desc { font-size: .8rem; font-weight: 300; color: var(--muted); line-height: 1.7; }

    /* ── PRICING ── */
    .pricing { border-top: 1px solid var(--border); }
    .pricing-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem; }
    .pricing-note { font-size: .82rem; font-weight: 300; color: var(--muted); max-width: 260px; line-height: 1.65; }
    .plans { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .plan { background: var(--white); padding: 2.1rem; display: flex; flex-direction: column; }
    .plan.feat { background: var(--forest); }
    .plan-badge {
      display: inline-block; align-self: flex-start; margin-bottom: .9rem;
      background: #f5c842; color: #7a5800;
      border: 1px solid #e0b020;
      font-family: 'DM Sans', sans-serif;
      font-weight: 700;
      font-size: .72rem; letter-spacing: .08em;
      text-transform: uppercase;
      padding: .25rem .7rem; border-radius: 4px;
    }
    .plan-name { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: var(--muted); margin-bottom: 1rem; }
    .plan.feat .plan-name { color: rgba(255,255,255,.38); }
    .plan-price {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 3rem; color: var(--ink); line-height: 1; letter-spacing: -.06em;
      font-variant-numeric: tabular-nums;
    }
    .plan.feat .plan-price { color: #fff; }
    .plan-cur { font-family: 'DM Sans', sans-serif; font-size: 1.1rem; font-weight: 400; color: var(--muted); vertical-align: super; }
    .plan.feat .plan-cur { color: rgba(255,255,255,.35); }
    .plan-period { font-size: .74rem; font-weight: 300; color: var(--muted); margin-top: .3rem; }
    .plan.feat .plan-period { color: rgba(255,255,255,.32); }
    .plan-line { height: 1px; background: var(--border); margin: 1.5rem 0; }
    .plan.feat .plan-line { background: rgba(255,255,255,.12); }
    .plan-feats { list-style: none; display: flex; flex-direction: column; gap: .58rem; flex: 1; }
    .plan-feats li { display: flex; align-items: flex-start; gap: .55rem; font-size: .81rem; font-weight: 300; color: var(--ink2); }
    .plan.feat .plan-feats li { color: rgba(255,255,255,.7); font-weight: 400; }
    .pfy { color: var(--forest); font-style: normal; font-size: .85rem; flex-shrink: 0; margin-top: -.02rem; font-weight: 700; }
    .plan.feat .pfy { color: #78e08a; }
    .pfn { color: var(--border); font-size: .85rem; flex-shrink: 0; font-weight: 700; }
    .plan-btn { margin-top: 1.8rem; padding: .85rem; border-radius: 5px; font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: .85rem; cursor: pointer; border: none; width: 100%; transition: all .2s; }
    .pb-out { background: transparent; border: 1px solid var(--border); color: var(--ink); }
    .pb-out:hover { border-color: var(--forest); color: var(--forest); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.07); }
    .pb-white { background: #fff; color: var(--forest); }
    .pb-white:hover { opacity: .93; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(255,255,255,.18); }

    /* ── TESTIMONIALS ── */
    .testimonials { background: var(--sage-bg); border-top: 1px solid #bfddc8; border-bottom: 1px solid #bfddc8; }
    .test-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem; }
    .test-score-num {
      font-family: 'DM Sans', sans-serif; font-weight: 800;
      font-size: 2.4rem; color: var(--gold); line-height: 1; letter-spacing: -.05em;
      font-variant-numeric: tabular-nums;
    }
    .test-stars { font-size: .82rem; color: var(--gold); margin-top: .15rem; letter-spacing: .05em; }
    .test-lbl { font-size: .68rem; font-weight: 300; color: var(--muted); }
    .test-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.1rem; }
    .test-card { background: var(--white); border: 1px solid #c8e8d0; border-radius: 10px; padding: 1.7rem; transition: box-shadow .25s, transform .25s; }
    .test-card:hover { box-shadow: 0 12px 36px rgba(26,92,40,.13); transform: translateY(-3px); }
    .test-top { display: flex; align-items: center; gap: .75rem; margin-bottom: 1.1rem; }
    .test-av {
      width: 38px; height: 38px; border-radius: 50%;
      font-family: 'DM Sans', sans-serif; font-weight: 800; font-size: .82rem;
      color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .av1 { background: var(--forest); }
    .av2 { background: #1e3a70; }
    .av3 { background: #6e1e1e; }
    .test-name { font-size: .84rem; font-weight: 600; color: var(--ink); }
    .test-role { font-size: .72rem; font-weight: 300; color: var(--muted); }
    .test-s { font-size: .76rem; color: var(--gold); margin-bottom: .75rem; }
    .test-q { font-size: .84rem; font-weight: 300; color: var(--ink2); line-height: 1.78; font-style: italic; }

    /* ── FAQ ── */
    .faq { border-top: 1px solid var(--border); }
    .faq-inner { display: grid; grid-template-columns: 1fr 1.2fr; gap: 5rem; margin-top: 3rem; align-items: start; }
    .faq-left h2 { margin-bottom: 1rem; }
    .faq-left p { font-size: .88rem; font-weight: 300; color: var(--muted); line-height: 1.8; margin-bottom: 1.4rem; }
    .faq-link { color: var(--forest); font-size: .84rem; font-weight: 500; text-decoration: none; border-bottom: 1px solid #bfddc8; padding-bottom: 1px; transition: border-color .2s; }
    .faq-link:hover { border-color: var(--forest); }
    .faq-list { display: flex; flex-direction: column; }
    .faq-item {
      border-top: 1px solid var(--border);
      padding: 1.1rem 0 1.1rem 1rem;
      position: relative;
      transition: background .15s;
    }
    .faq-item:last-child { border-bottom: 1px solid var(--border); }
    /* left accent bar that grows in on open */
    .faq-item::before {
      content: ''; position: absolute; left: 0; top: 0; bottom: 0;
      width: 2px; background: var(--forest); border-radius: 1px;
      transform: scaleY(0); transform-origin: top;
      transition: transform .28s ease;
    }
    .faq-item.open::before { transform: scaleY(1); }
    .faq-q {
      font-size: .87rem !important; font-weight: 500 !important;
      font-family: 'DM Sans', sans-serif !important;
      color: var(--ink) !important; cursor: pointer !important;
      display: flex !important; justify-content: space-between !important; align-items: center !important;
      gap: 1rem !important; background: transparent !important;
      border: none !important; outline: none !important;
      padding: 0 !important; margin: 0 !important;
      width: 100% !important; text-align: left !important;
      appearance: none !important; -webkit-appearance: none !important;
      box-shadow: none !important; line-height: 1.5 !important;
    }
    .faq-item.open .faq-q { color: var(--forest) !important; }
    .faq-ic {
      font-size: .88rem !important; color: var(--muted) !important; flex-shrink: 0 !important;
      transition: transform .28s cubic-bezier(.34,1.3,.64,1), color .2s !important;
      font-style: normal !important; line-height: 1 !important;
      width: 20px !important; height: 20px !important;
      display: inline-flex !important; align-items: center !important; justify-content: center !important;
      border: 1px solid var(--border) !important; border-radius: 50% !important;
    }
    .faq-item.open .faq-ic { transform: rotate(45deg) !important; color: var(--forest) !important; border-color: rgba(26,92,40,.3) !important; background: rgba(26,92,40,.06) !important; }
    /* use max-height instead of display:none — avoids WordPress CSS fight and adds smooth animation */
    .faq-a {
      font-size: .82rem; font-weight: 300; color: var(--muted); line-height: 1.8;
      overflow: hidden; max-height: 0; opacity: 0; margin-top: 0;
      transition: max-height .4s cubic-bezier(.4,0,.2,1), opacity .3s ease, margin-top .3s ease;
      padding-right: 1.5rem; will-change: max-height, opacity;
    }
    .faq-item.open .faq-a { max-height: 300px; opacity: 1; margin-top: .65rem; }

    /* ── CTA / FORMULARIO ── */
    .cta { background: var(--forest); padding: 5.5rem 5%; position: relative; overflow: hidden; }
    .cta::after {
      content: ''; position: absolute; bottom: -80px; right: -80px;
      width: 340px; height: 340px; border: 1px solid rgba(255,255,255,.05); border-radius: 50%;
    }
    .cta::before {
      content: ''; position: absolute; bottom: -140px; right: -140px;
      width: 520px; height: 520px; border: 1px solid rgba(255,255,255,.03); border-radius: 50%;
    }
    .cta-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; position: relative; }
    .cta .ey { color: rgba(255,255,255,.38); }
    .cta .ey::before { background: rgba(255,255,255,.2); }
    .cta h2.d { color: #fff; margin-bottom: 1rem; }
    .cta h2.d em { color: rgba(255,255,255,.45); }
    .cta-desc { font-size: .9rem; font-weight: 300; color: rgba(255,255,255,.42); line-height: 1.8; }
    .cta-form { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; padding: 1.9rem; }
    .cta-ftitle { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: rgba(255,255,255,.28); margin-bottom: 1.2rem; }
    .cf-row { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-bottom: .7rem; }
    /* Label visualmente oculto pero legible por lectores de pantalla */
    .sr-only {
      position: absolute; width: 1px; height: 1px;
      padding: 0; margin: -1px; overflow: hidden;
      clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }
    .ci {
      width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
      color: #fff; padding: .85rem 1rem; border-radius: 6px;
      font-family: 'DM Sans', sans-serif; font-size: .87rem; font-weight: 300;
      outline: none; margin-bottom: .7rem; transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .ci::placeholder { color: rgba(255,255,255,.25); }
    .ci:focus { border-color: rgba(120,224,138,.5); background: rgba(255,255,255,.09); box-shadow: 0 0 0 3px rgba(120,224,138,.1); }
    .ci:focus-visible { outline: none; }
    .ci.is-invalid { border-color: #f87171; }
    .cf-row .ci { margin-bottom: 0; }
    .cta-sub {
      width: 100%; background: #fff; color: var(--forest); border: none;
      padding: .95rem; border-radius: 6px;
      font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: .88rem;
      cursor: pointer; margin-top: .4rem; transition: opacity .2s, transform .18s, box-shadow .18s;
    }
    .cta-sub:hover { opacity: .95; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,255,255,.2); }
    .cta-trust { font-size: .68rem; font-weight: 300; color: rgba(255,255,255,.2); text-align: center; margin-top: .75rem; }
    /* Feedback del formulario */
    .form-feedback {
      border-radius: 7px; padding: 1rem 1.2rem;
      font-size: .85rem; font-weight: 500; margin-bottom: 1rem;
      display: flex; align-items: center; gap: .6rem;
    }
    .form-success { background: rgba(120,224,138,.15); border: 1px solid rgba(120,224,138,.35); color: #78e08a; }
    .form-error   { background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.3); color: #f87171; }

    /* ── FOOTER ── */
    footer { background: var(--ink); padding: 3.5rem 5% 2rem; }
    .ft-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2rem; margin-bottom: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,.06); }
    .ft-logo { font-family: 'DM Serif Display', serif; font-size: 1.2rem; color: #fff; text-decoration: none; display: flex; align-items: center; gap: .4rem; margin-bottom: .8rem; }
    .ft-dot { width: 7px; height: 7px; background: var(--forest); border-radius: 50%; }
    .ft-desc { font-size: .8rem; font-weight: 300; color: rgba(255,255,255,.25); max-width: 230px; line-height: 1.7; }
    .ft-col h5 { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: rgba(255,255,255,.32); margin-bottom: .9rem; }
    .ft-col ul { list-style: none; display: flex; flex-direction: column; gap: .42rem; }
    .ft-col ul a { color: rgba(255,255,255,.35); text-decoration: none; font-size: .82rem; font-weight: 300; transition: color .2s; }
    .ft-col ul a:hover { color: rgba(255,255,255,.7); }
    .ft-bottom { display: flex; justify-content: space-between; font-size: .73rem; font-weight: 300; color: rgba(255,255,255,.17); flex-wrap: wrap; gap: .5rem; }
    .ft-bottom a { color: rgba(255,255,255,.25); text-decoration: none; }
    .ft-bottom a:hover { color: rgba(255,255,255,.5); }

    /* ── REVEAL ── */
    .reveal { opacity: 0; transform: translateY(14px); transition: opacity .5s ease, transform .5s ease; will-change: opacity, transform; }
    .reveal.visible { opacity: 1; transform: translateY(0); will-change: auto; }

    /* ── COOKIE BANNER ── */
    #nexus-cookie-banner {
      position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
      background: var(--ink); color: rgba(255,255,255,.7);
      border: 1px solid rgba(255,255,255,.08); border-radius: 10px;
      padding: 1rem 1.5rem; max-width: 560px; width: calc(100% - 3rem);
      display: flex; align-items: center; gap: 1.2rem; flex-wrap: wrap;
      box-shadow: 0 12px 40px rgba(0,0,0,.3);
      font-size: .8rem; line-height: 1.55;
      z-index: 9000;
      transition: opacity .3s, transform .3s;
    }
    #nexus-cookie-banner.hidden { opacity: 0; pointer-events: none; transform: translateX(-50%) translateY(20px); }
    #nexus-cookie-banner p { flex: 1; margin: 0; min-width: 200px; }
    #nexus-cookie-banner a { color: #78e08a; text-decoration: underline; }
    .cookie-btns { display: flex; gap: .6rem; flex-shrink: 0; flex-wrap: wrap; }
    .cookie-accept {
      background: var(--forest); color: #fff; border: none;
      padding: .5rem 1.1rem; border-radius: 5px;
      font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: .78rem;
      cursor: pointer; transition: background .2s;
    }
    .cookie-accept:hover { background: var(--forest2); }
    .cookie-decline {
      background: transparent; color: rgba(255,255,255,.35); border: 1px solid rgba(255,255,255,.12);
      padding: .5rem 1rem; border-radius: 5px;
      font-family: 'DM Sans', sans-serif; font-weight: 400; font-size: .78rem;
      cursor: pointer; transition: color .2s, border-color .2s;
    }
    .cookie-decline:hover { color: rgba(255,255,255,.6); border-color: rgba(255,255,255,.25); }

    /* ── RESPONSIVE ── */
    @media (max-width: 960px) {
      .hero, .why-grid, .process-grid, .faq-inner, .cta-inner { grid-template-columns: 1fr; gap: 2.5rem; }
      .hero-right { display: none; }
      .svc-grid, .test-grid, .plans { grid-template-columns: 1fr; }
      .cases { grid-template-columns: 1fr; }
      .ft-top { grid-template-columns: 1fr 1fr; }
      /* Mobile nav */
      .nav-links {
        display: none; flex-direction: column; gap: 0;
        position: absolute; top: 100%; left: 0; right: 0;
        background: rgba(244,242,238,.97); backdrop-filter: blur(18px);
        border-bottom: 1px solid var(--border);
        padding: .5rem 5%;
      }
      .nav-links.is-open { display: flex; }
      .nav-links li { border-bottom: 1px solid var(--border); }
      .nav-links a { display: block; padding: .8rem 0; font-size: .9rem; }
      .nav-toggle { display: flex; align-items: center; }
      .nav-btn { display: none; }
    }
    @media (max-width: 580px) {
      .ft-top { grid-template-columns: 1fr; }
      .cf-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- ── Skip link: accesibilidad (WCAG 2.1 — 2.4.1) ─────────────────── -->
  <a class="skip-link" href="#contenido-principal">Saltar al contenido principal</a>

  <!-- ── NAV ──────────────────────────────────────────────────────────── -->
  <nav role="navigation" aria-label="Navegación principal">
    <a href="<?php echo esc_url( $nexus_site_url ); ?>" class="logo" aria-label="Nexus Agency — inicio">
      Nexus <span class="logo-dot" aria-hidden="true"></span>
    </a>

    <ul class="nav-links" id="nav-menu" role="list">
      <li><a href="#servicios">Servicios</a></li>
      <li><a href="#resultados">Resultados</a></li>
      <li><a href="#precios">Precios</a></li>
      <li><a href="#contacto">Contacto</a></li>
    </ul>

    <button
      class="nav-toggle"
      aria-expanded="false"
      aria-controls="nav-menu"
      aria-label="Abrir menú de navegación"
    >
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
        <rect y="3"  width="20" height="2" rx="1" fill="currentColor"/>
        <rect y="9"  width="20" height="2" rx="1" fill="currentColor"/>
        <rect y="15" width="20" height="2" rx="1" fill="currentColor"/>
      </svg>
    </button>

    <a href="#contacto" class="nav-btn" role="button">Hablar con un especialista</a>
  </nav>

  <!-- ── MAIN ─────────────────────────────────────────────────────────── -->
  <main id="contenido-principal">

    <!-- HERO -->
    <section class="hero" aria-label="Portada">
      <div class="hero-left reveal">
        <div class="kicker" aria-hidden="true"><span class="kicker-line"></span> Agencia de Marketing Digital</div>
        <h1>Más clientes.<br>Menos <em>esfuerzo</em><br>de tu parte.</h1>
        <p class="hero-sub">Nos encargamos de tu presencia digital completa — desde el primer clic hasta el cliente que paga — para que vos te concentres en lo que sabés hacer.</p>

        <div class="hero-badges" role="list" aria-label="Certificaciones y asociaciones">
          <span class="badge badge-g" role="listitem"><span class="badge-dot" aria-hidden="true"></span>Google Partner</span>
          <span class="badge badge-m" role="listitem"><span class="badge-dot" aria-hidden="true"></span>Meta Business Partner</span>
          <span class="badge badge-w" role="listitem"><span class="badge-dot" aria-hidden="true"></span>WooCommerce Expert</span>
          <span class="badge badge-r" role="listitem"><span class="badge-dot" aria-hidden="true"></span>4.9 ★ en Workana</span>
        </div>

        <div class="hero-btns">
          <a href="#contacto" class="btn-primary">Diagnóstico gratuito →</a>
          <a href="#resultados" class="btn-secondary">Ver resultados reales</a>
        </div>

        <div class="hero-trust" aria-label="Estadísticas de confianza">
          <div class="trust-avs" aria-hidden="true">
            <div class="tav ta">MR</div>
            <div class="tav tb">LG</div>
            <div class="tav tc">PT</div>
          </div>
          <p class="trust-text"><strong>60+ negocios</strong> creciendo con nosotros hoy</p>
          <div class="trust-sep" aria-hidden="true"></div>
          <div class="trust-stat">
            <strong>+120%</strong>
            <span>ROI promedio</span>
          </div>
          <div class="trust-sep" aria-hidden="true"></div>
          <div class="trust-stat">
            <strong>30 días</strong>
            <span>primeros resultados</span>
          </div>
        </div>
      </div>

      <!-- RIGHT VISUAL (decorativo) -->
      <div class="hero-right reveal" aria-hidden="true">
        <div class="fc fc-b">
          <div class="fc-tag">Posición Google</div>
          <div class="fc-val">#1</div>
          <div class="fc-sub">"dentista córdoba centro"</div>
          <span class="fc-pill">Antes estaba en #14</span>
        </div>
        <div class="dash">
          <div class="dash-chrome">
            <div class="dc" style="background:#3a3a3a"></div>
            <div class="dc" style="background:#3a3a3a"></div>
            <div class="dc" style="background:#3a3a3a"></div>
            <div class="dash-url">analytics.nexusagency.com · Panel de clientes</div>
          </div>
          <div class="dash-body">
            <div class="db-lbl">Resumen · Últimos 30 días · todos los clientes</div>
            <div class="db-main">
              <div class="db-num">$48.290</div>
              <div class="db-num-lbl">Facturación generada para clientes activos</div>
              <div class="db-delta">↑ +127% respecto al mes anterior</div>
            </div>
            <div class="db-chart">
              <div class="dbc" style="height:30%"></div>
              <div class="dbc" style="height:42%"></div>
              <div class="dbc mid" style="height:38%"></div>
              <div class="dbc" style="height:54%"></div>
              <div class="dbc mid" style="height:48%"></div>
              <div class="dbc" style="height:67%"></div>
              <div class="dbc mid" style="height:60%"></div>
              <div class="dbc" style="height:78%"></div>
              <div class="dbc hi" style="height:100%"></div>
            </div>
            <div class="db-stats">
              <div class="db-stat"><div class="db-stat-n">340%</div><div class="db-stat-l">Tráfico org.</div></div>
              <div class="db-stat"><div class="db-stat-n">4.2×</div><div class="db-stat-l">ROAS Ads</div></div>
              <div class="db-stat"><div class="db-stat-n">38%</div><div class="db-stat-l">Email open</div></div>
            </div>
            <div class="db-feed">
              <div class="db-feed-lbl">Actividad reciente</div>
              <div class="db-feed-item">
                <div class="feed-dot"></div>
                <div class="feed-text">Clínica Rivas — nueva consulta desde Google Ads</div>
                <div class="feed-time">hace 4 min</div>
              </div>
              <div class="db-feed-item">
                <div class="feed-dot gold"></div>
                <div class="feed-text">Moda Urbana — venta completada $12.400</div>
                <div class="feed-time">hace 11 min</div>
              </div>
              <div class="db-feed-item">
                <div class="feed-dot"></div>
                <div class="feed-text">Torres Inmob. — lead calificado desde Meta</div>
                <div class="feed-time">hace 23 min</div>
              </div>
            </div>
          </div>
        </div>
        <div class="fc fc-a">
          <div class="fc-tag">Meta Ads · ROAS</div>
          <div class="fc-val">4.2×</div>
          <div class="fc-sub">Inmobiliaria Torres</div>
          <span class="fc-pill">Objetivo superado</span>
        </div>
        <div class="notif">
          <div class="notif-icon">
            <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M7 1L8.5 5.5H13L9.5 8L11 12.5L7 10L3 12.5L4.5 8L1 5.5H5.5L7 1Z" fill="#1a5c28"/>
            </svg>
          </div>
          <div>
            <div class="notif-text">Nuevo cliente onboarded</div>
            <div class="notif-sub">RestoBelgrano · Plan Growth · hace 2h</div>
          </div>
        </div>
      </div>
    </section>

    <!-- LOGOS -->
    <div class="logos" role="list" aria-label="Clientes activos">
      <span class="logos-lbl" aria-hidden="true">Clientes activos</span>
      <span class="brand" role="listitem">Clínica Rivas</span>
      <span class="brand" role="listitem">Moda Urbana</span>
      <span class="brand" role="listitem">Torres Inmobiliaria</span>
      <span class="brand" role="listitem">RestoBelgrano</span>
      <span class="brand" role="listitem">Estudio Paz</span>
      <span class="brand" role="listitem">FitZona Gym</span>
      <span class="brand" role="listitem">Auto Centro Norte</span>
    </div>

    <!-- TICKER -->
    <div class="ticker" aria-hidden="true">
      <div class="tk">
        <span class="tki"><span>—</span> SEO Local</span>
        <span class="tki"><span>—</span> Google Ads</span>
        <span class="tki"><span>—</span> Meta Ads</span>
        <span class="tki"><span>—</span> Diseño Web</span>
        <span class="tki"><span>—</span> Email Marketing</span>
        <span class="tki"><span>—</span> WooCommerce</span>
        <span class="tki"><span>—</span> Analítica</span>
        <span class="tki"><span>—</span> Branding</span>
        <span class="tki"><span>—</span> SEO Local</span>
        <span class="tki"><span>—</span> Google Ads</span>
        <span class="tki"><span>—</span> Meta Ads</span>
        <span class="tki"><span>—</span> Diseño Web</span>
        <span class="tki"><span>—</span> Email Marketing</span>
        <span class="tki"><span>—</span> WooCommerce</span>
        <span class="tki"><span>—</span> Analítica</span>
        <span class="tki"><span>—</span> Branding</span>
      </div>
    </div>

    <!-- WHY -->
    <section class="why" id="nosotros" aria-labelledby="why-heading">
      <div class="why-grid">
        <div class="why-left reveal">
          <div class="ey" aria-hidden="true">Por qué elegirnos</div>
          <h2 class="d" id="why-heading">No somos otra agencia que desaparece después de cobrar.</h2>
          <p>Sabemos que ya tuviste malas experiencias. Reportes imposibles de entender, promesas sin resultados, soporte que no responde. Mirá la diferencia:</p>
          <div class="ba" role="table" aria-label="Comparación: otras agencias vs Nexus Agency">
            <div class="ba-col" role="rowgroup">
              <div class="ba-head" role="columnheader">Otras agencias</div>
              <div class="ba-row neg" role="row"><i class="baic" aria-label="No">✕</i> Reportes en Excel una vez al mes</div>
              <div class="ba-row neg" role="row"><i class="baic" aria-label="No">✕</i> Contratos de 6 a 12 meses</div>
              <div class="ba-row neg" role="row"><i class="baic" aria-label="No">✕</i> Soporte por ticket (48–72h)</div>
              <div class="ba-row neg" role="row"><i class="baic" aria-label="No">✕</i> Estrategia genérica para todos</div>
              <div class="ba-row neg" role="row"><i class="baic" aria-label="No">✕</i> Resultados "en unos meses"</div>
            </div>
            <div class="ba-col after" role="rowgroup">
              <div class="ba-head" role="columnheader">Nexus Agency</div>
              <div class="ba-row pos" role="row"><i class="baic" aria-label="Sí">✓</i> Dashboard en tiempo real</div>
              <div class="ba-row pos" role="row"><i class="baic" aria-label="Sí">✓</i> Mes a mes, sin ataduras</div>
              <div class="ba-row pos" role="row"><i class="baic" aria-label="Sí">✓</i> WhatsApp directo (&lt;4h)</div>
              <div class="ba-row pos" role="row"><i class="baic" aria-label="Sí">✓</i> Plan 100% personalizado</div>
              <div class="ba-row pos" role="row"><i class="baic" aria-label="Sí">✓</i> KPIs claros desde el día 1</div>
            </div>
          </div>
        </div>
        <div class="why-right reveal">
          <div class="wi"><div class="wi-n" aria-hidden="true">01</div><div><h4>Diagnóstico gratuito antes de cobrar</h4><p>Analizamos tu negocio, tus competidores y las oportunidades reales. Sin cargo ni compromiso.</p></div></div>
          <div class="wi"><div class="wi-n" aria-hidden="true">02</div><div><h4>Resultados medibles en 30 días</h4><p>KPIs concretos desde el día 1. Si no hay movimiento, ajustamos sin costo adicional.</p></div></div>
          <div class="wi"><div class="wi-n" aria-hidden="true">03</div><div><h4>Un solo contacto, siempre disponible</h4><p>Tu account manager por WhatsApp. Respuesta real en menos de 4 horas en horario laboral.</p></div></div>
          <div class="wi"><div class="wi-n" aria-hidden="true">04</div><div><h4>Sin contratos de permanencia</h4><p>Mes a mes. Si no estás satisfecho, cancelás sin penalidad. Confiamos en resultados, no en contratos.</p></div></div>
        </div>
      </div>
    </section>

    <!-- SERVICES -->
    <section class="services" id="servicios" aria-labelledby="svc-heading">
      <div class="ey reveal" aria-hidden="true">Servicios</div>
      <h2 class="d reveal" id="svc-heading">Todo lo que necesitás<br><em>para crecer online.</em></h2>
      <br><br>
      <div class="svc-grid">
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">01</div><h3>SEO Local y Nacional</h3><p>Aparecé primero cuando te buscan. Auditoría técnica, contenido optimizado y construcción de autoridad de dominio.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre SEO Local y Nacional">Ver más →</a></article>
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">02</div><h3>Google y Meta Ads</h3><p>Campañas que generan consultas reales. Segmentación precisa y optimización continua para bajar el costo por cliente.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre Google y Meta Ads">Ver más →</a></article>
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">03</div><h3>Diseño Web que convierte</h3><p>Tu sitio es tu vendedor 24/7. Lo diseñamos para que cada visita tenga una razón clara para contactarte.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre Diseño Web">Ver más →</a></article>
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">04</div><h3>Email Marketing automatizado</h3><p>Nutrición de leads y seguimiento post-venta. Tus clientes actuales son tu activo más valioso y más ignorado.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre Email Marketing">Ver más →</a></article>
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">05</div><h3>Tienda WooCommerce</h3><p>E-commerce con pasarela de pagos, gestión de stock y experiencia de compra optimizada para móvil.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre WooCommerce">Ver más →</a></article>
        <article class="svc reveal"><div class="svc-n" aria-hidden="true">06</div><h3>Analítica y Reportes</h3><p>Dashboard en tiempo real en Looker Studio. Datos reales para decisiones inteligentes, sin Excel imposibles.</p><a class="svc-link" href="#contacto" aria-label="Ver más sobre Analítica y Reportes">Ver más →</a></article>
      </div>
    </section>

    <!-- RESULTS -->
    <section class="results" id="resultados" aria-labelledby="results-heading">
      <div class="results-top">
        <div class="reveal">
          <div class="ey" aria-hidden="true">Resultados reales</div>
          <h2 class="d" id="results-heading">Números de clientes <em>actuales.</em><br>No promesas.</h2>
        </div>
        <a href="#contacto" class="btn-primary reveal">Quiero resultados así →</a>
      </div>
      <div class="cases">
        <article class="case reveal">
          <div class="case-vis cv1" aria-hidden="true">
            <div class="case-bg-n">+340</div>
            <div class="mbars"><div class="mb" style="height:28%;background:#78e08a"></div><div class="mb" style="height:42%;background:#78e08a"></div><div class="mb" style="height:56%;background:#78e08a"></div><div class="mb" style="height:70%;background:#78e08a"></div><div class="mb hi" style="height:100%;background:#78e08a"></div></div>
            <span class="case-pill">SEO Local</span>
          </div>
          <div class="case-body">
            <div class="case-metric" aria-label="Resultado: +340%">+340%</div>
            <div class="case-metric-lbl">Tráfico orgánico</div>
            <h3>Clínica Dental Rivas — Córdoba</h3>
            <p>De la página 3 a posición #1 para "dentista Córdoba centro". 6 meses de trabajo. Abrieron una segunda sede.</p>
            <div class="case-tags"><span class="ctag">SEO</span><span class="ctag">6 meses</span><span class="ctag">Salud</span></div>
          </div>
        </article>
        <article class="case reveal">
          <div class="case-vis cv2" aria-hidden="true">
            <div class="case-bg-n">3.8×</div>
            <div class="mbars"><div class="mb" style="height:22%;background:#7eaaff"></div><div class="mb" style="height:45%;background:#7eaaff"></div><div class="mb" style="height:62%;background:#7eaaff"></div><div class="mb hi" style="height:100%;background:#7eaaff"></div><div class="mb" style="height:88%;background:#7eaaff"></div></div>
            <span class="case-pill">Meta Ads + WooCommerce</span>
          </div>
          <div class="case-body">
            <div class="case-metric" aria-label="Resultado: 3.8x retorno">3.8×</div>
            <div class="case-metric-lbl">Retorno sobre inversión</div>
            <h3>Moda Urbana — Buenos Aires</h3>
            <p>Tienda online lanzada en 3 semanas. En 90 días recuperaron la inversión completa y siguen creciendo.</p>
            <div class="case-tags"><span class="ctag">Meta Ads</span><span class="ctag">WooCommerce</span><span class="ctag">Retail</span></div>
          </div>
        </article>
        <article class="case reveal">
          <div class="case-vis cv3" aria-hidden="true">
            <div class="case-bg-n">4.2×</div>
            <div class="mbars"><div class="mb" style="height:35%;background:#ffaa7e"></div><div class="mb" style="height:56%;background:#ffaa7e"></div><div class="mb hi" style="height:100%;background:#ffaa7e"></div><div class="mb" style="height:88%;background:#ffaa7e"></div><div class="mb" style="height:74%;background:#ffaa7e"></div></div>
            <span class="case-pill">Google Ads + Email</span>
          </div>
          <div class="case-body">
            <div class="case-metric" aria-label="Resultado: 4.2x ROAS">4.2×</div>
            <div class="case-metric-lbl">ROAS en Meta Ads</div>
            <h3>Inmobiliaria Torres — CABA</h3>
            <p>ROAS 4.2x y tasa de apertura de email del 38%. Más leads calificados con el mismo presupuesto.</p>
            <div class="case-tags"><span class="ctag">Google Ads</span><span class="ctag">Email</span><span class="ctag">Inmobiliaria</span></div>
          </div>
        </article>
        <article class="case reveal">
          <div class="case-vis cv4" aria-hidden="true">
            <div class="case-bg-n">+210</div>
            <div class="mbars"><div class="mb" style="height:20%;background:#ffe07e"></div><div class="mb" style="height:38%;background:#ffe07e"></div><div class="mb" style="height:58%;background:#ffe07e"></div><div class="mb" style="height:78%;background:#ffe07e"></div><div class="mb hi" style="height:100%;background:#ffe07e"></div></div>
            <span class="case-pill">Diseño Web + SEO</span>
          </div>
          <div class="case-body">
            <div class="case-metric" aria-label="Resultado: +210% reservas">+210%</div>
            <div class="case-metric-lbl">Reservas online</div>
            <h3>RestoBelgrano — CABA</h3>
            <p>Nuevo sitio y Google Ads en 45 días. Las reservas se triplicaron y el costo por reserva bajó un 60%.</p>
            <div class="case-tags"><span class="ctag">Web</span><span class="ctag">SEO</span><span class="ctag">Gastronomía</span></div>
          </div>
        </article>
      </div>
    </section>

    <!-- PROCESS -->
    <section class="process" id="proceso" aria-labelledby="process-heading">
      <div class="process-grid">
        <div class="process-left reveal">
          <div class="ey" aria-hidden="true">Nuestro proceso</div>
          <h2 class="d" id="process-heading">Así trabajamos<br><em>desde el día 1.</em></h2>
          <p>Sin vueltas. Sabés exactamente qué pasa en cada etapa y cuándo esperar resultados.</p>
        </div>
        <ol class="tl reveal" aria-label="Etapas del proceso">
          <li class="tl-item">
            <div class="tl-dot on" aria-hidden="true"></div>
            <div class="tl-wk">Semana 1</div>
            <div class="tl-title">Diagnóstico gratuito</div>
            <div class="tl-desc">Analizamos tu negocio, tus competidores y las oportunidades que estás perdiendo. Sin cargo, sin compromiso.</div>
          </li>
          <li class="tl-item">
            <div class="tl-dot on" aria-hidden="true"></div>
            <div class="tl-wk">Semana 1 — 2</div>
            <div class="tl-title">Estrategia y metas claras</div>
            <div class="tl-desc">Plan con KPIs concretos antes de firmar nada. Sabés exactamente qué esperar y cuándo.</div>
          </li>
          <li class="tl-item">
            <div class="tl-dot on" aria-hidden="true"></div>
            <div class="tl-wk">Mes 1</div>
            <div class="tl-title">Implementación</div>
            <div class="tl-desc">Arrancamos: campañas, contenido, optimizaciones técnicas. Actualizaciones semanales en todo momento.</div>
          </li>
          <li class="tl-item">
            <div class="tl-dot on" aria-hidden="true"></div>
            <div class="tl-wk">Mes 2 en adelante</div>
            <div class="tl-title">Optimización continua</div>
            <div class="tl-desc">Medimos, aprendemos y mejoramos cada semana. El marketing digital requiere atención constante.</div>
          </li>
        </ol>
      </div>
    </section>

    <!-- PRICING -->
    <section class="pricing" id="precios" aria-labelledby="pricing-heading">
      <div class="pricing-top">
        <div class="reveal">
          <div class="ey" aria-hidden="true">Planes</div>
          <h2 class="d" id="pricing-heading">Precios claros.<br><em>Sin sorpresas.</em></h2>
        </div>
        <p class="pricing-note reveal">Sin contratos anuales. Cancelá cuando quieras. El plan crece con tu negocio.</p>
      </div>
      <div class="plans">
        <div class="plan reveal">
          <div class="plan-name">Starter</div>
          <div class="plan-price"><span class="plan-cur">$</span>299</div>
          <div class="plan-period">USD por mes · facturación mensual</div>
          <div class="plan-line" aria-hidden="true"></div>
          <ul class="plan-feats">
            <li><i class="pfy" aria-label="Incluido">✓</i> SEO on-page (hasta 10 páginas)</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> 1 campaña Google Ads activa</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Reporte mensual de resultados</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Soporte por email (48h)</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Auditoría inicial gratuita</li>
            <li><i class="pfn" aria-label="No incluido">✕</i> Meta Ads</li>
            <li><i class="pfn" aria-label="No incluido">✕</i> Email marketing</li>
            <li><i class="pfn" aria-label="No incluido">✕</i> Account manager dedicado</li>
          </ul>
          <button class="plan-btn pb-out" type="button" aria-label="Empezar con el plan Starter a USD 299 por mes">Empezar con Starter</button>
        </div>
        <div class="plan feat reveal">
          <div class="plan-badge">Más elegido</div>
          <div class="plan-name">Growth</div>
          <div class="plan-price"><span class="plan-cur">$</span>599</div>
          <div class="plan-period">USD por mes · facturación mensual</div>
          <div class="plan-line" aria-hidden="true"></div>
          <ul class="plan-feats">
            <li><i class="pfy" aria-label="Incluido">✓</i> SEO completo + link building</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Google Ads + Meta Ads</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Email marketing básico</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Landing page incluida</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Reporte semanal detallado</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Soporte WhatsApp (&lt;4h)</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Account manager asignado</li>
            <li><i class="pfn" aria-label="No incluido">✕</i> Tienda WooCommerce</li>
          </ul>
          <button class="plan-btn pb-white" type="button" aria-label="Empezar con el plan Growth a USD 599 por mes">Empezar con Growth</button>
        </div>
        <div class="plan reveal">
          <div class="plan-name">Agency</div>
          <div class="plan-price"><span class="plan-cur">$</span>999</div>
          <div class="plan-period">USD por mes · facturación mensual</div>
          <div class="plan-line" aria-hidden="true"></div>
          <ul class="plan-feats">
            <li><i class="pfy" aria-label="Incluido">✓</i> Todo lo de Growth</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Sitio web o tienda completa</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Email marketing avanzado</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Dashboard Looker Studio</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Reunión quincenal de estrategia</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Soporte prioritario 24h</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Copywriting incluido</li>
            <li><i class="pfy" aria-label="Incluido">✓</i> Contenido mensual</li>
          </ul>
          <button class="plan-btn pb-out" type="button" aria-label="Empezar con el plan Agency a USD 999 por mes">Empezar con Agency</button>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials" id="testimonios" aria-labelledby="test-heading">
      <div class="test-head">
        <div class="reveal">
          <div class="ey" aria-hidden="true">Testimonios</div>
          <h2 class="d" id="test-heading">Lo que dicen quienes ya<br>trabajan con nosotros.</h2>
        </div>
        <div class="reveal" style="text-align:right" aria-label="Calificación promedio: 4.9 de 5 estrellas basado en 60 clientes">
          <div class="test-score-num">4.9</div>
          <div class="test-stars" aria-hidden="true">★★★★★</div>
          <div class="test-lbl">promedio · 60+ clientes</div>
        </div>
      </div>
      <div class="test-grid">
        <article class="test-card reveal">
          <div class="test-top">
            <div class="test-av av1" aria-hidden="true">MR</div>
            <div><div class="test-name">Martín Rivas</div><div class="test-role">Director · Clínica Dental Rivas</div></div>
          </div>
          <div class="test-s" aria-label="5 estrellas">★★★★★</div>
          <blockquote class="test-q">"Llevábamos 2 años sin crecer. En 4 meses con Nexus triplicamos las consultas online y abrimos una segunda sede. Entendieron el negocio real, no solo el marketing."</blockquote>
        </article>
        <article class="test-card reveal">
          <div class="test-top">
            <div class="test-av av2" aria-hidden="true">LG</div>
            <div><div class="test-name">Laura González</div><div class="test-role">Fundadora · Moda Urbana</div></div>
          </div>
          <div class="test-s" aria-label="5 estrellas">★★★★★</div>
          <blockquote class="test-q">"Nunca había invertido en publicidad digital. Me explicaron todo en lenguaje simple, armaron la tienda en 3 semanas y el primer mes ya recuperé la inversión."</blockquote>
        </article>
        <article class="test-card reveal">
          <div class="test-top">
            <div class="test-av av3" aria-hidden="true">PT</div>
            <div><div class="test-name">Pablo Torres</div><div class="test-role">CEO · Inmobiliaria Torres</div></div>
          </div>
          <div class="test-s" aria-label="5 estrellas">★★★★★</div>
          <blockquote class="test-q">"Lo que más valoro es la transparencia. Cada peso invertido en ads lo veo reflejado en el dashboard. No hay humo, hay resultados medibles y respuesta en horas."</blockquote>
        </article>
      </div>
    </section>

    <!-- FAQ -->
    <section class="faq" aria-labelledby="faq-heading">
      <div class="faq-inner">
        <div class="faq-left reveal">
          <div class="ey" aria-hidden="true">Preguntas frecuentes</div>
          <h2 class="d" id="faq-heading">Todo lo que querés saber antes de empezar.</h2>
          <p>Si tu pregunta no está acá, escribinos. Respondemos en menos de 4 horas en horario laboral.</p>
          <a href="#contacto" class="faq-link">Hacer una pregunta →</a>
        </div>
        <div class="faq-list reveal" role="list">
          <div class="faq-item open" role="listitem">
            <button class="faq-q" aria-expanded="true" aria-controls="faq-a-1">¿Cuánto tiempo tarda en verse resultados? <i class="faq-ic" aria-hidden="true">+</i></button>
            <div class="faq-a" id="faq-a-1" role="region">Depende del servicio. Google Ads y Meta Ads pueden generar consultas desde la primera semana. SEO requiere entre 2 y 4 meses para posicionarse de forma sostenida. Siempre te lo decimos antes de arrancar.</div>
          </div>
          <div class="faq-item" role="listitem">
            <button class="faq-q" aria-expanded="false" aria-controls="faq-a-2">¿Hay contrato de permanencia? <i class="faq-ic" aria-hidden="true">+</i></button>
            <div class="faq-a" id="faq-a-2" role="region">No. Trabajamos mes a mes. Si en cualquier momento no estás satisfecho, podés cancelar sin penalidad. Confiamos en los resultados, no en los contratos.</div>
          </div>
          <div class="faq-item" role="listitem">
            <button class="faq-q" aria-expanded="false" aria-controls="faq-a-3">¿El presupuesto de las campañas está incluido? <i class="faq-ic" aria-hidden="true">+</i></button>
            <div class="faq-a" id="faq-a-3" role="region">No. Los planes cubren el servicio de gestión. El presupuesto de ads va aparte y lo manejás vos directamente. Así es 100% transparente — el dinero en ads es tuyo.</div>
          </div>
          <div class="faq-item" role="listitem">
            <button class="faq-q" aria-expanded="false" aria-controls="faq-a-4">¿Qué pasa si ya tengo una agencia? <i class="faq-ic" aria-hidden="true">+</i></button>
            <div class="faq-a" id="faq-a-4" role="region">Podemos auditar lo que tenés actualmente. Muchas veces encontramos oportunidades claras de mejora. Si decidís cambiar, la transición es simple y sin pérdida de historial.</div>
          </div>
          <div class="faq-item" role="listitem">
            <button class="faq-q" aria-expanded="false" aria-controls="faq-a-5">¿Con qué tipo de negocios trabajan? <i class="faq-ic" aria-hidden="true">+</i></button>
            <div class="faq-a" id="faq-a-5" role="region">PYMEs locales: salud, inmobiliaria, gastronomía, retail y servicios profesionales. Si tu rubro no está en la lista, hacemos una llamada gratuita y te decimos honestamente si podemos ayudarte.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA / CONTACTO -->
    <section class="cta" id="contacto" aria-labelledby="cta-heading">
      <div class="cta-inner">
        <div class="reveal">
          <div class="ey" aria-hidden="true">Empezá hoy</div>
          <h2 class="d" id="cta-heading">¿Listo para conseguir <em>más clientes?</em></h2>
          <p class="cta-desc">Completá el formulario y un especialista te contacta en menos de 24 horas para hacer el diagnóstico gratuito. Sin cargo, sin compromiso.</p>
        </div>

        <div class="cta-form reveal">
          <div class="cta-ftitle" aria-hidden="true">Diagnóstico gratuito</div>

          <?php if ( $nexus_sent ) : ?>
            <div class="form-feedback form-success" role="alert" aria-live="polite">
              ✓ ¡Listo! Te contactamos en menos de 24 horas. Revisá tu correo.
            </div>
          <?php elseif ( $nexus_error ) : ?>
            <div class="form-feedback form-error" role="alert" aria-live="polite">
              ✕ Algo salió mal. Por favor revisá los campos e intentá de nuevo.
            </div>
          <?php endif; ?>

          <?php if ( ! $nexus_sent ) : ?>
          <form
            method="post"
            action="<?php echo esc_url( $nexus_page_url ); ?>#contacto"
            novalidate
            aria-label="Formulario de diagnóstico gratuito"
          >
            <?php wp_nonce_field( 'nexus_contact_form', 'nexus_nonce' ); ?>

            <div class="cf-row">
              <div>
                <label class="sr-only" for="nexus_nombre">Nombre *</label>
                <input
                  id="nexus_nombre" name="nexus_nombre" class="ci"
                  type="text" placeholder="Nombre *"
                  value="<?php echo esc_attr( $_POST['nexus_nombre'] ?? '' ); ?>"
                  required autocomplete="given-name"
                />
              </div>
              <div>
                <label class="sr-only" for="nexus_wa">WhatsApp</label>
                <input
                  id="nexus_wa" name="nexus_wa" class="ci"
                  type="tel" placeholder="WhatsApp"
                  value="<?php echo esc_attr( $_POST['nexus_wa'] ?? '' ); ?>"
                  autocomplete="tel"
                />
              </div>
            </div>

            <label class="sr-only" for="nexus_email">Email *</label>
            <input
              id="nexus_email" name="nexus_email" class="ci"
              type="email" placeholder="Email *"
              value="<?php echo esc_attr( $_POST['nexus_email'] ?? '' ); ?>"
              required autocomplete="email"
            />

            <label class="sr-only" for="nexus_negocio">¿A qué se dedica tu negocio?</label>
            <input
              id="nexus_negocio" name="nexus_negocio" class="ci"
              type="text" placeholder="¿A qué se dedica tu negocio?"
              value="<?php echo esc_attr( $_POST['nexus_negocio'] ?? '' ); ?>"
            />

            <button class="cta-sub" type="submit">
              Quiero mi diagnóstico gratuito →
            </button>
            <p class="cta-trust">
              <svg width="11" height="13" viewBox="0 0 11 13" fill="none" aria-hidden="true" style="display:inline-block;vertical-align:middle;margin-right:.3rem;opacity:.4"><rect x="1" y="5" width="9" height="7" rx="1.5" stroke="white" stroke-width="1.2"/><path d="M3 5V3.5a2.5 2.5 0 0 1 5 0V5" stroke="white" stroke-width="1.2"/></svg>
              Tu información está segura. No compartimos datos con terceros.
            </p>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main><!-- /main -->

  <!-- ── FOOTER ────────────────────────────────────────────────────────── -->
  <footer role="contentinfo">
    <div class="ft-top">
      <div>
        <a href="<?php echo esc_url( $nexus_site_url ); ?>" class="ft-logo" aria-label="Nexus Agency — inicio">
          Nexus <span class="ft-dot" aria-hidden="true"></span>
        </a>
        <p class="ft-desc">Agencia de marketing digital para PYMEs que quieren crecer de forma sostenible y medible.</p>
      </div>
      <nav class="ft-col" aria-label="Servicios">
        <h5>Servicios</h5>
        <ul>
          <li><a href="#servicios">SEO Local</a></li>
          <li><a href="#servicios">Google Ads</a></li>
          <li><a href="#servicios">Meta Ads</a></li>
          <li><a href="#servicios">Diseño Web</a></li>
          <li><a href="#servicios">WooCommerce</a></li>
        </ul>
      </nav>
      <nav class="ft-col" aria-label="Empresa">
        <h5>Empresa</h5>
        <ul>
          <li><a href="#nosotros">Sobre Nexus</a></li>
          <li><a href="#resultados">Casos de éxito</a></li>
          <li><a href="<?php echo esc_url( home_url('/blog') ); ?>">Blog</a></li>
          <li><a href="#contacto">Trabaja con nosotros</a></li>
        </ul>
      </nav>
      <nav class="ft-col" aria-label="Contacto">
        <h5>Contacto</h5>
        <ul>
          <li><a href="https://wa.me/549XXXXXXXXXX" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
          <li><a href="mailto:hola@nexusagency.com">hola@nexusagency.com</a></li>
          <li><a href="https://instagram.com/nexusagency" target="_blank" rel="noopener noreferrer">Instagram</a></li>
          <li><a href="https://linkedin.com/company/nexusagency" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
        </ul>
      </nav>
    </div>
    <div class="ft-bottom">
      <span>© <?php echo esc_html( date('Y') ); ?> Nexus Agency. Todos los derechos reservados.</span>
      <span>
        <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Política de Privacidad</a>
        ·
        <a href="<?php echo esc_url( home_url('/terminos-y-condiciones') ); ?>">Términos y Condiciones</a>
      </span>
    </div>
  </footer>

  <!-- ── Cookie Consent Banner (GDPR / LGPD) ───────────────────────────── -->
  <div
    id="nexus-cookie-banner"
    role="dialog"
    aria-modal="false"
    aria-label="Aviso de cookies"
    aria-describedby="nexus-cookie-desc"
  >
    <p id="nexus-cookie-desc">
      Usamos cookies para mejorar tu experiencia y analizar el tráfico del sitio.
      Consultá nuestra <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Política de Privacidad</a>.
    </p>
    <div class="cookie-btns">
      <button class="cookie-accept" id="nexus-cookie-accept">Aceptar</button>
      <button class="cookie-decline" id="nexus-cookie-decline">Solo esenciales</button>
    </div>
  </div>

  <!-- ── Scripts ────────────────────────────────────────────────────────── -->
  <script>
  (function () {
    'use strict';

    /* ── Reveal on scroll ── */
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
      });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(function(el) { obs.observe(el); });

    /* ── FAQ accordion — accesible (aria-expanded) ── */
    /* Ensure initial open state is reflected correctly in aria attributes */
    document.querySelectorAll('.faq-item').forEach(function(item) {
      var btn = item.querySelector('.faq-q');
      if (!btn) return;
      if (item.classList.contains('open')) {
        btn.setAttribute('aria-expanded', 'true');
      } else {
        btn.setAttribute('aria-expanded', 'false');
      }
    });

    document.querySelectorAll('.faq-q').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var item    = btn.closest('.faq-item');
        var isOpen  = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(function(i) {
          i.classList.remove('open');
          var b = i.querySelector('.faq-q');
          if (b) b.setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });

    /* ── Mobile nav toggle ── */
    var toggle  = document.querySelector('.nav-toggle');
    var navMenu = document.getElementById('nav-menu');
    if (toggle && navMenu) {
      toggle.addEventListener('click', function() {
        var expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!expanded));
        navMenu.classList.toggle('is-open', !expanded);
      });
      /* Cerrá el menú al hacer click en un link */
      navMenu.querySelectorAll('a').forEach(function(a) {
        a.addEventListener('click', function() {
          navMenu.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
        });
      });
    }

    /* ── Plan buttons → scroll a contacto ── */
    document.querySelectorAll('.plan-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('contacto')?.scrollIntoView({ behavior: 'smooth' });
      });
    });

    /* ── Nav CTA button → scroll a contacto ── */
    document.querySelector('.nav-btn')?.addEventListener('click', function() {
      document.getElementById('contacto')?.scrollIntoView({ behavior: 'smooth' });
    });

    /* ── Cookie consent ── */
    var COOKIE_KEY = 'nexus_cookie_consent';
    var banner     = document.getElementById('nexus-cookie-banner');

    function setCookie(val) {
      var d = new Date();
      d.setFullYear(d.getFullYear() + 1);
      document.cookie = COOKIE_KEY + '=' + val + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
    }
    function getCookie(name) {
      return document.cookie.split(';').some(function(c) { return c.trim().startsWith(name + '='); });
    }

    if (banner) {
      if (getCookie(COOKIE_KEY)) {
        banner.classList.add('hidden');
      }
      document.getElementById('nexus-cookie-accept')?.addEventListener('click', function() {
        setCookie('all');
        banner.classList.add('hidden');
        /* Aquí podés activar GA4 si aceptaron */
        if (typeof window.nexusGA4 === 'function') { window.nexusGA4(); }
      });
      document.getElementById('nexus-cookie-decline')?.addEventListener('click', function() {
        setCookie('essential');
        banner.classList.add('hidden');
      });
    }

    /* ── Form: validación client-side básica ── */
    var form = document.querySelector('form[aria-label="Formulario de diagnóstico gratuito"]');
    if (form) {
      form.addEventListener('submit', function(e) {
        var nombre = form.querySelector('#nexus_nombre');
        var email  = form.querySelector('#nexus_email');
        var ok = true;

        [nombre, email].forEach(function(f) { f && f.classList.remove('is-invalid'); });

        if (nombre && !nombre.value.trim()) { nombre.classList.add('is-invalid'); nombre.focus(); ok = false; }
        if (email && !email.validity.valid)  { email.classList.add('is-invalid'); if (ok) email.focus(); ok = false; }

        if (!ok) e.preventDefault();
      });
    }

  })();
  </script>

  <!--
    ══ GOOGLE ANALYTICS 4 ════════════════════════════════════════════════
    Opciones:
      A) Instalar el plugin "Site Kit by Google" (recomendado — se gestiona desde WP admin)
      B) Reemplazá G-XXXXXXXXXX con tu Measurement ID real y descomentá el bloque de abajo.
         El snippet solo carga DESPUÉS de que el usuario acepte cookies.

  <script>
    window.nexusGA4 = function() {
      var s  = document.createElement('script');
      s.src  = 'https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX';
      s.async = true;
      document.head.appendChild(s);
      window.dataLayer = window.dataLayer || [];
      function gtag(){ dataLayer.push(arguments); }
      gtag('js', new Date());
      gtag('config', 'G-XXXXXXXXXX');
    };
  </script>
  ══════════════════════════════════════════════════════════════════════ -->

  <!-- wp_footer() — OBLIGATORIO: plugins de formulario, analytics, etc. inyectan desde aquí -->
  <?php wp_footer(); ?>

</body>
</html>
