<?php
/* Template Name: Admin Upload Garuda */

// Proteksi: Hanya Admin yang bisa akses
if (!current_user_can('manage_options')) {
    wp_die('Maaf, halaman ini khusus untuk Administrator Kwartir Cabang.');
}

global $wpdb;
$table_name = $wpdb->prefix . 'garuda_data'; // wp_garuda_data

// 1. LOGIKA IMPORT CSV
if (isset($_POST['submit_upload']) && !empty($_FILES['csv_file']['tmp_name'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Lewati header
        $row_count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $wpdb->replace($table_name, array(
                'NO_SK'         => $data[0], //
                'TAHUN_SK'      => $data[1],
                'NO_SERTIFIKAT' => $data[2],
                'GOLONGAN'      => $data[3],
                'PANGKALAN'     => $data[4],
                'NO_GUDEP'      => $data[5],
                'FULLNAME'      => $data[6]
            ));
            $row_count++;
        }
        fclose($handle);
        set_transient('upload_success_count', $row_count, 30);
        wp_redirect(get_permalink());
        exit;
    }
}

// 2. HITUNG TOTAL DATA
$total_data = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

get_header(); ?>

<style>
    /* MODAL FIX: Memastikan benar-benar di tengah viewport */
    #uploadModal { 
        display: none; /* Akan diubah jadi flex via JS */
        position: fixed; 
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%; 
        height: 100%; 
        background: rgba(0, 0, 0, 0.75); 
        backdrop-filter: blur(5px);
        z-index: 9999999 !important; /* Sangat tinggi agar di atas header */
        justify-content: center;
        align-items: center;
        margin: 0 !important;
        padding: 20px;
    }
    
    .modal-content { 
        background: white; 
        padding: 40px; 
        border-radius: 24px; 
        width: 100%; 
        max-width: 500px; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
        position: relative;
        transform: translateY(0);
    }

    .close-modal { position: absolute; right: 25px; top: 20px; font-size: 32px; cursor: pointer; color: #94a3b8; line-height: 1; }
    .close-modal:hover { color: #1e293b; }
</style>

<main id="wp--skip-link--target" class="wp-block-group alignfull has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="margin-top:0;padding-top:0;">

    <div class="wp-block-group alignfull" style="background: #0f172a; padding: 80px 20px; text-align: center; color: white; margin-top: 0; margin-bottom: 0;">
        <div class="wp-block-group alignwide" style="margin: 0 auto; max-width: 1200px;">
            <h1 style="color: white !important; margin: 0; font-size: 2.8rem; font-weight: 800;">Dashboard Database Garuda</h1>
            <p style="opacity: 0.6; margin-top: 10px; letter-spacing: 1px;">PENGELOLAAN DATA TERPUSAT KWARCAB BANYUMAS</p>
        </div>
    </div>

    <div class="wp-block-group alignwide is-layout-flow" style="margin-top: -50px; position: relative; z-index: 10; padding: 0 20px;">
        
        <?php if ($count = get_transient('upload_success_count')) : delete_transient('upload_success_count'); ?>
            <div id="success-alert" style="background:#dcfce7; color:#166534; padding:20px; border-radius:15px; margin-bottom:25px; border: 1px solid #bbf7d0; text-align: center; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                ✅ Berhasil Mengimpor <?php echo $count; ?> Data!
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 60px 40px; text-align: center; border: 1px solid #f1f5f9;">
            <p style="color: #64748b; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem; font-weight: 800; margin-bottom: 20px;">Statistik Data Saat Ini</p>
            <h2 style="font-size: 6rem; margin: 0; color: #0f172a; font-weight: 900; line-height: 1;">
                <?php echo number_format($total_data, 0, ',', '.'); ?>
            </h2>
            <p style="color: #94a3b8; margin-top: 20px; margin-bottom: 50px;">Total baris ditemukan di tabel <code>wp_garuda_data</code></p>

            <button onclick="openUploadModal()" style="background: #2563eb; color: white; border: none; padding: 20px 50px; border-radius: 60px; font-weight: 800; cursor: pointer; font-size: 1.1rem; transition: 0.3s; box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);">
                + UNGGAH FILE CSV
            </button>
        </div>
    </div>

    <div id="uploadModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeUploadModal()">&times;</span>
            <h2 style="margin-top: 0; color: #0f172a; font-weight: 900; font-size: 1.8rem;">Unggah Data</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 30px;">Pilih file CSV anggota Garuda (Max 3 MB).</p>

            <form id="uploadForm" method="post" enctype="multipart/form-data">
                <div id="dropzone" style="border: 2px dashed #cbd5e1; border-radius: 20px; padding: 60px 20px; text-align: center; cursor: pointer; background: #f8fafc; transition: 0.2s;">
                    <div style="font-size: 50px; margin-bottom: 15px;">📁</div>
                    <p id="dropText" style="margin: 0; font-weight: 800; color: #334155; font-size: 1.1rem;">Klik atau Lepas File</p>
                    <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 8px;">Hanya format .CSV yang didukung</p>
                    <input type="file" name="csv_file" id="fileInput" accept=".csv" style="display: none;">
                    <p id="fileError" style="color: #ef4444; font-size: 0.9rem; display: none; margin-top: 15px; font-weight: 700;"></p>
                </div>

                <div style="margin-top: 35px;">
                    <button type="submit" name="submit_upload" id="btnSubmit" disabled 
                            style="width: 100%; background: #e2e8f0; color: white; border: none; padding: 18px; border-radius: 15px; font-weight: 800; cursor: not-allowed; font-size: 1rem; transition: 0.3s;">
                        MULAI IMPORT DATA
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// 1. Auto-hide Alert Sukses (5 Detik)
document.addEventListener('DOMContentLoaded', () => {
    const alert = document.getElementById('success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }
});

// 2. Centered Modal Logic
const modal = document.getElementById('uploadModal');
function openUploadModal() { 
    modal.style.display = 'flex'; // Gunakan flex agar centering dari CSS bekerja
    document.body.style.overflow = 'hidden'; // Kunci scroll layar belakang
}
function closeUploadModal() { 
    modal.style.display = 'none'; 
    document.body.style.overflow = 'auto'; // Aktifkan kembali scroll
}

window.onclick = (e) => { if (e.target == modal) closeUploadModal(); }

// 3. Dropzone & Validation
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const btnSubmit = document.getElementById('btnSubmit');
const dropText = document.getElementById('dropText');
const fileError = document.getElementById('fileError');

dropzone.onclick = () => fileInput.click();
fileInput.onchange = function() { validateFile(this.files[0]); };

dropzone.ondragover = (e) => { e.preventDefault(); dropzone.style.background = "#eff6ff"; dropzone.style.borderColor = "#2563eb"; };
dropzone.ondragleave = () => { dropzone.style.background = "#f8fafc"; dropzone.style.borderColor = "#cbd5e1"; };
dropzone.ondrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    fileInput.files = e.dataTransfer.files;
    validateFile(file);
};

function validateFile(file) {
    fileError.style.display = 'none';
    if (!file) return;

    if (!file.name.endsWith('.csv')) {
        showError("Gagal: Harus file .CSV");
        return;
    }

    if (file.size > 3 * 1024 * 1024) { // 3MB Limit
        showError("Gagal: Ukuran Maks 3 MB");
        return;
    }

    dropText.innerHTML = "✅ Siap: " + file.name;
    btnSubmit.disabled = false;
    btnSubmit.style.background = "#2563eb";
    btnSubmit.style.cursor = "pointer";
    dropzone.style.borderColor = "#22c55e";
}

function showError(msg) {
    fileError.innerText = msg;
    fileError.style.display = 'block';
    btnSubmit.disabled = true;
    btnSubmit.style.background = "#e2e8f0";
    dropzone.style.borderColor = "#ef4444";
}
</script>

<?php get_footer(); ?>