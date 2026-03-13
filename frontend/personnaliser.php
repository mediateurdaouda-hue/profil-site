<?php
if (!defined('APP_URL')) {
    require_once __DIR__ . '/../backend/config.php';
}
if (!isset($user))   $user   = [];
if (!isset($succes)) $succes = '';
if (!isset($layout)) $layout = [];

$h        = fn($s) => htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
$username = $user['username'] ?? ($_SESSION['username'] ?? '');
$minisite = APP_URL . '/profil.php?u=' . urlencode($username);

// Layout par défaut
$photoPosition = $layout['photo_position'] ?? 'right';
$sectionsOrder = $layout['sections_order']  ?? ['nom_titre','bio','competences','projets','contact','reseaux'];

$sectionsLabels = [
    'nom_titre'  => ['icon' => 'bi-person-badge',    'label' => 'Nom & Titre'],
    'bio'        => ['icon' => 'bi-file-text',        'label' => 'Biographie'],
    'competences'=> ['icon' => 'bi-tools',            'label' => 'Compétences'],
    'projets'    => ['icon' => 'bi-folder2-open',     'label' => 'Projets'],
    'contact'    => ['icon' => 'bi-envelope',         'label' => 'Contact'],
    'reseaux'    => ['icon' => 'bi-share',            'label' => 'Réseaux sociaux'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Personnaliser mon site — ProfilSite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/style.css"/>
    <style>
        body { background: #f1f5f9; font-family: 'DM Sans', sans-serif; }

        /* Layout éditeur */
        .editor-wrapper {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            min-height: 100vh;
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Panneau gauche */
        .editor-panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 24px;
        }
        .editor-panel-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Photo position */
        .photo-toggle { display: flex; gap: 12px; margin-bottom: 24px; }
        .photo-option {
            flex: 1;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }
        .photo-option:hover { border-color: #6366f1; }
        .photo-option.active {
            border-color: #6366f1;
            background: #eef2ff;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        .photo-option input { display: none; }
        .photo-preview-mini {
            display: flex;
            gap: 6px;
            height: 36px;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
        }
        .ppm-photo {
            width: 24px; height: 32px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 4px;
        }
        .ppm-lines {
            display: flex; flex-direction: column; gap: 4px; flex: 1;
        }
        .ppm-lines span {
            display: block; height: 5px;
            background: #cbd5e1; border-radius: 3px;
        }
        .ppm-lines span:last-child { width: 70%; }

        /* Sections drag */
        .section-label {
            font-size: .75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 10px;
        }
        .drag-list { display: flex; flex-direction: column; gap: 8px; }
        .drag-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: grab;
            transition: all .2s;
            user-select: none;
        }
        .drag-item:hover { border-color: #6366f1; background: #f5f3ff; }
        .drag-item.dragging { opacity: .4; }
        .drag-item.drag-over { border-color: #6366f1; background: #eef2ff; transform: scale(1.01); }
        .drag-handle { color: #94a3b8; cursor: grab; font-size: 1.1rem; }
        .drag-icon { color: #6366f1; font-size: 1rem; }
        .drag-name { flex: 1; font-weight: 500; color: #334155; font-size: .9rem; }
        .drag-arrows { display: flex; flex-direction: column; gap: 2px; }
        .drag-arrows button {
            background: none; border: none; padding: 2px 4px;
            color: #94a3b8; cursor: pointer; line-height: 1;
            border-radius: 4px; transition: all .15s;
        }
        .drag-arrows button:hover { background: #e2e8f0; color: #6366f1; }

        /* Bouton save */
        .btn-save-layout {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: .95rem;
            margin-top: 20px;
            cursor: pointer;
            transition: opacity .2s;
        }
        .btn-save-layout:hover { opacity: .9; }

        /* Aperçu droite */
        .preview-panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .preview-toolbar {
            background: #1e293b;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .preview-dot {
            width: 12px; height: 12px; border-radius: 50%;
        }
        .preview-url {
            flex: 1;
            background: #334155;
            border-radius: 6px;
            padding: 4px 12px;
            color: #94a3b8;
            font-size: .8rem;
            margin: 0 8px;
        }
        .preview-frame-wrap {
            position: relative;
            overflow: hidden;
            height: calc(100vh - 120px);
        }
        .preview-frame {
            width: 100%;
            height: 100%;
            border: none;
            transform-origin: top left;
        }

        /* Navbar retour */
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-bar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .sortable-ghost {
            opacity: .3;
            background: #eef2ff;
        }

        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .88rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<!-- Barre du haut -->
<div class="top-bar">
    <a href="<?= APP_URL ?>/backend/dashboard.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour au dashboard
    </a>
    <span class="top-bar-title">
        <i class="bi bi-layout-wtf text-primary me-2"></i>Personnaliser mon site
    </span>
    <a href="<?= $h($minisite) ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-auto">
        <i class="bi bi-box-arrow-up-right me-1"></i> Voir le site
    </a>
</div>

<!-- Éditeur principal -->
<div class="editor-wrapper">

    <!-- PANNEAU GAUCHE : contrôles -->
    <div class="editor-panel">

        <?php if ($succes): ?>
        <div class="alert-success-custom">
            <i class="bi bi-check-circle-fill"></i> <?= $h($succes) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/backend/dashboard.php" id="layoutForm">
            <input type="hidden" name="action" value="layout"/>
            <input type="hidden" name="redirect" value="personnaliser"/>

            <!-- Photo position -->
            <div class="editor-panel-title">
                <i class="bi bi-image text-primary"></i> Position de la photo
            </div>
            <div class="photo-toggle mb-4">
                <label class="photo-option <?= $photoPosition === 'left' ? 'active' : '' ?>"
                       onclick="setPhotoPos('left', this)">
                    <input type="radio" name="photo_position" value="left"
                           <?= $photoPosition === 'left' ? 'checked' : '' ?>>
                    <div class="photo-preview-mini">
                        <div class="ppm-photo"></div>
                        <div class="ppm-lines"><span></span><span></span></div>
                    </div>
                    <div class="small fw-semibold">Photo à gauche</div>
                </label>
                <label class="photo-option <?= $photoPosition === 'right' ? 'active' : '' ?>"
                       onclick="setPhotoPos('right', this)">
                    <input type="radio" name="photo_position" value="right"
                           <?= $photoPosition === 'right' ? 'checked' : '' ?>>
                    <div class="photo-preview-mini">
                        <div class="ppm-lines"><span></span><span></span></div>
                        <div class="ppm-photo"></div>
                    </div>
                    <div class="small fw-semibold">Photo à droite</div>
                </label>
            </div>

            <!-- Ordre des sections -->
            <div class="editor-panel-title">
                <i class="bi bi-list-ol text-primary"></i> Ordre des sections
            </div>
            <p class="text-muted small mb-3">Glissez ou utilisez les flèches ↑↓ pour réorganiser.</p>

            <div class="drag-list" id="dragList">
                <?php foreach ($sectionsOrder as $sec):
                    if (!isset($sectionsLabels[$sec])) continue;
                    $info = $sectionsLabels[$sec];
                ?>
                <div class="drag-item" draggable="true" data-section="<?= $sec ?>">
                    <i class="bi bi-grip-vertical drag-handle"></i>
                    <i class="bi <?= $info['icon'] ?> drag-icon"></i>
                    <span class="drag-name"><?= $info['label'] ?></span>
                    <div class="drag-arrows">
                        <button type="button" onclick="moveUp(this)" title="Monter">
                            <i class="bi bi-chevron-up"></i>
                        </button>
                        <button type="button" onclick="moveDown(this)" title="Descendre">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="sections_order" id="sectionsOrderInput"
                   value="<?= $h(implode(',', $sectionsOrder)) ?>"/>

            <button type="submit" class="btn-save-layout">
                <i class="bi bi-check-lg me-2"></i> Enregistrer la disposition
            </button>
        </form>
    </div>

    <!-- PANNEAU DROITE : aperçu -->
    <div class="preview-panel">
        <div class="preview-toolbar">
            <div class="preview-dot" style="background:#ef4444;"></div>
            <div class="preview-dot" style="background:#f59e0b;"></div>
            <div class="preview-dot" style="background:#22c55e;"></div>
            <div class="preview-url">
                <?= $h('localhost/profilsite/profil.php?u=' . $username) ?>
            </div>
            <button onclick="refreshPreview()" class="btn btn-sm btn-outline-secondary border-0 text-white">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <div class="preview-frame-wrap">
            <iframe id="previewFrame" class="preview-frame"
                    src="<?= $h($minisite) ?>"
                    title="Aperçu du mini-site">
            </iframe>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ── Drag & drop avec SortableJS ──
const dragList = document.getElementById('dragList');
Sortable.create(dragList, {
    animation: 200,
    handle: '.drag-handle',
    ghostClass: 'sortable-ghost',
    onEnd: updateOrder
});

function updateOrder() {
    const items = dragList.querySelectorAll('.drag-item');
    const order = Array.from(items).map(i => i.dataset.section);
    document.getElementById('sectionsOrderInput').value = order.join(',');
}

// ── Boutons flèches ──
function moveUp(btn) {
    const item = btn.closest('.drag-item');
    const prev = item.previousElementSibling;
    if (prev) { dragList.insertBefore(item, prev); updateOrder(); }
}
function moveDown(btn) {
    const item = btn.closest('.drag-item');
    const next = item.nextElementSibling;
    if (next) { dragList.insertBefore(next, item); updateOrder(); }
}

// ── Position photo ──
function setPhotoPos(pos, label) {
    document.querySelectorAll('.photo-option').forEach(l => l.classList.remove('active'));
    label.classList.add('active');
    label.querySelector('input').checked = true;
}

// ── Rafraîchir l'aperçu ──
function refreshPreview() {
    const frame = document.getElementById('previewFrame');
    frame.src = frame.src;
}

// ── Rafraîchir après sauvegarde ──
<?php if ($succes): ?>
setTimeout(() => refreshPreview(), 500);
<?php endif; ?>
</script>
</body>
</html>
