<?php
/* Template Name: Indexing Garuda */
get_header(); ?>

<main id="wp--skip-link--target" class="wp-block-group alignfull has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:var(--wp--preset--spacing--60)">

    <div class="wp-block-group alignfull garuda-hero" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); padding: 80px 20px; text-align: center; color: white; margin-top: 0; margin-bottom: 0;">
        <div class="wp-block-group alignwide" style="margin: 0 auto; max-width: 1200px;">
            <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; color: white !important;">Pangkalan Data Pramuka Garuda</h1>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 30px; color: white !important;">Kwartir Cabang Banyumas</p>
            
            <form action="<?php echo esc_url( get_permalink() ); ?>" method="get" style="max-width: 700px; margin: 0 auto; position: relative;">
                <input type="text" name="keyword" placeholder="Cari Nama Lengkap atau Pangkalan..." 
                       value="<?php echo isset($_GET['keyword']) ? esc_attr($_GET['keyword']) : ''; ?>"
                       style="width: 100%; padding: 18px 25px; border-radius: 50px; border: none; font-size: 1.1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); color: #333; outline: none;">
                <button type="submit" style="position: absolute; right: 8px; top: 8px; background: #ff9800; color: white; border: none; padding: 12px 25px; border-radius: 40px; cursor: pointer; font-weight: bold;">
                    CARI
                </button>
            </form>
        </div>
    </div>

    <div class="wp-block-group alignwide is-layout-flow wp-block-group-is-layout-flow" style="margin-top: -40px; position: relative; z-index: 10; padding: 0 20px;">
        <div class="garuda-results-card" style="background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 40px; min-height: 400px; border: 1px solid #eee;">
            <?php
            if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
                global $wpdb;
                $keyword = '%' . $wpdb->esc_like(trim($_GET['keyword'])) . '%';
                
                $results = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM wp_garuda_data WHERE FULLNAME LIKE %s OR PANGKALAN LIKE %s OR NO_SK LIKE %s ORDER BY TAHUN_SK DESC",
                    $keyword, $keyword, $keyword
                ));

                if ($results) {
                    echo '<h3 style="margin-bottom: 25px; color: #333;">Ditemukan <span style="color: #2575fc; font-weight: bold;">' . count($results) . ' data</span></h3>';
                    echo '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse; text-align: left; font-family: sans-serif;">';
                    echo '<tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 15px; color: #475569;">NAMA LENGKAP</th>
                            <th style="padding: 15px; color: #475569;">GOLONGAN</th>
                            <th style="padding: 15px; color: #475569;">PANGKALAN</th>
                            <th style="padding: 15px; color: #475569;">TAHUN SK</th>
                            <th style="padding: 15px; color: #475569;">NO. SERTIFIKAT</th>
                          </tr>';
                    
                    foreach ($results as $row) {
                        $data = (array) $row;
                        echo "<tr style='border-bottom: 1px solid #f1f5f9;'>
                                <td style='padding: 15px; font-weight: 700; color: #1e40af;'>" . esc_html($data['FULLNAME'] ?? $data['fullname'] ?? '-') . "</td>
                                <td style='padding: 15px;'>" . esc_html($data['GOLONGAN'] ?? $data['golongan'] ?? '-') . "</td>
                                <td style='padding: 15px;'>" . esc_html($data['PANGKALAN'] ?? $data['pangkalan'] ?? '-') . "</td>
                                <td style='padding: 15px;'>" . esc_html($data['TAHUN_SK'] ?? $data['tahun_sk'] ?? '-') . "</td>
                                <td style='padding: 15px; font-family: monospace; font-weight: 600;'>" . esc_html($data['NO_SERTIFIKAT'] ?? $data['no_sertifikat'] ?? '-') . "</td>
                              </tr>";
                    }
                    echo '</table></div>';
                } else {
                    echo '<div style="text-align: center; padding: 60px;"><p style="color: #64748b;">Data tidak ditemukan.</p></div>';
                }
            } else {
                echo '<div style="text-align: center; padding: 80px 0; color: #94a3b8;">
                        <h2 style="margin: 0; font-weight: 700;">Silakan masukkan kata kunci pencarian</h2>
                        <p>Cari berdasarkan nama peserta atau nama pangkalan (sekolah)</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>